<?php

use frontend\services\CityCodeResolver;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Html;

/** @var \yii\web\View $this */

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index', 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Личный кабинет</span>
    </div>
</div>
<div class="catalog-page width-default bx-center">
    <h1 class="center-text">Личный кабинет</h1>
    <div class="lk-page js-sticky-row">
        <div class="lk-page-left">
            <h3>Сменить пароль</h3>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'popup-form']
            ]); ?>
            <div class="form-cols">
                <?= $form->field(
                    $changePasswordForm,
                    'currentPassword',
                    [
                        'options' => [
                            'class' => 'popup-form-item example_popup-form-item'
                        ],
                        'template' => '<label for="lk-password">{label}</label>{input}{hint}{error}',
                    ]
                )
                    ->passwordInput([
                        'id' => 'lk-password',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
            </div>
            <div class="form-cols">
                <?= $form->field(
                    $changePasswordForm,
                    'newPassword',
                    [
                        'options' => [
                            'class' => 'popup-form-item example_popup-form-item'
                        ],
                        'template' => '<label for="lk-password-new">{label}</label>{input}{hint}{error}',
                    ]
                )
                    ->passwordInput([
                        'id' => 'lk-password-new',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
            </div>
            <div class="form-cols">
                <?= $form->field(
                    $changePasswordForm,
                    'verifyNewPassword',
                    [
                        'options' => [
                            'class' => 'popup-form-item example_popup-form-item'
                        ],
                        'template' => '<label for="lk-password-new-r">{label}</label>{input}{hint}{error}',
                    ]
                )
                    ->passwordInput([
                        'id' => 'lk-password-new-r',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
            </div>

            <div class="popup-form-item-col">
                <div class="popup-form-item-col-right">
                    <?= Html::submitButton("Сохранить изменения", ['class' => 'btn-bg full black radius'])?>
                </div>
            </div>
            <?php ActiveForm::end()?>
        </div>
        <?= $this->render('_nav'); ?>
    </div>
    </div>




