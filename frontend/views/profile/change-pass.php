<?php

use frontend\forms\ChangePasswordForm;
use yii\widgets\ActiveForm;

/**
 * @var ChangePasswordForm $changePasswordForm
 */

?>

<section class="lk">
    <div class="container">
        <div class="lk__wrap">
            <?= $this->render('_nav'); ?>

            <div class="lk__content">
                <div class="lk-tab">

                    <h2 class="lk__title">Сменить пароль</h2>
                    <?php $form = ActiveForm::begin(['id' => 'lk-fl-password-form', 'method' => 'post']); ?>
                        <div class="lk-fl__block3">
                            <?= $form->field($changePasswordForm, 'currentPassword', ['options' => ['class' => 'field-regflform-old-password']])->passwordInput(); ?>
                            <?= $form->field($changePasswordForm, 'newPassword', ['options' => ['class' => 'field-regflform-new-password']])->passwordInput(); ?>
                            <?= $form->field($changePasswordForm, 'verifyNewPassword', ['options' => ['class' => 'field-regflform-password-repeat']])->passwordInput(); ?>
                        </div>
                        <div class="form-group">
                            <button name="button">Сохранить изменения</button>
                        </div>

                    <?php $form::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>