<?php 

namespace frontend\controllers\actions\admin\product;

use frontend\models\shop\RelatedCategories;
use Yii;
use yii\base\Action;


class RelatedCategoryDeleteAction extends Action 
{
    public function run($product_id, $category_id)
    {
        if(Yii::$app->request->isAjax) {
            $models = RelatedCategories::find()->where(['product_id' => $product_id, 'category_id' => $category_id])->all();
            foreach($models as $model) {
                $model->delete();
            }
        }
    }  
}