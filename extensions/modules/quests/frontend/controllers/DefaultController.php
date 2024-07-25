<?php

namespace CmsModule\Quests\frontend\controllers;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Reviews\frontend\forms\ReviewsForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Session;
use yii\web\UploadedFile;

class DefaultController extends Controller
{


    public function actionIndex()
    {
        return $this->render('index');
    }

}
