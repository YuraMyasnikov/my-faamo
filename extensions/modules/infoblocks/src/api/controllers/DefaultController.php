<?php


namespace CmsModule\Infoblocks\api\controllers;

use CmsModule\Infoblocks\models\InfoblockTypes;
use yii\web\Controller;

class DefaultController extends Controller {
    public function actionIndex(): array
    {
        $models = InfoblockTypes::find()->all();
        $result = [];
        foreach($models as $model) {
            $iblock = [
                'id'=>(int)$model->id,
                'name'=>$model->name,
                'properties'=>[['name'=> 'Первичный ключ']]
            ];

            foreach($model->properties as $property) {
                $iblock['properties'][]=[
                    'id'=>(int)$property->id,
                    'name'=>$property->name
                ];
            }
            $result[]=$iblock;
        }
        return $result;

    }
}