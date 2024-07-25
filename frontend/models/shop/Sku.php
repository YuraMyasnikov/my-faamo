<?php

namespace frontend\models\shop;

use CmsModule\Shop\common\models\Sku as ShopSku;
use yii\helpers\ArrayHelper;

class Sku extends ShopSku
{
    public function rules()
    {
        $rules = [];
        return ArrayHelper::merge(parent::rules(), $rules);
    }

    public function attributeLabels()
    {
        $labels = [];
        return ArrayHelper::merge(parent::attributeLabels(), $labels);
    }
}