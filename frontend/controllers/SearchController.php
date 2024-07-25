<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class SearchController extends Controller
{
    public function actionIndex($q)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->search->searchQuery($q),
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $dataProvider->prepare();

        return $this->render('index', ['dataProvider' => $dataProvider, 'q' => $q]);
    }
}