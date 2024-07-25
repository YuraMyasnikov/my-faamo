<?php

namespace frontend\controllers;

use cms\common\forms\PasswordResetRequestForm;
use cms\common\forms\ResetPasswordForm;
use Exception;
use frontend\controllers\actions\site\ApplyPromocode;
use frontend\controllers\actions\site\IndexAction;
use frontend\controllers\actions\site\OneclickOrder;
use frontend\controllers\actions\site\PricelistAction;
use frontend\controllers\actions\site\RegistrationAction;
use frontend\forms\BookcallForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\forms\CallbackForm;
use frontend\forms\LoginForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;
use yii\web\Session;

/**
 * Site controller
 */
class SiteController extends BaseController
{   
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
                'view' => '404'
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'registration' => [
                'class' => RegistrationAction::class
            ],
            'pricelist' => [
                'class' => PricelistAction::class,
            ],
            'index' => [
                'class' => IndexAction::class,
            ],
            'oneclick-order' => [
                'class' => OneclickOrder::class,
            ],
            'apply-promocode' => [
                'class' => ApplyPromocode::class,
            ]
        ];
    }

    public function actionLogin()
    {
        $loginForm = Yii::$container->get(LoginForm::class);

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        }

        $loginForm->password = '';

        return $this->render('login', [
            'loginForm' => $loginForm,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Мы отправили инструкции на ваш адресс');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Произошла ошибка');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (Exception $e) {
            throw new NotFoundHttpException();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Пароль успешно изменен');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact'/*, [
            'model' => $model,
        ]*/);
    }
    
    public function actionHowToOrder()
    {
        return $this->render('howToOrder');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionClients()
    {
        return $this->render('clients');
    }

    public function actionDelivery()
    {
        return $this->render('delivery');
    }
    public function actionRequisites()
    {
        return $this->render('requisites');
    }

    public function actionPolitics()
    {
        return $this->render('politics');
    }

    public function actionCertificate()
    {
        return $this->render('certificate');
    }

    /**
     * Реквизиты
     * @return string
     */
    public function actionProps()
    {
        return $this->render('props');
    }

    public function actionCallback(Session $session)
    {
        $callbackForm = Yii::$container->get(CallbackForm::class);

        if ($callbackForm->load(Yii::$app->request->post()) && $callbackForm->validate()) {
            if ($callbackForm->save()) {
                $session->setFlash('success', 'Заявка успешно оставлена');
            } else {
                $session->setFlash('error', 'Произошла ошибка');
            }
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionBookcall(Session $session)
    {
        $bookcallForm = Yii::$container->get(BookcallForm::class);

        if ($bookcallForm->load(Yii::$app->request->post()) && $bookcallForm->validate()) {
            if ($bookcallForm->save()) {
                $session->setFlash('success', 'Заявка успешно оставлена');
            } else {
                $session->setFlash('error', 'Произошла ошибка');
            }
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSubscribe(Session $session)
    {
        $subscribeForm = Yii::$container->get(\frontend\forms\SubscribersForm::class);

        if ($subscribeForm->load(Yii::$app->request->post()) && $subscribeForm->validate()) {
            if ($subscribeForm->save()) {
                $session->setFlash('success', 'Вы успешно подписались');
            }
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        $this->redirect(Yii::$app->request->referrer);
    }

    public function actionChangeCity(string $code): void 
    {
        $referrer = Yii::$app->request->referrer;
        Yii::$app->session->set('city', $code);

        $this->redirect($referrer);
    }
}
