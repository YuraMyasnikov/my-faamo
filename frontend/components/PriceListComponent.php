<?php

namespace frontend\components;

use frontend\services\CalculatorService;
use Yii;
use yii\base\Component as BaseComponent;

class PriceListComponent extends BaseComponent
{
    private array $categories = [];
    private array $categoriesMap = [];
    private array $skuMap = [];
    private array $productsMap = [];
    private array $features = [];
    private array $columns = [];

    public function create(): self 
    {
        $calculator  = Yii::$container->get(CalculatorService::class);
        $pricesTypes = $calculator->getPrices();

        $this->fillFeatures();
        $this->fillCategories();
        $this->fillProducts($pricesTypes);
        $this->fillCategoriesCountProducts();
        $this->fillSkuMultyOptionsValues();
        $this->fillProductMultyOptionsValues();
        $this->fillProductOptionsValues();

        $this->columns = [
            'sku_code'     => 'Код',
            'sku_articul'  => 'Артикул',
            'product_name' => 'Название',
            'remnants'     => 'Остаток',
            'price'        => 'Цена',
        ];
        foreach($this->features as $featureId => $featureTitle) {
            $this->columns['feature_' . $featureId] = $featureTitle;
        }

        return $this;
    }

    public function getColumns(): array 
    {
        return $this->columns;
    }

    public function getCatalog(): array 
    {
        return $this->categories;
    }

    public function getListing(): array 
    {
        $listing = [];
        $this->flatList($this->categories, $listing);

        return $listing;
    }

    private function fillFeatures(): void 
    {
        $sql = "SELECT o.id, o.name FROM module_shop_options o WHERE o.active = 1";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        foreach($list as $item) {
            $this->features[$item['id']] = $item['name'];
        }
    }

    private function fillCategories(): void 
    {
        $sql = "SELECT msc.id, msc.parent_id, msc.name FROM module_shop_categories msc WHERE msc.active = 1 ORDER BY msc.id ASC, msc.parent_id ASC";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        foreach($list as &$item) {
            $id = $item['id'];
            $this->categoriesMap[$id] = &$item;
        }

        foreach($this->categoriesMap as &$item) {
            $id = $item['id'];
            $parentId = $item['parent_id'];

            $this->categoriesMap[$id] = &$item;

            if($parentId) {
                $this->categoriesMap[$parentId]['childs'][$id] = &$item;    
            } else {
                $this->categories[$id] = &$item;
            }
        }
    }

    private function fillCategoriesCountProducts(): void 
    {
        foreach($this->categoriesMap as &$item) {
            $countProducts = count($item['products'] ?? []);
            $item['count_products'] = $countProducts;
            if(!$countProducts) {
                continue;
            }

            $parentId = intval($item['parent_id'] ?? null);
            if(!$parentId) {
                continue;
            }

            do {
                $this->categoriesMap[$parentId]['count_products'] += $countProducts;
                $parentId = $this->categoriesMap[$parentId]['parent_id'] ?? null;
            } while($parentId);
        }
    }

    private function fillProducts(array $priceTypes): void
    {
        $sqlPricesDiscount = [];
        foreach($priceTypes as $name => $priceType) {
            $sqlPricesDiscount[$name] = floatval($priceType['discount'] ?? null);
        }

        $sql = "SELECT 
            sku.id, 
            sku.image_id, 
            sku.product_id, 
            p.name AS product_name,
            sku.name AS sku_name, 
            sku.code AS sku_code, 
            sku.articul AS sku_articul, 
            sku.remnants, 
            sku.price,
            round((price * ((100 - {$sqlPricesDiscount['small_wholesale_price']})/100)), 2) as small_wholesale_price, 
            round((price * ((100 - {$sqlPricesDiscount['wholesale_price']})/100)), 2) as wholesale_price,
            c.id AS category_id
        FROM module_shop_sku sku 
        LEFT JOIN module_shop_products p ON p.id = sku.product_id 
        LEFT JOIN module_shop_categories c ON c.id = p.category_id
        WHERE 
            p.active = 1
        ;
        ";

        $list = Yii::$app->db->createCommand($sql)->queryAll();
        foreach($list as &$item) {
            $skuId = $item['id'];
            $productId = $item['product_id'];
            $categoryId = $item['category_id'];

            foreach($this->features as $id => $feature) {
                $item['feature_' . $id] = null;
            }
            
            $this->skuMap[$skuId] = &$item;
            $this->productsMap[$productId][$skuId] = &$item;
            $this->categoriesMap[$categoryId]['products'][$skuId] = &$item;
        }
    }

    private function fillSkuMultyOptionsValues(): void 
    {
        $sql = "SELECT 
            mssmo.id, 
            mssmo.sku_id, 
            mssmo.option_id, 
            msoi.name AS option_value
        FROM module_shop_sku_multi_options mssmo 
        LEFT JOIN module_shop_option_items msoi ON mssmo.option_item_id = msoi.id;
        ";

        $list = Yii::$app->db->createCommand($sql)->queryAll();
        foreach($list as $feature) {
            $skuId = $feature['sku_id'];
            $featureId = $feature['option_id'];
            $featureValue = $feature['option_value'];

            $this->skuMap[$skuId]['feature_' . $featureId][] = $featureValue;
        }    
    }

    private function fillProductMultyOptionsValues(): void 
    {
        $sql = "SELECT 
            mspmo.id, 
            mspmo.product_id, 
            mspmo.option_id, 
            msoi.name AS option_value
        FROM module_shop_products_multi_options mspmo 
        LEFT JOIN module_shop_option_items msoi ON mspmo.option_item_id = msoi.id;
        ";

        $list = Yii::$app->db->createCommand($sql)->queryAll();
        
        foreach($list as $feature) {
            $productId    = $feature['product_id'];
            $featureId    = $feature['option_id'];
            $featureValue = $feature['option_value'];
            
            foreach($this->productsMap[$productId] as &$sku) {
                $sku['feature_' . $featureId][] = $featureValue;
            }
        }    
    }

    private function fillProductOptionsValues(): void 
    {
        $sql = "SELECT 
            mspo.id, 
            mspo.product_id, 
            mspo.option_id, 
            msoi.name AS option_value
        FROM module_shop_products_options mspo 
        LEFT JOIN module_shop_option_items msoi ON mspo.option_item_id = msoi.id;
        ";

        $list = Yii::$app->db->createCommand($sql)->queryAll();
        
        foreach($list as $feature) {
            $productId    = $feature['product_id'];
            $featureId    = $feature['option_id'];
            $featureValue = $feature['option_value'];
            
            foreach($this->productsMap[$productId] as &$sku) {
                $sku['feature_' . $featureId][] = $featureValue;
            }
        }    
    }

    private function flatList(array $categories, array &$listing, int|null $deepLevel = null) 
    {
        if(is_null($deepLevel)) {
            $deepLevel = 0;
        } else {
            $deepLevel +=1;
        }
        foreach($categories as $category) {
            $listing[] = [
                'type'      => 'category',
                'deep_level'=> $deepLevel, 
                'id'        => $category['id'],
                'parent_id' => $category['parent_id'],
                'name'      => $category['name'],
                'count_products' => intval($category['count_products'] ?? 0),
            ];

            if(isset($category['products'])) {
                foreach($category['products'] as $product) {
                    $listing[] = array_merge(['type' => 'sku',], $product);
                }
            }

            if(isset($category['childs'])) {
                $this->flatList($category['childs'], $listing, $deepLevel);
            }
        }

        return $listing;
    }
}

?>