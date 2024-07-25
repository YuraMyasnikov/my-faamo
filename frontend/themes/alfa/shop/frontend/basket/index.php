<?php

use cms\common\models\Images;
use CmsModule\Shop\common\models\Basket;
use frontend\services\CityCodeResolver;
use yii\base\View;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

[$totalCount, $totalSum] = array_reduce($basketProducts, function($result, Basket $basketProduct) {
    $result[0] += $basketProduct->count;
    $result[1] += $basketProduct->price * $basketProduct->count;
    return $result; 
}, [0,0]);

/** @var View $this */

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>



<?php $this->beginBlock('article-css'); ?>page bg-gray<?php $this->endBlock(); ?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="/" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Корзина</span>
    </div>
</div>

<?php Pjax::begin(['enablePushState' => false,'timeout' => 5000,'id' => 'pjax_basket_form']); ?>
<script>
    $(function () {
        $('.cart-page-item-info-right-count-minus').click(function () {
            if ($(this).siblings('.cart-page-item-info-right-count-num').val() > 0) {
                let temp = +$(this).siblings('.cart-page-item-info-right-count-num').val();
                if(temp == 1) {
                    return;
                }
                $(this)
                    .siblings('.cart-page-item-info-right-count-num')
                    .val(temp-1)
                    .change();
            }
        });

        $('.cart-page-item-info-right-count-num').on('change', function () {
            if ($(this).siblings('.cart-page-item-info-right-count-num').val() < 1) {
                $(this).siblings('.cart-page-item-info-right-count-num').val(1);
            } else if ($(this).siblings('.cart-page-item-info-right-count-num').val() > 9999) {
                $(this).siblings('.cart-page-item-info-right-count-num').val(9999);
            }
            $(this).parent().parent().submit();
        });

        $('.cart-page-item-info-right-count-plus').click(function () {
            let temp = +$(this).siblings('.cart-page-item-info-right-count-num').val();
            $(this)
                .siblings('.cart-page-item-info-right-count-num')
                .val(temp+1)
                .change();
        });
    });

    $(document).on('pjax:success', function(event, data, status, xhr, options) {
        $('.header-num').text($('.cart-page-right-wrp-row-col-digit').text());
    });
</script>
<div class="catalog-page width-default bx-center">
    <h1 class="center-text">Корзина товаров</h1>
    <div class="cart-page js-sticky-row">
        <div class="cart-page-left">
            <?php foreach ($basketProducts as $basketProduct) { ?>
                <?php 
                    /** @var ActiveRecord $product */
                    $product = $basketProduct->sku?->product;
                    
                    $articul = $product->getAttribute('articul');
                    $title   = $product->getAttribute('name');
                    $price   = $basketProduct->price;
                    $count   = $basketProduct->count;
                    $sum     = $basketProduct->price * $basketProduct->count;
                    $image   = Images::findOne($product->mainImage);

                    // TODO ...
                    $sql = "SELECT
                                o.name as option_name, 
                                GROUP_CONCAT(DISTINCT i.name ORDER BY i.name ASC SEPARATOR ', ') as option_values
                            FROM module_shop_sku_multi_options mo
                            LEFT JOIN module_shop_options o on o.id = mo.option_id 
                            LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
                            WHERE mo.sku_id = {$basketProduct->sku->id}
                            GROUP by mo.sku_id, option_name
                            UNION 
                            SELECT
                                o.name as option_name, 
                                GROUP_CONCAT(DISTINCT i.name ORDER BY i.name ASC SEPARATOR ', ') as option_values
                            FROM module_shop_sku_options mo
                            LEFT JOIN module_shop_options o on o.id = mo.option_id 
                            LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
                            WHERE mo.sku_id = {$basketProduct->sku->id}
                            GROUP by mo.sku_id, option_name
                            ";

                    $skuFeatures = Yii::$app->db->createCommand($sql)->queryAll();
                    
                ?>
                <div class="cart-page-item">
                    <div class="cart-page-item-img">
                        <a href="<?= Url::to(['/shop/frontend/products/view', 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()]) ?>">
                            <img src="<?= $image?->file ?>" alt="<?= $product->name ?>" title="<?= $product->name ?>">
                        </a>
                    </div>
                    <div class="cart-page-item-info">
                        <div class="cart-page-item-info-left">
                            <div class="cart-page-item-info-left-top">
                                <div class="cart-item-desc"><span>Артикул:</span> <?= $articul ?></div>
                                <h4><a href="<?= Url::to(['/shop/frontend/products/view', 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()]) ?>" class="link-line">
                                    <span><?= $title ?></span>
                                </a></h4>
                                <?php foreach(is_array($skuFeatures) ? $skuFeatures : [] as $feature) { ?>
                                    <div class="cart-item-desc"><span><?= $feature['option_name'] ?>:</span> <?= $feature['option_values'] ?></div>
                                <?php } ?>    
                                
                            </div>
                            <div class="cart-page-item-info-left-bottom">
                                <div class="cart-item-desc"><span>Цена за единицу:</span></div>
                                <div class="cart-page-item-price"><?= Html::encode($price); ?> ₽</div>
                            </div>
                        </div>
                        <div class="cart-page-item-info-right">
                            <?php 
                                $deleteSkuForm = ActiveForm::begin([
                                    'method' => 'post',
                                    'action' => ['/basket/delete-sku', 'sku_id' => $basketProduct->sku->id],
                                    'options' => ['data-pjax' => true]
                                ]); 
                            ?>
                                <button class="cart-page-item-info-right-delete"></button>
                            <?php ActiveForm::end(); ?>
                            
                            <div class="cart-page-item-info-right-total"><?= Html::encode($sum); ?> ₽</div>

                            <?php 
                                $updateSkuForm = ActiveForm::begin([
                                    'method' => 'post',
                                    'action' => ['/basket/update-sku', 'sku_id' => $basketProduct->sku->id],
                                    'options' => ['data-pjax' => true, 'class' => 'js-sku-count-input']
                                ]); 
                            ?>
                            <div class="cart-page-item-info-right-count radius">
                                <span class="cart-page-item-info-right-count-minus"></span>
                                <input 
                                    class="cart-page-item-info-right-count-num" 
                                    name="count"
                                    value="<?= $count ?>"
                                />
                                <span class="cart-page-item-info-right-count-plus"></span>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>    
        </div>
        <div class="cart-page-right">
            <div class="cart-page-right-wrp js-sticky-box" data-margin-top="30" data-margin-bottom="30">
                <h3>Ваш заказ</h3>
                <div class="cart-page-right-wrp-row">
                    <div class="cart-page-right-wrp-row-col">Количество товаров</div>
                    <div class="cart-page-right-wrp-row-col cart-page-right-wrp-row-col-digit"><?= $totalCount ?></div>
                </div>
                <div class="cart-page-right-wrp-total">
                    <div class="cart-page-right-wrp-total-col">Итого:</div>
                    <div class="cart-page-right-wrp-total-col"><?= Html::encode($totalSum); ?> ₽</div>
                </div>
                <a href="<?= Url::to(['orders/create']); ?>" class="btn-bg full black radius">Перейти к оформлению</a>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>