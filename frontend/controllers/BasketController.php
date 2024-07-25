<?php

namespace CmsModule\Shop\frontend\controllers;

use CmsModule\Shop\common\services\BasketService;
use Yii;
use yii\web\Controller;

class BasketController extends Controller
{
    public function actionIndex()
    {
        $basketProducts = Yii::$app->basket->get();
        if (!$basketProducts) {
            if (Yii::$app->request->isAjax) {
                return $this->redirect('/basket/index');
            }

            return $this->render('empty');
        }

        return $this->render('index', ['basketProducts' => $basketProducts]);
    }

    public function actionDeleteSku($sku_id)
    {
        $basketService = Yii::$container->get(BasketService::class);

        if (Yii::$app->request->isAjax) {
            if ($basketService->deleteSku($sku_id)) {
                return $this->actionIndex();
            }
        }
    }

    public function actionDeleteProduct($product_id)
    {
        $basketService = Yii::$container->get(BasketService::class);

        if (Yii::$app->request->isAjax) {
            if ($basketService->deleteProduct($product_id)) {
                return $this->actionIndex();
            }
        }
    }

    public function actionUpdateSku($sku_id)
    {
        $basketService = Yii::$container->get(BasketService::class);

        $count = Yii::$app->request->post()['count'];

        if (Yii::$app->request->isAjax) {
            if ($basketService->update($sku_id, $count)) {
                return $this->actionIndex();
            }
        }
    }

    public function actionClear()
    {
        $basketService = Yii::$container->get(BasketService::class);
        $basketService->clear();

        return $this->redirect('/basket/index');
    }
}