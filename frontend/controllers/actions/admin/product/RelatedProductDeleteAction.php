<?php 

namespace frontend\controllers\actions\admin\product;

use frontend\models\shop\Products;
use frontend\models\shop\RelatedProducts;
use Yii;
use yii\base\Action;


class RelatedProductDeleteAction extends Action 
{
    public function run($base_product_id, $related_product_id)
    {
        if(Yii::$app->request->isAjax) {
            $models = RelatedProducts::find()->where(['base_product_id' => $base_product_id, 'related_product_id' => $related_product_id])->all();
            foreach($models as $model) {
                $model->delete();
            }
        }
    }  
}