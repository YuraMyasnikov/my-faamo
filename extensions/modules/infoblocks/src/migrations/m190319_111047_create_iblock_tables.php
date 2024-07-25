<?php

use yii\db\Migration;
use CmsModule\Infoblocks\traits\Access;

/**
 * Class m190319_111047_create_iblock_tables
 */
class m190319_111047_create_iblock_tables extends Migration
{
    use Access;

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('cms_iblock_property', [
            'id' => $this->primaryKey()->unsigned(),
            'iblock' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(),
            'code' => $this->string()->notNull(),
            'type' => $this->integer()->notNull(),
            'multi' => $this->boolean()->notNull()->defaultValue(false),
            'sort' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('cms_iblock_property_links', [
            'id' => $this->primaryKey()->unsigned(),
            'property_id' => $this->integer()->unsigned()->notNull(),
            'iblock_id' => $this->integer()->notNull(),
            'linked_id' => $this->integer()->notNull(),
            'type_id' => $this->integer(1)->notNull(),
        ], $tableOptions);

        $this->createTable('cms_iblock_property_validators', [
            'id' => $this->primaryKey()->unsigned(),
            'property_id' => $this->integer()->notNull()->unsigned(),
            'name' => $this->string(),
            'enabled' => $this->boolean()->defaultValue(false),
            'param1' => $this->string(),
            'param2' => $this->string(),
            'param3' => $this->string(),
            'param4' => $this->string(),
            'param5' => $this->string(),
            'param6' => $this->string(),
        ], $tableOptions);

        $this->createTable('cms_infoblock_types', [
            'id' => $this->primaryKey()->unsigned(),
            'folder_id' => $this->integer()->unsigned(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'type' => $this->integer(1)->notNull()->defaultValue(1)
        ], $tableOptions);

        $this->createTable('cms_iblock_folders', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'parent_id' => $this->integer()->unsigned()
        ], $tableOptions);


        $this->createTable('cms_iblock_sites', [
            'id' => $this->primaryKey()->unsigned(),
            'iblock_id' => $this->integer()->unsigned()->notNull(),
            'site_id' => $this->integer(3)->unsigned()->notNull()
        ]);

        $this->createTable('cms_iblock_access', [
            'iblock_id' => $this->integer()->unsigned()->notNull(),
            'role_name' => $this->string()
        ]);


        $this->addForeignKey(
            'FK_cms_iblock_types_folder',
            'cms_infoblock_types',
            'folder_id',
            'cms_iblock_folders',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'FK_cms_iblock_folder_folder',
            'cms_iblock_folders',
            'parent_id',
            'cms_iblock_folders',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'FK_cms_iblock_property_ibfk_1',
            'cms_iblock_property',
            'iblock',
            'cms_infoblock_types',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'FK_cms_iblock_property_links_ibfk_1',
            'cms_iblock_property_links',
            'property_id',
            'cms_iblock_property',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'FK_cms_iblock_property_validators_ibfk_1',
            'cms_iblock_property_validators',
            'property_id',
            'cms_iblock_property',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'FK_iblock_access_iblock_id',
            'cms_iblock_access',
            'iblock_id',
            'cms_infoblock_types',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->removeAccess();
        $this->setAccess();
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_cms_iblock_property_ibfk_1', 'cms_iblock_property');
        $this->dropForeignKey('FK_cms_iblock_property_links_ibfk_1', 'cms_iblock_property_links');
        $this->dropForeignKey('FK_cms_iblock_property_validators_ibfk_1', 'cms_iblock_property_validators');
        $this->dropForeignKey('FK_cms_iblock_folder_folder', 'cms_iblock_folders');
        $this->dropForeignKey('FK_cms_iblock_types_folder', 'cms_infoblock_types');
        $this->dropForeignKey('FK_iblock_access_iblock_id', 'cms_iblock_access');

        $this->dropTable('cms_iblock_sites');
        $this->dropTable('cms_iblock_folders');
        $this->dropTable('cms_infoblock_types');
        $this->dropTable('cms_iblock_property_validators');
        $this->dropTable('cms_iblock_property_links');
        $this->dropTable('cms_iblock_property');
        $this->dropTable('cms_iblock_access');

        $this->removeAccess();
    }
}