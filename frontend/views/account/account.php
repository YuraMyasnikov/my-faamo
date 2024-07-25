<?php

use frontend\services\CityCodeResolver;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Html;

/** @var ProfileForm $signup */
/** @var string $registrationMode */
/** @var \yii\web\View $this */

\frontend\assets\MaskAsset::register($this);

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
            <h3>Профиль</h3>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'popup-form'],
            ]); ?>
            <h4>Персональная информация</h4>

            <?= $form->field(
                $signup,
                'fio',
                [
                    'options' => [
                        'class' => 'popup-form-item'
                    ],
                    'template' => '<label for="f-name">Ф.И.О.:</label>{input}{hint}{error}',
                ]
            )
                ->textInput([
                    'id' => 'f-name',
                    'class' => 'radius',
                    'autocomplete' => 'off',
                    'required' => true
                ]) ?>

            <div class="form-cols">
                <?= $form->field(
                    $signup,
                    'phone',
                    [
                        'options' => [
                            'class' => 'popup-form-item'
                        ],
                        'template' => '<label for="f-phone">Телефон:</label>{input}{hint}{error}',
                    ]
                )
                    ->textInput([
                        'id' => 'f-name',
                        'class' => 'radius mask-phone',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
                <?= $form->field(
                    $signup,
                    'email',
                    [
                        'options' => [
                            'class' => 'popup-form-item'
                        ],
                        'template' => '<label for="f-email">E-mail:</label>{input}{hint}{error}',
                    ]
                )
                    ->textInput([
                        'id' => 'f-email',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
            </div>

            <h4>Город и адрес</h4>

            <div class="form-cols">
                <?= $form->field(
                    $signup,
                    'zip',
                    [
                        'options' => [
                            'class' => 'popup-form-item'
                        ],
                        'template' => '<label for="f-zip">Индекс:</label>{input}{hint}{error}',
                    ]
                )
                    ->textInput([
                        'id' => 'f-zip',
                        'class' => 'radius mask-zip',
                        'autocomplete' => 'off',
                    ]) ?>
                <?= $form->field(
                    $signup,
                    'city',
                    [
                        'options' => [
                            'class' => 'popup-form-item'
                        ],
                        'template' => '<label for="f-city">Город:</label>{input}{hint}{error}',
                    ]
                )
                    ->textInput([
                        'id' => 'f-city',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                    ]) ?>
            </div>
            <?= $form->field(
                $signup,
                'address',
                [
                    'options' => [
                        'class' => 'popup-form-item'
                    ],
                    'template' => '<label for="f-address">Адрес:</label>{input}{hint}{error}',
                ]
            )
                ->textInput([
                    'id' => 'f-address',
                    'class' => 'radius',
                    'autocomplete' => 'off',
                ]) ?>

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
