<?php

namespace CmsModule\Infoblocks\models;

use yii\{db\ActiveQuery, db\ActiveRecord};

/**
 * This is the model class for table "cms_iblock_property_links".
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $iblock_id
 * @property integer $linked_id
 * @property integer $type_id
 *
 * @property InfoblockProperties $property
 */
class InfoblockLinks extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'cms_iblock_property_links';
    }

    public function rules(): array
    {
        return [
            [['property_id', 'iblock_id'], 'required'],
            [['property_id', 'iblock_id', 'linked_id', 'type_id'], 'integer']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'iblock_id' => 'Iblock ID',
            'linked_id' => 'Linked ID',
            'type_id' => 'Type ID',
        ];
    }

    public function getProperty(): ActiveQuery
    {
        return $this->hasOne(InfoblockProperties::class, ['id' => 'property_id']);
    }

    public function getLinkedProperty(): ActiveQuery
    {
        return $this->hasOne(InfoblockProperties::class, ['id' => 'linked_id']);
    }

    public function getType(): ActiveQuery
    {
        return $this->hasOne(InfoblockTypes::class, ['id' => 'iblock_id']);
    }
}
