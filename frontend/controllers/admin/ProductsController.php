<?php

namespace CmsModule\Shop\admin\controllers;

use cms\admin\controllers\AdminController;
use cms\common\models\Images;
use CmsModule\Shop\admin\forms\CategoriesSearch;
use CmsModule\Shop\admin\forms\ProductsSearch;
use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\common\models\Options;
use CmsModule\Shop\common\models\OptionsToCategories;
use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\common\models\ProductsImages;
use CmsModule\Shop\common\models\ProductsMultiOptions;
use CmsModule\Shop\common\models\ProductsOptions;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use CmsModule\Shop\admin\forms\UploadAdminImageForm;
use CmsModule\Shop\common\models\ProductsLinks;
use frontend\controllers\actions\admin\product\RelatedCategoriesAction;
use frontend\controllers\actions\admin\product\RelatedCategoryAddAction;
use frontend\controllers\actions\admin\product\RelatedCategoryDeleteAction;
use frontend\controllers\actions\admin\product\RelatedProductAddAction;
use frontend\controllers\actions\admin\product\RelatedProductDeleteAction;
use frontend\controllers\actions\admin\product\RelatedProductsAction;
use frontend\models\shop\RelatedCategories;
use frontend\models\shop\RelatedProducts;
use yii\helpers\Json;


class ProductsController extends AdminController
{
    public function actions()
    {
        return [
            'related-products' => [
                'class' => RelatedProductsAction::class
            ],
            'related-product-add' => [ 
                'class' => RelatedProductAddAction::class
            ],
            'related-product-delete' => [
                'class' => RelatedProductDeleteAction::class
            ],
            'related-categories' => [
                'class' => RelatedCategoriesAction::class
            ],
            'related-category-add' => [ 
                'class' => RelatedCategoryAddAction::class
            ],
            'related-category-delete' => [
                'class' => RelatedCategoryDeleteAction::class
            ],
        ];
    }

    public function actionIndex()
    {
        $categories = Yii::$container->get(Categories::class);
        $searchModel = Yii::$container->get(CategoriesSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'categories' => $categories]);
    }

    public function actionCreate($category_id)
    {
        $product = Yii::$container->get(Products::class);
        $productLinks = Yii::$container->get(ProductsLinks::class);
        $categoriesList = Categories::getTreeList();
        $product->category_id = $category_id;
        $options = Options::find()
            ->alias('option')
            ->innerJoin(OptionsToCategories::tableName() . ' otc', 'otc.option_id=option.id')
            ->where(['option.type' => Options::PRODUCT_TYPE, 'otc.category_id' => $product->category_id])
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

            if ($product->load(Yii::$app->request->post()) && $product->save()) {
                $productLinks->load(Yii::$app->request->post());
                $productLinks->product_id = $product->id;
                $productLinks->save();

                $session->setFlash('success', 'Запись успешно изменена');
            } else {
                Yii::error($product->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }

            $redirectUrl = Yii::$app->request->post('apply-button') !== null ? ['products/update', 'id' => $product->id] : ['products/view', 'category_id' => $product->category_id];
    
            return $this->redirect($redirectUrl);
        }

        return $this->render('create', [
            'product' => $product,
            'productLinks' => $productLinks,
            'category_id' => $category_id,
            'optionsList' => $optionsList,
            'multiOptionsList' => $multiOptionsList,
            'categoriesList' => $categoriesList
        ]);
    }

    public function actionView($category_id)
    {
        $searchModel = Yii::$container->get(ProductsSearch::class);
        $searchModel->category_id = $category_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'category_id' => $category_id]);
    }

    public function actionUpdate($id)
    {
        $product = \frontend\models\shop\Products::findOne(['id' => $id]);
        if(!$product->links) {
            $productLinks = new ProductsLinks(['product_id' => $product->id]);
            $productLinks->save();
        } else {
            $productLinks = $product->links; 
        }
        
        $categoriesList = Categories::getTreeList();
        $options = Options::find()
            ->alias('option')
            ->innerJoin(OptionsToCategories::tableName() . ' otc', 'otc.option_id=option.id')
            ->where(['option.type' => Options::PRODUCT_TYPE, 'otc.category_id' => $product->category_id])
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

        $product->options = ProductsOptions::find()->select('option_item_id')->where(['product_id' => $id])->indexBy('option_id')->column();
        $productmultiOptions = ProductsMultiOptions::find()->select(['option_item_id', 'option_id'])->where(['product_id' => $id])->all();
        
        foreach ($productmultiOptions as $multi_option) {
            $product->multi_options[$multi_option->option_id][] = $multi_option->option_item_id;
        }

        if (Yii::$app->request->post()) {
            $product->load(Yii::$app->request->post());
            $productLinks->load(Yii::$app->request->post());

            $session = Yii::$app->session;
            if ($product->save() && $productLinks->save()) {
                $session->setFlash('success', 'Запись успешно изменена');
            } else {
                Yii::error($product->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }

            $redirectUrl = Yii::$app->request->post('apply-button') !== null ? ['products/update', 'id' => $id] : ['products/view', 'category_id' => $product->category_id];
    
            return $this->redirect($redirectUrl);
        }

        return $this->render('update', [
            'product' => $product,
            'productLinks' => $productLinks,
            'categoriesList' => $categoriesList,
            'optionsList' => $optionsList,
            'multiOptionsList' => $multiOptionsList,
            'relatedProductsCount' => RelatedProducts::find()->where(['base_product_id' => $product->id])->count(),
            'relatedCategoriesCount' => RelatedCategories::find()->where(['product_id' => $product->id])->count(),
        ]);
    }

    public function actionDelete($id)
    {
        $session = Yii::$app->session;

        if (Products::deleteAll(['id' => $id])) {
            $session->setFlash('success', 'Запись успешно удалена');
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        return $this->redirect(['products/index']);
    }

    public function actionSetMainImage($product_id, $image_id)
    {
        if (Products::updateAll(['image_id' => $image_id], ['id' => $product_id])) {
            return $this->asJson(['status' => 1]);
        }

        return Json::encode(['status' => 0]);
    }

    public function actionUploadImage($product_id)
    {
        $form = Yii::$container->get(UploadAdminImageForm::class);
        $productImages = Yii::$container->get(ProductsImages::class);
        $image = UploadedFile::getInstance($form, 'image');
        if ($image) {
            $image_id = Images::uploadFileToDir(sprintf('product/%s/', $product_id), $image);
            $productImages->product_id = $product_id;
            $productImages->image_id = $image_id;
            $productImages->sort = $image_id;
            $productImages->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionImageChangeSort($product_id, $image_id, $sort)
    {
        if (Yii::$app->request->isAjax) {
            ProductsImages::updateAll(['sort' => $sort], ['product_id' => $product_id, 'image_id' => $image_id]);
        }
    }
}