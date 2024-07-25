<?php

namespace CmsModule\Shop\frontend\services;

use cms\common\forms\SignupForm;
use cms\common\models\User;
use CmsModule\Shop\common\services\BasketService;
use CmsModule\Shop\frontend\forms\OrganizationOrderCreateForm;
use CmsModule\Shop\frontend\forms\UserOrderCreateForm;
use Exception;
use Yii;
use yii\web\Session;

class OrderService
{
    public function createOrderForUser(UserOrderCreateForm $userOrderCreateForm)
    {
        $session = Yii::$container->get(Session::class);
        $basketService = Yii::$container->get(BasketService::class);
        $signupForm = Yii::$container->get(SignupForm::class);

        if (Yii::$app->user->isGuest) {
            if (!User::findOne(['email' => $userOrderCreateForm->email])) {
                $signupForm->scenario = SignupForm::SCENARIO_INDIVIDUAL;

                $signupForm->load(Yii::$app->request->post(), 'UserOrderCreateForm');
                $signupForm->password = Yii::$app->security->generateRandomString(12);
                $user = $signupForm->signup();

                $sender = Yii::$app->sender->email;
                $sender->viewPath = Yii::getAlias('@common/mail/');
        
                try {
                    $sender->send([
                        'from' => [Yii::$app->params['adminEmail'] => Yii::$app->params['senderName']],
                        'to' => $user->email,
                        'subject' => 'Данные от аккаунта на сайте ceramic-store',
                        'view' => 'system-create-user',
                        'trans' => [
                            'email' => $signupForm->email,
                            'password' => $signupForm->password
                        ]
                    ]);
                } catch (Exception $e) {
                    Yii::error($e->getMessage());
                }


                if ($order_id = $userOrderCreateForm->save($user->id)) {
                    $basketService->clear();
                    $session->setFlash('order_id', $order_id);
                    $session->setFlash('success', 'Заказ успешно оформлен');
                    return $order_id;
                } else {
                    $session->setFlash('error', 'Произошла ошибка');
                    return false;
                }
            } else {
                // $session->setFlash('error', 'Этот email уже зарегистрированю');
                // return false;

                $user = User::findOne(['email' => $userOrderCreateForm->email]);
                if ($order_id = $userOrderCreateForm->save($user->id)) {
                    $basketService->clear();
                    $session->setFlash('order_id', $order_id);
                    $session->setFlash('success', 'Заказ успешно оформлен');
                    return $order_id;
                } else {
                    $session->setFlash('error', 'Произошла ошибка');
                    return false;
                }
            }
        } else {
            if ($order_id = $userOrderCreateForm->save()) {
                $basketService->clear();
                $session->setFlash('order_id', $order_id);
                $session->setFlash('success', 'Заказ успешно оформлен');
                return $order_id;
            } else {
                $session->setFlash('error', 'Произошла ошибка');
                return false;
            }
        }
    }

    // public function createOrderForOrg(OrganizationOrderCreateForm $organizationOrderCreateForm)
    // {
    //     $session = Yii::$container->get(Session::class);
    //     $basketService = Yii::$container->get(BasketService::class);
    //     $signupForm = Yii::$container->get(SignupForm::class);

    //     if (Yii::$app->user->isGuest) {
    //         if (!User::findOne(['email' => $organizationOrderCreateForm->email])) {
    //             $signupForm->scenario = SignupForm::SCENARIO_INDIVIDUAL;

    //             $signupForm->load(Yii::$app->request->post(), 'OrganizationOrderCreateForm');
    //             $signupForm->organization = $organizationOrderCreateForm->form_sob . ' ' . $organizationOrderCreateForm->organization;
    //             $signupForm->email = $organizationOrderCreateForm->email;
    //             $signupForm->password = Yii::$app->security->generateRandomString(12);
    //             $user = $signupForm->signup();
        
    //             $sender = Yii::$app->sender->email;
    //             $sender->viewPath = Yii::getAlias('@common/mail/');
        
    //             try {
    //                 $sender->send([
    //                     'from' => [Yii::$app->params['adminEmail'] => Yii::$app->params['senderName']],
    //                     'to' => $user->email,
    //                     'subject' => 'Данные от аккаунта на сайте ceramic-store',
    //                     'view' => 'system-create-user',
    //                     'trans' => [
    //                         'email' => $signupForm->email,
    //                         'password' => $signupForm->password
    //                     ]
    //                 ]);
    //             } catch (Exception $e) {
    //                 Yii::error($e->getMessage());
    //             }

    //             if ($order_id = $organizationOrderCreateForm->save($user->id)) {
    //                 $basketService->clear();
    //                 $session->setFlash('order_id', $order_id);
    //                 $session->setFlash('success', 'Заказ успешно оформлен');
    //                 return $order_id;
    //             } else {
    //                 $session->setFlash('error', 'Произошла ошибка');
    //                 return false;
    //             }
    //         } else {
    //             // $session->setFlash('error', 'Этот email уже зарегистрированю');
    //             // return false;

                
    //             $user = User::findOne(['email' => $organizationOrderCreateForm->email]);
    //             if ($order_id = $organizationOrderCreateForm->save($user->id)) {
    //                 $basketService->clear();
    //                 $session->setFlash('order_id', $order_id);
    //                 $session->setFlash('success', 'Заказ успешно оформлен');
    //                 return $order_id;
    //             } else {
    //                 $session->setFlash('error', 'Произошла ошибка');
    //                 return false;
    //             }
    //         }
    //     } else {
    //         if ($order_id = $organizationOrderCreateForm->save()) {
    //             $basketService->clear();
    //             $session->setFlash('order_id', $order_id);
    //             $session->setFlash('success', 'Заказ успешно оформлен');
    //             return $order_id;
    //         } else {
    //             $session->setFlash('error', 'Произошла ошибка');
    //             return false;
    //         }
    //     }
    // }
}