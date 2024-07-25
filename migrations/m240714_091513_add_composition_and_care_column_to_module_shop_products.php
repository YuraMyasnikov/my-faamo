<?php

use yii\db\Migration;

/**
 * Class m240714_091513_add_composition_and_cate_column_to_module_shop_products
 */
class m240714_091513_add_composition_and_care_column_to_module_shop_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{module_shop_products}}', 'description_composition_and_care', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{module_shop_products}}', 'description_composition_and_care');
    }
}
