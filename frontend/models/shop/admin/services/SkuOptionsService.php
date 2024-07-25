<?php

namespace frontend\models\shop\admin\services;

use Yii;

class SkuOptionsService
{
    public function getSkuFeatureValuePairs(int $skuId): array 
    {
        $sql = "SELECT
                    o.name as option_name, 
                    GROUP_CONCAT(i.name SEPARATOR ', ') as option_values
                FROM module_shop_sku_multi_options mo
                LEFT JOIN module_shop_options o on o.id = mo.option_id 
                LEFT JOIN module_shop_option_items i on i.id = mo.option_item_id  
                WHERE mo.sku_id = {$skuId}
                GROUP by mo.sku_id, option_name";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function getSkuFeatureValuePairsOptions(int $skuId): array
    {
        $sql = "SELECT
                    o.name as option_name, 
                    GROUP_CONCAT(i.name SEPARATOR ', ') as option_values
                FROM module_shop_sku_options so
                LEFT JOIN module_shop_options o on o.id = so.option_id 
                LEFT JOIN module_shop_option_items i on i.id = so.option_item_id  
                WHERE so.sku_id = {$skuId}
               
                GROUP by so.sku_id, option_name";

        return Yii::$app->db->createCommand($sql)->queryAll();
    }
}




