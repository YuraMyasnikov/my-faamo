<?php

use CmsModule\Shop\common\models\Payments;
use yii\db\Migration;

/**
 * Class m231203_165533_add_payment_type
 */
class m231203_165533_add_payment_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $payment = new Payments();
        $payment->name = 'Оплата по счету';
        $payment->code = Payments::PAYMENT_ON_ACCOUNT;
        $payment->active = true;
        $payment->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Payments::deleteAll(['code' => Payments::PAYMENT_ON_ACCOUNT]);
    }
}
