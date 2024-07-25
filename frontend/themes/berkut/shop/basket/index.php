<?php

use CmsModule\Shop\common\helpers\PriceHelper;
use frontend\forms\PromocodeForm;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var \yii\web\View $this */
\frontend\assets\BasketAsset::register($this);


?>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Корзина товаров-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/']) ?>">Главная</a></li>
            <li>Корзина</li>
        </ul>
    </div>

    <div class="container">

        <h1>Корзина товаров</h1>

        <div class="layout">
            <div class="layout-content">

                <!--🤟 Доступны под заказ-->
                <div class="basket-items">
                    <?php foreach ($basketProducts as $basketProduct) { 
                        echo $this->render('_item', ['basketProduct' => $basketProduct, 'priceType' => $pricesType]);
                    } ?>
                    
                    <button type="button" class="btn btn--grey btn--mobile js-clear">
                        <svg width="16" height="16">
                            <use xlink:href="#icon-cancel"></use>
                        </svg>
                        Очистить корзину
                    </button>
                </div>

                <?php if(count($basketProductsUnavailableForOrder)) { ?>
                <!--🤟 Недоступны для заказа-->
                <div class="offset-top">
                    <div class="sold js-collapse">
                        <div class="sold-title active js-collapse-title">
                            Недоступны для заказа
                            <svg width="12" height="12">
                                <use xlink:href="#icon-arrow-down"></use>
                            </svg>
                        </div>

                        <div class="sold-content js-collapse-content" style="display:block;">

                            <div class="sold-items">
                                <?php foreach ($basketProductsUnavailableForOrder as $basketProduct) { 
                                    echo $this->render('_item-unavailable', ['basketProduct' => $basketProduct, 'priceType' => $pricesType]);
                                } ?>
                            </div>

                        </div>

                    </div>
                </div>
                <?php } ?>

            </div>
            <div class="layout-aside layout-aside--long">

                <div class="layout-sticky">

                    <div class="order-box">
                        <div class="order-box__title">Ваш заказ</div>
                        <ul class="order-box__list">
                            <li>
                                Ценовая категория
                                <b class="order-price-type-title"><?= $pricesTypeTitle ?></b>
                            </li>
                            <li>
                                Количество товаров
                                <b><span class="basket-counter"><?= $countItemsInBasket ?></span> <span class="cart-rubl">шт.</span></b>
                            </li>
                            <li>
                                Цена без скидки
                                <b><span class="total-sum-without-discount"><?= PriceHelper::format($totalPriceWithoutDiscount) ?></span> <span class="cart-rubl">₽</span></b>
                            </li>
                            <li>
                                Цена со скидкой
                                <b><span class="total-sum"><?= PriceHelper::format($calculate['sum']) ?></span> <span class="cart-rubl">₽</span></b>
                            </li>
                            <li>
                                Скидка
                                <b><span class="discount-price"><?= PriceHelper::format($discountPrice) ?></span> <span class="cart-rubl">₽</span></b>
                            </li>
                        </ul>

                        <div class="promo-code">
                            <div class="promo-code__title">Введите промокод:</div>
                            <?php 
                                $promocodeModel = PromocodeForm::buildForm();
                                $form = ActiveForm::begin(['action' => Url::to(['/site/apply-promocode']), 'method' => 'post']); 
                            ?>
                            <div class="promo-code__form">
                                <!-- <input type="text" class="promo-code__input" placeholder="Промокод"> -->
                                <?php  
                                    echo $form
                                        ->field($promocodeModel, 'promocode', ['template' => '{input}{hint}{error}'])
                                        ->textInput([
                                            'class' => 'promo-code__input',
                                        ]);
                                ?>
                                <button type="submit" class="promo-code__button">
                                    Применить
                                </button>
                            </div>
                            <?php $form::end(); ?>

                            <?php if(!empty($promocodeModel->description)) { ?>
                                <p><?= $promocodeModel->description ?></p>
                            <?php } ?>    
                        </div>

                        <ul class="order-box__total">
                            <li>Итого:</li>
                            <li>
                                <!-- <span id="total-sum"><?= Yii::$app->basket->totalPrice() ?></span> <span class="cart-rubl">₽</span> -->
                                <span id="total-sum" class="total-sum-final"><?= PriceHelper::format($totalPriceWithDiscount) ?></span> <span class="cart-rubl">₽</span>
                            </li>
                        </ul>

                        <a href="<?= Url::to(['orders/create']); ?>" class="btn btn--large btn--full">Перейти к оформлению</a>

                    </div>

                </div>

            </div>
        </div>


    </div>


</div>

<!--🔥 КОНЕЦ ШАБЛОНА-->
