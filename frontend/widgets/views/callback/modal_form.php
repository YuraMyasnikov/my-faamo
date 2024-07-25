<?php

use frontend\forms\CallbackForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Url;

/**
 * @var CallbackForm $callbackForm
 */

?>

<div class="popup-bg popup getcall-popup" role="alert">
    <div class="popup-bx-center">
        <div class="close popup-close"></div>
        <div class="popup-title">Заказать звонок</div>
        <?php $form = ActiveForm::begin(['action' => CallbackForm::FORM_ACTION, 'method' => 'post']); ?>
        <?= $form->field($callbackForm,
            'fio',
            ['options' => ['class' => 'popup-form-item']])->textInput(); ?>
        <?= $form->field($callbackForm, 'phone', ['options' => ['class' => 'popup-form-item']])->widget(MaskedInput::class, ['mask' => '+7 (999) 999-99-99']) ?>
        <?php
        /*        echo ReCaptcha3::widget([
                    'model' => $callbackForm,
                    'attribute' => 'reCaptcha',
                    'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                    'action' => BookcallForm::FORM_ACTION,
                    'options' => [
                        'id' => 'callback-modal-form-recaptcha'
                    ]
                ]);
                */?>

        <p class="center-text">
            <button type="submit" class="btn-bg black radius">Записаться</button>
        </p>


        <div class="checkbox-wrp">
            <input type="checkbox" class="" name="checkboxGreen" id="get-appointment-check" checked />
            <label for="get-appointment-check">Нажимая на кнопку «Записаться», вы подтверждаете согласие на
                обработку персональных данных в соответствии с <a href="<?= Url::to(['/site/politics'])?>"
                                                                  class="link-underline"><span>Пользовательским
                                соглашением</span></a></label>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>