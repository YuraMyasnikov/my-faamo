<?php

namespace frontend\services;

use CmsModule\Shop\frontend\services\FiltersService as ShopFiltersService;
use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\common\models\OptionItems;
use CmsModule\Shop\common\models\Options;
use CmsModule\Shop\common\models\OptionsToCategories;
use CmsModule\Shop\common\models\OptionsToCategoriesFriendlyUrl;
use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\common\models\ProductsMultiOptions;
use CmsModule\Shop\common\models\ProductsOptions;
use CmsModule\Shop\common\models\Sku;
use CmsModule\Shop\common\models\SkuMultiOptions;
use CmsModule\Shop\common\models\SkuOptions;
use yii\db\Query;

class FiltersService extends ShopFiltersService
{
    public function getFilters($categoryId, array $filterParams = [], $minPrice = false, $maxPrice = false, $orderBy = 'sort'): array
    {
        if($categoryId) {
            $categoryModel = Categories::find()->where(['id' => $categoryId])->cache()->one();
        } else {
            $categoryModel = null;
        }
        // $categoryIdList = Categories::find()->select('id')->where(['parent_id' => $categoryModel->id])->column();
        // $categoryIdList[] = $categoryModel->id;
        $query = (new Query())->select([
            'option.id',
            'option.code',
            'option.sort',
            'option.friendly_url',
            'optionitem.sort as valueSort',
            'option.type',
            'option.name',
            'optionitem.id as valueId',
            'optionitem.name as valueName',
            'optionitem.code as valueCode',
            'sku.active as sku_active',
            'product.active as product_active',
        ])
            ->distinct(true)
            ->from(Options::tableName() . ' as option');
        
        $query->innerJoin(OptionItems::tableName()         . ' optionitem'          , 'optionitem.option_id           = option.id');
        $query->leftJoin(OptionsToCategories::tableName()  . ' option_to_category'  , 'option_to_category.option_id   = option.id');
        $query->leftJoin(ProductsOptions::tableName()      . ' product_option'      , 'product_option.option_id       = option.id');
        $query->leftJoin(ProductsMultiOptions::tableName() . ' product_multi_option', 'product_multi_option.option_id = option.id');
        $query->leftJoin(Products::tableName()             . ' product'             , 'product.id                     = product_option.product_id or product.id = product_multi_option.product_id');
        $query->leftJoin(SkuOptions::tableName()           . ' sku_option'          , 'sku_option.option_id           = option.id');
        $query->leftJoin(SkuMultiOptions::tableName()      . ' sku_multi_option'    , 'sku_multi_option.option_id     = option.id');
        $query->leftJoin(Sku::tableName()                  . ' sku'                 , 'sku.id                         = sku_option.sku_id or sku.id = sku_multi_option.sku_id');
        // 
        $query->andWhere(['option.active' => 1]);
        $query->andWhere([
            'OR', 
            ['product.active' => 1, 'option.type' => 1], 
            ['sku.active'     => 1, 'option.type' => 2],
        ]);
        $query->andWhere([
            'optionitem.active' => true,
            'option.filter'     => true,
        ]);

        if($categoryModel) {
            $query->andWhere(['option_to_category.category_id' => $categoryModel->id]);
        }

        $query
            ->orderBy([
                'sort' => SORT_ASC,
                'valueSort' => SORT_ASC,
                'name' => SORT_ASC
            ]);

        $data = $query->all();

        $result = [];
        $added = [];
        $filterIdList = [];

        foreach ($data as $d) {
                $optionToCategoriesConditions = ['option_id' => $d['id']];
                if($categoryModel) {
                    $optionToCategoriesConditions['category_id'] = $categoryModel->id;
                }
                $optionToCategoriesFriendlyUrl = OptionsToCategoriesFriendlyUrl::find()
                    ->where($optionToCategoriesConditions)
                    ->one();
                
                $filterIdList[] = (int)$d['id'];

                if (empty($result[$d['code']])) {
                    $result[$d['code']] = [
                        'id' => (int)$d['id'],
                        'name' => $d['name'],
                        'code' => $d['code'],
                        'sort' => (int)$d['sort'],
                        'friendly_url' => (int)($d['friendly_url'] && $optionToCategoriesFriendlyUrl),
                        'params' => []
                    ];
                }

            if (in_array((int)$d['valueId'], $added, true)) {
                continue;
            }
            $added[] = (int)$d['valueId'];

            $tmp = [
                'id' => (int)$d['valueId'],
                'name' => $d['valueName'],
                'code' => $d['valueCode'] ?: 'filter_' . ((int)$d['valueId']),
                'sort' => (int)$d['valueSort'],
            ];

            $result[$d['code']]['params'][] = $tmp;
        }

        $resultArray = [];
        foreach ($result as $k => $v) {
            $resultArray[] = $v;
        }

        $filters = [
            'catalogs' => $resultArray,
        ];

        foreach ($filters['catalogs'] as $index => $prop) {
            if (!$prop['params']) {
                unset($filters['catalogs'][$index]);
            }
        }

        foreach ($filters['catalogs'] as $index => $prop) {
            foreach ($prop['params'] as $k => $v) {
                if (!empty($filterParams[$prop['code']]) && in_array($v['code'], $filterParams[$prop['code']])) {
                    $isChecked = true;
                } else {
                    $isChecked = false;
                }

                $filters['catalogs'][$index]['params'][$k]['isChecked'] = $isChecked;
            }
        }

        return $filters;
    }
}