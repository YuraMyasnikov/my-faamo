<?php

use cms\common\helpers\RoleHelper;
use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\frontend\helpers\BreadcrumbsHelper;
use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/** 
 * @var Products $product
 * @var ProductViewModel $productViewModel
 * @var \yii\data\ActiveDataProvider $productReviewsDataProvider
 * @var \frontend\models\shop\ProductReviewsForm $productReviewsForm
 */


$seo = Yii::$app->seo->getPage();
$h1 = $seo && $seo->h1 ? $seo->h1 : $product->name;

$breadcrumbsCategories = BreadcrumbsHelper::getTreeParentForCategory($product->mainCategory->id);
$this->params['breadcrumbs'] = $breadcrumbsCategories;
$this->params['breadcrumbs'][] = $product->name;

$firstSku = $productViewModel->getFirstSku();
?>

<script>
  function calculatorDetailsShow(data) {
    let currentPriceType = data.price_type;
    let nextPriceType = null;
    let nextRemaining = null; 	
    if(data.next) {
      nextPriceType = data.next.price_type;
      nextRemaining = data.next.remaining;
    }
    let priceTypeTitle = (priceType) => {
      if(priceType == 'small_wholesale_price') {
        return 'Мелкий опт';  
      } else if(priceType == 'wholesale_price') {
        return 'Опт';  
      }
      return 'Розница';
    }; 
    let template = '';
    template += '<div>';
    template += '<div id="current-price-type">'+ priceTypeTitle(currentPriceType) +'</p>';
    if(nextPriceType) {
      template += '<div>';
      template += '<p>Для того чтобы перейти в <b><i>'+ priceTypeTitle(nextPriceType) +'</i></b> осталось набрать <b><i>'+nextRemaining+'</i></b> руб.</p>';
      template += '</div>';
    }
    template += '</div>';

    $('#calculator-details').html(template);
  }

  $(document).ready(function() {
    $(this).on('calculated', function(e, response) {
      calculatorDetailsShow(response);
    });

    let skus = {
      'price': {},
      'count': {},
      'sum': {},
    };
    $('.sku-count').each(function(i, el) {
      let skuId = $(el).data('sku-id');
      skus['price'][skuId] = $('#sku_price-' + skuId);
      skus['count'][skuId] = $('#sku_count-' + skuId);
      skus['sum'][skuId]   = $('#sku_sum-' + skuId);
    });

    $('.sku-count').change(function(){
      let skuId = $(this).data('sku-id');
      let count = $(this).val();

      let skuCandidates = [];
      Object.keys(skus.count).forEach(skuId => {
        let count = $(skus.count[skuId]).val();
        skuCandidates.push({
          'sku_id': skuId,
          'count': count
        });
      });

      $.post('/api/shop/basket/calculate', { skus: skuCandidates }, function(response) {
        response = JSON.parse(response);

        /**
         * Calculate prices
         */
        Object.keys(skus.price).forEach(skuId => {
          $(skus.price[skuId]).html(response.skus[skuId].price)
          $(skus.sum[skuId]).html($(skus.count[skuId]).val() * response.skus[skuId].price)
        });

        $(document).trigger('calculated', response);
      });

    }); 

    $('.add-to-basket').click(function(e) {
      e.preventDefault();
      
      skuCandidates = [];
      Object.keys(skus.count).forEach(skuId => {
        let count = $(skus.count[skuId]).val();
        if(count > 0 ) {
          skuCandidates.push({
            'sku_id': skuId,
            'count': count
          });
        }
      });

      $.post('/api/shop/basket/add-skus', { skus: skuCandidates }, function(response) {
        /**
         * Reset input data
         */
        Object.keys(skus.count).forEach(skuId => {
          $(skus.count[skuId]).val(0);
          $(skus.sum[skuId]).html(0);
        });
        noty('В корзину добавлено!');
        updateBasketItemsCount();
      });
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

        <div id="calculator-details">
        </div>   

        <p>Skus: </p>
        <table class="table" style="width: 100%;">
          <tr>
            <td>id</td>
            <td>nam</td>
            <td>код</td>
            <td>цена</td>
            <td>количество</td>
            <td>сумма</td>
          </tr>
        <?php foreach($productViewModel->skuList as $sku) { ?>
            <tr>
              <td><?= $sku->id ?></td>
              <td><?= $sku->name ?></td>
              <td><?= $sku->code ?></td>
              <td id="sku_price-<?= $sku->id ?>"><?= $sku->price ?></td>
              <td>
                <input 
                  class="sku-count" 
                  id="sku_count-<?= $sku->id ?>" 
                  data-sku-id="<?= $sku->id ?>" 
                  data-sku-price="<?= $sku->price ?>" 
                  type="number" 
                  min=0 
                  max="<?= $sku->remnants ?>" 
                  value="0"
                  />
              </td>
              <td><div class="sku-sum" id="sku_sum-<?= $sku->id ?>" data-sku-id="<?= $sku->id ?>" >0</div></td>
            </tr>
          
        <?php } ?>
        </table>
        <button type="button" class="add-to-basket">Add</button>
        
       <div id="type">

       </div>   





        <div class="product-price__content">
          <!--
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
        -->
      </div>
    </div>
  </div>
</section>

<section class="">
  <div class="container">
    <div class="product-about__content">
      <div class="product-about__description">
          <h2 class="product-about__title">Отзывы</h2>
          
          <?php if ($productViewModel->description) { ?>
            <?= Html::encode($productViewModel->description); ?>
          <?php } else { ?>
            <P>Пусто</P>
          <?php } ?>

          <?php if($productViewModel->product->links):?> 
            <?php if($productViewModel->product->links->wildberries):?> 
              <p>wildberries: <?= $productViewModel->product->links->wildberries ?></p>
            <?php endif ?>
            <?php if($productViewModel->product->links->ozon):?> 
              <p>ozon: <?= $productViewModel->product->links->ozon ?></p>
            <?php endif ?>
            <?php if($productViewModel->product->links->yandex_market):?> 
              <p>yandex market: <?= $productViewModel->product->links->yandex_market ?></p>
            <?php endif ?>
          <?php endif ?>

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

      <?php if(!Yii::$app->user->isGuest): ?>
      <div class="product-about__extra-info">
        <button id="review-popup" class="reviews__button btn">Оставить свой отзыв</button>
      </div>
      

      <!-- FORM -->
      <div class="overlay-reviews">
      <div class="popup-fos review-fos">
        <?php 
          $form = ActiveForm::begin([
            'id' => 'review-form',
            'action' => Url::to(['product/add-review', 'code' => $product->code]),
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
              'options' => [
                'tag' => 'div',
                'class' => '',
              ] 
            ]
          ]); 
        ?>
        <?= $form->field($productReviewsForm, 'product_id', ['options' => ['class' => 'field-reviewform-order']])->hiddenInput(); ?>

        <div class="review-fos__block">
          <?= $form->field($productReviewsForm, 'fio', ['options' => ['class' => 'field-reviewform-name']])->textInput(); ?>
          <?= $form->field($productReviewsForm, 'email', ['options' => ['class' => 'field-reviewform-email']])->input('email'); ?>
          <?= $form->field($productReviewsForm, 'review_text', ['options' => ['class' => 'field-reviewform-body']])->textarea(['rows' => '4']); ?>
          <?php 
            echo $form->field($productReviewsForm, 'grade', ['options' => ['class' => 'field-reviewform-rate'],])->radioList([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1], [
              'class' => 'rating-area',
              'item' => function ($index, $label, $name, $checked, $value) {
                $id = 'star-'. $value;
                return Html::radio($name, $checked, ['value' => $value, 'id' => $id]) .
                Html::label('', $id, ['class' => 'icon-star']);
              },
            ]); 
          ?>
        </div>
        
        <?= $form->field($productReviewsForm, 'photo[]')->fileInput(['multiple' => true]); ?>

        <div class="reg-form__confirm">
            <div class="forms-text">Нажимая кнопку «Отправить», вы соглашаетесь с <a class="text-link" href="">политикой&nbsp;конфиденциальности</a></div>
            <div class="form-group">
                <button type="submit" name="contact-button">Отправить отзыв</button>
            </div>
        </div>
        <?php $form::end(); ?>
      </div>
      </div>  
      <!-- /FORM -->
      <?php endif;?>

      <div>
        <?php 
          $layout = '<div>{items}</div><div><div>{pager}</div></div>';
          echo ListView::widget([
            'dataProvider' => $productReviewsDataProvider,
            'emptyText' => 'Отзывов нет',
            'itemView' => function ($model) {
              return $this->render('_elem', ['review' => $model]);
            },
            'options' => [
              'tag' => false
            ],
            'layout' => $layout,
            'pager' => [
              'linkOptions' => ['class' => 'pagination__link'],
              'linkContainerOptions' => ['class' => 'pagination__item'],
              'options' => ['class' => 'pagination'],
              'prevPageCssClass' => 'prev-link',
              'nextPageCssClass' => 'next-link',
              'activePageCssClass' => 'current',
              'nextPageLabel' => '',
              'prevPageLabel' => '',
              'maxButtonCount' => 3,
            ]
          ]);             
        ?>
      </div>
    </div>
  </div>
</section>