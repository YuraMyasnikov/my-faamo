<?php

use CmsModule\Subscribers\frontend\forms\SubscribersForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\widgets\ActiveForm;

?>

    <?php $form = ActiveForm::begin(['action' => '/subscribers/create', 'method' => 'post', 'options' => ['class' => 'newsletter-wrp radius']]); ?>
        <?= $form->field($subscribeForm, 'email')->input('email', ['class' => 'newsletter-wrp-input', 'placeholder' => 'Введите ваш Email...'])->label(false); ?>

        <button class="newsletter-wrp-btn"><img src="/images/icons/arr-right-small.svg" alt=""></button>
    <?php $form::end(); ?>
