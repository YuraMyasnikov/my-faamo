<?php

use frontend\assets\AccountAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var \frontend\forms\ProfileForm $signup */
/** @var \yii\web\View $this */

AccountAsset::register($this);

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
<div class="lk-box">
    <div class="lk-box__title">Профиль</div>
    <div class="lk-box__items">

        <?php 
            $form = ActiveForm::begin([
                'options' => [
                ]
            ]); 
        ?>

        <div class="lk-box__item">
            <div class="order-item__title">Персональная информация</div>
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
                            ->textInput(['class' => 'input']);
                    ?>
                    <!-- <div class="input-group">
                        <div class="input-label required">ФИО:</div>
                        <input type="text" class="input"
                                value="Константиновский Константин Константинович">
                    </div> -->
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
                    <!-- <div class="input-group">
                        <div class="input-label required">Телефон:</div>
                        <input type="text" class="input" value="+7 928-45-78-54">
                    </div> -->
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
                            ->textInput(['class' => 'input']);
                    ?>
                    <!-- <div class="input-group">
                        <div class="input-label required">E-mail:</div>
                        <input type="text" class="input" value="example@mail.com">
                    </div> -->
                </div>
            </div>
        </div>
        <div class="lk-box__item">
            <div class="order-item__title">Город и адрес</div>
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
        <div class="lk-box__item">
            <ul class="form-footer">
                <li>
                    <a href="<?= Url::to(['/account/change-account-type']) ?>" class="btn btn--grey btn--mobile">Стать юр. лицом</a>
                </li>
                <li>
                    <button type="submit" class="btn btn--mobile">Сохранить изменения</button>
                </li>
            </ul>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
