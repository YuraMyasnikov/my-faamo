<?php 

use CmsModule\Shop\common\helpers\PriceHelper;
use CmsModule\Shop\common\models\Sku;
use CmsModule\Shop\common\models\Basket;
use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use yii\helpers\Url;

/** @var Basket $basketProduct */
/** @var string $priceType */
/** @var Sku $sku */

$sku = $basketProduct->sku;
$skuPrice = match($priceType) {
    'small_wholesale_price' => Yii::$app->prices->getSmallWholesalePrice($sku->price),
    'wholesale_price' => Yii::$app->prices->getWholesalePrice($sku->price),
    default => $sku->price,
};
$product = $sku->product;
$category = $product->mainCategory;

$productViewModel = Yii::$container->get(ProductViewModel::class);
$productViewModel->product_id = $product->id;
$productViewModel->init();
$productUrl = Url::to(['/product/view', 'code' => $product->code]);
$productName = $product->name;
$productImage = $productViewModel->mainImage;

// TODO ...
$sql = "SELECT
            o.name as option_name, 
            GROUP_CONCAT(DISTINCT i.name ORDER BY i.name ASC SEPARATOR ', ') as option_values
        FROM module_shop_sku_multi_options mo
        LEFT JOIN module_shop_options o on o.id = mo.option_id 
        LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
        WHERE mo.sku_id = {$sku->id}
        GROUP by mo.sku_id, option_name";

$skuFeatures = Yii::$app->db->createCommand($sql)->queryAll();

?>

<div 
    class="basket-item"
    data-sku-id="<?= $sku->id ?>"
    data-sku-remnants="<?= $sku->remnants ?>"
    data-price="<?= $basketProduct->price ?>"
    data-count="<?= $basketProduct->count ?>"
>
    <div class="basket-item__del">
        <span>
            <svg width="10" height="10">
                <use xlink:href="#icon-burger-close"></use>
            </svg>
        </span>
        Удалить
    </div>
    
    <div class="basket-item__main">
        <div class="basket-item__image">
            <?php 
                $src = Yii::getAlias('@webroot') . $productImage['file'];
                $cachedName = Yii::$app->imageCache->resize($src, null, 210);
                $cachedPath = Yii::$app->imageCache->relativePath($cachedName);    
            ?>
            <img src="<?= $cachedPath ?>" alt="" loading="lazy">
        </div>

        <div class="basket-item__content">
            <div class="basket-item__article">
                <span>Артикул:</span> <?= $sku->code ?>
            </div>
            <a href="<?= $productUrl ?>" class="basket-item__title">
                <?= $productName ?>
            </a>
            <?php if(count($skuFeatures)) { ?>
            <ul class="basket-item__info">
                <?php foreach($skuFeatures as $feature) { ?>
                <li>
                    <?= $feature['option_name'] ?> <span><?= $feature['option_values'] ?></span>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>

            <div class="basket-item__price">
                <div class="basket-item__price-title">Цена за единицу:</div>
                <div style="display: flex; gap: 5px; font-size: 22px; font-weight: bold; white-space: nowrap;">
                    <span class="basket-item__price-count"><?= PriceHelper::format($skuPrice) ?></span> <span class="cart-rubl">₽</span>
                </div>
            </div>
        </div>
    </div>

    <div class="basket-item__aside">
        <!--value-->
        <div class="value value--round js-value">
            <div class="value-button value-button--medium value-button--round minus js-dec">
                <svg width="14" height="14">
                    <use xlink:href="#icon-minus"></use>
                </svg>
            </div>
            <input type="text" readonly class="value-count js-count" value="<?= $basketProduct->count ?>">
            <div class="value-button value-button--medium value-button--round plus js-inc">
                <svg width="14" height="14">
                    <use xlink:href="#icon-plus"></use>
                </svg>
            </div>
        </div>
        <div style="display: flex; gap: 5px; font-weight: bold; font-size: 34px; line-height: 30px; white-space: nowrap;">
            <span class="basket-item__total"><?= PriceHelper::format($skuPrice * $basketProduct->count) ?></span> <span class="cart-rubl">₽</span>
        </div>
    </div>
</div>