<?php

/**
 * @var array $data
 * @var array $categoryNames
 */

use CmsModule\Shop\frontend\widgets\CatalogItem;
use yii\helpers\Html;

?>

<section class="main-catalog">
    <div class="container">
        <h2 class="main-catalog__title block-title">Выгодная покупка</h2>
        <div class="main-catalog__content">
            <div class="main-catalog__nav">
                <ul class="tile-types">
                    <?php foreach ($categoryNames as $key => $category_name) { ?>
                        <li class="tile-types__item <?= $key === 0 ? 'active' : ''; ?>"><?= Html::encode($category_name); ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="main-catalog__pages">
                <?php foreach ($data as $category_name => $products) { ?>
                    <div class="main-catalog__page">
                        <div class="main-catalog__cards catalog__cards">
                            <?php foreach ($products as $product_id) { ?>
                                <div class="catalog-item__container">
                                    <?= CatalogItem::widget(['product_id' => $product_id]); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
</section>