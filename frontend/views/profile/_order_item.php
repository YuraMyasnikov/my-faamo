<?php

use yii\helpers\Html;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrdersStates;
use CmsModule\Shop\common\models\OrderStatusGroups;
use CmsModule\Shop\common\models\OrderViewModel;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var Orders $model
 */

$orderViewModel = Yii::$container->get(OrderViewModel::class);
$orderViewModel->orderId = $model->id;
$orderViewModel->init();
?>

<div class="one-order">
    <div class="one-order__summary">
        <div class="one-order__id">
            <div class="one-order__title">Заказ</div>
            <div class="one-order__number"><?= Html::encode($model->id); ?></div>
            <button class="one-order__opener" type="button"></button>
        </div>


        <div class="one-order__cost"><?= Html::encode($model->total_discount_price); ?> <span class="icon-rub"></span></div>
    </div>
    <div class="one-order__details">

        <div class="one-order__date details-row">
            <div class="details-row__title">Дата и время создания заказа:</div>
            <time class="details-row__value"><?= Yii::$app->formatter->asDatetime($model->created_at); ?></time>
        </div>
        <div class="one-order__status details-row">
            <div class="details-row__title">Статус:</div>
            <div class="details-row__value details-accent"><?= Html::encode($model->status->name); ?></div>
        </div>


        <div class="one-order__payment details-row">
            <div class="details-row__title">Способ оплаты:</div>
            <div class="details-row__value"><?= Html::encode($model->payment->name); ?></div>
        </div>
        <div class="one-order__delivery details-row">
            <div class="details-row__title">Доставка:</div>
            <div class="details-row__value"><?= Html::encode($model->delivery->name); ?> – <span class="details-accent"><?= Html::encode($model->delivery_price); ?> <span class="icon-rub"></span></span></div>
        </div>
    </div>

    <div class="one-order__gallery">
        <?php foreach ($orderViewModel->getOrderItems() as $orderItem) { ?>
            <div class="gallery-item">
                <div class="gallery-item__image"><img src="<?= $orderItem['mainImage'] ?>" alt=""></div>
                <div class="gallery-item__description">
                    <div class="gallery-item__title"><?= Html::encode($orderItem['name']); ?></div>
                    <div class="gallery-item__price details-row">
                        <div class="details-row__title">Цена:</div>
                        <div class="details-row__value"><?= Html::encode($orderItem['discountPrice']) ?> <span class="icon-rub"></span></div>
                    </div>
                    <div class="gallery-item__quantity details-row">
                        <div class="details-row__title">Количество:</div>
                        <div class="details-row__value"><?= Html::encode($orderItem['count']) ?> шт.</div>
                    </div>
                    <div class="gallery-item__cost"><?= Html::encode($orderItem['fullDiscountPrice']) ?> <span class="icon-rub"></span></div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if (!in_array($model->status->group->id, OrderStatusGroups::REFUSED_PROHIBITED) && !in_array($model->state_id, OrdersStates::REFUSED_PROHIBITED)) { ?>
        <?php $form = ActiveForm::begin(['action' => ['/profile/refusal-order', 'order' => $model->id], 'method' => 'post']) ?>
            <div class="form-group">
                <button name="button">Отменить заказ</button>
            </div>
        <?php $form::end() ?>
    <?php } ?>
</div>