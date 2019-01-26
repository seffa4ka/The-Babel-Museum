<?php
/**
 * Render page.
 *
 * @var $imgSelect
 * @var $imgStr
 */

use app\helpers\Image;
?>

<div>
    <form method="post">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <textarea name="imgStr" maxlength="104856" style="width: 100%; height: 80vh; resize: none;"><?= $imgStr ?></textarea>
        <select name="imgSelect">
        <?php
            for ($i = 0; $i < 256; $i++) {
                echo '<option value="'
                    . str_pad(Image::base32_encode($i),  2, '0', STR_PAD_LEFT)
                    . '" '
                    . (str_pad(Image::base32_encode($i),  2, '0', STR_PAD_LEFT) === $imgSelect ? 'selected' : null)
                    . '>'
                    . str_pad(Image::base32_encode($i),  2, '0', STR_PAD_LEFT)
                    . '</option>';
            }
        ?>
        </select>
        <button name="btn" value="random">random</button>
        <button name="btn" value="submit">submit</button>
    </form>
</div>