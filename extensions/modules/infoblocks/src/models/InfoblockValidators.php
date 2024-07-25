<?php

namespace CmsModule\Infoblocks\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_iblock_property_validators".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $validator
 * @property string $enabled
 * @property string $param1
 * @property string $param2
 * @property string $param3
 * @property string $param4
 * @property string $param5
 * @property string $param6
 *
 * @property InfoblockProperties $property
 */
class InfoblockValidators extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_iblock_property_validators';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id'], 'integer'],
            [['name', 'param1', 'param2', 'param3', 'param4', 'param5', 'param6'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'name' => 'Name',
            'enabled' => 'Enabled',
            'param1' => 'Param1',
            'param2' => 'Param2',
            'param3' => 'Param3',
            'param4' => 'Param4',
            'param5' => 'Param5',
            'param6' => 'Param6',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProperty(): ActiveQuery
    {
        return $this->hasOne(InfoblockProperties::class, ['id' => 'property_id']);
    }
}
