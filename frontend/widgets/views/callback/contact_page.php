<?php

use frontend\forms\CallbackForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * @var CallbackForm $callbackForm
 */

?>

<div class="contacts__fos">
    <div class="contacts__fos-title">Обратная связь</div>
    <?php $form = ActiveForm::begin(['action' => CallbackForm::FORM_ACTION, 'method' => 'post', 'id' => 'contact-form']); ?>

    <?= $form->field($callbackForm, 'fio', ['options' => ['class' => 'contactform-name']])->textInput(['autofocus' => true]) ?>
    <?= $form->field($callbackForm, 'email', ['options' => ['class' => 'contactform-email']])->input('email') ?>
    <?= $form->field($callbackForm, 'phone', ['options' => ['class' => 'contactform-phone']])->widget(MaskedInput::class, ['mask' => '+7 (999) 999-99-99']) ?>
    <?= $form->field($callbackForm, 'comment', ['options' => ['class' => 'contactform-body']])->textarea(['rows' => 6]) ?>

    <?php
        echo ReCaptcha3::widget([
            'model' => $callbackForm,
            'attribute' => 'reCaptcha',
            'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
            'action' => CallbackForm::FORM_ACTION,
            'options' => [
                'id' => 'callback-page-contact-form-recaptcha'
            ]
        ]);
    ?>

    <div class="forms-text">Нажимая кнопку «Отправить», вы соглашаетесь с <a class="text-link" href="">политикой&nbsp;конфиденциальности</a></div>

    <div class="form-group">
        <button type="submit" name="contact-button">Отправить</button>
    </div>

    <?php ActiveForm::end(); ?>
</div>