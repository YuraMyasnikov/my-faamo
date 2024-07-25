<?php

use yii\db\Migration;

class m240714_100908_app_price_to_product_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{module_shop_products}}', 'price', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{module_shop_products}}', 'price');
    }
}
