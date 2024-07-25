<?php

namespace frontend\controllers\shop\admin;

use CmsModule\Shop\admin\controllers\SkuController as ShopsSkuController;
use CmsModule\Shop\common\models\Options;
use frontend\models\shop\Sku;
use CmsModule\Shop\common\models\SkuMultiOptions;
use CmsModule\Shop\common\models\SkuOptions;
use frontend\models\shop\Products;
use Yii;
use yii\helpers\ArrayHelper;

class SkuController extends ShopsSkuController
{
    public function actionCreate($product_id)
    {
        $product = Products::findOne(['id' => $product_id]);
        $sku = Yii::$container->get(Sku::class);
        $sku->product_id = $product_id;
        $options = Options::find()
            ->alias('option')
            ->where(['option.type' => Options::SKU_TYPE])
            ->all();

        $optionsList = [];
        $multiOptionsList = [];

        foreach ($options as $option) {
            $list = ['id' => $option->id, 'label' => $option->name];
            foreach ($option->optionItems as $items) {
                $list['value'][$items->id] = $items->name;
            }

            $list['value'] = ArrayHelper::merge([null => 'Не выбрано'], $list['value'] ?? []);

            if ($option->multi) {
                $multiOptionsList[] = $list;
            } else {
                $optionsList[] = $list;
            }
        }

        if (Yii::$app->request->post()) {
            $session = Yii::$app->session;
            $sku->load(Yii::$app->request->post());
            $sku->price = $product->price;
            if ($sku->save()) {
                $session->setFlash('success', 'Запись успешно изменена');
            } else {
                Yii::error($sku->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }
            return $this->redirect(['sku/index', 'product_id' => $product_id]);
        }

        return $this->render('create', [
            'sku' => $sku,
            'optionsList' => $optionsList,
            'multiOptionsList' => $multiOptionsList,
            'product_id' => $product_id
        ]);
    }

    public function actionUpdate($id)
    {
        $sku = Sku::findOne(['id' => $id]);
        $options = Options::find()
            ->alias('option')
            ->where(['option.type' => Options::SKU_TYPE])
            ->all();

        $optionsList = [];
        $multiOptionsList = [];

        foreach ($options as $option) {
            $list = ['id' => $option->id, 'label' => $option->name];
            foreach ($option->optionItems as $items) {
                $list['value'][$items->id] = $items->name;
            }

            $list['value'] = ArrayHelper::merge([null => 'Не выбрано'], $list['value'] ?? []);

            if ($option->multi) {
                $multiOptionsList[] = $list;
            } else {
                $optionsList[] = $list;
            }
        }

        $sku->options = SkuOptions::find()->select('option_item_id')->where(['sku_id' => $id])->indexBy('option_id')->column();
        $productmultiOptions = SkuMultiOptions::find()->select(['option_item_id', 'option_id'])->where(['sku_id' => $id])->all();
        
        foreach ($productmultiOptions as $multi_option) {
            $sku->multi_options[$multi_option->option_id][] = $multi_option->option_item_id;
        }

        if (Yii::$app->request->post()) {
            $session = Yii::$app->session;
    
            if ($sku->load(Yii::$app->request->post()) && $sku->save()) {
                $session->setFlash('success', 'Запись успешно изменена');
            } else {
                Yii::error($sku->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }
    
            return $this->redirect(['sku/index', 'product_id' => $sku->product_id]);
        }

        return $this->render('update', [
            'sku' => $sku,
            'optionsList' => $optionsList,
            'multiOptionsList' => $multiOptionsList
        ]);
    }
}