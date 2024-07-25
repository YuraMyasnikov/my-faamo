<?php 

namespace frontend\controllers\actions\admin\product;

use frontend\models\shop\RelatedCategories;
use frontend\models\shop\RelatedProducts;
use Yii;
use yii\base\Action;


class RelatedCategoryAddAction extends Action 
{
    public function run($product_id, $category_id)
    {
        if(Yii::$app->request->isAjax) {
            $this->createIfNotExist($product_id, $category_id);
        }
    }  

    private function createIfNotExist($product_id, $category_id): bool 
    {
        $rp = RelatedCategories::findOne(['product_id' => $product_id, 'category_id' => $category_id]);
        if(!$rp) {
            $rp = new RelatedCategories(['product_id' => $product_id, 'category_id' => $category_id]);
            return $rp->save();
        }
        return true;
    }
}