<?php 

use yii\helpers\Url;

?>

<div class="element js-element">
    <?php if(count($stickers)) { ?>
    <div class="stickers">
        <?php foreach($stickers as $sticker) { ?>
        <div class="sticker sticker--<?= $sticker['code'] ?? '' ?>"><?= $sticker['name'] ?? ''?></div>
        <?php } ?>
    </div>
    <?php } ?>
    <button 
        type="button" 
        class="favorite <?= $isBookmark ? 'active' : '' ?>"
        data-url-add="<?= Url::to(['/shop/api/favorite/add-product', 'product_id' => $id]) ?>"
        data-url-remove="<?= Url::to(['/shop/api/favorite/delete-product', 'product_id' => $id]) ?>"
        data-product_id="<?= $id ?>"
    >
        <svg width="18" height="18">
            <use xlink:href="#icon-favorite"></use>
        </svg>
    </button>

    <a href="<?= $url ?>" class="element-image">
        <div class="element-image__main">
            <?php 
                $cachedPath = $images[0]['file'];
            ?>
            <img class="js-image-main" src="<?= $cachedPath ?>" alt="" loading="lazy" />
        </div>

        <ul class="element-image__nav js-image-nav">
            <?php foreach ($images as $i => $image) { ?>
                <?php 
                    $cachedPath = $image['file'];
                ?>
                <li class="<?= !$i ? 'active' : '' ?>" data-src="<?= $cachedPath ?>"></li>
            <?php } ?>
        </ul>
    </a>

    <div class="element-info">
        <div class="available on">В наличии</div>
        <div class="element-article">
            Артикул: <span><?= $articul ?></span>
        </div>
    </div>
    <div class="element-title">
        <a href="<?= $url ?>"><?= $name ?></a>
    </div>
    <div class="element-price">
        <div class="element-price__item">
            <div class="element-price__name">Розница:</div>
            <div class="element-price__value">
                <span><?= $price['price'] ?></span> ₽
            </div>
        </div>
        <div class="element-price__item">
            <div class="element-price__name">Мел. опт:</div>
            <div class="element-price__value">
                <span><?= $price['small_wholesale_price'] ?></span> ₽
            </div>
        </div>
        <div class="element-price__item">
            <div class="element-price__name">Опт:</div>
            <div class="element-price__value">
            <span><?= $price['wholesale_price'] ?></span> ₽
            </div>
        </div>
    </div>
</div>