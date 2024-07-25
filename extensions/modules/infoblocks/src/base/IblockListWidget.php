<?php

namespace CmsModule\Infoblocks\base;

use cms\common\frontend\base\DataWidget;
use CmsModule\Infoblocks\models\Infoblock;
use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 * Class IblockListWidget
 *
 * Базовый абстрактный виджет для списка элементов инфоблока
 *
 * @package CmsModule\Infoblocks\base
 */
abstract class IblockListWidget extends DataWidget
{
    public $iblock;
    public $where = ['active' => true];
    public $limit;
    public $order = ['sort' => SORT_ASC];
    private static $models;

    /*public function data()
    {
        $md5 = md5(serialize([$this->iblock, $this->where, $this->limit, $this->order]));

        if (!self::$models[$md5]) {
            $class = Infoblock::byCode($this->iblock);
            self::$models[$md5] = $class::find()
                ->where($this->where)
                ->limit($this->limit)
                ->orderBy($this->order)
                ->all();
        }
        return ['models' => self::$models[$md5]];
    }*/

    /**
     * @return array
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function data()
    {
        $md5 = md5(serialize([$this->iblock, $this->where, $this->limit, $this->order]));

        if (!isset(self::$models[$md5]) || empty(self::$models[$md5])) {
            $class = Infoblock::byCode($this->iblock);
            self::$models[$md5] = $class::find()
                ->where($this->where)
                ->limit($this->limit)
                ->orderBy($this->order)
                ->all();
        }
        return ['models' => self::$models[$md5]];
    }
}