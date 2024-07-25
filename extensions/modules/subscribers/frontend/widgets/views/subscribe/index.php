<?php

use yii\widgets\ActiveForm;
use CmsModule\Subscribers\frontend\forms\SubscribersForm;
use himiklab\yii2\recaptcha\ReCaptcha3;

/**
 * @var SubscribersForm $subscribeForm
 */

?>

<div class="order-mailing">
    <!-- <div class="mailing-icon"></div> -->
    <div class="order-mailing__content">
        <div class="order-mailing__title">
            Подписка на рассылку 
        </div>
        <div class="mailing-fos">
            <?php $form = ActiveForm::begin(['action' => '/subscribers/create', 'method' => 'post', 'class' => 'mailing-fos']); ?>
                <?= $form->field($subscribeForm, 'email')->input('email', ['class' => 'mailing-fos__field', 'placeholder' => 'Введите ваш email и будьте в курсе новостей и акций...'])->label(false); ?>
                <?php
                    echo ReCaptcha3::widget([
                        'model' => $subscribeForm,
                        'attribute' => 'reCaptcha',
                        'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                        'action' => SubscribersForm::RECAPTCHA_CREATE_ACTION,
                        'options' => [
                            'id' => 'index-page-create-form-recaptcha'
                        ]
                    ]);
                ?>
                <button class="mailing-fos__button" name="button">Подписаться</button>
            <?php $form::end(); ?>
        </div>
    </div>
</div>
