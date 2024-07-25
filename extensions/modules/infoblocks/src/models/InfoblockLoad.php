<?php

namespace CmsModule\Infoblocks\models;

use yii\base\Model;

/**
 * Class InfoblockLoad
 * @package CmsModule\Infoblocks\models
 */
class InfoblockLoad extends Model
{
    public $loadFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['loadFile', 'safe'],
            ['loadFile', 'file', 'extensions' => 'xls, xlsx'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'loadFile' => 'Таблица',
        ];
    }
}
