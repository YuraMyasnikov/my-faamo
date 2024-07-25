<?php 

namespace frontend\services;

use frontend\models\shop\CalculateItem;
use Yii;

class CalculatorService 
{
    public function getPrices(): array 
    {
        return Yii::$app->params['basket.calculator.price_types'];
    }

    /**
     * @param array<int, int> $skusLimit
     */
    public function countDetails(string $basketToken, array $priceTypes, array|null $skus, array|null $skusLimit = null): array 
    {
        $sql = "SELECT b.sku_id, b.count FROM module_shop_basket b WHERE b.token = '$basketToken'";
        $skusInBasket = array_column(\Yii::$app->db->createCommand($sql)->queryAll(), 'count', 'sku_id');
        if(!$skusLimit) {
            $skusLimit = [];
        }
        foreach($skusLimit as $skuId => $skuLimit) {
            if(!isset($skusInBasket[$skuId])) {
                continue;
            }
            if($skusInBasket[$skuId] > $skuLimit) {
                $skusInBasket[$skuId] = $skuLimit;
            }
        }
        $skusTotal = $skusInBasket;
        if(is_array($skus) && count($skus)) {
            foreach($skus as $id => $count) {
                if(!isset($skusTotal[$id])) {
                    $skusTotal[$id] = 0;
                }
                $skusTotal[$id] += $count;
            }    
        };

        $subQuery = "SELECT b.sku_id FROM module_shop_basket b WHERE b.token = '$basketToken'";
        if(is_array($skus) && count($skus)) {
            $idsStr = implode(', ', array_keys($skus)); 
            $subQuery .= " UNION SELECT id FROM module_shop_sku WHERE id IN ($idsStr) ";
        }

        $sqlPricesDiscount = [];
        foreach($priceTypes as $name => $priceType) {
            $sqlPricesDiscount[$name] = floatval($priceType['discount'] ?? null);
        }

        $sql = <<<SQL
        SELECT 
            id, 
            price, 
            round((price * ((100 - {$sqlPricesDiscount['small_wholesale_price']})/100)), 2) as small_wholesale_price, 
            round((price * ((100 - {$sqlPricesDiscount['wholesale_price']})/100)), 2) as wholesale_price 
        FROM module_shop_sku 
        WHERE 
            id IN ($subQuery)
            ;
SQL;
        $skus = \Yii::$app->db->createCommand($sql)->queryAll();
        $skusNew = [];
        foreach($skus as $sku) {
            $skusNew[(int) $sku['id']] = new CalculateItem([
                'skuId' => $sku['id'],
                'count' => $skusTotal[$sku['id']] ?? 0,
                'price' => $sku['price'],
                'smallWholesalePrice' => $sku['small_wholesale_price'],
                'wholesalePrice' => $sku['wholesale_price'],
            ]);
        }

        return $skusNew;
    }


    /**
     * @param CalculateItem[] $skus
     */
    public function calculate(array $skus, array $types): array 
    {
        foreach($types as $type => $data) {
            $sum = 0;
        
            foreach($skus as $sku) {
                /** @var CalculateItem $sku */
                $sum += $sku->getRelevantPriceByType($type) * $sku->count;
                
                if(isset($data['max']) && ($sum > $data['max'])) {
                    break;
                }
            }
            if(isset($data['max']) && ($sum <= $data['max'])) {
                break;
            }
        }

        return [
            'sum'  => $sum,
            'price_type' => $type,
            'next' => isset($types[$type]['next']) ? [
                'price_type' => $types[$type]['next'],
                'sum' => $types[$type]['max'] + 1,
                'remaining' => isset($types[$type]['max']) ? ($types[$type]['max'] + 1) - $sum : null
            ] : null,
            'skus' => array_map(function($sku) use($type) {
                /** @var CalculateItem $sku */
                return [
                    'sku_id' => $sku->skuId,
                    'price'  => $sku->getRelevantPriceByType($type),
                    'count'  => $sku->count
                ];
            }, $skus),
        ];
    }
}