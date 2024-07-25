<?php

use CmsModule\Shop\common\helpers\PriceHelper;
use CmsModule\Shop\common\models\Delivery;
use CmsModule\Shop\common\models\Payments;
use frontend\assets\OrderAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this  
 * @var \CmsModule\Shop\frontend\forms\UserOrderCreateForm $orderDataForm 
 */

OrderAsset::register($this);

$transportCompanies = array_reduce($deliveryList, function($result, Delivery $delivery) {
    $image = Yii::$app->image->get($delivery->image_id);
    $result[] = [
        'id' => $delivery->id,
        'code' => $delivery->code,
        'name' => $delivery->name,
        'img_url' => $image ? $image-> file : '',
    ];
    return $result;
}, []);

$paymentTypes = array_reduce($paymentsList, function($result, Payments $payment) {
    $result[] = [ 'id' => $payment->id, 'name' => $payment->name];
    return $result;
}, []);

?>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Оформление заказа-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="">Главная</a></li>
            <li><a href="">Корзина</a></li>
            <li>Оформление заказа</li>
        </ul>
    </div>

    <div class="container">

        <h1>Оформление заказа</h1>

        <?php 
            $form = ActiveForm::begin([
                'method' => 'post',
                'action' => ['/orders/store-org'],
                'enableAjaxValidation' => true,
                'id' => 'create-order-form',
                'options' => [
                    'data-transport-companies' => $transportCompanies,
                    'data-payment-types' => $paymentTypes,
                    'data-delivery-price' => 0,
                    'data-total-price-with-discount' => $totalPriceWithDiscount,
                ]
            ]); 
        ?>

        <input type="hidden" id="kladr_tkId" name="kladr[tkId]" value="" />
        <input type="hidden" id="kladr_number" name="kladr[number]" value="" />
        <input type="hidden" id="kladr_zip" name="kladr[zip]" value="" />
        <input type="hidden" id="kladr_city" name="kladr[city]" value="" />

        <div class="layout">
            <div class="layout-content">

                <!--🤟 order-header-->
                <div class="order-header">
                    <div class="order-tabs">
                        <a href="/orders/create" class="order-tabs__item">Физ. лицо</a>
                        <a href="" class="order-tabs__item active">Юр. лицо</a>
                    </div>
                    <?php if (Yii::$app->user->isGuest) :?>
                    <ul class="order-user">
                        <li>Уже есть аккаунт?</li>
                        <li>
                            <a href="<?= \yii\helpers\Url::to(['/site/login'])?>">
                                <svg width="17" height="17">
                                    <use xlink:href="#icon-login"></use>
                                </svg>
                                Авторизоваться
                            </a>
                        </li>
                    </ul>
                    <?php endif;?>
                </div>

                <!--🤟 order-block-->
                <div class="order-items">

                    <div class="order-item">
                        <div class="order-item__title">Персональная информация</div>
                        <div class="columns">
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'form_sob', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->dropDownList(['ip' => 'ИП', 'ooo' => 'ООО'], ['class' => 'input']);
                                ?>
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'organization', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">Название организации:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'phone', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">Телефон:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'email', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                            </div>
                            <div class="column col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'fio', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="order-item">
                        <div class="order-item__title">Банковские данные</div>
                        <div class="columns">

                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'bank', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">Название банка:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'bic', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">БИК банка:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'inn', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">ИНН:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'kpp', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">КПП:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'rs', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">Расчётный счёт:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'ks', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input']);
                                ?>
                                <!-- <div class="input-group">
                                    <div class="input-label required">Корреспондентский счет:</div>
                                    <input type="text" class="input">
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!--Способы доставки-->
                    <div class="order-item">
                        <?php 
                            echo $form->field($orderDataForm, 'delivery_id', [
                                'template' => '<div class="order-item__title">{label}</div>{input}{hint}{error}'
                            ])->hiddenInput(); 
                        ?>

                        <div class="columns columns--grid">
                            <?php foreach($transportCompanies as $company) { ?>
                            <div class="column col-6">
                                <label class="box-checkbox box-checkbox--image">
                                    <input type="radio" class="checkbox" name="transport-company" value="<?= $company['id'] ?>" <?= $company['id'] == $orderDataForm->delivery_id ? 'checked' : '' ?> />
                                    <span class="box-checkbox__content">
                                        <span class="box-checkbox__image">
                                            <img src="<?= $company['img_url'] ?>" alt="" />
                                        </span>
                                        <span class="box-checkbox__text"><?= $company['name'] ?></span>
                                    </span>
                                </label>
                            </div>
                            <?php } ?>
                        </div>

                    </div>

                    <!--Адрес доставки-->  
                    <div 
                        class="order-item" 
                        id="address-block" 
                        style="<?php if(!$orderDataForm->delivery_id): ?>display: none;<?php endif?>"
                    >
                        <div class="order-item__title">Адрес доставки</div>
                        <div 
                            class="columns" 
                            id="address-block__form" 
                            style="<?= (empty($orderDataForm->kladr) || empty($orderDataForm->full_address)) ? '' : 'display: none;'  ?>"
                        >
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'city', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->label(false)
                                        ->hiddenInput(['class' => 'input']);
                                ?>
                            </div>
                            <div class="column col-6 md-col-12">
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'zip', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->label(false)
                                        ->hiddenInput(['class' => 'input']);
                                ?>
                            </div>
                            <div class="column col-12" >
                                <?php 
                                    echo $form
                                        ->field($orderDataForm, 'address', [
                                            'options' => ['class' => 'input-group'],
                                            'labelOptions' => [
                                                'class' => 'input-label required'
                                            ]
                                        ])
                                        ->textInput(['class' => 'input', 'placeholder' => 'Введите город, улицу ...',]);
                                ?>
                            </div>
                        </div>                        
                        <div class="columns" id="address-block__kladr" style="<?= (!empty($orderDataForm->kladr) && !empty($orderDataForm->full_address)) ? '' : 'display: none;'  ?> margin-bottom: 1rem;">
                            <?php if(!empty($orderDataForm->kladr) && !empty($orderDataForm->full_address)) { ?>
                                <div 
                                    class="column col-12 default-kladr" 
                                    data-delivery_id="<?= $orderDataForm->delivery_id ?>"
                                    data-kladr="<?= $orderDataForm->kladr ?>"
                                    data-zip="<?= $orderDataForm->zip ?>"
                                    data-city="<?= $orderDataForm->city ?>"
                                    data-address="<?= $orderDataForm->address ?>"
                                    data-full_address="<?= $orderDataForm->full_address ?>"
                                > 
                                    <p><?= $orderDataForm->full_address ?></p>
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

                    <!--Способы оплаты-->
                    <div class="order-item">
                        <?php 
                            echo $form->field($orderDataForm, 'payment_id', [
                                'template' => '<div class="order-item__title">{label}</div>{input}{hint}{error}'
                            ])->hiddenInput(); 
                        ?>
                        <div class="columns columns--grid">
                            <?php foreach($paymentTypes as $paymentType) { ?>
                            <div class="column col-6 md-col-12">
                                <label class="box-checkbox">
                                    <input type="radio" class="checkbox" name="payment-type" value="<?= $paymentType['id'] ?>" <?= $paymentType['id'] == $orderDataForm->payment_id ? 'checked' : '' ?> >
                                    <span class="box-checkbox__content">
                                        <span class="box-checkbox__text"><?= $paymentType['name'] ?></span>
                                    </span>
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!--Комментарий к заказу-->
                    <div class="order-item">
                        <div class="order-item__title">Комментарий к заказу</div>
                        <?php 
                            echo $form
                                ->field($orderDataForm, 'comment', [
                                    'options' => ['class' => 'input-group'],
                                    'labelOptions' => [
                                        'class' => 'input-label required'
                                    ]
                                ])
                                ->textInput(['class' => 'input textarea', 'placeholder' => "Текст комментария..."]);
                        ?>
                    </div>

                </div>


            </div>
            <div class="layout-aside layout-aside--long">

                <div class="layout-sticky">

                    <div class="order-box">
                        <div class="order-box__title">Доставка</div>
                        <ul 
                            id="delivery-calculated-data"
                            class="order-box__list"
                        >
                            <li>Цена <b id="delivery-cost">не известно</b></li>            
                            <li>Ожидание <b id="delivery-days">не известно</b></li>            
                        </ul>

                        <div class="order-box__title">Ваш заказ</div>
                        <ul class="order-box__list">
                            <li>
                                Ценовая категория
                                <b><?= $pricesTypeTitle ?></b>
                            </li>
                            <li>
                                Количество товаров
                                <b><?= $countItemsInBasket ?> <span class="cart-rubl">шт.</span></b>
                            </li>
                            <li>
                                Цена без скидки
                                <b><?= PriceHelper::format($totalPriceWithoutDiscount) ?> <span class="cart-rubl">₽</span></b>
                            </li>
                            <li>
                                Цена со скидкой
                                <b><span><?= PriceHelper::format($calculate['sum']) ?></span> <span class="cart-rubl">₽</span></b>
                            </li>
                            <li>
                                Скидка
                                <b><span><?= PriceHelper::format($discountPrice) ?></span> <span class="cart-rubl">₽</span></b>
                            </li>
                            <?php if($promocode) {  ?>
                                <li>
                                    Скидка по промокоду
                                    <b><span><?= PriceHelper::format($promocode->discount) ?></span> <span class="cart-rubl"><?= $promocode->type == 0 ? '%' : '₽' ?></span></b>
                                </li>
                            <?php } ?>    
                        </ul>

                        <ul class="order-box__total">
                            <li>Итого:</li>
                            <li>
                            <span id="total-price"><?= PriceHelper::format($totalPriceWithDiscount) ?></span> <span class="cart-rubl">₽</span>
                            </li>
                        </ul>

                        <button class="btn btn--large btn--full">
                            Оформить заказ
                        </button>

                    </div>

                    <ul class="order-confirm">
                        <li>
                            <label class="checkbox-label">
                                <input type="checkbox" class="checkbox" name="user_agreement" id="user_agreement" />
                                <span class="checkbox-text checkbox-text--small checkbox-text--border">
                                    Нажимая на кнопку «Оформить заказ», вы подтверждаете согласие на обработку персональных данных в соответствии с
                                    <a href="">Пользовательским соглашением</a>
                                </span>
                            </label>
                        </li>
                        <li>
                            <label class="checkbox-label">
                                <input type="checkbox" class="checkbox" name="subscribe" />
                                <span class="checkbox-text checkbox-text--small checkbox-text--border">
                                    Да, я хочу получать информацию о скидках и специальных предложениях
                                </span>
                            </label>
                        </li>
                    </ul>

                </div>

            </div>
        </div>

        <?php $form::end(); ?>


    </div>


</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->