<?php 

namespace frontend\controllers\actions\admin\product;

use frontend\models\shop\Products;
use frontend\models\shop\RelatedProducts;
use Yii;
use yii\base\Action;


class RelatedProductAddAction extends Action 
{
    public function run($base_product_id, $related_product_id)
    {
        if(Yii::$app->request->isAjax) {
            $this->createIfNotExist($base_product_id, $related_product_id);
        }
    }  

    private function createIfNotExist($base_product_id, $related_product_id): bool 
    {
        $rp = RelatedProducts::findOne(['base_product_id' => $base_product_id, 'related_product_id' => $related_product_id]);
        if(!$rp) {
            $rp = new RelatedProducts(['base_product_id' => $base_product_id, 'related_product_id' => $related_product_id]);
            return $rp->save();
        }
        return true;
    }
}