<?php

namespace cms\extensions\modules\settings\admin\controllers;

use cms\admin\controllers\AdminController;
use cms\extensions\modules\settings\admin\models\SeoPages;
use cms\extensions\modules\settings\admin\forms\SeoPagesSearch;
use cms\extensions\modules\settings\admin\models\RobotsFile;
use Yii;
use yii\helpers\Url;
use yii\web\Session;

class SeoController extends AdminController
{
    public function actionIndex()
    {
        $model = Yii::$container->get(SeoPages::class);
        $searchModel = new SeoPagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'model' => $model]);
    }

    public function actionView($id)
    {
        $model = SeoPages::findOne(['id' => $id]);
        return $this->render('view', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = SeoPages::findOne(['id' => $id]);
        $robotsAvailableValues = SeoPages::getRobots();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $session = Yii::$app->session;

            $array = Yii::$app->request->post()['SeoPages']['robots'] ?? null;
            if(!is_array($array)) {
                $array = [];
            }
            $robotsSelectedId = array_values(array_map(fn($id) => intval($id), $array));
            $robotsSelectedValues = [];
            foreach($robotsSelectedId as $id) {
                if(!isset($robotsAvailableValues[$id])) {
                    continue;
                }
                $robotsSelectedValues[] = $robotsAvailableValues[$id];
            }
            $model->robots = implode(',', $robotsSelectedValues);
            
            if ($model->save()) {
                $session->setFlash('success', 'Запись успешно изменена');
            } else {
                Yii::error($model->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }
    
            return $this->redirect(Url::to(['seo/index']));
        } 

        $robotsSelectedValues = array_reduce(explode(',', $model->robots), function($result, $item) use($robotsAvailableValues) {
            foreach($robotsAvailableValues as $id => $value) {
                if($item !== $value) {
                    continue;
                }
                $result[$id] = [
                    'selected' => true,
                ];
            }
            return $result;
        }, []);

        return $this->render('update', ['model' => $model, 'robotsSelectedValues' => $robotsSelectedValues]);
    }

    public function actionCreate()
    {
        $model = Yii::$container->get(SeoPages::class);
        $robotsAvailableValues = SeoPages::getRobots();

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $session = Yii::$app->session;

            $robotsSelectedId = array_values(array_map(fn($id) => intval($id), Yii::$app->request->post()['SeoPages']['robots']));
            $robotsSelectedValues = [];
            foreach($robotsSelectedId as $id) {
                if(!isset($robotsAvailableValues[$id])) {
                    continue;
                }
                $robotsSelectedValues[] = $robotsAvailableValues[$id];
            }
            $model->robots = implode(',', $robotsSelectedValues);
    
            if ($model->save()) {
                $session->setFlash('success', 'Запись успешно создана');
            } else {
                Yii::error($model->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }
    
            return $this->redirect(Url::to(['seo/index']));
        }

        $robotsSelectedValues = array_reduce(explode(',', $model->robots), function($result, $item) use($robotsAvailableValues) {
            foreach($robotsAvailableValues as $id => $value) {
                if($item !== $value) {
                    continue;
                }
                $result[$id] = [
                    'selected' => true,
                ];
            }
            return $result;
        }, []);

        return $this->render('create', ['model' => $model, 'robotsSelectedValues' => $robotsSelectedValues]);
    }

    public function actionDelete($id)
    {
        $session = Yii::$app->session;

        if (SeoPages::deleteAll(['id' => $id])) {
            $session->setFlash('success', 'Запись успешно удалена');
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        return $this->redirect(Url::to(['seo/index']));
    }

    public function actionSitemapGenerate()
    {
        Yii::$app->seo->generateSiteMap();

        $this->redirect(['index']);
    }

    public function actionUpdateRobots(Session $session)
    {
        $robotsFileModel = RobotsFile::buildForm();

        if ($robotsFileModel->load(Yii::$app->request->post()) && $robotsFileModel->save()) {
            $session->setFlash('success', 'Запись успешно изменена');
        }

        return $this->render('update-robots', ['robotsFileModel' => $robotsFileModel]);
    }
}