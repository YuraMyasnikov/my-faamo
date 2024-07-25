<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Авторизация</span>
    </div>
</div>
<div class="catalog-page">
    <h1 class="center-text">Авторизация</h1>
    <div class="block40 bx-center">
        <p class="offset3 center-text">У вас нет учетной записи? <a href="<?= Url::to(['/site/registration']) ?>" class="link-underline"><span>Зарегистрируйтесь</span></a></p>


            <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'popup-form'],
    ]); ?>

            <?= $form->field(
                    $loginForm,
                    'username',
                    [
                'options' => [
                    'class' => 'popup-form-item'
                ],
                'template' => '<label for="login-mail">Электронная почта: *</label>{input}{hint}{error}',
                ]
            )
                ->textInput([
                        'id' => 'login-mail',
                    'class' => 'radius',
                    'autocomplete' => 'off',
                    'required' => true
                ]) ?>

        <?= $form->field(
            $loginForm,
            'password',
            [
                'options' => [
                    'class' => 'popup-form-item example_popup-form-item'
                ],
                'template' => '<label for="login-mail">Пароль: *</label>{input}{hint}{error}',
            ]
        )
            ->passwordInput([
                'id' => 'login-mail',
                'class' => 'radius',
                'autocomplete' => 'off',
                'required' => true
            ]) ?>

            <div class="popup-form-item-col">
                <div class="popup-form-item-col-left">
                    <?= Html::a('Забыли пароль?', Url::to(['/site/request-password-reset']), ['class' => 'link-underline']) ?>
                </div>
                <div class="popup-form-item-col-right">
                    <?= Html::submitButton('Войти', ['class' => 'btn-bg full black radius', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

    </div>
</div>
