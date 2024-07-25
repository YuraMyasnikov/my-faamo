<?php
use yii\helpers\Url;
?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>404</span>
    </div>
</div>
<div class="catalog-page">
    <div class="center-text">
        <h1 class="page404">404</h1>
        <h2>страница не найдена</h2>
    </div>
    <div class="block40 bx-center">
        <p class="offset3 center-text">Такой страницы не существует, либо она была удалена. Чтобы найти нужную
            страницу воспользуйтесь поиском.</p>
        <p class="center-text">
            <a href="<?= Url::to(['/site/index'])?>" class="btn-bg black radius">Перейти на главную</a>
        </p>
    </div>
</div>