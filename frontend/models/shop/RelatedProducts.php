<?php

namespace frontend\models\shop;

use yii\db\ActiveRecord;

class RelatedProducts extends ActiveRecord
{
    public static function tableName()
    {
        return 'related_products';
    }

    public function rules()
    {
        return [
            [['base_product_id', 'related_product_id'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'base_product_id'    => 'Базовый продукт',
            'related_product_id' => 'Сопутствующий продукт',
        ];
    }

    public function getProduct()
    {
        $query = $this->hasOne(Products::class, ['id' => 'related_product_id']);

        return $query; 
    }
}