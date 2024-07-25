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
    class="basket-item basket-item--small"
>
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
            <div class="basket-item__sold">Раскупили</div>
        </div>
    </div>

</div>