<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\PasswordResetRequestForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Восстановить пароль';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Забытый пароль</span>
    </div>
</div>
<div class="catalog-page">
    <h1 class="center-text">Восстановить пароль</h1>
    <div class="block40 bx-center">
        <p class="offset3 center-text">Пожалуйста, введите свой адрес электронной почты ниже, и мы вышлем вам ссылку для сброса пароля.</p>
        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','options' => ['class' => 'popup-form'],]); ?>
            <div class="popup-form-item">
                <?= $form->field(
                $model,
                'email',
                [
                    'options' => [
                    'class' => 'popup-form-item'
                    ],
                    'template' => '<label for="login-mail"><!--{label}--> Электронная почта:</label>{input}{hint}{error}',
                ])
                ->textInput([
                    'id' => 'login-mail',
                    'class' => 'radius',
                    'autocomplete' => 'off',
                    'required' => true
                ]) ?>
            </div>

            <p class="center-text">
                <?= Html::submitButton('Восстановить', ['class' => 'btn-bg black radius']) ?>
            </p>
        <?php ActiveForm::end(); ?>
    </div>
</div>