<?php

namespace frontend\controllers;

use cms\common\models\Profile;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrdersStates;
use CmsModule\Shop\common\models\OrdersStatus;
use CmsModule\Shop\common\models\OrderStatusGroups;
use frontend\forms\ChangePasswordForm;
use frontend\forms\ProfileForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $profileForm = ProfileForm::buildForm(Yii::$app->user->id);
        $session = Yii::$container->get(Session::class);

        if (!$profileForm) {
            throw new NotFoundHttpException();
        }

        if (Yii::$app->request->post()) {
            if ($profileForm->load(Yii::$app->request->post()) && $profileForm->save()) {
                $session->setFlash('success', 'Профиль успешно изменен');
            } else {
                $session->setFlash('error', 'Произошла ошибка');
            }
        }

        return $this->render('index', ['profileForm' => $profileForm]);
    }

    // public function actionChangePass()
    // {
    //     $changePasswordForm = Yii::$container->get(ChangePasswordForm::class);
    //     $session = Yii::$container->get(Session::class);

    //     if (Yii::$app->request->post()) {
    //         if ($changePasswordForm->load(Yii::$app->request->post()) && $changePasswordForm->save()) {
    //             $session->setFlash('success', 'Пароль успешно изменен');
    //         } else {
    //             $changePasswordForm = $changePasswordForm::instance(true);
    //             $session->setFlash('error', 'Произошла ошибка');
    //         }
    //     }

    //     return $this->render('change-pass', ['changePasswordForm' => $changePasswordForm]);
    // }

    public function actionOrders($status_group = null)
    {
        $status_groups = OrderStatusGroups::find()->all();
        $query = Orders::find()->alias('order')->where(['order.user_id' => Yii::$app->user->id])->orderBy(['order.created_at' => SORT_DESC]);

        if ($status_group !== null) {
            $query->innerJoin(OrdersStatus::tableName() . ' status', 'status.id = order.status_id');
            $query->andWhere(['status.group_id' => $status_group]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('orders', ['dataProvider' => $dataProvider, 'status_groups' => $status_groups, 'status_group' => $status_group]);
    }

    public function actionRefusalOrder($order)
    {
        $session = Yii::$container->get(Session::class);
        $order = Orders::find()
            ->where([
                'user_id' => Yii::$app->user->id,
                'id' => $order,
            ])
            ->one();

        if ($order && !in_array($order->status->group->id, OrderStatusGroups::REFUSED_PROHIBITED) && !in_array($order->state_id, OrdersStates::REFUSED_PROHIBITED)) {
            $order->status_id = OrdersStatus::STATUS_REFUSAL;
            $order->state_id = OrdersStates::STATUS_CANCELLED;

            if ($order->save()) {
                $session->setFlash('success', 'Заказ успешно отменен');
            } else {
                $session->setFlash('error', 'Произошла ошибка');
            }
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        return $this->redirect(['/account/orders']);
    }
}