<?php

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\assets\CompleteAsset;

/** @var \yii\web\View $this */

CompleteAsset::register($this);

?>


<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Оформление заказа-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['site/index'])?>">Главная</a></li>
            <li><a href="<?= Url::to(['orders/create'])?>">Оформление заказа</a></li>
            <li>Заказ создан</li>
        </ul>
    </div>

    <div class="container">

        <h1>Создан заказ №<?= intval($orderViewModel?->orderId) ?></h1>

        <div class="layout">
            <div class="layout-content">

                <p>Ваши заказы хранятся в <?= Html::a('истории', Url::to(['/account/orders']))?></p>

                <p id="info-timer" data-url="<?= Url::to(['/account/orders'])?>">автоматичесуий переход будет через 10 секунд</p>

            </div>
        </div>
    </div>
</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->