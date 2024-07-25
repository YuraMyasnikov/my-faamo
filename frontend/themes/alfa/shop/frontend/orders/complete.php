<?php

use yii\helpers\Url;

?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Заказ №<?= intval($orderViewModel?->orderId) ?></span>
    </div>
</div>
<div class="catalog-page">
    <h1 class="center-text">Заказ №<?= intval($orderViewModel?->orderId) ?> успешно оформлен</h1>
    <div class="block40 bx-center">
        <p class="offset3 center-text">Отправили Вам письмо на почту</p>
        <p class="center-text">
            <a href="<?= Url::to(['/site/index'])?>" class="btn-bg black radius">Перейти на главную</a>
        </p>
    </div>
</div>