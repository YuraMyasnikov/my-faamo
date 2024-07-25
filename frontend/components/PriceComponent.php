<?php

namespace frontend\components;

use yii\base\Component as BaseComponent;

class PriceComponent extends BaseComponent
{
    public function __construct(
        protected int $smallWholesaleDiscountPercent = 0,
        protected int $wholesaleDiscountPercent = 0,
    ) {
    }

    public function getSmallWholesalePrice(float $retailPrice): float 
    {
        return $this->getPrice($retailPrice, $this->smallWholesaleDiscountPercent);
    }

    public function getWholesalePrice(float $retailPrice): float 
    {
        return $this->getPrice($retailPrice, $this->wholesaleDiscountPercent);
    }

    protected function getPrice(float $retailPrice, int $discountPercent): float 
    {
        return round(($retailPrice * ((100 - $discountPercent)/100)), 2);
    }
}
?>