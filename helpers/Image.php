<?php

namespace app\helpers;

class Image
{
    public static $characters = '0123456789abcdefghijklmnopqrstuv';

    public static function base32_encode($base10)
    {
        return base_convert($base10, 10, 32);
    }

    public static function base32_decode($base36)
    {
        return base_convert($base36, 32, 10);
    }

    public static function getColor($img, $val) {
        return imagecolorallocate($img, $val, $val, $val);
    }

    public static function arrToStr($arr, $full = true)
    {
        $c = count($arr);
        $res = 0;

        foreach($arr as $k => $v) {
            $res += $v * pow(256, $c - ($k +1));
        }

        if ($full) {
            return str_pad(self::base32_encode($res),  8, '0', STR_PAD_LEFT);
        } else {
            return str_pad(self::base32_encode($res),  2, '0', STR_PAD_LEFT);
        }
    }

    public static function imgToStr32($arr, $str = '')
    {
        if (count($arr) === 1) {
            return self::arrToStr($arr, false) . $str;
        }

        if (count($arr) >= 5) {
            $mArr = [];

            for($i = 0; $i < 5; $i++) {
                $mArr[] = array_pop($arr);
            }

            $str = self::arrToStr(array_reverse($mArr)) . $str;
            return self::imgToStr32($arr, $str);
        }

        return $str;
    }

    public static function getStrImg($imgArr)
    {
        $imgArrArr = array_chunk(array_reverse($imgArr), 256*15);

        $res = '';
        foreach($imgArrArr as $arr) {
            $res = self::imgToStr32(array_reverse($arr)) . $res;
        }

        return $res;
    }

    public static function numToArr($num, $arr = [])
    {
        $color = $num % 256;
        $arr[] = $color;
        $next = ($num - $color) / 256;
        if ($next > 0) {
            return self::numToArr($next, $arr);
        } else {
            return $arr;
        }
    }

    public static function convertImg($str)
    {
        $mArr = [];
        $arr = str_split(strrev($str), 8);

        foreach($arr as $k => $item) {
            $numArr = self::numToArr(self::base32_decode(strrev($item)));
            $mArrC = [];
            if ($k === 13107) {
                $mArrC[0] = isset($numArr[0]) ? $numArr[0] : 0;
                $mArr[] = $mArrC;
            } else {
                for ($i = 0; $i < 5 - count($numArr); $i++) {
                    $numArr[] = 0;
                }
                $mArrC = array_reverse($numArr);
                $mArr[] = $mArrC;
            }
        }

        $resArr = [];

        foreach(array_reverse($mArr) as $chunk) {
            foreach($chunk as $color) {
                $resArr[] = $color;
            }
        };

        return $resArr;
    }

    public static function getRandomString($length = 104856)
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::$characters[rand(0, strlen(self::$characters) - 1)];
        }
        return $randomString;
    }

    public static function validateString($str)
    {
        return strlen($str) === strlen(preg_replace('~[^a-vA-V0-9]+~','', $str));
    }
}
