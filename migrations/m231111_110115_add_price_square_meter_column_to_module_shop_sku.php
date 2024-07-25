<?php

use CmsModule\Shop\common\models\Sku;
use yii\db\Migration;

/**
 * Class m231111_110115_add_price_square_meter_column_to_module_shop_sku
 */
class m231111_110115_add_price_square_meter_column_to_module_shop_sku extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Sku::tableName(), 'price_square_meter', $this->double()->unsigned()->notNull()->defaultValue(0));
        $this->addColumn(Sku::tableName(), 'old_price_square_meter', $this->double()->unsigned()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Sku::tableName(), 'price_square_meter');
        $this->dropColumn(Sku::tableName(), 'old_price_square_meter');
    }
}
