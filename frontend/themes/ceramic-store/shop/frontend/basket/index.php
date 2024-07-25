<?php

use CmsModule\Shop\common\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = 'Корзина';
?>

<?php Pjax::begin(['enablePushState' => false,'timeout' => 5000,'id' => 'pjax_basket_form']); ?>
<script>
  $(function () {
    let w = $(window).width();
  
    $('.basket-minus-button').click(function () {
      if ($(this).siblings('.product-counter__field').val() > 0) {
        let temp = +$(this).siblings('.product-counter__field').val();
        $(this).siblings('.product-counter__field').val(temp-1);
       }
    });

    $('.product-counter__field').on('change', function () {
      if ($(this).siblings('.product-counter__field').val() < 0) {
        $(this).siblings('.product-counter__field').val(1);
      } else if ($(this).siblings('.product-counter__field').val() > 9999) {
        $(this).siblings('.product-counter__field').val(9999);
      }
        $('.js-sku-count-input').submit();
    });

    $('.basket-plus-button').click(function () {
        let temp = +$(this).siblings('.product-counter__field').val();
        $(this).siblings('.product-counter__field').val(temp+1);
    });
  });

  $(document).on('pjax:success', function(event, data, status, xhr, options) {
      updateBasketItemsCount();
  });
</script>
<section class="cart-block">
        <div class="container">
          <div class="cart__content">
            <div class="cart-list">
                <?php $totalPrice = 0; ?>
                <?php foreach ($basketProducts as $basketProduct) { ?>
                  <?php $totalPrice += PriceHelper::round($basketProduct->price * $basketProduct->count); ?>
                <div class="cart-list__item cart-item">
                    <div class="cart-item__img">
                    <img src="<?= Yii::$app->image->getFile($basketProduct->sku->product->mainImage); ?>" alt="<?= Html::encode($basketProduct->sku->product->name); ?>">
                    </div>
                    <div class="cart-item__description">
                    <div class="cart-item__title"><a href="<?= Url::to(['/shop/frontend/products/view', 'code' => $basketProduct->sku->code]); ?>"><?= Html::encode($basketProduct->sku->name); ?></a></div>
                    <div class="cart-item__art">
                        <span class="item__title">Артикул:</span>
                        <span class="item__value"><?= Html::encode($basketProduct->sku->articul); ?></span>
                    </div>
                    
                    </div>
                    <div class="cart-item__control">
                    <?php $deleteSkuForm = ActiveForm::begin([
                        'method' => 'post',
                        'action' => ['/basket/delete-sku', 'sku_id' => $basketProduct->sku->id],
                        'options' => ['data-pjax' => true]
                    ]); ?>
                        <button class="cart-item__delete">Удалить</button>
                    <?php ActiveForm::end(); ?>
                    <div class="cart-item__result">
                        <div class="cart-item__cost"><?= PriceHelper::round($basketProduct->price * $basketProduct->count); ?> <span class="icon-rub"></span></div>
                        <?php $updateSkuForm = ActiveForm::begin([
                            'method' => 'post',
                            'action' => ['/basket/update-sku', 'sku_id' => $basketProduct->sku->id],
                            'options' => ['data-pjax' => true, 'class' => 'js-sku-count-input']
                        ]); ?>
                        <div class="cart-item__counter product-counter">
                        <button class="product-counter__button basket-minus-button minus-button"></button>
                        <input class="product-counter__field" name="count" type="number" min="0" max="1000" step="1" value="<?= $basketProduct->count; ?>">
                        <button class="product-counter__button basket-plus-button plus-button"></button>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    </div>
                </div>
                <?php } ?>

              <a href="<?= Url::to('/basket/clear'); ?>" class="cart-clear__button btn">Очистить корзину</a>
            </div>

          <div class="cart-summary">
            <div class="cart-summary__title">Ваш заказ</div>
              <div class="cart-summary__list">
                <div class="cart-summary__row">
                  <div class="cart-summary__row-title">товаров: <?= Yii::$app->basket->count(); ?></div>
                  <div class="cart-summary__row-value"><?= $totalPrice; ?> <span class="icon-rub"></span></div>
                </div>
              </div>
            <div class="cart-summary__row cart-result">
              <div class="cart-result__title">Итого: </div>
              <div class="cart-result__value"><?= $totalPrice; ?>  <span class="icon-rub"></span></div>

            </div>
            <a href="<?= Url::to('/orders/create'); ?>" class="cart__button btn">Перейти к оформлению</a>
          </div>

        </div>
        </div>
      </section>
<?php Pjax::end(); ?>
