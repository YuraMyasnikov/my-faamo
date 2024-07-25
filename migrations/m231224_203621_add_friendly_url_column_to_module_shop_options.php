<?php

use CmsModule\Shop\common\models\Options;
use yii\db\Migration;

/**
 * Class m231224_203621_add_friendly_url_column_to_module_shop_options
 */
class m231224_203621_add_friendly_url_column_to_module_shop_options extends Migration
{
    const COLUMN_NAME = 'friendly_url';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Options::tableName(), self::COLUMN_NAME, $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Options::tableName(), self::COLUMN_NAME);
    }
}
