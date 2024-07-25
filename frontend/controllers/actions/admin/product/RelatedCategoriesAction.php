<?php 

namespace frontend\controllers\actions\admin\product;

use frontend\models\admin\RelatedCategoriesFilterForm;
use frontend\models\shop\RelatedCategories;
use Yii;
use yii\base\Action;


class RelatedCategoriesAction extends Action 
{

    public function run($id)
    {
        /** @var RelatedCategoriesFilterForm $searchModel */
        $searchModel  = Yii::$container->get(RelatedCategoriesFilterForm::class, [], []);
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $product      = \frontend\models\shop\Products::findOne(['id' => $id]);

        return $this->controller->render('related_categories', [
            'relatedCategories' => $this->getRelatedCategoriesIdsForProduct($product->id),
            'product'      => $product,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel 
        ]);
    }
    
    private function getRelatedCategoriesIdsForProduct(int $productId): array 
    {
        $allIds = array_reduce(RelatedCategories::find()->select(['category_id'])->where(['product_id' => $productId])->all(), function($result, RelatedCategories $rp) {
            $result[] = $rp->category_id;
            return $result;
        }, []);

        return $allIds;
    }
}