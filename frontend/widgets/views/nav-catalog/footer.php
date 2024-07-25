<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var array $categories
 */

?>

<div class="footer__column">
    <div class="footer__column-title">Каталог</div>
    <ul class="footer__menu">
        <?php foreach ($categories as $category) { ?>
            <li class="footer__menu-item"><a href="<?= Html::encode($category['url']); ?>" class="footer__menu-link"><?= Html::encode($category['name']); ?></a></li>
        <?php } ?>
    </ul>
</div>