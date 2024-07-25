<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Авторизация-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="">Главная</a></li>
            <li>Авторизация</li>
        </ul>
    </div>

    <div class="container">

        <h1>Авторизация</h1>

        <div class="auth-block">
            <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => [
                        'class' => 'auth-block__container'
                    ]
                ]); 
            ?>
                <div class="auth-block__item">
                    <?php  
                        echo $form
                            ->field(
                                $loginForm, 
                                'username',
                                [
                                    'options' => [
                                        'class' => 'input-group'
                                    ],
                                    'template' => '<div class="input-label required">Электронная почта:</div>{input}{hint}{error}',
                                ]
                            )
                            ->input('email', [ 'required' => 'required', 'autofocus' => true,
                                'class' => 'input']);
//                            ->textInput([
//                                'autofocus' => true,
//                                'class' => 'input',
//                            ]);
                    ?>
                    <?php  
                        echo $form
                            ->field(
                                $loginForm, 
                                'password',
                                [
                                    'options' => [
                                        'class' => 'input-group'
                                    ],
                                    'template' => '<div class="input-label required">Пароль:</div>{input}{hint}{error}',
                                ]
                            )
                            ->passwordInput([
                                'class' => 'input',
                            ]);
                    ?>

                    <div class="input-group">
                        <ul class="form-footer form-footer--hor">
                            <li>
                                <label class="checkbox-label">
                                    <?php  
                                        echo $form
                                            ->field(
                                                $loginForm, 
                                                'rememberMe',
                                                [
                                                    'template' => '{input}{hint}{error}',
                                                ]
                                            )
                                            ->checkbox();
                                    ?>
                                    <!-- <input type="checkbox" class="checkbox">
                                    <span class="checkbox-text">Запомнить меня</span> -->
                                </label>
                            </li>
                            <li>
                                <button type="submit" class="btn">Войти</button>
                            </li>
                        </ul>
                    </div>
                    <ul class="form-info">
                        <li>
                            <a href="<?= Url::to(['/cms/frontend/user/request-password-reset']) ?>">Забыли пароль</a>
                        </li>
                        <li>
                            Если вы ранее не регистрировались — вам нужно <a href="<?= Url::to(['/site/registration']) ?>">зарегистрироваться</a>.
                        </li>
                        <li>
                            Нажимая кнопку «Войти», вы соглашаетесь с
                            <a href="<?= Url::to(['/site/politics']) ?>">политикой конфиденциальности</a>
                        </li>
                    </ul>
                </div>
            <?php ActiveForm::end(); ?>
        </div>


    </div>


</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->