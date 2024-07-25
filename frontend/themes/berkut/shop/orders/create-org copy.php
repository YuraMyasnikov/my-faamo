<?php

use CmsModule\Shop\frontend\forms\OrderDataForm;
use Symfony\Component\Console\Input\Input;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Url;

?>

<a href="<?= Url::to(['/orders/create']); ?>">Физ лицо</a><br>
<a href="<?= Url::to(['/orders/create-org']); ?>">Юр лицо</a>

<section>
    <h1>Оформление заказа</h1>
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['api/order/create'],
        'enableAjaxValidation' => true
    ]); ?>

    <h2>Данные заказчика:</h2>
    <?= $form->field($orderDataForm, 'form_sob')->dropDownList([1 => 'ИП', 2 => 'ООО', 3 => 'ОАО']); ?>
    <?= $form->field($orderDataForm, 'organization')->textInput(); ?>
    <?= $form->field($orderDataForm, 'inn')->textInput(); ?>
    <?= $form->field($orderDataForm, 'kpp')->textInput(); ?>
    <?= $form->field($orderDataForm, 'bank')->textInput(); ?>
    <?= $form->field($orderDataForm, 'rs')->textInput(); ?>
    <?= $form->field($orderDataForm, 'bic')->textInput(); ?>
    <?= $form->field($orderDataForm, 'fio')->textInput(); ?>
    <?= $form->field($orderDataForm, 'email')->textInput(); ?>
    <?= $form->field($orderDataForm, 'phone')->textInput(); ?>

    <h2>Адрес доставки:</h2>
    <?= $form->field($orderDataForm, 'zip')->textInput(); ?>
    <?= $form->field($orderDataForm, 'city')->textInput(); ?>
    <?= $form->field($orderDataForm, 'address')->textInput(); ?>

    <h2>Способы доставки:</h2>
    <?= $form->field($orderDataForm, 'delivery_id')->radioList($deliveryList, [
        'item' => function ($index, $label, $name, $checked, $value) {
            $return = '';
            $return .= '<input id="' . $value . '" type="radio" name="' . $name . '" value="' . $value . '"
                  tabindex="3">';
            $return .= '<label for="' . $value . '">';
            $return .= $label;
            $return .= '</label>';
            $return .= '';
            return $return;
        }
    ]); ?>

    <h2>Оплата:</h2>
    <?= $form->field($orderDataForm, 'payment_id')->radioList($paymentsList, [
        'item' => function ($index, $label, $name, $checked, $value) {
            $return = '';
            $return .= '<input id="' . $label . '" type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
            $return .= '<label for="' . $label . '">';
            $return .= $label;
            $return .= '</label>';
            $return .= '';
            return $return;
        }
    ]); ?>

    <h2>Комментарий к заказу</h2>
    <?= $form->field($orderDataForm, 'comment')->textarea([
        'placeholder' => "Оставьте комментарий",
        'cols' => "30",
        'rows' => "3"
    ]); ?>

    <?= $form->field($orderDataForm, 'promocode')->textInput(); ?>
    <button>Применить</button>
    <p></p>

    <button>Оформить заказ</button>

    <input type="checkbox" id="main_fos_cbx">
    <label for="main_fos_cbx">Пользовательским соглашением</a>.</label>


    <?php $form::end(); ?>

</section>