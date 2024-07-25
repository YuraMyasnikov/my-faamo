<?php

use yii\helpers\Url;

?>
<!--📰 Доставка и оплата-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
            <li>Контакты</li>
        </ul>
    </div>

    <div class="container">
        <h1>Контакты</h1>        

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
                        <!--<td><a href="tel:<?php /*= str_replace(' ','', Yii::$app->settings->getPhone2()) */?>"><?php /*echo Yii::$app->settings->getPhone2() */?></a></td>-->
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