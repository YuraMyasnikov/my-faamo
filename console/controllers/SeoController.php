<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class SeoController extends Controller
{
    public function actionIndex()
    {
        return Yii::$app->seo->generateSiteMap();
    }
}