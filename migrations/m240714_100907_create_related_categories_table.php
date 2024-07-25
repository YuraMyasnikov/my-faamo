<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%related_categories}}`.
 */
class m240714_100907_create_related_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%related_categories}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'category_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%related_categories}}');
    }
}
