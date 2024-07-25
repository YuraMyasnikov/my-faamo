<?php

use yii\helpers\Url;

?>
<!--📰 Доставка и оплата-->
 <div class="content">

<!--🤟 Хлебные крошки-->
<div class="container">
    <ul class="breadcrumbs">
        <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
        <li>Доставка и оплата</li>
    </ul>
</div>

<div class="container">

    <h1>Доставка и оплата</h1>

    <!--👉-->
    <div class="delivery-box">
        <div class="delivery-box__content">
            <h2>Условия</h2>
            <ul>
                <li>Мы работаем с физическими и юридическими лицами, без НДС.</li>
                <li>Заказ от 1 штуки, оптовые цены - при заказе от <?= Yii::$app->params['basket.calculator.price_types']['small_wholesale_price']['max'] + 1 ?> рублей.</li>
                <li>Расцветки могут отличаться от представленных на сайте. Товар отгружается в ассортименте
                    производимых цветов, но мы стараемся учесть Ваши пожелания.
                </li>
                <li>Отгружаем товар до 3 дней, в зависимости от наличия на складе и объема заказа.</li>
                <li>Заказ оформляется на сайте через корзину.</li>
                <li>Весь ассортимент продается поштучно, без размерных рядов. Вся информация отражена в
                    каталоге сайта.
                </li>
                <li>В случае брака возможен возврат товара или скидка. Замена и возврат по причине не
                    понравившейся расцветки не допускается!
                </li>
            </ul>
            <b>Инструкцию о том, как сделать заказ в нашем интернет-магазине смотрите по этой <a
                    href="<?php echo Url::to(['/site/how-to-order'])?>">ссылке.</a></b>
        </div>
        <div class="delivery-box__aside">
            <img src="/img/delivery-image.jpg" width="397" height="463" alt="" loading="lazy">
        </div>
    </div>

    <!--👉-->
    <div class="delivery-box">
        <div class="delivery-box__aside">
            <img src="/img/payment-image.jpg" width="397" height="340" alt="" loading="lazy">
        </div>
        <div class="delivery-box__content">
            <h2>Оплата</h2>
            <b>Оплата производится в течение 2 рабочих дней после выставления счета.</b>
            <ul>
                <li>На банковскую карту в течении 24ч. с момента предоставления вам чека об отправке;</li>
                <li>На расчетный счет (+6% к сумме заказа);</li>
                <li>Наличными при самовывозе;</li>
                <li>Наши реквизиты вы можете найти в разделе Контакты;</li>
            </ul>
            <blockquote>
                Внимательно проверяйте реквизиты! Наша почта <a href="mailto:<?php echo Yii::$app->settings->getEmail() ?>"><?= Yii::$app->settings->getEmail()?>!</a>
                Поле «Сообщение» оставляйте пустым!
            </blockquote>
        </div>
    </div>

    <!--👉-->
    <div class="delivery-box">
        <div class="delivery-box__content">
            <h2>Доставка и получение товара</h2>
            <b>Оплата производится в течение 2 рабочих дней после выставления счета.</b>
            <ul>
                <li>
                    <h4>Почта России</h4>
                    100% предоплата. Рассчитать стоимость можно на калькуляторе нажмите здесь.
                </li>
                <li>
                    <h4>Самовывоз</h4>
                    <?= Yii::$app->settings->getAddress()?>
                </li>
                <li>
                    <h4>Транспортные компании</h4>
                    Бесплатная доставка до транспортной компании по городу Иваново. Вы можете выбрать любую
                    удобную для себя транспортную компанию, представительство которой есть в городе Иваново
                </li>
            </ul>
        </div>
    </div>

    <h2>Список транспортных компаний:</h2>

    <!--👉 Список транспортных компаний-->
    <div class="columns columns--element">
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-jde.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«ЖелДор Экспедиция»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-linii.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«Деловые линии»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-cargo.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«CAR-GO!»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-pek.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«ПЭК»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-tv.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«Транс-Вектор»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-gtd.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«GTD»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-ratek.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«Ратэк»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-energia.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«Энергия»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-cdek.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«СДЭК»</a>
            </div>
        </div>
        <!---->
        <div class="column col-3 md-col-4 sm-col-6">
            <div class="delivery-item">
                <div class="delivery-item__image">
                    <img src="/img/delivery-kit.svg" alt="" loading="lazy">
                </div>
                <a href="" class="delivery-item__title">«КИТ»</a>
            </div>
        </div>
    </div>

</div>


</div>