<?php

use CmsModule\Blogs\frontend\widgets\BlogItem;
use yii\helpers\Html;
use yii\helpers\Url;

?>
    <style>
.breadcrumbs-item-last::before { border: none; }
    </style>

    <div class="bx-center width-full">
        <div class="breadcrumbs">
            <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
            <a href="<?= Url::to(['/blogs'])?>" class="breadcrumbs-item breadcrumbs-item-last link-line"><span>Статьи</span></a>
        </div>
    </div>

    <?= BlogItem::widget(['blog_id' => $blog_id]) ?>