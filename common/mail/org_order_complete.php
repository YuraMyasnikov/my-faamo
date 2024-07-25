<?php

use cms\common\helpers\FormatterHelper;
use CmsModule\Shop\common\helpers\PriceHelper;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrderViewModel;
use yii\helpers\Html;

$orderViewModel = Yii::$container->get(OrderViewModel::class);
$orderViewModel->orderId = $orderId;
$orderViewModel->init();
$order = $orderViewModel->order;

?>

<h2>Заказ №<?= Html::encode($orderViewModel->orderId); ?></h2>

<h3>ФИО: <?= Html::encode($order->orderData->fio); ?></h3>
<h3>Телефон: <?= Html::encode(FormatterHelper::echoPhone($order->orderData->phone)); ?></h3>
<h3>Индекс: <?= Html::encode($order->orderData->zip); ?></h3>
<h3>Город: <?= Html::encode($order->orderData->city); ?></h3>
<h3>Адресс: <?= Html::encode($order->orderData->address); ?></h3>
<h3>Способ оплаты: <?= Html::encode($order->payment->name); ?></h3>
<h3>Способ доставки: <?= Html::encode($order->delivery->name); ?></h3>
<h3>Комментарий: <?= Html::encode($order->comment); ?></h3>

<table class="order__final-summa">
    <tr>
        <td>Сумма заказа:</td>
        <td style="text-align: right;">
            <?= Orders::getProductsFullPriceForOrder($orderViewModel->orderId) ?> руб.
        </td>
    </tr>
    <tr>
        <td>Стоимость доставки</td>
        <td style="text-align: right;"><?= PriceHelper::round($order->delivery_price); ?> руб.</td>
    </tr>
    <tr>
        <td>Скидка</td>
        <td style="text-align: right;"><?= PriceHelper::round($order->total_price - $order->total_discount_price); ?> руб.</td>
    </tr>
    <tr style="color: #23C06D; font-weight: bold">
        <td>Итого к оплате:</td>
        <td style="text-align: right;">
            <?= $order->total_discount_price ?> руб.
        </td>
    </tr>
</table>

<div class="order-table-wrap">
        <section class="goods">
            <h2>Товары в заказе</h2>
            <article>
                <table class="order-table">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Кол-во</th>
                        <th>Цена</th>
                        <th>Итог</th>
                    </tr>
                    </thead>
                    <?php foreach ($orderViewModel->getOrderItems() as $orderItem) { ?>
                        <tbody>
                        <tr>
                            <td class="order-table-td-first">
                                <div class="order-table-name">
                                    <div class="order-table-name__image">
                                        <img src="<?= $orderItem['image']; ?>" width="150">
                                    </div>
                                    <div class="order-table-name__title">
                                        <a href="<?= $orderItem['url'] ?>">
                                            <?= $orderItem['name'] ?>
                                        </a>
                                        <?php if ($orderItem['articul']): ?>
                                            <div class="order-table-name__articul">
                                                <p>Артикул: <span><?= $orderItem['articul'] ?></span></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= $orderItem['count'] ?></td>
                            <td><?= $orderItem['price'] ?></td>
                            <td><b><?= $orderItem['fullDiscountPrice'] ?></b></td>
                        </tr>
                        </tbody>
                    <?php } ?>
                </table>
            </article>
        </section>
    </div>
