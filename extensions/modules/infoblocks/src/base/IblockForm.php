<?php

namespace CmsModule\Infoblocks\base;

use cms\common\Core;
use cms\common\frontend\base\FormModel;
use CmsModule\Infoblocks\models\Infoblock;

/**
 * Class IblockForm
 * @package CmsModule\Infoblocks\base
 */
abstract class IblockForm extends FormModel
{
    protected $iblock = 'code';
    protected $availableFields = [];

    public function save()
    {
        $class = Infoblock::byCode($this->iblock);

        $model = $class::create();
        $model->code = time();
        $model->site_id = Core::getCurrentSiteId();
        foreach ($this->availableFields as $availableField) {
            $model->$availableField = $this->$availableField;
        }

        return $model->save();
    }
}
