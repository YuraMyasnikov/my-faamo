<?php

namespace CmsModule\Infoblocks\base;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Infoblocks\admin\controllers\ContentController;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class AdminController
 * @package CmsModule\Infoblocks\base
 */
class AdminController extends ContentController
{
    public const IBLOCK_CODE = 'unknown';
    public $skipInstall = true;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionIndex($folder = null)
    {
        $class = Infoblock::byCode(static::IBLOCK_CODE);

        $dataProvider = new ActiveDataProvider([
            'query' => $class::find(),
            'pagination' => [
                'pageSizeLimit' => [20, 50, 100]
            ],
        ]);

        return $this->render('iblock', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate($code = null, $back = '')
    {
        $class = Infoblock::byCode(static::IBLOCK_CODE);
        $model = $class::create();
        return $this->modify($model, Yii::$app->request->getAbsoluteUrl());
    }

    public function actionUpdate($id, $code, $back = '')
    {
        $model = $this->findModel($id);
        return $this->modify($model, $back);
    }

    public function actionDelete($id, $code, $back = '')
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return mixed|Infoblock
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws HttpException
     */
    protected function findModel($id)
    {
        $class = Infoblock::byCode(static::IBLOCK_CODE);

        if (($model = $class::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function mainRedirect($model, $back = '')
    {
        $this->redirect(['index']);
    }
}
