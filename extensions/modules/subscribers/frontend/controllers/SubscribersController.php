<?php

namespace CmsModule\Subscribers\frontend\controllers;

use CmsModule\Subscribers\frontend\forms\SubscribersForm;
use Yii;
use yii\web\Controller;
use yii\web\Session;

class SubscribersController extends Controller
{
    public function actionCreate(Session $session)
    {
        $subscribeForm = Yii::$container->get(SubscribersForm::class);

        if ($subscribeForm->load(Yii::$app->request->post()) && $subscribeForm->validate()) {
            if ($subscribeForm->save()) {
                $session->setFlash('success', 'Вы успешно подписались');
            }
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        $this->redirect(Yii::$app->request->referrer);
    }
}
