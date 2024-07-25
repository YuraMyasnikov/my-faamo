<?php 

namespace frontend\controllers\actions\admin\product;

use frontend\models\admin\RelatedProductsFilterForm;
use frontend\models\shop\RelatedProducts;
use Yii;
use yii\base\Action;


class RelatedProductsAction extends Action 
{
    public function run($id)
    {
        $product = \frontend\models\shop\Products::findOne(['id' => $id]);

        /** @var RelatedProductsFilterForm $searchModel */
        $searchModel = Yii::$container->get(RelatedProductsFilterForm::class, [], []);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->controller->render('related_products', [
            'product' => $product,
            'relatedProductsIds' => $this->getRelatedProductsIdsForProduct($id),
            'productsDataProvider' => $dataProvider,
            'searchModel' => $searchModel 
        ]);
    }  

    private function getRelatedProductsIdsForProduct(int $productId): array 
    {
        $allIds = array_reduce(RelatedProducts::find()->select(['related_product_id'])->where(['base_product_id' => $productId])->all(), function($result, RelatedProducts $rp) {
            $result[] = $rp->related_product_id;
            return $result;
        }, []);

        return $allIds;
    }

}