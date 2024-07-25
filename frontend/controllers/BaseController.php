<?php

namespace frontend\controllers;

use frontend\services\CityCodeResolver;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;


class BaseController extends Controller
{
    public function init()
    {
        parent::init();

        /** @var CityCodeResolver $cityCodeResolver */
        $cityCodeResolver = Yii::$container->get(CityCodeResolver::class);
        $cityCode = $cityCodeResolver->getCode();
        if(!Yii::$app->session->has('city')) {
            Yii::$app->session->set('city', $cityCode);
        }
    }

    public function beforeAction($action)
    {
        if(!Yii::$app->session->has('city')) {
            return true;
        }

        /** @var CityCodeResolver $cityCodeResolver */
        $cityCodeResolver = Yii::$container->get(CityCodeResolver::class);
        if($cityCodeResolver->getCode() == $cityCodeResolver->getCodeForCurrentCity()) {
            return true;
        }

        $this->redirect(Url::current(['city' => $cityCodeResolver->getCodeForCurrentCity()]));

        return true;
    }
}