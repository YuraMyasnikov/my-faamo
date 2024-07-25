<?php

use frontend\services\CityCodeResolver;
use yii\helpers\Url;

/** @var ContactModel */
$contactActive = \Yii::$app->settings->active;

/** @var ContactModel */
$contactMsk = \Yii::$app->settings->getByCode('msk');

/** @var ContactModel */
$contactSpb = \Yii::$app->settings->getByCode('spb');

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
    <a href="<?= Url::to(['/site/index', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Контакты</span>
    </div>
</div>
<div class="bx-center width-default catalog-page">
    <h1 class="center-text">Контактная информация</h1>
    <div class="cols-wrp four">
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery6.svg">
            <p class="offset1">Адрес в Санкт-Петербурге:<br>
                <strong><?= $contactSpb->address ?></strong>
            </p>
            <p>Адрес в Москве:<br>
                <strong><?= $contactMsk->address ?></strong>
            </p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-phone.svg">
            <p class="offset1">Телефон в Санкт-Петербурге:<br>
                <strong><a href="tel:<?= $contactSpb->phone ?>"><?= $contactSpb->phone ?></a></strong>
            </p>
            <p>Телефон в Москве:<br>
                <strong><a href="tel:<?= $contactMsk->phone ?>"><?= $contactMsk->phone ?></a></strong>
            </p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-mail-black.svg">
            <p>Электронная почта:
                <a href="mailto:<?= $contactActive->email ?>"><strong><?= $contactActive->email ?></strong></a>
            </p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-time.svg">
            <p>Режим работы:<br>
                <strong><?= $contactActive->working_hours ?></strong>
            </p>
        </div>
    </div>
</div>
<div class="maps-wrp">
    <input class="tabs-radio" id="map-spb" name="group" type="radio" checked>
    <input class="tabs-radio" id="map-m" name="group" type="radio">
    <div class="tabs-map">
        <label class="tabs-map-item" id="spb-tab" for="map-spb">Санкт-Петербург</label>
        <label class="tabs-map-item" id="m-tab" for="map-m">Москва</label>
    </div>
    <div class="panels-map">
        <div class="panels-map-item" id="spb-panel">
            <?= $contactSpb->ya_map_widget ?>
        </div>
        <div class="panels-map-item" id="m-panel">
            <?= $contactMsk->ya_map_widget ?>
        </div>
    </div>
</div>
<div class="bx-center width-default">
    <div class="block50">
        <h4>Реквизиты:</h4>
        <p class="less-size">
            ИП Макаренко И. Ю.<br>
            Юридический адрес: 197374, г. Санкт – Петербург, Приморский пр., д. 52, корп./ст. 1, кв./оф. 541<br>
            Почтовый адрес: 197374, г. Санкт – Петербург, Приморский пр., д. 52, корп./ст. 1, кв./оф. 541<br>
            ИНН 410117535252<br>
            ОГРНИП 315784700040141<br>
            Р/с 40802810532470001505 в ФИЛИАЛ «САНКТ-ПЕТЕРБУРГСКИЙ» АО «АЛЬФА-БАНК»<br>
            К/с 30101810600000000786<br>
            БИК 044030786
        </p>
    </div>
</div>
