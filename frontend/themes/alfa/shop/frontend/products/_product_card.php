<?php 

use frontend\models\shop\Products;
use frontend\services\CityCodeResolver;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var Products $product
 */

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

$images   = $product->productImages;
$image    = count($images) ? $images[0] : null;
$imageUrl = $image ? $image->image->file : null;
$url      = Url::to(['/shop/frontend/products/view', 'code' => $product->code, "city" => $cityCodeResolver->getCodeForCurrentCity()]);

?>

<div class="swiper-slide">
    <div class="category-item">
        <div class="category-item-img">
            <a href="<?= $url ?>"><img src="<?= $imageUrl ?>" alt="<?= Html::encode($product->name) ?>"  title="<?= Html::encode($product->name) ?>"/></a>
        </div>
        <a href="<?= $url ?>" class="category-item-name link-line" title="<?= Html::encode($product->name) ?>"><span><?= $product->name ?></span></a>
        <div class="category-item-price">
            <?= $product->price ?> ₽ 
        </div>
    </div>
</div>



