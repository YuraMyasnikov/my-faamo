<?php

use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use CmsModule\Quests\frontend\widgets\FaqWidget;

$this->title = 'Quests';
$this->params['breadcrumbs'][] = 'Отзывы';
?>


<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>FAQ</span>
    </div>
</div>
<div class="catalog-page width-default bx-center">
    <h1 class="center-text">Часто задаваемые вопросы</h1>
    <div class="cart-page js-sticky-row">
        <div class="cart-page-left">
            <div class="js-accordion faq-wrp">
                <?= FaqWidget::widget() ?>
            </div>
        </div>
        <div class="cart-page-right">
            <div class="faq-right js-sticky-box" data-margin-top="30" data-margin-bottom="30">
                <img src="/images/faq.jpg">
            </div>
        </div>
    </div>
</div>
