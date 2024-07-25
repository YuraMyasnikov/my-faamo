<?php

use cms\common\helpers\FormatterHelper;
use cms\common\helpers\UserHelper;
use cms\common\models\Profile;
use CmsModule\Shop\common\helpers\PriceHelper;

?>

<div class="container">
        <div class="invoice-body">
          <div class="invoice__header">
            <div class="invoice__header-title">Внимание
            </div>
            <p class="invoice__header-text">
              Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате обязательно, в противном случа не гарантируется наличие товара на складе. Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.
            </p>
          </div>
          <h2 class="invoice__subtitle">Образец заполнения платежного поручения</h2>
          <table class="invoice__example-table">
            <tr>
              <td class="receiver-bank cell-3" rowspan="2" colspan="2">ООО "Бланк Банк"</td>
              <td class="cell-1">Бик</td>
              <td class="cell-2" colspan="3">044525801</td>
            </tr>
            <tr >
              <td class="cell-1">Сч. №</td>
              <td class="cell-2" colspan="3">30101810645250000801</td>
            </tr>
            <tr>
              <td class="cell-2">ИНН <span>230407319116</span></td>
              <td class="cell-2">КПП <span></span></td>
              <td class="cell-1">Сч. №</td>
              <td class="cell-2"colspan="3">40802810800100126349</td>
            </tr>
            <tr>
              <td class="receiver cell-3" colspan="2" rowspan="3">ИП Быков Павел Владимирович</td>
              <td class="cell-1">Вид оп.</td>
              <td class="cell-1 cell-top" rowspan="3">01</td>
              <td class="cell-1">Срок плат.</td>
              <td class="cell-1 cell-middle" rowspan="3">5</td>
            </tr>
            <tr>
              <td class="cell-1">Наз. пл.</td>
              <td class="cell-1">Очер. плат.</td>
            </tr>
            <tr>
              <td class="cell-1">Код</td>
              <td class="cell-1">Рез. поле</td>
            </tr>
            <tr>
              <td class="payment-purpose" colspan="6">Оплата по заказу клиента №<?= $order->id ?></td>
            </tr>
          </table>
          <h1 class="invoice__title">Счет на оплату № <div class="invoice__number"><?= $order->id ?></div> от <div class="invoice__date">16.09.2023</div> г.</h1>
          <div class="counterparties">
            <div class="counterparty">
              <div class="counterparty__status">Поставщик:</div>
              <div class="counterparty__details">ИП Быков Павел Владимирович, ИНН 230407319116</div>
            </div>
            <div class="counterparty">
              <div class="counterparty__status">Покупатель:</div>
              <?php if ($orderData->type === Profile::ORGANIZATION_TYPE) { ?>
                <div class="counterparty__details">
                  <?= UserHelper::getFormsSob()[$orderData->form_sob] ?? ''; ?> 
                  <?= $orderData->organization ?? ''; ?> 
                  <?= $orderData->inn ? ', ИНН ' . $orderData->inn : ''; ?>
                  <?= $orderData->kpp ? ', КПП ' . $orderData->kpp : ''; ?>
                  <?= $orderData->address ? ', Адрес ' . $orderData->zip . ' ' . $orderData->city . ' ' . $orderData->address : ''; ?>
                  <?= $orderData->phone ? ', Телефон ' . FormatterHelper::echoPhone($orderData->phone) : ''; ?>
                </div>
              <?php } elseif ($orderData->type === Profile::INDIVIDUAL_TYPE) { ?>
                <div class="counterparty__details">
                  <?= $orderData->fio ?? ''; ?> 
                  <?= $orderData->address ? ', Адрес ' . $orderData->zip . ' ' . $orderData->city . ' ' . $orderData->address : ''; ?>
                  <?= $orderData->phone ? ', Телефон ' . FormatterHelper::echoPhone($orderData->phone) : ''; ?>
                </div>
              <?php } ?>
            </div>
            <div class="goods-table__wrap">
              <table class="goods-table">
              <tr>
                <th>№</th>
                <th>Товар</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма без скидки</th>
                <th>Скидка (наценка)</th>
                <th>Сумма</th>
              </tr>
              <?php $i = 0; ?>
              <?php foreach ($orderViewModel->getOrderItems() as $orderItem) { ?>
                <?php $i++; ?>
                <tr>
                  <td><?= $i; ?></td>
                  <td><?= $orderItem['name'] ?></td>
                  <td class="align-right"><?= $orderItem['count'] ?></td>
                  <td><?= PriceHelper::format($orderItem['price'], 2); ?></td>
                  <td><?= PriceHelper::format($orderItem['fullDiscountPrice'], 2); ?></td>
                  <td></td>
                  <td><?= PriceHelper::format($orderItem['fullDiscountPrice'], 2); ?></td>
                </tr>
              <?php } ?>
            </table>
            </div>
            <div class="goods-table__summary">
              <div class="shipment">
                <div class="shipment__span">
                  <div class="shipment__span-text">срок поставки</div>
                  <div class="shipment__span-days">15 рабочих дней</div>
                  <div class="shipment__span-conditions">после оплаты</div>
                </div>
                <div class="shipment__summary">
                  <div class="shipment__summary-row">
                    <div class="shipment__summary-text">Итого:</div>
                    <div class="shipment__summary-amount"><?= PriceHelper::format($order->total_discount_price); ?></div>
                    <div class="shipment__summary-discount"></div>
                    <div class="shipment__summary-result"><?= PriceHelper::format($order->total_discount_price); ?></div>
                   </div>
                   <div class="shipment__summary-row">
                    <div class="shipment__summary-text">Сумма с НДС:</div>
                    <div class="shipment__summary-tax"> Без НДС</div>
                  </div>
                </div>
              </div>
              <div class="shipment__result">
                <div class="shipment__result-text">Всего наименований</div>
                <div class="shipment__result-quantity"><?= count($orderViewModel->getOrderItems()); ?></div>
                <div class="shipment__result-text">, на сумму</div>
                <div class="shipment__result-amount"><?= PriceHelper::format($order->total_discount_price); ?></div>
                <!-- <div class="shipment__amount-spell">Сто двадцать восемь тысяч двести тридцать рублей 68 копеек</div> -->
              </div>
            </div>
            <div class="signatures-block">
              <div class="signatures-block__row">
                <div class="signatures-block__status">Руководитель</div>
                <div class="signatures-block__signature"></div>
                <div class="signatures-block__person">Быков П.В.</div>
              </div>
              <div class="signatures-block__row">
                <div class="signatures-block__status">Бухгалтер</div>
                                 <div class="signatures-block__signature"></div>
                <div class="signatures-block__person">Быков П.В.</div>
              </div>
              <div class="signatures-block__row">
                <div class="signatures-block__status">Менеджер</div>
                <div class="signatures-block__signature"></div>
                <div class="signatures-block__person"></div>
              </div>
            </div>
          </div>
        </div>
        </div>