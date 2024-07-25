<?php

use yii\helpers\Url;
use yii\helpers\Html;
/*use frontend\forms\SubscribersForm as FormsSubscribersForm;

$subscribeForm = FormsSubscribersForm::buildForm();*/


?>

<div class="footer width-default bx-center">
    <div class="footer-item color-gray">
        <a href="<?= Url::to(['/site/index'])?>" class="footer-item-logo"><img src="/images/logotype-white.svg"></a>
        <p>© 2024 <br>интернет-магазин мужских <br>классических костюмов</p>
        <div class="footer-item-social">
            <a href="#"><img src="/images/icon-instagram-white.svg"></a>
            <a href="#"><img src="/images/icon-vk-white.svg"></a>
            <a href="#"><img src="/images/icon-facebook-white.svg"></a>
            <a href="#"><img src="/images/icon-telegram-white.svg"></a>
        </div>
        <div class="newsletter">
            <p>Будьте в курсе актуальных акций!</p>
            <form class="newsletter-wrp radius">
                <input class="newsletter-wrp-input" id="newsletter_mail" name="newsletter_mail" type="text"
                       placeholder="Введите ваш Email..." autocomplete="off" required>
                <button type="button" class="newsletter-wrp-btn"></button>
            </form>
        </div>
        <p class="codi-way">Разработано в <img src="/images/codi-way.svg"></p>
    </div>
    <div class="footer-item">
        <p class="footer-item-title">Компания</p>
        <ul class="footer-item-menu">
            <li><a href="<?= Url::to(['/site/about'])?>" class="link-line white"><span>О компании</span></a></li>
            <li><a href="<?= Url::to(['/site/delivery'])?>" class="link-line white"><span>Доставка и оплата</span></a></li>
            <li><a href="<?= Url::to(['/site/clients'])?>" class="link-line white"><span>Клиенты</span></a></li>
            <li><a href="<?= Url::to(['/site/contact'])?>" class="link-line white"><span>Контакты</span></a></li>
            <li><a href="<?= Url::to(['/site/certificate'])?>" class="link-line white"><span>Сертификаты</span></a></li>
            <li><a href="<?= Url::to(['/site/requisites'])?>" class="link-line white"><span>Реквизиты</span></a></li>
            <li><a href="<?= Url::to(['/site/politics'])?>" class="link-line white"><span>Политика конфиденциальности</span></a></li>
        </ul>
    </div>
    <div class="footer-item">
        <p class="footer-item-title">Контакты</p>
        <p class="footer-item-name">Телефон в Санкт-Петербурге:</p>
        <p><a href="tel:+79523579097">+7 952 357 90 97</a></p>
        <p class="footer-item-name">Телефон в Москве:</p>
        <p><a href="tel:+79956221317">+7 995 622 13 17</a></p>
        <div class="footer-item-buttons">
            <p class="btn footer black radius get-call">Заказать звонок</p>
            <p class="btn footer black radius header-appointment appointment-button">Записаться на примерку</p>
        </div>
    </div>
    <div class="footer-item">
        <p class="footer-item-name">Адрес в Санкт-Петербурге:</p>
        <p class="weight">ул. Большая Московская, д. 9</p>
        <p class="footer-item-name">Адрес в Москве:</p>
        <p class="weight">ул. Бирюлёвская, д. 9</p>
        <p class="footer-item-name">Электронная почта:</p>
        <p><a href="mailto:azbukaofstyle@gmail.com" class="weight">azbukaofstyle@gmail.com</a></p>
        <p class="footer-item-name">Работаем ежедневно:</p>
        <p class="weight">с 10:00 до 21:00</p>
    </div>
</div>


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