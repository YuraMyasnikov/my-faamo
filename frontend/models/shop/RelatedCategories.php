<?php

namespace frontend\models\shop;

use yii\db\ActiveRecord;

class RelatedCategories extends ActiveRecord
{
    public static function tableName()
    {
        return 'related_categories';
    }

    public function rules()
    {
        return [
            [['product_id', 'category_id'], 'safe']
        ];
    }

    public function getProduct()
    {
        $query = $this->hasOne(Products::class, ['id' => 'product_id']);

        return $query; 
    }

    public function getCategory()
    {
        $query = $this->hasOne(Categories::class, ['id' => 'category_id']);

        return $query; 
    }
}