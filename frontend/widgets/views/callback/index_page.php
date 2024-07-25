<?php

use frontend\forms\CallbackForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * @var CallbackForm $callbackForm
 */

?>

<section class="fos-section">
    <div class="container section__content">

        <div class="main-fos">
            <h2 class="main-fos__title block-title">Нужна помощь в подборе материалов?</h2>
            <div class="main-fos__text">
                <p>Ни у кого не вызывает сомнений тот факт, что на конечный результат любого ремонта влияют правильно подобранные отделочные материалы.</p>
                <p>Мы предлагаем качественный расширенный подбор требуемых материалов по эстетическим, техническим и ценовым параметрам.</p>
            </div>

            <?php $form = ActiveForm::begin(['action' => CallbackForm::FORM_ACTION, 'method' => 'post', 'options' => ['class' => 'main-form']]); ?>
            <?= $form->field($callbackForm, 'fio')->textInput(); ?>
            <?= $form->field($callbackForm, 'phone')->widget(MaskedInput::class, ['mask' => '+7 (999) 999-99-99']) ?>

            <?php
                echo ReCaptcha3::widget([
                    'model' => $callbackForm,
                    'attribute' => 'reCaptcha',
                    'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                    'action' => CallbackForm::FORM_ACTION,
                    'options' => [
                        'id' => 'callback-page-index-form-recaptcha'
                    ]
                ]);
            ?>

            <button type="submit" class="main-fos__btn btn">Отправить заявку</button>
            <div class="forms-text">Нажимая кнопку «Отправить», вы соглашаетесь с <a class="text-link" href="">политикой&nbsp;конфиденциальности</a></div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="main-fos__tiles">
            <img src="/images/content/tiles-1.png" alt="" class="main-fos__tiles-img">
            <img src="/images/content/tiles-2.png" alt="" class="main-fos__tiles-img">
            <img src="/images/content/tiles-3.png" alt="" class="main-fos__tiles-img">
            <img src="/images/content/tiles-4.png" alt="" class="main-fos__tiles-img">
            <img src="/images/content/tiles-5.png" alt="" class="main-fos__tiles-img">
        </div>

    </div>
</section>