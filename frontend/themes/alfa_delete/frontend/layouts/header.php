<?php

use frontend\models\shop\settings\ContactModel;
use yii\helpers\Url;
/*use CmsModule\Shop\common\models\Categories;*/
use yii\helpers\Html;

/** @var Categories $catalog */

/** @var ContactModel */
$contactModel = \Yii::$app->settings->active;
dd($contactModel);
exit;
?>

<div class="header-top bx-between-center">
    <div class="header-top-left">
        <div class="select-city">
            <div class="selected-city"><?= $contactModel->name ?></div>
            <div class="select-city-choice">
                <div class="city">
                    Москва
                    <span></span>
                </div>
                <div class="city checked">
                    Санкт-Петербург
                    <span></span>
                </div>
                <div class="select-city-close"></div>
            </div>
        </div>
        <span class="city"></span>
    </div>
    <div class="header-top-right">
        <a href="tel:<?= $contactModel->phone ?>"><?= $contactModel->phone ?></a>
        <div class="header-appointment link-line appointment-button"><span>Записаться на примерку</span></div>
        <div class="header-social">
            <a href="#"><img src="/images/icon-telegram.svg" alt="Telegram"></a>
            <a href="#"><img src="/images/icon-instagram.svg" alt="Instagram"></a>
            <a href="#"><img src="/images/icon-vk.svg" alt="Vk"></a>
        </div>
    </div>
</div>
<div class="header-wrp bx-between-center width-full bx-center">
    <div class="header-logo">
        <a href="/">
            <img src="/images/logotype.svg" alt="Alfa Collection">
        </a>
    </div>
    <nav>
        <ul>
            <li class="sub-menu">
                <a href="<?= Url::to(['/catalog/index'])?>" class="link-line"><span>Каталог</span></a>
                <div class="sub-menu-bx">
                    <div class="sub-menu-bx-left">
                        <ul>
                            <li><a href="catalog.html" class="link-line"><span>Костюмы на свадьбу</span></a>
                            </li>
                            <li><a href="catalog.html" class="link-line"><span>Костюмы тройка</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Костюмы двубортные</span></a>
                            </li>
                            <li><a href="catalog.html" class="link-line"><span>Костюмы двойки</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Большие размеры</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Костюмы для высоких</span></a>
                            </li>
                            <li><a href="catalog.html" class="link-line"><span>Cмокинги</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Пальто</a></li>
                            <li><a href="catalog.html" class="link-line"><span>Дублёнки</a></li>
                        </ul>
                        <ul>
                            <li><a href="catalog.html" class="link-line"><span>Рубашки</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Брюки</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Плащи</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Пуховики</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Галстуки</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Бабочки</span></a></li>
                            <li><a href="catalog.html" class="link-line"><span>Ремни</span></a></li>
                        </ul>
                    </div>
                    <div class="sub-menu-bx-right">
                        <div class="sub-menu-bx-right-item">
                            <div class="sub-menu-bx-right-item-img">
                                <a href="catalog.html">
                                    <img src="/images/menu01.jpg" alt="Костюмы тройка">
                                </a>
                            </div>
                            <a href="catalog.html" class="link-line white"><span>Костюмы тройка</span></a>
                        </div>
                        <div class="sub-menu-bx-right-item">
                            <div class="sub-menu-bx-right-item-img">
                                <a href="catalog.html">
                                    <img src="/images/menu02.jpg" alt="Пальто">
                                </a>
                            </div>
                            <a href="catalog.html" class="link-line white"><span>Пальто</span></a>
                        </div>
                    </div>
                </div>
            </li>
            <li><a href="<?= Url::to(['/site/about'])?>" class="link-line"><span>О компании</span></a></li>
            <li><a href="<?= Url::to(['/site/delivery'])?>" class="link-line"><span>Доставка и оплата</span></a></li>
            <li><a href="<?= Url::to(['/site/clients'])?>" class="link-line"><span>Клиенты</span></a></li>
            <li><a href="<?= Url::to(['/site/contact'])?>" class="link-line"><span>Контакты</span></a></li>
        </ul>
        <div class="nav-close"></div>
        <div class="mobile-menu-info">
            <a href="tel:<?= $contactModel->phone ?>" class="header-phone"><?= $contactModel->phone ?></a>
            <div class="mobile-menu-info-zapis appointment-button radius">Записаться на примерку</div>
            <div class="mobile-menu-info-social">
                <a href="#"><img src="/images/icon-instagram-black.svg"></a>
                <a href="#"><img src="/images/icon-vk-black.svg"></a>
                <a href="#"><img src="/images/icon-facebook-black.svg"></a>
                <a href="#"><img src="/images/icon-telegram-black.svg"></a>
            </div>
        </div>
    </nav>
    <div class="header-icons">
        <a href="<?= Url::to(['/account/index'])?>" class="header-lk"></a>
        <a href="favorite.html" class="header-favorite"></a>
        <div class="header-search"></div>
        <a href="cart.html" class="header-cart"><span class="header-num">3</span></a>
        <div class="header-search-form">
            <form class="header-search-form-wrp" autocomplete="off">
                <input class="header-search-input" type="text" value="" placeholder="Поисковый запрос..."
                       maxlength="100">
                <div class="header-search-form-close"></div>
                <button class="header-search-button" type="submit" value=""></button>
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
        <a href="favorite.html"></a>
        <img src="/images/icon-favorite.svg">
        <span class="mobile-item-num">1</span>
        <span class="mobile-item-name">Избранное</span>
    </div>
    <div class="header-mobile-item cart">
        <a href="cart.html"></a>
        <img src="/images/icon-cart.svg">
        <span class="mobile-item-num">3</span>
        <span class="mobile-item-name">Корзина</span>
    </div>
    <div class="header-mobile-item lk">
        <a href="<?= Url::to(['/account/index'])?>"></a>
        <img src="/images/icon-lk.svg">
        <span class="mobile-item-name">Кабинет</span>
    </div>
</div>