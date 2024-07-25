<?php

use CmsModule\Reviews\frontend\forms\ReviewsForm;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="overlay-reviews">
    <div class="popup-fos review-fos">
        <div class="review-fos__close"></div>
        <h2 class="popup-fos__title">Написать отзыв</h2>
        <?php $form = ActiveForm::begin([
            'id' => 'review-form',
            'action' => '/reviews/create',
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'options' => [
                    'tag' => 'div',
                    'class' => '',
                ]
            ]
        ]); ?>
            <div class="review-fos__block">
                <?= $form->field($reviewForm, 'fio', ['options' => ['class' => 'field-reviewform-name']])->textInput(); ?>
                <?= $form->field($reviewForm, 'order_number', ['options' => ['class' => 'field-reviewform-order']])->input('number'); ?>
                <?= $form->field($reviewForm, 'email', ['options' => ['class' => 'field-reviewform-email']])->input('email'); ?>
                <?= $form->field($reviewForm, 'review_text', ['options' => ['class' => 'field-reviewform-body']])->textarea(['rows' => '4']); ?>
                    <?= $form->field($reviewForm, 'grade', ['options' => ['class' => 'field-reviewform-rate'],])->radioList([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1], [
                            'class' => 'rating-area',
                        'item' => function ($index, $label, $name, $checked, $value) {
                                $id = 'star-'. $value;
                                return Html::radio($name, $checked, ['value' => $value, 'id' => $id]) .
                                        Html::label('', $id, ['class' => 'icon-star']);
                        },
                    ]); ?>
            </div>
            <?= $form->field($reviewForm, 'photo[]')->fileInput(['multiple' => true]); ?>

            <?php
                echo ReCaptcha3::widget([
                    'model' => $reviewForm,
                    'attribute' => 'reCaptcha',
                    'siteKey' => Yii::$app->reCaptcha->siteKeyV3,
                    'action' => ReviewsForm::RECAPTCHA_CREATE_ACTION,
                    'options' => [
                        'id' => 'reviews-page-create-form-recaptcha'
                    ]
                ]);
            ?>
            <!-- <div class="mb-3 field-reviewform-file required">

                <input type="file" id="reviewform-file" class="form-control" multiple name="ReviewForm[file]" aria-required="true">
                <div class="invalid-feedback"></div>

            </div> -->
            <div class="reg-form__confirm">
                <div class="forms-text">Нажимая кнопку «Отправить», вы соглашаетесь с <a class="text-link" href="">политикой&nbsp;конфиденциальности</a></div>
                <div class="form-group">
                    <button type="submit" name="contact-button">Отправить отзыв</button>
                </div>
            </div>
        <?php $form::end(); ?>
    </div>
</div>