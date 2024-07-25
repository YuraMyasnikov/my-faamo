<?php

use yii\db\Migration;

class m240714_100908_fill_price_at_product_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db
            ->createCommand("UPDATE module_shop_products p SET p.price = (SELECT MAX(s.price) FROM module_shop_sku s WHERE s.product_id = p.id GROUP BY s.product_id)")
            ->execute();
    }
}
