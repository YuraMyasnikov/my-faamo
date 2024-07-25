<?php

namespace CmsModule\News\frontend\controllers;

use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    const IBLOCK_TYPE = 'news';

    public function actionIndex()
    {
        $infoblock = Infoblock::byCode(self::IBLOCK_TYPE);

        $dataProvider = new ActiveDataProvider([
            'query' => $infoblock::find()->where(['active' => true])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 3,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($code)
    {
        $infoblock = Infoblock::byCode(self::IBLOCK_TYPE);
        $news = $infoblock::find()->where(['code' => $code, 'active' => true])->one();

        if (!$news) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'news' => $news
        ]);
    }
}
