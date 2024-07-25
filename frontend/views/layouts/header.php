<?php

use frontend\services\CityCodeResolver;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var ContactModel */
$contactActive = \Yii::$app->settings->active;

/** @var array<ContactModel> */
$contactsSettings = \Yii::$app->settings->all;
$cityCodeResolver = \Yii::$container->get(CityCodeResolver::class);

?>

<div class="header-top bx-between-center">
    <div class="header-top-left">
        <div class="select-city">
            <div class="selected-city"><?= $contactActive->name ?></div>
            <div class="select-city-choice">
                <?php foreach($contactsSettings as $_contactSettings) { ?>
                <a href="<?= Url::to(['/site/change-city', 'code' => $_contactSettings->code]) ?>" class="city <?php if($_contactSettings->code == $contactActive->code) {?>checked<?php } ?>" data-code="<?= $_contactSettings->code ?>">
                    <?= $_contactSettings->name ?>
                    <span></span>
                </a>
                <?php } ?>
                <div class="select-city-close"></div>
            </div>
        </div>
        <span class="city"><?= $contactActive->address ?></span>
    </div>
    <div class="header-top-right">
        <a href="tel:<?= $contactActive->phone ?>"><?= $contactActive->phone ?></a>
        <div class="header-appointment link-line appointment-button"><span>Записаться на примерку</span></div>
        <div class="header-social">
            <a href="<?= \Yii::$app->settings->getSocial('tg')?>" target="_blank"><img src="/images/icon-telegram.svg" alt="Telegram"></a>
            <a href="<?= \Yii::$app->settings->getSocial('insta')?>" target="_blank"><img src="/images/icon-instagram.svg" alt="Instagram"></a>
            <a href="<?= \Yii::$app->settings->getSocial('vk')?>" target="_blank"><img src="/images/icon-vk.svg" alt="Vk"></a>
        </div>
    </div>
</div>
<div class="header-wrp bx-between-center width-full bx-center">
    <div class="header-logo">
        <a href="<?= Url::to(['/site/index', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>">
            <img src="/images/logotype.svg" alt="Alfa Collection">
        </a>
    </div>
    <nav>
        <ul>
            <li class="sub-menu">
                <a href="<?= Url::to(['/shop/frontend/catalog/index', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line" title="<?= Html::encode('Каталог')?>"><span>Каталог</span></a>
                <div class="sub-menu-bx">
                    <?= \frontend\widgets\HeadNavigation::widget() ?> 
                </div>
            </li>
            <li><a href="<?= Url::to(['/site/about', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line" title="<?= 'О компании'?>"><span>О компании</span></a></li>
            <li><a href="<?= Url::to(['/site/delivery', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line" title="<?= Html::encode('Доставка и оплата')?>"><span>Доставка и оплата</span></a></li>
            <li><a href="<?= Url::to(['/blogs/frontend/default/index'])?>" class="link-line" title="<?= Html::encode('Блог')?>"><span>Блог</span></a></li>
            <li><a href="<?= Url::to(['/site/contact', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="link-line" title="<?= Html::encode('Контакты')?>"><span>Контакты</span></a></li>
        </ul>
        <div class="nav-close"></div>
        <div class="mobile-menu-info">
            <a href="tel:<?= $contactActive->phone ?>" class="header-phone"><?= $contactActive->phone ?></a>
            <div class="mobile-menu-info-zapis appointment-button radius">Записаться на примерку</div>
            <div class="mobile-menu-info-social">
                <a href="<?= \Yii::$app->settings->getSocial('insta')?>" target="_blank"><img src="/images/icon-instagram-black.svg"></a>
                <a href="<?= \Yii::$app->settings->getSocial('vk')?>" target="_blank"><img src="/images/icon-vk-black.svg"></a>
                <a href="<?= \Yii::$app->settings->getSocial('fb')?>" target="_blank"><img src="/images/icon-facebook-black.svg"></a>
                <a href="<?= \Yii::$app->settings->getSocial('tg')?>" target="_blank"><img src="/images/icon-telegram-black.svg"></a>
            </div>
        </div>
    </nav>
    <div class="header-icons">
        <a href="<?= Url::to(['/account/index'])?>" class="header-lk"></a>
        <a href="<?= Url::to(['/favorite/index'])?>" class="header-favorite"></a>
        <div class="header-search"></div>
        <a href="<?= Url::to(['/shop/frontend/basket/index'])?>" class="header-cart"><span class="header-num"><?= Yii::$app->basket->count() ?></span></a>
        <div class="header-search-form">
            <form action="<?= Url::to(['/search']) ?>" class="header-search-form-wrp" autocomplete="off">
                <input class="header-search-input" type="text" name="q" value="<?= Html::encode(Yii::$app->request->get('q')) ?>" placeholder="Поисковый запрос..."
                       maxlength="100">
                <div class="header-search-form-close"></div>
                <button class="header-search-button" type="submit" ></button>
            </form>
        </div>
    </div>
</div>
<div class="header-mobile">
    <div class="header-mobile-item menu">
        <img src="/images/icon-menu.svg">
        <span class="mobile-item-name">Меню</span>
    </div>
    <div class="header-mobile-item search">
        <img src="/images/icon-search.svg">
        <span class="mobile-item-name">Поиск</span>
    </div>
    <div class="header-mobile-item favorite">
        <a href="<?= Url::to(['/favorite/index'])?>"></a>
        <img src="/images/icon-favorite.svg">
        <span class="mobile-item-num mobile-item-favorite-num"><?= Yii::$app->favorite->count() ?></span>
        <span class="mobile-item-name">Избранное</span>
    </div>
    <div class="header-mobile-item cart">
        <a href="<?= Url::to(['/shop/frontend/basket/index'])?>"></a>
        <img src="/images/icon-cart.svg">
        <span class="mobile-item-num mobile-item-cart-num"><?= Yii::$app->basket->count() ?></span>
        <span class="mobile-item-name">Корзина</span>
    </div>
    <div class="header-mobile-item lk">
        <a href="<?= Url::to(['/account/index'])?>"></a>
        <img src="/images/icon-lk.svg">
        <span class="mobile-item-name">Кабинет</span>
    </div>
</div>