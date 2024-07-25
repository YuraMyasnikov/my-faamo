<?php

use yii\helpers\Url;

?>

<!--📰 О компании-->
<div class="content">

<!--🤟 Хлебные крошки-->
<div class="container">
    <ul class="breadcrumbs">
        <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
        <li>О компании</li>
    </ul>
</div>

<div class="container">

    <!--👉-->
    <div class="about">
        <div class="about-content">
            <h1>О компании</h1>
            Компания «BERKUT.IV» работает на рынке спецодежды с 2008 года.
            <br>
            Деятельность компании началась с открытия производства полного цикла в г.Иваново, второе
            производство было запущено спустя 5 лет в г. Вичуга.
            <br>
            C 2018 года мы расширили географию поставок, стали делать поставки на маркетплейсы, а именно:
            WILDBERRIES, OZON, ALIEXPRESS.
            <br>
            <br>
            Мы производим одежду не только под собственным брендом. Являемся поставщиком для больших
            компаний, выполняя сезонные заказы под другими фирменными знаками.
            <br>
            На данный момент мы имеем отлаженные производства одежды и обуви, с выпуском 5000 единиц
            костюмов и 4000 единиц обуви ежемесячно.
            <br>
            Наш склад отгрузки находится в г.Иваново, ул Свободы д.17
            <br>
            <br>
            Нашу продукцию легко найти почти в каждом рыболовном магазине по всей стране. Область поставок
            каждый год расширяется, благодаря этому нам доверяют клиенты.
            <br>
            В 2021 году мы были на выставке "Охота и рыболовство на Руси" в экспоцентре г.Москва.
            <br>
            За время работы (с 2008 года) мы приобрели бесценный опыт работы в данной сфере. Всегда готовы
            предоставить низкие цены на продукцию. Поэтому звоните, приезжайте и убедитесь в этом сами.
            <br>
            Вы можете встретить обзоры нашего производства на YOUTUBE, канал ХАЧУХА. Там вы познакомитесь
            заочно с нашей командой и производственным процессом.
        </div>
        <div class="about-aside">
            <div class="about-image">
                <img src="/img/about-image.png" alt="" loading="lazy" width="362" height="593">
            </div>
        </div>
    </div>

    <!--👉-->
    <div class="columns columns--grid">
        <!---->
        <div class="column col-3 sm-col-6">
            <div class="about-number">
                <div class="about-number__value">5000</div>
                <div class="about-number__text">костюмов ежемесячно</div>
            </div>
        </div>
        <!---->
        <div class="column col-3 sm-col-6">
            <div class="about-number">
                <div class="about-number__value">2000</div>
                <div class="about-number__text">пар обуви ежемесячно</div>
            </div>
        </div>
        <!---->
        <div class="column col-3 sm-col-6">
            <div class="about-number">
                <div class="about-number__value">16</div>
                <div class="about-number__text">лет успешной деятельности</div>
            </div>
        </div>
        <!---->
        <div class="column col-3 sm-col-6">
            <div class="about-number">
                <div class="about-number__value">100</div>
                <div class="about-number__text">сотрудников в штате</div>
            </div>
        </div>
    </div>

    <!--👉 Фотогалерея-->
    <div class="offset-top">

        <div class="head">
            <div class="head-title">Фотогалерея</div>
        </div>

        <div class="gallery-slider splide">
            <div class="splide__track">
                <div class="splide__list">
                    <!---->
                    <div class="splide__slide">
                        <a href="/temp/showcase.jpg"
                           data-fancybox
                           style="background-image: url(/temp/showcase.jpg)"
                           class="gallery-item"></a>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <a href="/temp/showcase.jpg"
                           data-fancybox
                           style="background-image: url(/temp/showcase.jpg)"
                           class="gallery-item"></a>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <a href="/temp/showcase.jpg"
                           data-fancybox
                           style="background-image: url(/temp/showcase.jpg)"
                           class="gallery-item"></a>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <a href="/temp/showcase.jpg"
                           data-fancybox
                           style="background-image: url(/temp/showcase.jpg)"
                           class="gallery-item"></a>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <a href="/temp/showcase.jpg"
                           data-fancybox
                           style="background-image: url(/temp/showcase.jpg)"
                           class="gallery-item"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<!--👉 Карта-->
<div class="map-block">
<div id="map" class="map"></div>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script>
    ymaps.ready(init);
    let placemarks = [
            {
                balloon: [57.01752156768691, 41.019842499999996],
                hintContent:  '',
                balloonContent:  ''
            }
        ],
        geoObjects = [];

    function init() {
        let centerCoordinate = [57.017562533457664, 41.01716029098509];
        let zoom = 16;

        let map = new ymaps.Map('map', {
            center: centerCoordinate,
            zoom: zoom,
            controls: ['zoomControl'],
            behaviors: ['drag']
        });

        for (let i = 0; i < placemarks.length; i++) {
            geoObjects[i] = new ymaps.Placemark(placemarks[i].balloon, {
                    hintContent: placemarks[i].hintContent,
                    balloonContent: placemarks[i].balloonContent
                },
                {
                    iconLayout: 'default#image',
                    iconImageHref: 'img/marker.svg',
                    iconImageSize: [78, 110],
                    iconImageOffset: [-39, -95]
                });
        }

        let clusterer = new ymaps.Clusterer({
            clusterIconContentLayout: null
        });

        map.geoObjects.add(clusterer);
        clusterer.add(geoObjects);
    }
</script>
<div class="map-container container">
    <div class="map-contact">
        <!---->
        <div class="map-contact__item">
            <div class="map-contact__icon">
                <svg width="18" height="18">
                    <use xlink:href="#icon-input-phone"></use>
                </svg>
            </div>
            <div class="map-contact__title">Телефон:</div>
            <table class="map-contact__phones">
                <tbody>
                <tr>
                    <td><a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>">
                        <?php echo Yii::$app->settings->getPhone() ?></a></td>
                    <td>Михаил</td>
                </tr>
                <tr>
                    <td><a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone2()) ?>"><?php echo Yii::$app->settings->getPhone2() ?></a></td>
                    <td>Анна</td>
                </tr>
                <tr>
                    <td><a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone3()) ?>"><?php echo Yii::$app->settings->getPhone3() ?></a></td>
                    <td>Рабочий</td>
                </tr>
                </tbody>
            </table>
        </div>
        <!---->
        <div class="map-contact__item">
            <div class="map-contact__icon">
                <svg width="18" height="18">
                    <use xlink:href="#icon-location"></use>
                </svg>
            </div>
            <div class="map-contact__title">Производство:</div>
            <div class="map-contact__text">
                <?php echo Yii::$app->settings->getAddress() ?>
            </div>
        </div>
        <!---->
        <div class="map-contact__item">
            <div class="map-contact__icon">
                <svg width="18" height="18">
                    <use xlink:href="#icon-input-email"></use>
                </svg>
            </div>
            <div class="map-contact__title">Электронная почта:</div>
            <div class="map-contact__text">
                <a href="mailto: <?php echo Yii::$app->settings->getEmail() ?>"> 
                    <?php echo Yii::$app->settings->getEmail() ?>
                </a>
            </div>
        </div>
        <!---->
        <div class="map-contact__item">
            <div class="map-contact__icon">
                <svg width="18" height="18">
                    <use xlink:href="#icon-time"></use>
                </svg>
            </div>
            <div class="map-contact__title">График работы:</div>
            <div class="map-contact__text">
                с 10:00 до 17:00 <br>
                <span>Суббота, воскресенье – выходной</span>
            </div>
        </div>
        <!---->
        <div class="map-contact__item">
            <div class="social social--round">
                <a href="<?php echo Yii::$app->settings->getSocial('whatsapp') ?>" class="social-item social-item--wa">
                    <svg width="20" height="20">
                        <use xlink:href="#icon-wa"></use>
                    </svg>
                </a>
                <a href=" <?php echo Yii::$app->settings->getSocial('th') ?>" class="social-item social-item--tg">
                    <svg width="20" height="20">
                        <use xlink:href="#icon-tg"></use>
                    </svg>
                </a>
                <a href=" <?php echo Yii::$app->settings->getSocial('vk') ?>" class="social-item social-item--vk">
                    <svg width="20" height="20">
                        <use xlink:href="#icon-vk"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
</div>