<?php

namespace frontend\models\shop;

use CmsModule\Shop\common\models\Options;
use CmsModule\Shop\common\models\Products as ShopProducts;
use CmsModule\Shop\common\models\ProductsMultiOptions;
use frontend\helpers\ProductsHelper;
use frontend\models\shop\Options as ShopOptions;
use yii\db\Query;


class Products extends ShopProducts
{
    public function afterSave($insert, $changedAttributes) 
    {
        foreach($this->sku as $sku) {
            /** @var \frontend\models\shop\Sku $sku */
            $sku->price = $this->price;
            $sku->save();
        }
        
        parent::afterSave($insert, $changedAttributes);
    }

    public function getSku()
    {
        $query = $this->hasMany(Sku::class, ['product_id' => 'id']);
        return $query; 
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['description_composition_and_care', 'description_pilling', 'description_measurements'], 'string'],
            [['description_composition_and_care', 'description_pilling', 'description_measurements'], 'safe'],
            [['price'], 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'description_composition_and_care' => 'Состав и уход',
            'description_pilling' => 'Пиллингуемость',
            'description_measurements' => 'Обмеры изделия',
            'price' => 'Цена',
        ]);
    }

    public function getProductToStickers(): array
    {
        $stickerOption = Options::findOne(['code' => ProductsHelper::getOptionStickerCode()]);

        if (!$stickerOption) {
            return [];
        }

        return ProductsMultiOptions::find()
            ->where(['product_id' => $this->id])
            ->andWhere(['option_id' => $stickerOption->id])
            ->all();
    }

    public static function getFiltered(
        $categoryId,
        $filters = [],
        $minPrice = false,
        $maxPrice = false,
        $limit = false,
        $page = 1,
        $orderBy = 'sort',
        $useCache = 1,
    ) {

        $options = ShopOptions::find()->where(['code' => array_keys($filters)])->asArray()->all();
        $optionsKeys = array_column($options, 'code');

        $queryWrap = (new self())
            ->find()
            ->distinct()
            ->select(['p.*'])
            ->from(['p' => 'module_shop_products'])
            ->leftJoin(['s' => 'module_shop_sku'], 's.product_id = p.id')
            ->where(['p.active' => true])
            ->andWhere([
                'OR',
                ['p.category_id' => $categoryId],
                ['IN', 'p.id', (new Query())->select('rc.product_id')->from(['rc' => 'related_categories'])->where(['rc.category_id' => $categoryId])]
            ])
            ->orderBy(match ($orderBy) {
                'price' => 'p.price',
                '-price' => 'p.price DESC',
                default => 'p.sort DESC'
            });

        $productFilters = [];
        $skuFilters     = [];

        foreach($filters as $optionCode => $optionValues) {
            /** @var array $optionValues */
            $index = array_search($optionCode, $optionsKeys);
            if($index === false || !isset($options[$index]['type']) || !isset($options[$index]['multi'])) {
                continue;
            }
            $optionType  = $options[$index]['type'];
            $optionMulty = $options[$index]['multi'];
            switch($optionType) {
                // product
                case 1:
                    switch($optionMulty) {
                        case 0:
                            // single
                            $productSingleOptionQuery = new Query();
                            $productSingleOptionQuery
                                ->select('p.id')
                                ->from(['p' => 'module_shop_products'])
                                ->leftJoin(['po' => 'module_shop_products_options'], 'po.product_id = p.id')
                                ->leftJoin(['oi' => 'module_shop_option_items'], 'oi.id = po.option_item_id')
                                ->leftJoin(['o'  => 'module_shop_options'], 'o.id = po.option_id')
                                ->where([
                                    'AND',
                                    ['o.code' => $optionCode],
                                    ['oi.code' => $optionValues]
                                ])
                              ;
                              $productFilters[] = $productSingleOptionQuery;
                            break;        
                        case 1:
                            // multy
                            $productMultiOptionQuery = new Query();
                            $productMultiOptionQuery
                                ->select('p.id')
                                ->from(['p' => 'module_shop_products'])
                                ->leftJoin(['po' => 'module_shop_products_multi_options'], 'po.product_id = p.id')
                                ->leftJoin(['oi' => 'module_shop_option_items'], 'oi.id = po.option_item_id')
                                ->leftJoin(['o'  => 'module_shop_options'], 'o.id = po.option_id')
                                ->where([
                                    'AND',
                                    ['o.code' => $optionCode],
                                    ['oi.code' => $optionValues]
                                ])
                              ;
                              $productFilters[] = $productMultiOptionQuery;
                            break;        
                    };
                    break;

                // sku
                case 2:
                    switch($optionMulty) {
                        case 0:
                            // single
                            $skuSingleOptionQuery = new Query();
                            $skuSingleOptionQuery
                                ->select('s.id')
                                ->from(['s' => 'module_shop_sku'])
                                ->leftJoin(['so' => 'module_shop_sku_options'], 'so.sku_id = s.id')
                                ->leftJoin(['oi' => 'module_shop_option_items'], 'oi.id = so.option_item_id')
                                ->leftJoin(['o'  => 'module_shop_options'], 'o.id = so.option_id')
                                ->where([
                                    'AND',
                                    ['o.code' => $optionCode],
                                    ['oi.code' => $optionValues]
                                ])
                              ;
                              $skuFilters[] = $skuSingleOptionQuery;
                            break;        
                        case 1:
                            // multy
                            $skuMultiOptionQuery = new Query();
                            $skuMultiOptionQuery
                                ->select('s.id')
                                ->from(['s' => 'module_shop_sku'])
                                ->leftJoin(['so' => 'module_shop_sku_multi_options'], 'so.sku_id = s.id')
                                ->leftJoin(['oi' => 'module_shop_option_items'], 'oi.id = so.option_item_id')
                                ->leftJoin(['o'  => 'module_shop_options'], 'o.id = so.option_id')
                                ->where([
                                    'AND',
                                    ['o.code' => $optionCode],
                                    ['oi.code' => $optionValues]
                                ])
                              ;
                              $skuFilters[] = $skuMultiOptionQuery;
                            break;        
                    }
                    break;
            }
        }

        foreach($productFilters as $query) {
            if(!$query instanceof Query) {
                continue;
            }
            $queryWrap->andWhere(['p.id' => $query]);
        }

        if(count($skuFilters)) {
            $skuFiltersQuery = new Query();
            $skuFiltersQuery
                ->distinct()
                ->select(['s.product_id']);
            foreach($skuFilters as $query) {
                if(!$query instanceof Query) {
                    continue;
                }
                
                $queryWrap->andWhere(['s.id' => $query]);
            }    
        }
        return $queryWrap;
    }
}