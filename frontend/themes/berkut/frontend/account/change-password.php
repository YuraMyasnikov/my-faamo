<?php

use frontend\forms\ChangePasswordForm;
use yii\helpers\Url;
use yii\web\Session;
use yii\widgets\ActiveForm;

/**
 * @var ChangePasswordForm $changePasswordForm
 */

$session = Yii::$container->get(Session::class);
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
                        <a href="<?= Url::to(['/account']) ?>" class="lk-menu__item ">Профиль</a>
                        <a href="" class="lk-menu__item active">Смена пароля</a>
                        <a href="<?= Url::to(['/account/orders']) ?>" class="lk-menu__item">История заказов</a>
                        <a href="<?= Url::to(['/site/logout']) ?>" class="lk-menu__item link">Выйти</a>
                    </div>
                </div>

            </div>
            <div class="layout-content">

                <?php $form = ActiveForm::begin(['id' => 'lk-fl-password-form', 'method' => 'post']); ?>
                <div class="lk-box">
                    <div class="lk-box__title">Сменить пароль</div>
                    <div class="lk-box__items">
                        <div class="lk-box__item">
                            <div class="columns">
                                <div class="column col-12">
                                    <?php 
                                        echo $form
                                            ->field($changePasswordForm, 'currentPassword', [
                                                'options' => ['class' => 'input-group'],
                                                'labelOptions' => [
                                                    'class' => 'input-label required'
                                                ]
                                            ])
                                            ->passwordInput([
                                                'class' => 'input'
                                            ]); 
                                    ?>
                                    <!-- <div class="input-group">
                                        <div class="input-label required">Старый пароль:</div>
                                        <input type="text" class="input">
                                    </div> -->
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column col-6 md-col-12">
                                    <?php 
                                        echo $form
                                            ->field($changePasswordForm, 'newPassword', [
                                                'options' => ['class' => 'input-group'],
                                                'labelOptions' => [
                                                    'class' => 'input-label required'
                                                ]
                                            ])
                                            ->passwordInput([
                                                'class' => 'input'
                                            ]); 
                                    ?>
                                    <!-- <div class="input-group">
                                        <div class="input-label required">Новый пароль:</div>
                                        <input type="text" class="input">
                                    </div> -->
                                </div>
                                <div class="column col-6 md-col-12">
                                    <?php 
                                        echo $form
                                            ->field($changePasswordForm, 'verifyNewPassword', [
                                                'options' => ['class' => 'input-group'],
                                                'labelOptions' => [
                                                    'class' => 'input-label required'
                                                ]
                                            ])
                                            ->passwordInput([
                                                'class' => 'input'
                                            ]); 
                                    ?>
                                    <!-- <div class="input-group">
                                        <div class="input-label required">Новый пароль ещё раз:</div>
                                        <input type="text" class="input">
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="lk-box__item">
                            <ul class="form-footer">
                                <li></li>
                                <li>
                                    <button type="submit" class="btn btn--mobile">Сохранить изменения</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php $form::end(); ?>

            </div>
        </div>


    </div>


</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->