<?php

use frontend\forms\ProfileForm;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/** @var string $registrationMode */
/** @var ProfileForm $signup */
/** @var View $signup */

\frontend\assets\MaskAsset::register($this);

?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Регистрация</span>
    </div>
</div>
<div class="catalog-page">
    <h1 class="center-text">Регистрация</h1>
    <div class="block40 bx-center">
        <?php $form = ActiveForm::begin([
            /*'action' => ['site/registration'],*/
            'id' => 'registration-form',
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
                'labelOptions' => [],
            ]
        )
            ->textInput([
                'id' => 'red-name',
                'class' => 'radius',
                'autocomplete' => 'off',
                'required' => true
            ]) ?>

            <?= $form->field(
                $signup,
                'phone',
                [
                    'options' => [
                        'class' => 'popup-form-item '
                    ],
                    'labelOptions' => [],
                ]
            )
                ->textInput([
                    'class' => 'radius mask-phone',
                    'autocomplete' => 'off',
                    'required' => true,
                ])
            ?>

            <?= $form->field(
                $signup,
                'email',
                [
                    'options' => [
                        'class' => 'popup-form-item'
                    ],
                    'labelOptions' => [],
                ]
            )
                ->textInput([
                    'id' => 'red-mail',
                    'class' => 'radius',
                    'autocomplete' => 'off',
                    'required' => true
                ]) ?>

            <div class="form-cols">
                <?= $form->field(
                    $signup,
                    'password',
                    [
                        'options' => [
                            'class' => 'popup-form-item example_popup-form-item'
                        ],
                    ]
                )
                    ->passwordInput([
                        'id' => 'red-pass',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
                <?= $form->field(
                    $signup,
                    'verifyPassword',
                    [
                        'options' => [
                            'class' => 'popup-form-item example_popup-form-item'
                        ],
                    ]
                )
                    ->passwordInput([
                        'id' => 'red-varPass',
                        'class' => 'radius',
                        'autocomplete' => 'off',
                        'required' => true
                    ]) ?>
            </div>




        <p class="center-text">
            <?= Html::submitButton("Зарегистрироваться", ['class' => 'btn-bg black radius'])?>
        </p>

        <div class="checkbox-wrp">
            <input type="checkbox" class="" name="checkboxGreen" id="get-appointment-check" checked="">
            <label for="get-appointment-check">Нажимая на кнопку «Записаться», вы подтверждаете согласие на
                обработку персональных данных в соответствии с <a href="<?= Url::to(['/site/politics'])?>" class="link-underline"><span>Пользовательским
                                    соглашением</span></a></label>
        </div>

        <?php ActiveForm::end()?>

    </div>
