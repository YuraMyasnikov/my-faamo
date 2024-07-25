<?php

use CmsModule\Shop\common\helpers\PriceHelper;
use yii\helpers\Html;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrderSku;
use CmsModule\Shop\common\models\OrdersStates;
use CmsModule\Shop\common\models\OrderStatusGroups;
use CmsModule\Shop\common\models\OrderViewModel;
use frontend\services\CityCodeResolver;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/**
 * @var Orders $model
 */
\frontend\assets\OrderAsset::register($this);

$orderViewModel = Yii::$container->get(OrderViewModel::class);
$orderViewModel->orderId = $model->id;
$orderViewModel->init();
$orderItem = $orderViewModel->getOrderItems();

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

<div class="js-accordion-item a-history-item">
    <div class="js-accordion-header a-history-item-header">
        <div class="a-history-item-header-top">
            <h4 class="a-history-item-header-top-name">Заказ <?= Html::encode($model->id); ?></h4>
            <h4><?= Html::encode($model->total_discount_price); ?> ₽</h4>
        </div>
        <div class="a-history-item-header-bottom">
            <div class="a-history-item-header-bottom-text">
                <div><?= Yii::$app->formatter->asDatetime($model->created_at, 'd/m/Y'); ?></div>
                <div>Статус: <span class="a-history-item-header-bottom-text-status"><?= Html::encode($model->status->name); ?></span></div>
            </div>
            <div class="a-history-item-header-bottom-img">
                <div class="a-history-item-header-img">
                    <img src="<?= $orderItem[0]['mainImage'] ?? '' ?>">
                </div>

            </div>
        </div>
    </div>
    <div class="js-accordion-body a-history-item-body" style="display:none">
        <div class="a-history-item-body-info">
            <p>Статус заказа: <span><?= Html::encode($model->status->name); ?></span></p>
            <p>Дата и время создания заказа: <span><?= Yii::$app->formatter->asDatetime($model->created_at, 'd/m/Y'); ?></span></p>
        </div>
        <div class="a-history-item-body-items-wrp">
            <?php foreach ($model->orderSku as $sku): ?>
                <?php 
                    /** @var OrderSku $sku  */
                    $product = $sku->sku->product;
                    $productLink = Url::to(['shop/frontend/products/view', 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()]);

                    // TODO ...
                    $sql = "SELECT
                                o.name as option_name, 
                                GROUP_CONCAT(DISTINCT i.name ORDER BY i.name ASC SEPARATOR ', ') as option_values
                            FROM module_shop_sku_multi_options mo
                            LEFT JOIN module_shop_options o on o.id = mo.option_id 
                            LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
                            WHERE mo.sku_id = {$sku->sku_id}
                            GROUP by mo.sku_id, option_name
                            UNION 
                            SELECT
                                o.name as option_name, 
                                GROUP_CONCAT(DISTINCT i.name ORDER BY i.name ASC SEPARATOR ', ') as option_values
                            FROM module_shop_sku_options mo
                            LEFT JOIN module_shop_options o on o.id = mo.option_id 
                            LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
                            WHERE mo.sku_id = {$sku->sku_id}
                            GROUP by mo.sku_id, option_name
                            ";

                    $skuFeatures = Yii::$app->db->createCommand($sql)->queryAll();    
                ?>
            <div class="a-history-item-body-item">
                <div class="a-history-item-body-item-img">
                    <a href="<?= $productLink ?>">
                        <img src="<?= Yii::$app->image->get($product->image_id)->file ?>">
                    </a>
                </div>
                <div class="a-history-item-body-item-info">
                    <h4><a href="<?= $productLink ?>" class="link-line"><span><?= Html::encode($product->name) ?></span></a></h4>
                    <?php foreach(is_array($skuFeatures) ? $skuFeatures : [] as $feature) { ?>
                        <p><?= Html::encode($feature['option_name']) ?>: <span><?= Html::encode($feature['option_values']) ?></span></p>
                    <?php } ?>    
                    <p>Количество: <span><?= $sku->count?></span></p>
                    <p>Цена за единицу: <span><?= PriceHelper::round($sku->price) ?> ₽</span></p>
                    <p>Артикул: <span><?= $product->articul ?></span></p>
                    <h4 class="a-history-item-body-item-info-price"><?= PriceHelper::round($sku->count * $sku->price) ?> ₽</h4>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <?php if (!in_array($model->status->group->id, OrderStatusGroups::REFUSED_PROHIBITED) && !in_array($model->state_id, OrdersStates::REFUSED_PROHIBITED)) { ?>
        <?php $form = ActiveForm::begin(['action' => ['/profile/refusal-order', 'order' => $model->id], 'method' => 'post']) ?>
        <div class="form-group a-history-item-del">
            <button  name="button">Отменить заказ</button>
        </div>
        <?php $form::end() ?>
    <?php } ?>

</div>