<?php

namespace CmsModule\Shop\frontend\controllers;

use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use frontend\controllers\BaseController;
use Yii;
use yii\web\NotFoundHttpException;


class ProductsController extends BaseController
{
    public function actionView($code)
    {
        $product = Products::getQueryProductsActive()->andWhere(['code' => $code])->one();

        if (!$product) {
            throw new NotFoundHttpException();
        }

        $productViewModel = Yii::$container->get(ProductViewModel::class);
        $productViewModel->product_id = $product->id;
        $productViewModel->init();

        return $this->render('view', ['product' => $product, 'productViewModel' => $productViewModel]);
    }
}