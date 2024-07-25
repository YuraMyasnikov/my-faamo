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
        <span>Доставка и оплата</span>
    </div>
</div>
<div class="bx-center width-default catalog-page">
    <h1 class="center-text">Доставка и оплата</h1>
    <div class="block50 bx-center center-text offset3">
        <p>Комфортный шопинг для каждого покупателя превыше всего. Для удобства наших клиентов мы предлагаем
            разные способы оплаты и доставки товаров.</p>
    </div>
    <h3 class="bx-center center-text weight">Информация об оплате:</h3>
    <div class="cols-wrp three">
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery1.svg">
            <p><strong>Наличными при получении</strong><br>
                В нашем магазине по адресу:<br>
                Санкт–Петербург, <?= $contactSpb->address ?></p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery2.svg">
            <p><strong>Пластиковая карта</strong><br>
                через терминал</p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery3.svg">
            <p><strong>Онлайн-банкинг</strong><br>
                мобильный банк Сбербанка, представленный браузерной версией и приложением</p>
        </div>
    </div>
    <div class="text-border offset-bottom radius">
        <div class="text-border-icon">
            <img src="/images/icon-time.svg">
        </div>
        <div class="text-border-info">
            <p><strong>Наш магазин работает ежедневно с 10.00 до 21.00 без перерыва и выходных.</strong></p>
            <p class="less-size">Приходите и оцените качество наших мужских костюмов. <br>Вежливые и
                квалифицированные продавцы
                сделают всё, чтобы от процесса совершения покупки у вас остались только положительные эмоции.
            </p>
        </div>
    </div>
    <h3 class="bx-center center-text weight">Информация о доставке:</h3>
    <div class="cols-wrp three">
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery4.svg">
            <p><strong>Самовывоз из магазина</strong><br>
                бесплатно</p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery5.svg">
            <p><strong>Доставка с курьером по Санкт-Петербургу</strong><br>
                При совершении заказа до 17.00 часов товар будет привезён к вам в день оформления покупки.</p>
        </div>
        <div class="cols-wrp-item radius">
            <img src="/images/icon-delivery6.svg">
            <p><strong>Доставка в любые регионы России</strong><br>
                Доставка в регионы через транспортную компанию «СДЭК».</p>
        </div>
    </div>
    <div class="text-border radius">
        <div class="text-border-icon">
            <img src="/images/icon-sale.svg">
        </div>
        <div class="text-border-info">
            <p><strong>Точная стоимость доставки рассчитывается специалистом после телефонного разговора с
                    покупателем.</strong></p>
            <p class="less-size">Мы стремимся максимально сократить сроки прибытия товара к адресату, чтобы вы
                как можно быстрее получили ваш мужской костюм. Точное время в большей степени зависит от
                удалённости вашего населённого пункта от Санкт-Петербурга. Чаще всего заказ доставляется до
                указанного адреса не более, чем за 4 дня с момента оформления и отправки, которые совершаются
                только после того, как менеджер магазина свяжется с вами для подтверждения заказа.
            </p>
        </div>
    </div>
</div>
