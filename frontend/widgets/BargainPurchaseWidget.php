<?php

namespace frontend\widgets;

use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\common\models\Products;
use yii\base\Widget;

class BargainPurchaseWidget extends Widget
{
    public $viewFolder = 'bargain-purchase/';
    public $view = 'index';
    public $categoryLimit = 7;
    public $productLimit = 15;

    public function run()
    {
        $categories = Categories::find()
                                ->alias('category')
                                ->innerJoin(['product' => Products::tableName()], 'product.category_id = category.id')
                                ->where(['category.active' => true, 'category.parent_id' => null])
                                ->limit($this->categoryLimit)
                                ->all();

        
        $data = [];

        foreach ($categories as $category) {
            $products = Products::getQueryProductsActive()->andWhere(['category_id' => $category->id])->select('product.id')->limit($this->productLimit)->column();

            if (!empty($products)) {
                $data[$category->name] = $products;
            }
        }

        $categoryNames = array_keys($data);

        if (!empty($data)) {
            return $this->render($this->viewFolder . $this->view, ['data' => $data, 'categoryNames' => $categoryNames]);
        }
    }
}