<?php

namespace CmsModule\Shop\frontend\controllers;

use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrderViewModel;
use CmsModule\Shop\frontend\forms\UserOrderCreateForm;
use CmsModule\Shop\frontend\services\OrderService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class OrdersController extends Controller
{
    public function actionCreate()
    {
        if (Yii::$app->basket->count() < 1) {
            throw new NotFoundHttpException();
        }
        
        $orderDataForm  = UserOrderCreateForm::buildForm();
        $basketProducts = Yii::$app->basket->get();

        return $this->render('create', ['orderDataForm' => $orderDataForm, 'basketProducts' => $basketProducts]);
    }

    public function actionStore()
    {
        $request = Yii::$app->request->post();
        $userOrderCreateForm = Yii::$container->get(UserOrderCreateForm::class);
        $orderService = Yii::$container->get(OrderService::class);

        if (Yii::$app->request->isAjax && $userOrderCreateForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($userOrderCreateForm);
        }

        if ($userOrderCreateForm->load($request) && $userOrderCreateForm->validate()) {
            if ($orderId = $orderService->createOrderForUser($userOrderCreateForm)) {

                $order = Orders::findOne(['id' => $orderId]);

                // if ($order) {
                //     if ($order->payment->code === Payments::PAYMENT_ON_ACCOUNT) {
                //         Yii::$app->session->setFlash('payment_on_account_order_id', $orderId);
                //     }
                // }
                Yii::$app->session->setFlash('payment_on_account_order_id', $orderId);
                
                return $this->redirect(['/orders/complete']);
            }
        } else {
            Yii::$app->session->setFlash('create-error', json_encode($userOrderCreateForm->errors));
        }
        
        return $this->redirect(['/orders/create']);
    }

    public function actionComplete()
    {
        $orderId = Yii::$app->session->getFlash('payment_on_account_order_id', null);

        $order = null;
        $orderData = null;
        $orderViewModel = null;

        if ($orderId) {
            $order = Orders::findOne(['id' => $orderId]);
            $orderData = $order->orderData;
    
            $orderViewModel = Yii::$container->get(OrderViewModel::class);
            $orderViewModel->orderId = $orderId;
            $orderViewModel->init();
    
        }

        return $this->render('complete', [
            'order' => $order,
            'orderData' => $orderData,
            'orderViewModel' => $orderViewModel
        ]);
    }
}