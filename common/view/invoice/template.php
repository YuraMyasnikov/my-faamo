<?php 

use CmsModule\Shop\common\models\OrderViewModel;
use cms\common\models\Profile;

/**
 * @var OrderViewModel $orderViewModel
 * @var Profile $profile
 */

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Счет на оплату № <?= $orderViewModel->order->id ?> от <?= Yii::$app->formatter->asDate(new DateTime, 'php:d.m.Y') ?> г.</title>
        <style>
            * {
                font-family: "dejavu sans";
            }
        </style>
    </head>
    <body>
        <table style="width: 750px; padding: 0; margin: 0 auto; color: #000000; font-size: 12px; border="0"
                cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>
                    <table style="margin: 0 0 20px; padding: 0; width: 100%; border: 1px solid #000; line-height: 1.2em;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 360px;" colspan="2"
                                rowspan="2">
                                <p style="font-size: 13px; margin: 0;">
                                    ИВАНОВСКОЕ ОТДЕЛЕНИЕ N 8639 ПАО СБЕРБАНК г.&nbsp;Иваново
                                </p>
                                <br><br>
                                <small>Банк получателя</small>
                            </td>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 60px;">
                                БИК
                            </td>
                            <td style="vertical-align: top; padding: 1px; border-bottom: 1px solid #000; width: 320px" rowspan="2">
                                042406608<br>30101810000000000608<br>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 60px;">
                                Сч. №
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 180px;">
                                <span style="margin-right: 10px; font-size: 13px;">ИНН</span> 371200768605
                            </td>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000; border-bottom: 1px solid #000; width: 180px;">
                                <span style="margin-right: 10px; font-size: 13px;">КПП</span> 
                            </td>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000; width: 60px;" rowspan="2">
                                Сч. №
                            </td>
                            <td style="vertical-align: top; padding: 1px; width: 320px;" rowspan="2">
                                40802810017000025828
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 1px; border-right: 1px solid #000;" colspan="2">
                                <p style="font-size: 13px; margin: 0">ИП Иванов Алексей Геннадьевич</p>
                                <br>
                                <small>Получатель</small>
                            </td>
                        </tr>
                    </table>
                    <table style="margin: 24px 0 10px; padding: 0; width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="vertical-align: top; font-size: 18px; font-weight: bold; border-bottom: 2px solid #000; padding: 0 6px 11px;">
                                Счет на оплату № <?= $orderViewModel->order->id ?> от <?= Yii::$app->formatter->asDate(new DateTime, 'php:d.m.Y') ?> г.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin: 0; padding: 0; width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="vertical-align: top; padding: 5px 5px 5px 1px; width: 120px;">
                                Поставщик: (исполнитель):
                            </td>
                            <td style="vertical-align: top; padding: 5px 1px 5px; width: 625px; font-weight: bold; font-size: 13px;">
                                ИП Иванов Алексей Геннадьевич,  ИНН 371200768605,  153007, Ивановская обл, г Иваново, ул 5-я Минеевская, д. 58А,  тел.: 89303529958
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 5px 5px 5px 1px; width: 120px;">
                                Покупатель: (заказчик):
                            </td>
                            <td style="vertical-align: top; padding: 5px 1px 5px; width: 625px; font-weight: bold; font-size: 13px;">
                                <?= $profile->surname ?> <?= $profile->name ?> <?= $profile->patronymic ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; padding: 5px 5px 5px 1px; width: 120px;">
                                Основание:
                            </td>
                            <td style="vertical-align: top; padding: 5px 1px 5px; width: 625px; font-weight: bold; font-size: 13px;">
                                Основной договор
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin-top: 5px; border: 2px solid #000; padding: 0; width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 40px;">№</th>
                            <th style="border-left: 1px solid #000; width: 160px;">Товар (Услуга) </th>
                            <th style="border-left: 1px solid #000; width: 60px;">Код</th>
                            <th style="border-left: 1px solid #000; width: 60px;">Кол-во</th>
                            <th style="border-left: 1px solid #000; width: 40px;">Ед.</th>
                            <th style="border-left: 1px solid #000; width: 80px;">Цена</th>
                            <th style="border-left: 1px solid #000; width: 80px;">Сумма</th>
                        </tr>
                        <tr>
                            <th style="width: 40px; border-top: 1px dashed #000; font-weight: normal; color: #ccc;;">1</th>
                            <th style="border-left: 1px solid #000; border-top: 1px dashed #000; color: #ccc; font-weight: normal; width: 160px;">2</th>
                            <th style="border-left: 1px solid #000; border-top: 1px dashed #000; color: #ccc; font-weight: normal; width: 60px;">3</th>
                            <th style="border-left: 1px solid #000; border-top: 1px dashed #000; color: #ccc; font-weight: normal; width: 60px;">4</th>
                            <th style="border-left: 1px solid #000; border-top: 1px dashed #000; color: #ccc; font-weight: normal; width: 40px;">5</th>
                            <th style="border-left: 1px solid #000; border-top: 1px dashed #000; color: #ccc; font-weight: normal; width: 80px;">6</th>
                            <th style="border-left: 1px solid #000; border-top: 1px dashed #000; color: #ccc; font-weight: normal; width: 80px;">7</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($orderViewModel->getOrderItems() as $i => $orderItem) {?>
                            <?php 
                                $sku = $orderItem['orderSku']->sku;        
                                $count = $orderItem['orderSku']->count;
                                $price = $orderItem['orderSku']->price;
                            ?>
                            <tr>
                                <td style="vertical-align: top; padding: 1px; text-align: center; border-top: 1px solid #000;">
                                    <small><?= $i+1 ?></small>
                                </td>
                                <td style="vertical-align: top; padding: 1px; border-top: 1px solid #000; border-left: 1px solid #000;">
                                    <small><?= $orderItem['name'] ?> <?= $sku->name ?> </small>
                                </td>
                                <td style="vertical-align: top; padding: 1px; border-top: 1px solid #000; border-left: 1px solid #000; line-height: 1em;">
                                    <small><?= $sku->code ?></small>
                                </td>
                                <td style="vertical-align: top; padding: 1px; border-top: 1px solid #000; border-left: 1px solid #000; text-align: right;">
                                    <small><?= $count ?></small>
                                </td>
                                <td style="vertical-align: top; padding: 1px; border-top: 1px solid #000; border-left: 1px solid #000;">
                                    <small>шт</small>
                                </td>
                                <td style="vertical-align: top; padding: 1px; border-top: 1px solid #000; border-left: 1px solid #000; text-align: right;">
                                    <small><?= $price ?></small>
                                </td>
                                <td style="vertical-align: top; padding: 1px; border-top: 1px solid #000; border-left: 1px solid #000; text-align: right;">
                                    <small><?= $count * $price ?></small>
                                </td>
                            </tr>
                        <?php } ?>
                        <!-- ИТОГО -->
                        <tr>
                            <td style="vertical-align: top; padding: 1px; text-align: center; border-top: 2px solid #000;" colspan="3">
                                
                            </td>
                            <td style="vertical-align: top; padding: 1px; border-top: 2px solid #000; border-left: 1px solid #000; text-align: right;">
                                <small><?= $totalCount ?></small>
                            </td>
                            <td style="vertical-align: top; padding: 1px; border-top: 2px solid #000; border-left: 1px solid #000; text-align: right;" colspan="3">
                                <small><?= $totalPrice ?></small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin-top: 5px; padding: 0; border: 2px solid #fff; width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 360px; text-align: right; font-size: 13px; padding: 2px 0;">Итого:</th>
                            <th style="width: 160px; text-align: right; font-size: 13px; padding: 2px 0;">Сумма</th>
                        </tr>
                        <tr>
                            <th style="width: 360px; text-align: right; font-size: 13px; padding: 2px 0;">Без налога (НДС)</th>
                            <th style="width: 160px; text-align: right; font-size: 13px; padding: 2px 0;">-</th>
                        </tr>
                        <tr>
                            <th style="width: 360px; text-align: right; font-size: 13px; padding: 2px 0;">Всего к оплате: </th>
                            <th style="width: 160px; text-align: right; font-size: 13px; padding: 2px 0;"><?= $totalPrice ?></th>
                        </tr>
                        </thead>
                    </table>
                    <table style="margin: 0 0 7px; padding: 0; width: 100%; line-height: 1.5em;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="vertical-align: top; padding: 1px; font-size: 13px; border-bottom: 2px solid #000; padding: 0 6px 8px;">
                                Всего наименований <?= $totalCount ?>, на сумму <?= $totalPrice ?> руб<br>
                                <b><?= $totalPriceWord ?></b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin: 20px 0 0 0; padding: 0; width: 100%; line-height: 1.5em;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="font-size: 13px; font-weight: bold; width: 10px;">Предприниматель</td>
                            <td style="border-bottom: 1px solid #000; width: 200px;"></td>
                            <td style="width: 20px;"></td>
                            <td style="border-bottom: 1px solid #000;"><small>Иванов А. Г.</small></td>
                            <td style="width: 50px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; font-weight: bold; width: 10px;"></td>
                            <td style="width: 200px; text-align: center;"><small>подпись</small></td>
                            <td style="width: 20px;"></td>
                            <td style="text-align: center;"><small>расшифровка подписи</small></td>
                            <td style="width: 50px;"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin: 5px 0 0 0; padding: 0; width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="text-align: center;">
                                <img src="<?= $base64ContentOfStamp ?>" style="width: 200px;">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="margin: 5px 0 0 0; padding: 0; width: 100%;" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="text-align: right; font-style: italic;">
                                <small>Администратор</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </body>
</html>