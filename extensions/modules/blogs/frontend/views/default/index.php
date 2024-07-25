<?php

use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use CmsModule\Blogs\frontend\widgets\Blog;

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = 'Отзывы';
?>


<!-- <div class="blog-breadcrumbs">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Статьи</span>
    </div>
</div> -->
<div class="company-banner">
    <div class="company-banner-img">
        <img src=" <?= Url::to(['/images/blog-banner.jpg'])?> ">
    </div>
    <div class="company-banner-info">
        <h1>Блог</h1>
        <p>Добро пожаловать! Обзоры, новинки, мнения наших <br>дизайнеров, в общем много всего интересного</p>
    </div>
</div>
<div class="bx-center width-default offset-t3">
    <div class="blog-page">

    <?= Blog::widget(['blogs' => $blogs]) ?>

    </div>


    <div class="pagination reviews width-default bx-center">
        <?= LinkPager::widget([
            'pagination' => $pages,
            'options' => ['class' => 'pagination-wrp'],
            'linkOptions' => ['class' => 'pagination-wrp-item'],
            'prevPageLabel' => '&nbsp;',
            'nextPageLabel' => '&nbsp;',
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'disabled',
            'prevPageCssClass' => 'prev',
            'nextPageCssClass' => 'next',
            'maxButtonCount' => 5,
            'pageCssClass' => 'pagination-wrp-item',
            'linkContainerOptions' => ['class' => 'pagination-wrp-item']
        ]); ?>
    </div>
</div>
<div class="seo-text width-less bx-center">
    <? /*Yii::$app->settings->description */?>
</div>
