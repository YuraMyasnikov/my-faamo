<?php

namespace CmsModule\Shop\admin\controllers;

use cms\admin\controllers\AdminController;
use cms\common\models\Images;
use CmsModule\Shop\admin\events\NewImageEvent;
use CmsModule\Shop\admin\forms\CategoriesSearch;
use CmsModule\Shop\admin\forms\UploadAdminImageForm;
use frontend\models\shop\Categories;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

class CategoriesController extends AdminController
{
    const NEW_IMAGE_EVENT = 'new_image_event';
    const NEW_ICON_EVENT = 'new_icon_event';

    public function init()
    {
        parent::init();

        $this->on(self::NEW_IMAGE_EVENT, [$this, 'newImageEventHandler']);
        $this->on(self::NEW_ICON_EVENT,  [$this, 'newIconEventHandler']);
    }

    public function actionIndex()
    {
        $categories = Yii::$container->get(Categories::class);
        $searchModel = Yii::$container->get(CategoriesSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'categories' => $categories]);
    }

    public function actionCreate()
    {
        $uploadAdminImageForm = Yii::$container->get(UploadAdminImageForm::class);
        $category = Yii::$container->get(Categories::class);
        $categoriesList = ArrayHelper::merge([null => 'Корневая категория'], Categories::getTreeList());
        $redirectUrlAfterCreate = Url::to(['categories/index']);

        if (Yii::$app->request->post()) {
            $session = Yii::$app->session;
            if ($category->load(Yii::$app->request->post()) && $category->save()) {
                if($category->parent_id) {
                    $redirectUrlAfterCreate = Url::to(['categories/index', 'parent_id' => $category->parent_id]);
                }
                // $this->trigger(self::NEW_IMAGE_EVENT, new NewImageEvent([
                //     'modelId' => $category->id,
                //     'file' => UploadedFile::getInstance($uploadAdminImageForm, 'image')
                // ]));
                // $this->trigger(self::NEW_ICON_EVENT, new NewImageEvent([
                //     'modelId' => $category->id,
                //     'file' => UploadedFile::getInstance($uploadAdminImageForm, 'icon')
                // ]));
                $session->setFlash('success', 'Запись успешно создана');
            } else {
                Yii::error($category->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }
    
            return $this->redirect($redirectUrlAfterCreate);
        }

        return $this->render('create', ['category' => $category, 'categoriesList' => $categoriesList, 'uploadAdminImageForm' => $uploadAdminImageForm]);
    }

    public function actionUpdate($id)
    {
        $uploadAdminImageForm = Yii::$container->get(UploadAdminImageForm::class);
        $category = Categories::findOne(['id' => $id]);
        $categoriesList = ArrayHelper::merge([null => 'Корневая категория'], Categories::getTreeList());

        if (Yii::$app->request->post()) {
            $session = Yii::$app->session;
            // dd($category->load(Yii::$app->request->post()), $category->save(), );
            if ($category->load(Yii::$app->request->post()) && $category->save()) {
                // $this->trigger(self::NEW_IMAGE_EVENT, new NewImageEvent([
                //     'modelId' => $category->id,
                //     'file' => UploadedFile::getInstance($uploadAdminImageForm, 'image')
                // ]));
                // $this->trigger(self::NEW_ICON_EVENT, new NewImageEvent([
                //     'modelId' => $category->id,
                //     'file' => UploadedFile::getInstance($uploadAdminImageForm, 'icon')
                // ]));
                $session->setFlash('success', 'Запись успешно изменена');
            } else {
                Yii::error($category->getErrors());
                $session->setFlash('error', 'Произошла ошибка');
            }
    
            return $this->redirect(['categories/index']);
        }

        return $this->render('update', ['category' => $category, 'categoriesList' => $categoriesList, 'uploadAdminImageForm' => $uploadAdminImageForm]);
    }

    public function actionDelete($id)
    {
        $session = Yii::$app->session;

        if (Categories::deleteAll(['id' => $id])) {
            $session->setFlash('success', 'Запись успешно удалена');
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        return $this->redirect(['categories/index']);
    }

    public function newImageEventHandler($event)
    {
        if(!$event->file) {
            return;
        }
        $category = Categories::findOne($event->modelId);
        if(!$category) {
            return;
        }
        $oldImageId = $category->image_id;
        $newImageId = Images::uploadFileToDir(sprintf('category/%s/', $category->id), $event->file);

        if($newImageId) {
            $category->image_id = $newImageId;
            $category->save();
        }

        if($oldImageId) {
            Images::findOne($oldImageId)->delete();
        }
    }

    public function newIconEventHandler($event) 
    {
        if(!$event->file) {
            return;
        }
        $category = Categories::findOne($event->modelId);
        if(!$category) {
            return;
        }
        $oldIconId = $category->icon_id;
        $newIconId = Images::uploadFileToDir(sprintf('category/%s/', $category->id), $event->file);

        if($newIconId) {
            $category->icon_id = $newIconId;
            $category->save();
        }
        
        if($oldIconId) {
            Images::findOne($oldIconId)->delete();
        }
    }
}