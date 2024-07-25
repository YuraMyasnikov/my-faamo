<?php

use yii\db\Migration;

/**
 * Class m240718_113014_add_seo_links_column_to_categories
 */
class m240718_113014_add_seo_links_column_to_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{module_shop_categories}}', 'seo_links', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{module_shop_categories}}', 'seo_links');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240718_113014_add_seo_links_column_to_categories cannot be reverted.\n";

        return false;
    }
    */
}
