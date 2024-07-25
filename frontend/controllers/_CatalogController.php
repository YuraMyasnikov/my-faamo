<?php

namespace frontend\controllers;

use CmsModule\Shop\common\models\Categories;
use frontend\controllers\actions\catalog\IndexCatalogAction;
use frontend\controllers\actions\catalog\ViewCatalogAction;
use yii\web\Controller;


class CatalogController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexCatalogAction::class
            ],
            'view' => [
                'class' => ViewCatalogAction::class
            ],
        ];
    }

    public function actionIndex()
    {
        $categories = Categories::findAll(['active' => true, 'parent_id' => null]);

        return $this->render('index', ['categories' => $categories]);
    }

    
}