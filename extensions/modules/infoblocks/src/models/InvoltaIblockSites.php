<?php

namespace CmsModule\Infoblocks\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_iblock_sites".
 *
 * @property integer $id
 * @property integer $iblock_id
 * @property integer $site_id
 *
 * @property InfoblockTypes $iblock
 * @property InvoltaIblockSites $site
 */
class InvoltaIblockSites extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_iblock_sites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iblock_id'], 'required'],
            [['iblock_id', 'site_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iblock_id' => 'Iblock ID',
            'site_id' => 'Site ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getIblock(): ActiveQuery
    {
        return $this->hasOne(InfoblockTypes::class, ['id' => 'iblock_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSite(): ActiveQuery
    {
        return $this->hasOne(__CLASS__, ['id' => 'site_id']);
    }
}
