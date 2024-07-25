<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\loginForms\LoginForm $loginForm */

use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="authent">
    <div class="container">
        <div class="authent__block">
            <div class="authent__content">
                <?php $form = ActiveForm::begin(['id' => 'auth-form']); ?>
                <?= $form->field($loginForm, 'username', ['labelOptions' => ['class' => 'form-label']])->textInput(['autofocus' => true]); ?>
                <?= $form->field($loginForm, 'password', ['labelOptions' => ['class' => 'form-label']])->passwordInput(); ?>

                <div class="reg-form__confirm">
                    <?= $form->field($loginForm, 'rememberMe', ['labelOptions' => ['class' => 'form-label']])->checkbox(); ?>
                    <?= 
                        $form->field($loginForm, 'reCaptcha', ['enableAjaxValidation' => false])
                        ->widget(ReCaptcha3::class, [
                            'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                        ])->label(false);
                    ?>

                    <div class="form-group">
                        <button type="submit" name="contact-button">Войти</button>
                    </div>
                </div>
                <div class="authent__text">
                    <div class="form-text"><a href="<?= Url::to(['/user/request-password-reset']); ?>" class="text-link">Забыли пароль</a></div>
                    <div class="form-text">Если вы ранее не регистрировались — вам нужно <a class="text-link" href="<?= Url::to(['/signup']); ?>">зарегистрироваться</a>.</div>
                </div>
                <div class="forms-text">Нажимая кнопку «Войти», вы соглашаетесь с <a class="text-link" href="">политикой конфиденциальности</a></div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>