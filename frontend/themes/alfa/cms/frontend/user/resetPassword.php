<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Изменение пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Изменение пароля</span>
    </div>
</div>

<div class="catalog-page">
    <h1 class="center-text">Изменение пароля</h1>
    <div class="block40 bx-center">
        <p class="offset3 center-text">Пожалуйста, введите свой новый пароль.</p>

        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','options' => ['class' => 'popup-form'],]); ?>
        <div class="popup-form-item">
            <?= $form->field(
                $model, 'password',
                [
                    'options' => [
                        'class' => 'popup-form-item example_popup-form-item'
                    ],
                    'template' => '<label for="login-password">Новый пароль:</label>{input}{hint}{error}',
                ])
                ->passwordInput([
                    'id' => 'login-password',
                    'class' => 'radius',
                    'autocomplete' => 'off',
                    'required' => true
                ]) ?>
        </div>

        <p class="center-text">
            <?= Html::submitButton('Сохранить', ['class' => 'btn-bg black radius']) ?>
        </p>
        <?php ActiveForm::end(); ?>
    </div>
</div>