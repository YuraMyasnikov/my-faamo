<?php

namespace frontend\models\shop;

use CmsModule\Shop\common\models\Options as ShopOptions;
use yii\helpers\ArrayHelper;

class Options extends ShopOptions
{
    public function rules()
    {
        $rules = [
            [['friendly_url'], 'boolean']
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }

    public function attributeLabels()
    {
        $labels = [
            'friendly_url' => 'Использовать как ЧПУ'
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $labels);
    }
}