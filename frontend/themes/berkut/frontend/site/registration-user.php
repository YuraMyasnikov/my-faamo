<?php

use yii\widgets\ActiveForm;
use frontend\forms\ProfileForm;

/** @var ProfileForm $signup */

$isAssignCommonAddressError = false;
foreach($signup->errors as $field => $errors) {
    if($isAssignCommonAddressError) {
        break;
    }
    if(in_array($field, ['zip', 'city', 'address', 'full_address', 'kladr'])) {
        $signup->addError('address', 'Заполните корректный адрес с указанием индекса, города и улицы');
    }
}

?>

<!--🤟 Физ. лицо-->
<div class="auth-block">
    <?php 
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'auth-block__container long'      
            ]
        ]); 
    ?>
        <div class="auth-block__item">
            <div class="auth-block__title">Персональная информация</div>
            <div class="columns">
                <div class="column col-12">
                    <?php 
                        echo $form
                            ->field($signup, 'fio', [
                                'options' => ['class' => 'input-group'],
                                'labelOptions' => [
                                    'class' => 'input-label required'
                                ]
                            ])
                            ->textInput(['autofocus' => true, 'class' => 'input']);
                    ?>
                </div>
                <div class="column col-6 md-col-12">
                    <?php 
                        echo $form
                            ->field($signup, 'phone', [
                                'options' => ['class' => 'input-group'],

                                'labelOptions' => [
                                    'class' => 'input-label required'
                                ]
                            ])
                            ->textInput(['class' => 'input']);
                    ?>
                </div>
                <div class="column col-6 md-col-12">
                    <?php 
                        echo $form
                            ->field($signup, 'email', [
                                'options' => ['class' => 'input-group'],
                                'labelOptions' => [
                                    'class' => 'input-label required'
                                ]
                            ])
                            ->input('email', ['class' => 'input', 'required' => 'required']);
                    ?>
                </div>
                <div class="column col-6 md-col-12">
                    <?php 
                        echo $form
                            ->field($signup, 'password', [
                                'options' => ['class' => 'input-group'],
                                'labelOptions' => [
                                    'class' => 'input-label required'
                                ]
                            ])
                            ->passwordInput(['class' => 'input']);
                    ?>
                </div>
                <div class="column col-6 md-col-12">
                    <?php 
                        echo $form
                            ->field($signup, 'verifyPassword', [
                                'options' => ['class' => 'input-group'],
                                'labelOptions' => [
                                    'class' => 'input-label required'
                                ]
                            ])
                            ->passwordInput(['class' => 'input']);
                    ?>
                </div>
            </div>
        </div>
        <div class="auth-block__item">
            <div class="auth-block__title">Город и адрес</div>
            <?php 
                echo $form->field($signup, 'zip')->hiddenInput(['class' => 'val-zip'])->label(false);
                echo $form->field($signup, 'city')->hiddenInput(['class' => 'val-city'])->label(false);
                echo $form->field($signup, 'full_address')->hiddenInput(['class' => 'val-full_address'])->label(false);
                echo $form->field($signup, 'kladr')->hiddenInput(['class' => 'val-kladr'])->label(false);
            ?>
            <div class="columns">
                <div class="column col-12">
                    <div id="address-block__form" style="<?= (!empty($signup->kladr) && !empty($signup->full_address)) ? 'display: none;' : ''  ?>">
                        <?php 
                            echo $form
                                ->field($signup, 'address', [
                                    'options' => ['class' => 'input-group'],
                                    'labelOptions' => [
                                        'class' => 'input-label required',
                                    ]
                                ])
                                ->textInput(['class' => 'input input-kladr val-address', 'placeholder' => 'Введите город, улицу ...',]);
                        ?>
                    </div>
                    <div class="columns" id="address-block__kladr" style="<?= (!empty($signup->kladr) && !empty($signup->full_address)) ? '' : 'display: none;'  ?> margin-bottom: 1rem;">
                        <?php if(!empty($signup->kladr) && !empty($signup->full_address)) { ?>
                            <div class="column col-12"> 
                                <p><?= $signup->full_address ?></p>
                                <a href="#" id="to-change-kladr">Изменить</a>
                            </div>
                        <?php } ?>    
                    </div>
                    <div id="address-suggestions" class="columns" style="display: none;"> 
                        <h4 class="column" style="width: 100%;">Нажмите на адрес для того чтобы его выбрать</h4>
                        <ul class="suggestions">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-block__item">
            <ul class="form-footer">
                <li>
                    Нажимая кнопку «Зарегистрироваться», вы
                    соглашаетесь с <a href=""> политикой конфиденциальности</a>
                </li>
                <li>
                    <button type="submit" class="btn btn--mobile">Зарегистрироваться</button>
                </li>
            </ul>
        </div>
    <?php ActiveForm::end(); ?>
</div>