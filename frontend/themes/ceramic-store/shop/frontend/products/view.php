<?php

use cms\common\helpers\RoleHelper;
use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\frontend\helpers\BreadcrumbsHelper;
use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use yii\helpers\Html;
use yii\helpers\Url;

/** 
 * @var Products $product
 * @var Categories $category
 * @var ProductViewModel $productViewModel
 */


$seo = Yii::$app->seo->getPage();
$h1 = $seo && $seo->h1 ? $seo->h1 : $product->name;

$breadcrumbsCategories = BreadcrumbsHelper::getTreeParentForCategory($product->mainCategory->id);
$this->params['breadcrumbs'] = $breadcrumbsCategories;
$this->params['breadcrumbs'][] = $product->name;

$firstSku = $productViewModel->getFirstSku();
?>

<script>
  $(document).ready(function() {
    $(".js-btn-add-basket").on('click', function() {
      let product_count = $('.js-count-product').val();
      let sku_id = <?= $productViewModel->getSkuList()[0]->id ?>;

      if (product_count > 0 && product_count < 9999) {
        $.get('/api/shop/basket/add-product', {
          'sku_id': sku_id,
          'count': product_count
        }, function(response) {
          noty('В корзину добавлено товаров: ' + product_count + 'шт.');
          $('.js-count-product').val(1);
          updateBasketItemsCount();
        });
      }
    });
  })
</script>

<section class="one-product">
  <div class="container">
    <div class="product-content">
      <div class="product-images">
        <div class="product-slider slider-nav">
          <?php foreach ($productViewModel->images as $image) { ?>
            <div class="product-slider__item">
              <img src="<?= $image['file'] ?>" alt="">
            </div>
          <?php } ?>
        </div>
        <div class="product-img slider-for">
        <?php foreach ($productViewModel->images as $image) { ?>
            <div class="product-img__item">
              <img src="<?= $image['file'] ?>" alt="">
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="product-details">
        <?php if (RoleHelper::isAdmin()) { ?>
          <a href="<?= Url::to(['/admin/shop/products/update', 'id' => $product->id]); ?>">Администрирование</a>
        <?php } ?>
        <h1 class="product-title"><?= Html::encode($h1); ?></h1>
        <?php if ($product->articul) { ?>
          <div class="product__art">
            <div class="item__title">Артикул:</div>
            <div class="item__value"><?= Html::encode($product->articul); ?></div>
          </div>
        <?php } ?>

        <div class="product-price__content">
          <div class="product-price__for-count">
          <div class="product-price__title">Цена за 1 м<sup>2</sup>:</div>
            <?php if ($firstSku->old_price_square_meter > $firstSku->price_square_meter) { ?>
              <div class="product-price__old"><?= Html::encode($firstSku->old_price_square_meter); ?></div>
            <?php } ?>
            <div class="product-price__current"><?= Html::encode($firstSku->price_square_meter); ?> <span class="icon-rub"></span></div>
          </div>
          <div class="product-price__for-count">
            <div class="product-price__title">Цена за коробку:</div>
            <?php if ($firstSku->old_price > $firstSku->price) { ?>
              <div class="product-price__old"><?= Html::encode($firstSku->old_price); ?></div>
            <?php } ?>
            <div class="product-price__current"><?= Html::encode($firstSku->price); ?> <span class="icon-rub"></span></div>
          </div>
        </div>
        <div class="product-controls">

          <div class="product-counter">
            <button class="product-counter__button  minus-button" type="button"></button>
            <input class="product-counter__field js-count-product" type="number" min="1" max="1000" step="1" value="1">
            <button class="product-counter__button plus-button" type="button"></button>
          </div>
          <button class="product-controls__button btn js-btn-add-basket" type="submit">Добавить в корзину</button>
          <a class="product-controls__like-btn js-add-favorite <?= Yii::$app->favorite->isFavorite($product->id) ? 'active' : ''; ?>"  data-product_id="<?= $product->id ?>"><span class="icon-star"></span></a>
        </div>
        <div class="product-info">
          <div class="product-info__row count-in-store">
            <span class="product-info__row-title">Количество на складе:</span>
            <span class="product-info__row-value">Кол. коробок: <?= Html::encode($firstSku->remnants); ?> шт.</span>
          </div>
          <div class="product-info__row delivery-condition">
            <span class="product-info__row-title">Бесплатная доставка в Москве при заказе от</span>
            <span class="product-info__row-value">200&nbsp;000&nbsp;<span class="icon-rub"></span></span>
          </div>
          <a href="<?= Url::to('/delivery'); ?>" class="product-info__button btn">Подробнее о доставке и оплате</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="product-about">
  <div class="container">
    <div class="product-about__content">
      <div class="product-about__description">
          <h2 class="product-about__title">Описание</h2>
          <?php if ($productViewModel->description) { ?>
            <?= Html::encode($productViewModel->description); ?>
          <?php } else { ?>
            <P>Пусто</P>
          <?php } ?>

        <?php if ($productViewModel->optionList) { ?>
          <table class="product-properties">
            <?php foreach ($productViewModel->optionList as $optionName => $optionValue) { ?>
              <tr>
                <td><?= Html::encode($optionName); ?></td>
                <td><?= Html::encode(implode(', ', $optionValue)); ?></td>
              </tr>
            <?php } ?>
          </table>
        <?php } ?>
      </div>
      <div class="product-about__extra-info">
        <a href="" class="product-about__button btn">Скачать инструкцию</a>
        <div class="product-about__extra-content">
          <span class="icon-notice"></span>
          <div class="product-about__extra-text">
            <p>Цены и наличие товаров на сайте и в гипермаркете могут различаться. </p>
            <p>Информация о товарах на сайте обновляется и может быть неактуальна для таких же товаров, проданных ранее.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>