<?php

use yii\helpers\Url;
use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use frontend\models\shop\Products;
use frontend\services\CityCodeResolver;

/**
 * @var Products $product
 * @var ProductViewModel $productViewModel
*/

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

$firstSku = $productViewModel->getFirstSku();
?>

<div class="category-item">
    <div class="category-item-img">
        <a href="<?= Url::to(["/shop/frontend/products/view", 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()]) ?>">
            <img src='<?= Yii::$app->image->get($product->image_id)?->file ?>' alt="<?= $product->name?>" title="<?= $product->name?>">
        </a>
    </div>
    <a href=<?= Url::to(["/shop/frontend/products/view", 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()]) ?> class="category-item-name link-line" title="<?= $product->name?>" ><span><?= $product->name?></span></a>
    <div class="category-item-price">
        <?= $product->price ?> ₽
    </div>
</div>