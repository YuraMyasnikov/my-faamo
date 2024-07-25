<?php

namespace CmsModule\Infoblocks\models;

use yii\db\ActiveRecordInterface;

/**
 * Interface InfoblockInterface
 * @package CmsModule\Infoblocks\models
 */
interface InfoblockInterface extends ActiveRecordInterface
{
    /**
     * @return AbstractInfoblock
     */
    public static function create(): AbstractInfoblock;
}
