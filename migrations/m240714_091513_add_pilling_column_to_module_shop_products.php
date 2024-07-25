<?php

use yii\db\Migration;

class m240714_091513_add_pilling_column_to_module_shop_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{module_shop_products}}', 'description_pilling', $this->text());
        $this->addColumn('{{module_shop_products}}', 'description_measurements', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{module_shop_products}}', 'description_pilling');
        $this->dropColumn('{{module_shop_products}}', 'description_measurements');
    }
}
