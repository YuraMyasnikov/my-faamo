<?php

use yii\helpers\Url;

$this->title = 'Корзина пуста';
$this->params['breadcrumbs'][] = 'Корзина';

?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Корзина пуста</span>
    </div>
</div>
<div class="catalog-page width-default bx-center">
    <h1 class="center-text">Корзина пуста</h1>
</div>
