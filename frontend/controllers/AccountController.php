<?php

namespace frontend\controllers;

use frontend\controllers\actions\account\AccountAction;
use frontend\controllers\actions\account\ChangeAccountTypeAction;
use frontend\controllers\actions\account\ChangePasswordAction;
use frontend\controllers\actions\account\OrdersAction;
use yii\web\Controller;
use yii\filters\AccessControl;


class AccountController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [                    
                    [
                        'actions' => ['index', 'change-password', 'change-account-type', 'orders'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
            'index' => [
                'class' => AccountAction::class
            ],
            'change-account-type' => [
                'class' => ChangeAccountTypeAction::class
            ],
            'change-password' => [
                'class' => ChangePasswordAction::class
            ],
            'orders' => [
                'class' => OrdersAction::class
            ]
        ];
    }

}