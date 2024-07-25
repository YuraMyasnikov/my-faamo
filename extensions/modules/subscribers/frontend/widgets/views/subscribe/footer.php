<?php

use CmsModule\Subscribers\frontend\forms\SubscribersForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\widgets\ActiveForm;

?>

<div class="footer__fos">
    <div class="footer__fos-title">Будь в курсе актуальных акций!</div>
    <?php $form = ActiveForm::begin(['action' => '/subscribers/create', 'method' => 'post', 'options' => ['class' => 'footer__fos-form']]); ?>
        <?= $form->field($subscribeForm, 'email')->input('email', ['class' => 'form-control', 'placeholder' => 'Введите ваш Email...'])->label(false); ?>
        <?php
            echo ReCaptcha3::widget([
                'model' => $subscribeForm,
                'attribute' => 'reCaptcha',
                'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                'action' => SubscribersForm::RECAPTCHA_CREATE_ACTION,
                'options' => [
                    'id' => 'footer-page-create-form-recaptcha'
                ]
            ]);
        ?>
        <button class="footer__fos-btn"><img src="/images/icons/arr-right-small.svg" alt=""></button>
    <?php $form::end(); ?>
</div>