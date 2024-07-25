<?php

namespace CmsModule\Shop\frontend\controllers;

use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\common\models\OptionItems;
use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\frontend\forms\ProductsFilterForm;
use frontend\controllers\BaseController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class CatalogController extends BaseController
{
    public function actionIndex()
    {
        $categories = Categories::findAll(['active' => true, 'parent_id' => null]);

        return $this->render('index', ['categories' => $categories]);
    }

    public function actionView($filters)
    {
        $route = $this->parseFilters($filters);
        $category = Categories::findOne(['code' => $route['category'], 'active' => true]);
        if (!$category) {
            throw new NotFoundHttpException();
        }

        $products = Products::getQueryProductsActive()->all();
    
        $searchModel = Yii::$container->get(ProductsFilterForm::class, [], ['category' => $category]);
        $dataProvider = $searchModel->search($route['filters']);
        $dataProvider->prepare();

        return $this->render('view', ['category' => $category, 'products' => $products, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    protected function parseFilters($route)
    {
        $routeArray = explode('/', $route);

        $category = $routeArray[0];

        $resultFilters = [];
        $request = Yii::$app->request->get();
        if (!empty($routeArray[1])) {
            $filters = explode('-', $routeArray[1]);

            $optionItems = OptionItems::find()->where(['code' => $filters])->all();
            
            foreach ($optionItems as $optionItem) {
                $itemCode = $optionItem->code;
                if (!empty($request[$optionItem->option->code])) {
                    $itemCode = implode(',', [$itemCode, $request[$optionItem->option->code]]);
                }

                $resultFilters[$optionItem->option->code] = $itemCode;
            }
        }

        $resultArray = [
            'category' => $category,
            'filters' => ArrayHelper::merge($request, $resultFilters)
        ];

        return $resultArray;
    }
}