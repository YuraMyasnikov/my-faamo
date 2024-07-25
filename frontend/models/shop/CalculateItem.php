<?php 

namespace frontend\models\shop;

use yii\base\Model;

class CalculateItem extends Model 
{
    public int $skuId = 0;
    public int $count = 0;
    public float $price = 0;
    public float $smallWholesalePrice = 0;
    public float $wholesalePrice = 0;

    public function rules()
    {
        return [
            [['skuId', 'count', 'price', 'smallWholesalePrice', 'wholesalePrice'], 'safe']
        ];
    }

    public function getRelevantPriceByType(string $type): float 
    {
        if($type === 'price') {
            return $this->price;
        } else if($type === 'small_wholesale_price') {
            return $this->smallWholesalePrice;
        } else if($type === 'wholesale_price') {
            return $this->wholesalePrice;
        } 

        return $this->price;
    }
}