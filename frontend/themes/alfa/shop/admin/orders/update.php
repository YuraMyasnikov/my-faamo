<?php

use cms\common\models\Profile;
use CmsModule\Shop\common\helpers\PriceHelper;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrderViewModel;
use CmsModule\Shop\admin\assets\OrderAsset;
//use CmsModule\Shop\common\services\SkuOptionsService;
use frontend\models\shop\admin\services\SkuOptionsService;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

$ordersClass = Orders::class;

/** @var yii\web\View $this */
/** @var OrderViewModel $orderViewModel */
/** @var Orders $model */

$this->title = "Заказ № ".  $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = "№ ".  $model->id;

OrderAsset::register($this);

?>

<div style="margin: 10px 0"></div>
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title;?></h3>
            <div class="card-tools">
                <a href="/admin/shop/orders" class="btn btn_return float-right" style="margin-top: 6px;">Вернуться к заказам</a>
                <?php if($isOrganisation) { ?>
                <a 
                    href="<?= Url::to(['/admin/shop/orders/show-invoice', 'id' => $model->id]); ?>" 
                    id="print"
                    target="_blank" 
                    class="btn btn_return float-right" 
                    style="margin-top: 6px;"
                >Печать</a>
                <?php } ?>
            </div><br>
            <div>Дата заказа: <?= Yii::$app->formatter->asDatetime($model->created_at); ?></div>
        </div>
        <?php $form = ActiveForm::begin([
                'action' => ['edit', 'id' => $model->id],
        ]); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model->orderData, 'fio', ['options' => ['class' => 'col-xs-6']])->textInput(['placeholder' => 'ФИО'])->label('ФИО') ?>
                    <?= $form->field($model->orderData, 'email', ['options' => ['class' => 'col-xs-6']])->textInput(['placeholder' => 'E-mail'])->label('E-mail') ?>
                    <?= $form->field($model->orderData, 'phone', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Телефон'])->label('Телефон')->widget(MaskedInput::class, ['mask' => '+7 (999) 999-99-99']) ?>
                    <?= $form->field($model->orderData, 'zip', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Индекс'])->label('Индекс') ?>
                    <?= $form->field($model->orderData, 'city', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Город'])->label('Город') ?>
                    <?= $form->field($model->orderData, 'address', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Адрес'])->label('Адрес') ?>

                    <?php if ($model->orderData->type === Profile::ORGANIZATION_TYPE) { ?>
                        <?= $form->field($model->orderData, 'organization', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Название компании'])->label('Название компании') ?>
                        <?= $form->field($model->orderData, 'inn', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'ИНН'])->label('ИНН') ?>
                        <?= $form->field($model->orderData, 'kpp', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'КПП'])->label('КПП') ?>
                        <?= $form->field($model->orderData, 'rs', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Расчетный счет'])->label('Расчетный счет') ?>
                        <?= $form->field($model->orderData, 'bank', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Банк'])->label('Банк') ?>
                        <?= $form->field($model->orderData, 'ks', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'Корреспондентский счет'])->label('Корреспондентский счет') ?>
                        <?= $form->field($model->orderData, 'bic', ['options' => ['class' => 'col-xs-4']])->textInput(['placeholder' => 'БИК'])->label('БИК') ?>
                    <?php } ?>

                    <?= $form->field($model, 'delivery_id', ['options' => ['class' => 'col-xs-4']])->dropDownList($delivery)->label('Доставка') ?>
                    <?= $form->field($model, 'payment_id', ['options' => ['class' => 'col-xs-4']])->dropDownList($payments)->label('Оплата') ?>
                    <?= $form->field($model, 'comment')->textarea(['rows' => 6])->label('Комментарий клиента') ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'track_code')->textInput() ?>
                    <?= $form->field($model, 'plus_price')->input('number', ['min' => 0, 'placeholder' => 'Надбавка к цене']); ?>
                    <?= $form->field($model, 'admin_comment')->textarea(['rows' => 6])->label('Комментарий к заказу (виден только админу)') ?>
                    <!--<?//= $form->field($model, 'discount')->textInput() ?>-->
                    <?= $form->field($model, 'status_id')->dropDownList($orderStatuses) ?>
                    <?= $form->field($model, 'state_id')->dropDownList($orderStates) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Изменить заказ', ['class' => 'btn btn-success']) ?>
                        <?= Html::a('Пересчитать заказ', ['recalculation-price', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
                    </div>

                    <table class="order__final-summa">
                        <tr>
                            <td>Сумма товров:</td>
                            <td style="text-align: right;">
                                <span id="order_skus_price_sum"><?= Orders::getProductsFullPriceForOrder($model->id) ?></span> руб.
                            </td>
                        </tr>
                        <tr>
                            <td>Стоимость доставки</td>
                            <td style="text-align: right;">
                                <span id="order_delivery_price"><?= $model->delivery_price ?></span> руб.
                            </td>
                        </tr>
                        <tr>
                            <td>Скидка</td>
                            <td style="text-align: right;">
                                <span id="order_discount"><?= PriceHelper::round($model->total_price - $model->total_discount_price); ?></span> руб.
                            </td>
                        </tr>
                        <tr style="color: #23C06D; font-weight: bold">
                            <td>Итого к оплате:</td>
                            <td style="text-align: right;">
                                <span id="order_total_sum"><?= $model->total_discount_price ?></span> руб.
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <?php $form::end(); ?>

        <section class="union">
            <div class="card-body alert-warning" style="background-color: #ffc10714;">
                <div class="row">
                    <div class="col-md-12">
                        <form 
                            id="union-order"
                            action="<?= Url::to(['union-orders', 'id' => $model->id]) ?>"
                            >
                            <div class="input-group mb-3">
                                <input 
                                    type="text" 
                                    id="union-order-id" 
                                    class="form-control" 
                                    placeholder="Укажаите номер заказа с которым хотите объеденить" 
                                    aria-label="Номер заказа" 
                                    aria-describedby="union-order-btn"
                                    />
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Объеденить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="goods">
            <div class="card-header">
                <h3 class="card-title float-left align-middle">Товары в заказе</h3> 
                <button 
                    type="button" 
                    class="btn btn-default float-right align-middle" 
                    data-toggle="modal" 
                    data-target="#addSkuToOrderModal"
                >
                    Добавить товар
                </button>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Кол-во</th>
                        <th>Цена без скидки</th>
                        <th>Цена (со скидкой)</th>
                        <th>Итог</th>
                        <th></th>
                    </tr>
                    </thead>
                    <?php foreach ($orderViewModel->getOrderItems() as $orderItem) { ?>
                        <tbody>
                        <tr>
                            <td class="order-table-td-first">
                                <div class="order-table-name">
                                    <div class="order-table-name__image">
                                        <img src="<?= $orderItem['image']; ?>" class="rounded border border-secondary" width="50">
                                    </div>
                                    <div class="order-table-name__title">
                                        <a href="<?= $orderItem['url_product'] ?>" target="_blank">
                                            <?= $orderItem['name'] ?>
                                        </a>
                                        <?php if ($orderItem['articul']): ?>
                                            <div class="order-table-name__articul">
                                                <p>Артикул: <span><?= $orderItem['articul'] ?></span></p>
                                            </div>
                                        <?php endif; ?>
                                        <ul>
                                            <?php if ($orderItem['code']): ?>
                                                <li>
                                                    <a href="<?= $orderItem['url_sku'] ?>" target="_blank">Код: <span><?= $orderItem['code'] ?></span></a>
                                                </li>
                                            <?php endif; ?>
                                            <?php 
                                                $skuOptionsService = new SkuOptionsService();
                                                $skuOptions = $skuOptionsService->getSkuFeatureValuePairs(intval($orderItem['orderSku']?->sku_id));
                                                $skuOptionsO = $skuOptionsService->getSkuFeatureValuePairsOptions(intval($orderItem['orderSku']?->sku_id));
                                            ?>
                                            <?php if (count($skuOptions)): ?>
                                                <?php foreach($skuOptions as $skuOption) { ?>
                                                <li><?= $skuOption['option_name'] ?> <span><?= $skuOption['option_values'] ?></span></li>
                                                <?php } ?>
                                            <?php endif; ?>
                                            <?php if (count($skuOptionsO)): ?>
                                                <?php foreach($skuOptionsO as $skuOption) { ?>
                                                    <li><?= $skuOption['option_name'] ?> <span><?= $skuOption['option_values'] ?></span></li>
                                                <?php } ?>
                                            <?php endif; ?>

                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input 
                                    type="number" 
                                    class="form-control input-sku-count" 
                                    style="max-width: 150px"
                                    min=0 
                                    value="<?= $orderItem['count'] ?>" 
                                    data-url="<?= Url::to(['/admin/shop/orders/change-sku-count', 'orderId' => $model->id, 'skuId' => $orderItem['orderSku']->sku_id]) ?>"
                                    data-sku-sum-selector="#sku-sum-<?= $orderItem['orderSku']->sku_id ?>"
                                />
                            </td>
                            <td>
                                <input 
                                    type="text" 
                                    class="form-control input-sku-price" 
                                    style="max-width: 150px"
                                    value="<?= $orderItem['price'] ?>"
                                    data-url="<?= Url::to(['/admin/shop/orders/change-sku-price', 'orderId' => $model->id, 'skuId' => $orderItem['orderSku']->sku_id]) ?>"
                                    data-sku-sum-selector="#sku-sum-<?= $orderItem['orderSku']->sku_id ?>"
                                />
                            </td>
                            <td><?= $orderItem['discountPrice'] ?></td>
                            <td><b id="sku-sum-<?= $orderItem['orderSku']->sku_id ?>"><?= $orderItem['fullDiscountPrice'] ?></b></td>
                            <td>
                                <a 
                                    href="<?= Url::to(['/admin/shop/orders/delete-sku', 'orderId' => $model->id, 'skuId' => $orderItem['orderSku']->sku_id]) ?>" 
                                    onclick="return confirm('Вы действительно хотите удалить?')" 
                                    class="btn btn-sm btn-danger"
                                >
                                    удалить
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </div>
        </section>
    </div>
</div>

<!-- Add product -->
<div class="modal fade" id="addSkuToOrderModal" tabindex="-1" aria-labelledby="addSkuToOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSkuToOrderModalLabel">Добавить товар в заказ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        
        $url = Url::to(['/admin/shop/orders/find-product']);

        echo Select2::widget([
            'name' => 'add_sku_to_order',
            'theme' => Select2::THEME_KRAJEE_BS4,
            'size' => 'lg',
            // 'data' => $data,
            'options' => [
                'multiple' => true, 
                'placeholder' => 'Введите код или название товара ...'
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
            ],
        ]);

        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        <button 
            type="button" 
            class="btn btn-primary" 
            id="add_sku_to_order_action"
            data-url="<?= Url::to(['/admin/shop/orders/add-skus', 'orderId' => $model->id]) ?>"
        >
            Добавить
        </button>
      </div>
    </div>
  </div>
</div>


