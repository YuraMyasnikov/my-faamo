<?php

use CmsModule\Shop\common\models\Delivery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

?>

<section class="order">
    <div class="container">
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['/orders/store-org'],
            'enableAjaxValidation' => true
        ]); ?>
            <div class="order__content">
                <div class="order__tabs">
                    <div class="enter-block">
                        <div class="enter-block__title">Уже есть аккаунт?</div>
                        <a href="" class="enter-block__link">Авторизоваться</a>
                    </div>
                    <div class="tabs">
                        <div class="tabs-nav">
                            <a class="tabs-nav__item" href="<?= Url::to(['/orders/create']); ?>">Физ. лицо</a>
                            <a class="tabs-nav__item active" href="<?= Url::to(['/orders/create-org']); ?>">Юр. лицо</a>
                        </div>
                    </div>

                    <div class="tabs-content">
                        <div class="tab-page">
                            <h2 class="reg-form__title">Данные заказчика:</h2>
                            <div class="form-block3">
                                <?= $form->field($orderDataForm, 'form_sob', ['options' => ['class' => 'field-orderdataform-form_sob']])->dropDownList([1 => 'ИП', 2 => 'ООО', 3 => 'ОАО']); ?>
                                <?= $form->field($orderDataForm, 'organization', ['options' => ['class' => 'field-orderdataform-organization']])->textInput(); ?>
                                <?= $form->field($orderDataForm, 'inn', ['options' => ['class' => 'field-orderdataform-inn']])->textInput()->widget(MaskedInput::class, [
                                    'mask' => '[999] [999] [9999]',
                                ]); ?>
                                <?= $form->field($orderDataForm, 'kpp', ['options' => ['class' => 'field-orderdataform-rs']])->textInput()->widget(MaskedInput::class, [
                                    'mask' => '[99] [99] [99] [999]',
                                ]); ?>
                                <?= $form->field($orderDataForm, 'bank', ['options' => ['class' => 'field-orderdataform-bank']])->textInput(); ?>
                                <?= $form->field($orderDataForm, 'rs', ['options' => ['class' => 'field-orderdataform-rs']])->textInput()->widget(MaskedInput::class, [
                                    'mask' => '[9999] [9999] [9999] [9999] [9999]',
                                ]); ?>
                                <?/*= $form->field($orderDataForm, 'ks', ['options' => ['class' => 'field-orderdataform-rs']])->textInput()->widget(MaskedInput::class, [
                                    'mask' => '[9999] [9999] [9999] [9999] [9999]',
                                ]); */?> <!--выведен не был, добавить чтобы обрабатывался-->
                                <?= $form->field($orderDataForm, 'bic', ['options' => ['class' => 'field-orderdataform-bic']])->textInput()->widget(MaskedInput::class, [
                                    'mask' => '[99] [99] [99] [999]',
                                ]); ?>
                                <?= $form->field($orderDataForm, 'fio', ['options' => ['class' => 'field-orderdataform-fio']])->textInput(); ?>
                                <?= $form->field($orderDataForm, 'email', ['options' => ['class' => 'field-orderdataform-email']])->textInput(); ?>
                                <?= $form->field($orderDataForm, 'phone', ['options' => ['class' => 'field-orderdataform-phone']])
                                    ->widget(MaskedInput::class, [
                                        'mask' => '+7 ([999]) [999]-[99]-[99]',
                                        'options' => [
                                            'value' => mb_substr($orderDataForm->phone ?? '', 1)
                                        ]
                                    ]); ?>
                            </div>
                            <h2 class="reg-form__title">Адрес доставки</h2>
                            <div class="form-block2">
                                <?= $form->field($orderDataForm, 'zip')->textInput()->widget(MaskedInput::class, [
                                    'mask' => '[999999]',
                                ]); ?>
                                <?= $form->field($orderDataForm, 'city')->textInput(); ?>
                                <?= $form->field($orderDataForm, 'address')->textInput(); ?>
                            </div>

                            <h2 class="reg-form__title">Способы доставки</h2>
                            <?= $form->field($orderDataForm, 'delivery_id')->radioList($deliveryList, [
                                'class' => 'choosing-block',
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $delivery = Delivery::findOne(['id' => $value]);
                                    $image = Yii::$app->image->getFile($delivery ? $delivery->image_id : null);
                                    $return = '
                                            <label class="delivery-item" for="delivery_' . $index . '">
                                            <div class="delivery-item__img">
                                                <img src="' . $image . '" alt="' . $label . '">
                                            </div>
                                            <div class="delivery-item__radio">
                                                <input id="delivery_' . $index . '" type="radio" name="' . $name . '" value="' . $value . '" tabindex="7">
                                                <span>' . $label . '</span>
                                            </div>
                                        </label>';

                                    return $return;
                                }
                            ])->label(false); ?>

                            <h2 class="reg-form__title">Способы оплаты</h2>
                            <?= $form->field($orderDataForm, 'payment_id')->radioList($paymentsList, [
                                'class' => 'choosing-block',
                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $return = '
                                        <label class="payment-item" for="payment_' . $index . '">
                                            <input id="payment_' . $index . '" type="radio" name="' . $name . '" value="' . $value . '" tabindex="15">
                                            <span class="payment-item__title">' . $label . '</span>
                                        </label>';

                                    return $return;
                                }
                            ])->label(false); ?>

                            <h2 class="reg-form__title">Комментарий к заказу</h2>
                            <?= $form->field($orderDataForm, 'comment', ['options' => ['class' => 'field-orderdataform-comment']])->textarea([
                                'placeholder' => "Оставьте комментарий",
                                'cols' => "30",
                                'rows' => "3",
                                "tabindex" => "20"
                            ])->label(false); ?>
                        </div>
                    </div>
                </div>


                <div class="summary-block">
                    <div class="order-summary">
                        <div class="order-summary__title">Ваш заказ</div>
                        <div class="cart-summary__list">
                            <div class="cart-summary__row">
                                <div class="cart-summary__row-title">товаров: <?= Html::encode(Yii::$app->basket->count()) ?> шт.</div>
                                <div class="cart-summary__row-value"><?= Html::encode(Yii::$app->basket->totalPrice()) ?> <span class="icon-rub"></span></div>
                            </div>
                            <div class="cart-summary__row">
                                <div class="cart-summary__row-title">Скидка</div>
                                <div class="cart-summary__row-value">0 <span class="icon-rub"></span></div>
                            </div>
                        </div>
                        <div class="cart-summary__row cart-result">
                            <div class="cart-result__title">Итого: </div>
                            <div class="cart-result__value"><?= Html::encode(Yii::$app->basket->totalPrice()) ?> <span class="icon-rub"></span></div>

                        </div>
                        <div class="cart-promo uncorrect">
                            <?= $form->field($orderDataForm, 'promocode', ['options' => ['class' => 'field-orderdataform-promocode']])->textInput()->label('Ваш промокод:'); ?>
                            <p class="promocode-warning"></p>
                            <button class="cart-promo__button" type="button" id="get-promocode">Применить</button>
                        </div>
                        <!---
                            Не знаю к такому элементу правильно добавлять класс, для ситуации коррекстного и некорректного промокода
                            сделала ко всему блоку для ввода промокода. Классы correct и uncorrect соответственно
                            --->
                        <div class="form-group">
                            <button type="submit" name="order-fl-button" tabindex="28">Оформить заказ</button>
                        </div>
                    </div>
                    <div class="order-disclaimer">
                        <p>Нажимая на кнопку «Оформить заказ», вы подтверждаете согласие на обработку персональных данных в соответствии с <a class="text-link" href="">Пользовательским соглашением</a></p>
                    </div>
                </div>

            </div>
        <?php $form::end(); ?>
    </div>
</section>