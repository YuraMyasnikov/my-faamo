<?php

use CmsModule\Subscribers\frontend\widgets\SubscribeWidget;
use frontend\services\CityCodeResolver;
use frontend\widgets\CallbackWidget;
use frontend\widgets\BookcallWidget;
use yii\helpers\Url;
use yii\helpers\Html;


/** @var ContactModel */
$contactActive = \Yii::$app->settings->active;

/** @var ContactModel */
$contactMsk = \Yii::$app->settings->getByCode('msk');

/** @var ContactModel */
$contactSpb = \Yii::$app->settings->getByCode('spb');

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

<div class="footer width-default bx-center">
    <div class="footer-item color-gray">
        <a href="<?= Url::to(['/site/index'])?>" class="footer-item-logo"><img src="/images/logotype-white.svg"></a>
        <p>© 2024 <br>интернет-магазин мужских <br>классических костюмов</p>
        <div class="footer-item-social">

            <a href="<?= \Yii::$app->settings->getSocial('insta')?>" target="_blank"><img src="/images/icon-instagram-white.svg"></a>
            <a href="<?= \Yii::$app->settings->getSocial('vk') ?>" target="_blank"><img src="/images/icon-vk-white.svg"></a>
            <a href="<?= \Yii::$app->settings->getSocial('fb')?>" target="_blank"><img src="/images/icon-facebook-white.svg"></a>
            <a href="<?= \Yii::$app->settings->getSocial('tg')?>" target="_blank"><img src="/images/icon-telegram-white.svg"></a>
        </div>
        <div class="newsletter">
            <p>Будьте в курсе актуальных акций!</p>
            <?php
            try {
                echo SubscribeWidget::widget(['view' => 'alfafooter']);
            } catch (Exception $e) {
                Yii::error($e->getMessage());
                if (YII_DEBUG) {
                    echo 'Произошла ошибка в форме подписки на рассылку';
                }
            }
            ?>

        </div>
        <p class="codi-way">Разработано в <img src="/images/codi-way.svg"></p>
    </div>
    <div class="footer-item">
        <p class="footer-item-title">Компания</p>
        <ul class="footer-item-menu">
            <li><a href="<?= Url::to(['/site/about', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line white" title="<?= Html::encode('О компании')?>"><span>О компании</span></a></li>
            <li><a href="<?= Url::to(['/site/delivery', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line white" title="<?= Html::encode('Доставка и оплата')?>"><span>Доставка и оплата</span></a></li>
            <li><a href="<?= Url::to(['/blogs/frontend/default/index'])?>" class="link-line" title="<?= Html::encode('Блог')?>"><span>Блог</span></a></li>
            <li><a href="<?= Url::to(['/site/contact', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line white" title="<?= Html::encode('Контакты')?>"><span>Контакты</span></a></li>
            <li><a href="<?= Url::to(['/site/requisites', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line white" title="<?= Html::encode('Реквизиты')?>"><span>Реквизиты</span></a></li>
            <li><a href="<?= Url::to(['/site/politics', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line white" title="<?= Html::encode('Политика конфиденциальности')?>"><span>Политика конфиденциальности</span></a></li>
        </ul>
    </div>
    <div class="footer-item">
        <p class="footer-item-title">Контакты</p>
        <p class="footer-item-name">Телефон в Санкт-Петербурге:</p>
        <p><a href="tel:<?= $contactSpb->phone ?>"><?= $contactSpb->phone ?></a></p>

        <p class="footer-item-name">Телефон в Москве:</p>
        <p><a href="tel:<?= $contactMsk->phone ?>"><?= $contactMsk->phone ?></a></p>
        <div class="footer-item-buttons">
            <p class="btn footer black radius get-call">Заказать звонок</p>
            <p class="btn footer black radius header-appointment appointment-button">Записаться на примерку</p>
        </div>
    </div>
    <div class="footer-item">
        <p class="footer-item-name">Адрес в Санкт-Петербурге:</p>
        <p class="weight"><?= $contactSpb->address ?></p>

        <p class="footer-item-name">Адрес в Москве:</p>
        <p class="weight"><?= $contactMsk->address ?></p>
        <p class="footer-item-name">Электронная почта:</p>
        <p><a href="mailto:<?= $contactActive->email ?>" class="weight"><?= $contactActive->email ?></a></p>
        <p class="footer-item-name">Работаем ежедневно:</p>
        <p class="weight"><?= $contactActive->working_hours ?></p>
    </div>
</div>

<?php
try {
    echo CallbackWidget::widget(['view' => 'modal_form', ]);
} catch (Exception $e) {
    Yii::error($e->getMessage());
    if (YII_DEBUG) {
        echo 'Произошла ошибка в форме подписки на рассылку';
    }
}
?>

<?php
try {
    echo BookcallWidget::widget(['view' => 'modal_form', ]);
} catch (Exception $e) {
    Yii::error($e->getMessage());
    if (YII_DEBUG) {
        echo 'Произошла ошибка в форме подписки на рассылку';
    }
}
?>

<?php $isSsuccess = Yii::$app->session->getFlash('success'); ?>
<?php if($isSsuccess) { ?>
    <script>
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: '<?= Html::encode( $isSsuccess ) ?>',
            timeout: 3000,
        }).show();
    </script>
<?php } ?>

<?php $isError = Yii::$app->session->getFlash('error'); ?>
<?php if($isError) { ?>
    <script>
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: '<?= Html::encode( $isError ) ?>',
            timeout: 3000,
        }).show();
    </script>
<?php } ?>
