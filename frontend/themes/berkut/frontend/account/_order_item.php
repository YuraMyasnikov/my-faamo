<?php

use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use yii\helpers\Html;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrdersStates;
use CmsModule\Shop\common\models\OrderStatusGroups;
use CmsModule\Shop\common\models\OrderViewModel;
use yii\widgets\ActiveForm;


/**
 * @var Orders $model
 */

$orderViewModel = Yii::$container->get(OrderViewModel::class);
$orderViewModel->orderId = $model->id;
$orderViewModel->init();
?>


<!--👉-->
<div class="history js-collapse">
    <div class="history-header">
        <div class="history-header__number js-collapse-title">Заказ <?= Html::encode($model->id); ?></div>
        <div class="history-header__price"><?= Html::encode($model->total_discount_price); ?> ₽</div>
    </div>
    <ul class="history-info">
        <li><?= Yii::$app->formatter->asDatetime($model->created_at, 'short'); ?></li>
        <li>Статус: <b><?= Html::encode($model->status->name); ?></b></li>
    </ul>
    <div class="history-content js-collapse-content">
        <ul class="history-params">
            <li>Статус заказа: <span><b><?= Html::encode($model->status->name); ?></b></span></li>
            <li>Дата и время создания заказа: <span><?= Yii::$app->formatter->asDatetime($model->created_at, 'short'); ?></span></li>
            <li>Способ оплаты: <span><?= Html::encode($model->payment?->name ?? ''); ?></span></li>
            <li>Доставка: <span><?= Html::encode($model->delivery?->name ?? ''); ?> – <b><?= Html::encode($model->delivery_price); ?> ₽</b></span></li>
            <li>Трек-код от транспортной компании: <span><?= $model->track_code ?></span></li>
        </ul>

        <div class="history-basket-items">
            <?php foreach ($orderViewModel->getOrderItems() as $orderItem) { ?>
                <?php 
                    /** @var \CmsModule\Shop\common\models\OrderSku */
                    $orderSku = $orderItem['orderSku'];
                    $sku = $orderSku->sku;
                    $product = $sku->product;

                    $productViewModel = new ProductViewModel();
                    $productViewModel->product_id = $product->id;
                    $productViewModel->product = $product;

                    // TODO ...
                    $sql = "SELECT
                                o.name as option_name, GROUP_CONCAT(i.name SEPARATOR ', ') as option_values
                            FROM module_shop_sku_multi_options mo
                            LEFT JOIN module_shop_options o on o.id = mo.option_id 
                            LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
                            WHERE mo.sku_id = {$sku->id}
                            GROUP by mo.sku_id, option_name";
    
                    $skuFeatures = Yii::$app->db->createCommand($sql)->queryAll();
                ?>
                <!---->
                <div class="history-basket">
                    <div class="history-basket__image">
                        <img src="<?= $orderItem['mainImage'] ?>" alt="" loading="lazy">
                    </div>
                    <div class="history-basket__content">
                        <a href="" class="history-basket__title">
                            <?= Html::encode($orderItem['name']); ?>
                        </a>
                        <ul class="history-basket__params">
                            <li>Цена за единицу: <span><?= Html::encode($orderItem['discountPrice']) ?> ₽</span></li>
                            <?php foreach($productViewModel->getOptionList() as $optionTitle => $optionValues) { ?>
                                <li><?= $optionTitle ?>: <span><?= implode(' ', $optionValues) ?></span></li>
                            <?php } ?>    
                            <?php foreach($skuFeatures as $feature) { ?>
                                <li><?= $feature['option_name'] ?>: <span><?= $feature['option_values'] ?></span></li>
                            <?php } ?>    
                            
                            <li>Артикул: <span><?= $orderItem['articul'] ?></span></li>
                            <li>Количество: <span><?= Html::encode($orderSku->count) ?> </span></li>
                        </ul>
                        <div class="history-basket__price">
                            <?= Html::encode($orderItem['fullDiscountPrice']) ?> ₽
                        </div>
                    </div>
                </div>
            <?php } ?>       
        </div>

    </div>

    <div class="history-footer">
        <div class="history-footer__preview">
            <img src="<?= $orderItem['mainImage'] ?? null ?>" alt="" loading="lazy">
        </div>
        <div class="history-footer__button">
            <?php if (false && !in_array($model->status->group->id, OrderStatusGroups::REFUSED_PROHIBITED) && !in_array($model->state_id, OrdersStates::REFUSED_PROHIBITED)) { ?>
                <?php $form = ActiveForm::begin(['action' => ['/profile/refusal-order', 'order' => $model->id], 'method' => 'post']) ?>
                    <div class="form-group">
                        <button name="button" class="btn btn--full">Отменить заказ</button>
                    </div>
                <?php $form::end() ?>
            <?php } ?>            
        </div>
    </div>
</div>