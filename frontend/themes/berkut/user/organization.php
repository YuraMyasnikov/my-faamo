<?php

use yii\widgets\ActiveForm;
use cms\frontend\forms\SignupForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\MaskedInputAsset;

/**
 * @var SignupForm $signupForm
 */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

MaskedInputAsset::register($this);
?>

<section class="registration">
    <div class="container">
        <div class="tabs">
            <div class="tabs-nav">
                <?= Html::a('Физ лицо', ['index'], ['class' => 'tabs-nav__item']) ?>
                <?= Html::a('Юр. лицо', ['organization'], ['class' => 'tabs-nav__item active']) ?>
            </div>
            <div class="reg-form__content">
                <div class="reg-ul reg-form">

                    <div class="reg-form__title">Персональная информация</div>
                    <?php $form = ActiveForm::begin([
                        'method' => 'post',
                        'id' => 'reg-fl-form3',
                        'enableAjaxValidation' => true,
                    ]); ?>
                    <div class="reg-ul__block1">
                        <div class="field-regflform-name">
                                <?= $form->field($signupForm, 'organization', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                        </div>
                        <?= $form->field($signupForm, 'email', ['labelOptions' => ['class' => 'form-label']])->input('email'); ?>
                        <?= $form->field($signupForm, 'phone', ['labelOptions' => ['class' => 'form-label']])
                            ->input('tel')
                            ->widget(MaskedInput::class, [
                                'mask' => '+7 ([999]) [999]-[99]-[99]',
                            ]); ?>
                        <div class="field-regflform-name">
                            <?= $form->field($signupForm, 'fio', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                        </div>
                        <?= $form->field($signupForm, 'password', ['labelOptions' => ['class' => 'form-label']])->passwordInput(); ?>
                        <?= $form->field($signupForm, 'verifyPassword', ['labelOptions' => ['class' => 'form-label']])->passwordInput(); ?>
                    </div>

                    <div class="reg-form__title">Город и адрес</div>
                    <div class="reg-ul__block2">
                        <?= $form->field($signupForm, 'zip', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                            'mask' => '[999999]',
                        ]); ?>
                        <?= $form->field($signupForm, 'city', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                        <?= $form->field($signupForm, 'address', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                    </div>
                    <div class="reg-form__title">Банковские данные</div>
                    <div class="reg-ul__block3">
                        <?= $form->field($signupForm, 'bank', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                        <?= $form->field($signupForm, 'bic', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                            'mask' => '[99] [99] [99] [999]',
                        ]); ?>

                        <?= $form->field($signupForm, 'inn', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                            'mask' => '[999] [999] [9999]',
                        ]); ?>
                        <?= $form->field($signupForm, 'kpp', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                            'mask' => '[99] [99] [99] [999]',
                        ]); ?>
                        <?= $form->field($signupForm, 'rs', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                            'mask' => '[9999] [9999] [9999] [9999] [9999]',
                        ]); ?>

                        <?= 
                        $form->field($signupForm, 'reCaptcha', ['enableAjaxValidation' => false])
                        ->widget(ReCaptcha3::class, [
                            'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                            'action' => SignupForm::RECAPTCHA_SIGNUP_ACTION,
                        ])->label(false);
                        ?>
                    </div>
                    <div class="reg-form__confirm">
                        <div class="forms-text">Нажимая кнопку «Зарегистрироваться», вы соглашаетесь с <a class="text-link" href="">политикой конфиденциальности</a></div>

                        <div class="form-group">
                            <button type="submit" name="contact-button">Зарегистрироваться</button>
                        </div>
                    </div>

                    <?php $form::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>