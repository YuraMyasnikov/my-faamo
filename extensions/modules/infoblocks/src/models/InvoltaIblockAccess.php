<?php

namespace CmsModule\Infoblocks\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_iblock_access".
 *
 * @property integer $iblock_id
 * @property string $role_name
 *
 * @property InfoblockTypes $iblock
 */
class InvoltaIblockAccess extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_iblock_access';
    }

    /**
     * Определяем PrimaryKey для модели, так как в таблице он отсутствует
     *
     * @return array|string[]
     */
    public static function primaryKey()
    {
        return [
            'iblock_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iblock_id'], 'required'],
            [['iblock_id'], 'integer'],
            [['role_name'], 'string', 'max' => 255],
            [['iblock_id'], 'exist', 'skipOnError' => true, 'targetClass' => InfoblockTypes::class, 'targetAttribute' => ['iblock_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iblock_id' => 'Iblock ID',
            'role_name' => 'Role Name',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getIblock(): ActiveQuery
    {
        return $this->hasOne(InfoblockTypes::class, ['id' => 'iblock_id']);
    }
}
