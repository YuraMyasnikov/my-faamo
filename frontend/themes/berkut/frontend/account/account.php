<?php

use frontend\forms\ProfileForm;
use yii\helpers\Url;

/** @var ProfileForm $signup */
/** @var string $registrationMode */
/** @var \yii\web\View $this */

?>


<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Регистрация-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/']) ?>">Главная</a></li>
            <li>Личный кабинет</li>
        </ul>
    </div>

    <div class="container">

        <h1>Личный кабинет</h1>

        <div class="layout">
            <div class="layout-aside">

                <div class="layout-sticky">
                    <div class="lk-menu">
                        <a href="#" class="lk-menu__item active">Профиль</a>
                        <a href="<?= Url::to(['/account/change-password']) ?>" class="lk-menu__item">Смена пароля</a>
                        <a href="<?= Url::to(['/account/orders']) ?>" class="lk-menu__item">История заказов</a>
                        <a href="<?= Url::to(['/site/logout']) ?>" class="lk-menu__item link">Выйти</a>
                    </div>
                </div>

            </div>
            <div class="layout-content">

                <?php if($registrationMode === 'company'): ?>
                    <?= $this->render('account-company', ['signup' => $signup]) ?>
                <?php else : ?>
                    <?= $this->render('account-user', ['signup' => $signup]) ?>
                <?php endif ?>            

            </div>
        </div>


    </div>


</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->