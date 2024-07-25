<?php

use yii\web\View;
use cms\common\models\Profile;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * @var View $this
 * @var Profile $profileForm
 */

?>

<section class="lk">
    <div class="container">
        <div class="lk__wrap">


            <div class="lk__content">
                <div class="lk-tab">
                    <h2 class="lk__title">Профиль</h2>
                    <div class="lk-fos">
                        <div class="reg-form__title">Персональная информация</div>
                        <?php $form = ActiveForm::begin(); ?>
                            <div class="lk-fl__block1">
                                <?= $form->field($profileForm, 'fio', ['options' => ['class' => 'field-lkflform-name']])->textInput(); ?>
                                <?= $form->field($profileForm, 'phone', ['options' => ['class' => 'field-lkflform-phone']])->widget(MaskedInput::class, ['mask' => '+9 (999) 999-99-99', 'options' => ['disabled' => true]]); ?>
                                <?= $form->field($profileForm, 'email', ['options' => ['class' => 'field-lkflform-email']])->textInput(['disabled' => true]); ?>
                            </div>
                            <div class="reg-form__title">Город и адрес</div>
                            <div class="lk-fl__block2">
                                <?= $form->field($profileForm, 'zip', ['options' => ['class' => 'field-lkflform-index']])->textInput()->widget(MaskedInput::class, ['mask' => '[999999]']); ?>
                                <?= $form->field($profileForm, 'city', ['options' => ['class' => 'field-lkflform-city']])->textInput(); ?>
                                <?= $form->field($profileForm, 'address', ['options' => ['class' => 'field-lkflform-address']])->textInput(); ?>
                            </div>

                            <?php if (Yii::$app->user->identity->profile->type === Profile::ORGANIZATION_TYPE) { ?>
                                <div class="reg-form__title">Банковские данные</div>
                                <div class="lk-fl__block3">
                                    <?= $form->field($profileForm, 'bank', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                                    <?= $form->field($profileForm, 'bic', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                                        'mask' => '[99] [99] [99] [999]',
                                    ]); ?>
                                    <?= $form->field($profileForm, 'inn', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                                    <?= $form->field($profileForm, 'kpp', ['labelOptions' => ['class' => 'form-label']])->textInput(); ?>
                                    <?= $form->field($profileForm, 'rs', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                                        'mask' => '[9999] [9999] [9999] [9999] [9999]',
                                    ]); ?>
                                    <?= $form->field($profileForm, 'ks', ['labelOptions' => ['class' => 'form-label']])->textInput()->widget(MaskedInput::class, [
                                        'mask' => '[9999] [9999] [9999] [9999] [9999]',
                                    ]); ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <button type="submit" name="button">Сохранить изменения</button>
                            </div>
                        <?php $form::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>