<?php

use yii\helpers\Html;
use yii\helpers\Url;
use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use frontend\models\shop\Products;

/**
 * @var Products $product
 * @var ProductViewModel $productViewModel
*/

$firstSku = $productViewModel->getFirstSku();
?>

<div class="catalog-item">
    <div class="label-star js-add-favorite-catalog-item <?= Yii::$app->favorite->isFavorite($product->id) ? 'favorite' : ''; ?>" data-product_id="<?= $product->id ?>"></div>
    <a href="<?= Url::to(['/product/' . $product->code]); ?>">
        <div class="product-gallery">
            <div class="catalog-item__labels">
                <?php foreach ($product->productToStickers as $sticker) { ?>
                    <div class="catalog-item__label label-<?= Html::encode($sticker->optionItem->code); ?>"><?= Html::encode($sticker->optionItem->name); ?></div>
                <?php } ?>
            </div>
            <div class="product-gallery__main">
                <img class="" src="<?= Yii::$app->image->getFile($product->mainImage); ?>" alt="1q" loading="lazy" width="268">
            </div>
        </div>
        <div class="catalog-item__title"><?= Html::encode($product->name); ?></div>
    </a>
        <div class="catalog-item__row-price">
            <div class="catalog-item__price">
                <div class="catalog-item__price-caption">Цена:</div>
                <?php if ($firstSku->old_price > $firstSku->price) { ?>
                    <div class="catalog-item__price-old"><?= Html::encode($firstSku->old_price); ?></div>
                <?php } ?>
                <div class="catalog-item__price-current"><?= Html::encode($firstSku->price); ?></div>
                <?php if ($firstSku->old_price_square_meter > $firstSku->price_square_meter) { ?>
                    <div class="catalog-item__price_square_meter-old"><?= Html::encode($firstSku->old_price_square_meter); ?></div>
                <?php } ?>
                <div class="catalog-item__price-currency"><?= Html::encode($firstSku->price_square_meter); ?> ₽/м<sup>2</sup></div>
            </div>
            <button class="catalog-item__button js-fast-add-basket <?= Yii::$app->basket->isBasketProduct($product->id) ? 'active' : ''; ?>" type="button" data-sku-id="<?= $firstSku->id ?>"></button>
        </div>
</div>