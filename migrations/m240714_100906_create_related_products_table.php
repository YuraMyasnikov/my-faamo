<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%related_products}}`.
 */
class m240714_100906_create_related_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%related_products}}', [
            'id' => $this->primaryKey(),
            'base_product_id' => $this->integer(),
            'related_product_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%related_products}}');
    }
}
