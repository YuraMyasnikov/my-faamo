<?php

namespace frontend\controllers\shop\controllers;

use CmsModule\Shop\common\models\Favorite;
use CmsModule\Shop\frontend\controllers\FavoriteController as BaseFavoriteController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class FavoriteController extends BaseFavoriteController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Favorite::getActiveItemQuery(),
            'pagination' => [
                'defaultPageSize' => 3,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ]
        ]);

        $dataProvider->prepare();

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

}