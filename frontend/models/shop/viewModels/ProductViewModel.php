<?php

namespace frontend\models\shop\viewModels;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Shop\frontend\viewModels\ProductViewModel as BaseViewModel;
use frontend\controllers\actions\product\ReviewsProductAction;
use frontend\models\shop\Categories;
use frontend\models\shop\Products;
use frontend\models\shop\Sku;
use yii\data\ActiveDataProvider;
use Yii;

class ProductViewModel extends BaseViewModel
{
    public $product_id;
    public $product;
    private array $availableSkus = [];
    private array $availableSkusOptions = [];
    private array $availableSkusOptionsValues = [];

    public function init()
    {
        parent::init();

        if($this->product_id) {
            $this->fillAllAvailableSkusOptions();
            $this->fillAllAvailableSkusOptionsValues();
            $this->fillAllAvailableSkus();
        }
    }

    public function getCategory(): Categories|null 
    {
        if(!$this->product) {
            return null;
        }
        return Categories::find()->where(['id' => $this->product->category_id])->one();
    }


    public function getPilling(): string|null 
    {
        $productId = (int) $this->product_id;

        $sql = "SELECT msoi.name 
                FROM module_shop_products_options mspo
                LEFT JOIN module_shop_options mso ON mso.id = mspo.option_id
                LEFT JOIN module_shop_option_items msoi on msoi.id = mspo.option_item_id
                WHERE mspo.product_id = :product_id
                LIMIT 1;";

        $val = Yii::$app->db->createCommand($sql, ['product_id' => $productId])->queryScalar();
        if(empty($val)) {
            return null;
        }
        return $val;
    }

    public function getReviewStatistics(): array 
    {
        $grades = array_reduce($this->getReviewDataProvider()->getModels(), function($result, Infoblock $review) {
            $result[] = floatval($review->grade);
            return $result;
        }, []);
        $total      = array_sum($grades);
        $average    = count($grades) > 0 ? $total / count($grades) : 0;
        $iterations = (int) $average;

        return [$total, $average, $iterations];
    }

    public function getSeoH1(): string 
    {
        $seo = Yii::$app->seo->getPage();
        return $seo && $seo->h1 ? $seo->h1 : $this->product?->name ?? '';
    }

    public function getAvailableSkus(): array 
    {
        return $this->availableSkus;
    }

    public function getAvailableSkusOptions(): array 
    {
        return $this->availableSkusOptions;
    }

    public function getAvailableSkusOptionsValues(): array 
    {
        return $this->availableSkusOptionsValues;
    }

    public function getFirstSku()
    {
        $skuId = 0;
        foreach($this->availableSkus as $sku) {
            if(boolval($sku['is_filled'] ?? false)) {
                $skuId = (int) $sku['id'];    
                break;
            }
        }
        return Sku::findOne(['id' => $skuId]);
    }

    public function getSkuList()
    {
        return $this->availableSkus;
    }

    public function getRelatedProductsDataProvider()
    {
        $sql = "SELECT * 
                FROM module_shop_products p 
                WHERE 
                    p.active = 1 AND 
                    p.id IN (SELECT rp.related_product_id FROM related_products rp WHERE rp.base_product_id = :product_id)";
        $query = Products::findBySql($sql, ['product_id' => intval($this->product_id)]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
                'page' => 0
            ],
        ]);
    }

    public function getRandomProductsDataProvider()
    {
        $query = Products::findBySql("SELECT * FROM module_shop_products p WHERE p.active = 1 ORDER BY RAND()");
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
                'page' => 0
            ],
        ]);
    }

    public function getReviewDataProvider()
    {
        /**
         * Список отзывов
         */
        $infoblock = Infoblock::byCode('product_reviews');
        $productReviews = $infoblock::find()->where(['active' => true, 'product_id' => $this->product_id])->orderBy(['created_at' => SORT_DESC]);

        $page = \Yii::$app->request->get('page', 1);
        if($page >= 1) {
            $page -= 1;
        }

        return new ActiveDataProvider([
            'query' => $productReviews,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
        ]);
    }

    public function getProductReviewsForm()
    {
        $productId = $this->product_id;

        $productReviewsForm = new \frontend\models\shop\ProductReviewsForm();
        $productReviewsForm->product_id = $productId;
        if(!Yii::$app->user->isGuest) {
            $productReviewsForm->fio = Yii::$app->user->identity->profile->fio;
            $productReviewsForm->email = Yii::$app->user->identity->email;
        }

        return $productReviewsForm;
    }

    protected function fillAllAvailableSkusOptions() 
    {
        $productId = (int) $this->product_id;

        $sql = "SELECT mso.id, mso.name, mso.code 
                FROM module_shop_options mso 
                WHERE 
                    mso.id IN (
                        SELECT o1.option_id 
                        FROM module_shop_sku_options o1 
                        WHERE o1.sku_id IN(SELECT mss.id FROM module_shop_sku mss WHERE mss.product_id = {$productId} AND mss.active = 1)
                        UNION 
                        SELECT o2.option_id 
                        FROM module_shop_sku_multi_options o2 
                        WHERE o2.sku_id IN(SELECT mss.id FROM module_shop_sku mss WHERE mss.product_id = {$productId} AND mss.active = 1)
                    ) AND 
                    mso.active = 1
                ORDER BY mso.sort;";
                
        $this->availableSkusOptions = array_reduce(Yii::$app->db->createCommand($sql, [])->queryAll(), function($result, $item) {
            $result[$item['code']] = $item;
            return $result;
        }, []);
    }

    protected function fillAllAvailableSkusOptionsValues()
    {
        $productId = (int) $this->product_id;

        $sql = "SELECT msoi.id, msoi.option_id, mso.code as option_code, msoi.name, msoi.code 
                FROM module_shop_option_items msoi
                LEFT JOIN module_shop_options mso on mso.id = msoi.option_id 
                WHERE 
                    msoi.id IN (
                        SELECT o1.option_item_id 
                        FROM module_shop_sku_options o1 
                        WHERE o1.sku_id IN(SELECT mss.id FROM module_shop_sku mss WHERE mss.product_id = {$productId} AND mss.active = 1)
                        UNION 
                        SELECT o2.option_item_id 
                        FROM module_shop_sku_multi_options o2 
                        WHERE o2.sku_id IN(SELECT mss.id FROM module_shop_sku mss WHERE mss.product_id = {$productId} AND mss.active = 1)
                    ) AND 
                    msoi.active = 1
                ORDER BY msoi.sort;";

        foreach(Yii::$app->db->createCommand($sql, [])->queryAll() as $row) {
            $this->availableSkusOptionsValues[] = $row;
            if(!isset($this->availableSkusOptions[$row['option_code']])) {
                continue;
            }
            if(!isset($this->availableSkusOptions[$row['option_code']]['options'])) {
                $this->availableSkusOptions[$row['option_code']]['options'] = [];
            }
            $this->availableSkusOptions[$row['option_code']]['options'][$row['code']] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
            ];
        }
    }

    protected function fillAllAvailableSkus() 
    {
        $productId = (int) $this->product_id;

        /** 
         * Получение всех активных skus 
         */
        $sql = "SELECT mss.id, mss.name, mss.code, mss.remnants, mss.price 
                FROM module_shop_sku mss 
                WHERE 
                    mss.product_id = {$productId} AND 
                    mss.active = 1 
                ORDER BY mss.sort;";

        $skus = array_reduce(Yii::$app->db->createCommand($sql, [])->queryAll(), function ($result, $item) {
            $result[$item['id']] = $item;
            return $result;
        }, []);

        foreach($skus as &$sku) {
            foreach($this->availableSkusOptions as $option) {
                $sku['options'][$option['code']] = [];            
            }
        }

        /**
         * Получаем все значения свойст и заполняем ими sku
         */
        $sql = "SELECT 
                    t.id, 
                    t.sku_id, 
                    t.option_id, mso.name as option_name, mso.code as option_code, 
                    t.option_item_id, msoi.name as option_item_name, msoi.code as option_item_code 
                FROM (
                    SELECT * 
                    FROM module_shop_sku_options o1 
                    WHERE o1.sku_id IN(SELECT mss.id FROM module_shop_sku mss WHERE mss.product_id = {$productId} AND mss.active = 1)
                    UNION 
                    SELECT * 
                    FROM module_shop_sku_multi_options o2 
                    WHERE o2.sku_id IN(SELECT mss.id FROM module_shop_sku mss WHERE mss.product_id = {$productId} AND mss.active = 1)
                ) as t
                LEFT JOIN module_shop_options mso ON mso.id = t.option_id 
                LEFT JOIN module_shop_option_items msoi ON msoi.id = t.option_item_id   
                ;";
        $options = Yii::$app->db->createCommand($sql, [])->queryAll();
        foreach($options as $option) {
            $skus[$option['sku_id']]['options'][$option['option_code']][] = $option['option_item_code'];
        }

        foreach($skus as &$sku) {
            if(is_array($sku['options'] ?? null)) {
                foreach($sku['options'] as $code => &$values) {
                    $values = array_values(array_unique($values));
                }
            }

            $isFilled = true;
            foreach(['size', 'color'] as $optionCode) {
                if(!count($sku['options'][$optionCode] ?? [])) {
                    $isFilled = false;
                    break;
                }
            }
            $sku['is_filled'] = $isFilled;
        }

        $this->availableSkus = $skus;
    }



}