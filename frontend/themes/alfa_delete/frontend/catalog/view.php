<?php

use cms\common\models\User as ModelsUser;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\web\GroupUrlRule;
use yii\web\View;

require __DIR__ . '/events.php';

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$current_theme = 'ceramic-store';

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => ModelsUser::class,
            'loginUrl' => ['/login'],
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'reCaptcha' => [
            'class' => ReCaptchaConfig::class,
            'siteKeyV3' => 'test',
            'secretV3' => 'test'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'class' => View::class,
            'theme' => [
                'basePath' => '@frontend/themes/' . $current_theme,
                'baseUrl' => '@web/themes/' . $current_theme,
                'pathMap' => [
                    '@cms/frontend/views' => '@frontend/themes/' . $current_theme . '/cms/frontend',
                    '@cms/common/mails' => '@frontend/themes/' . $current_theme . '/cms/frontend',
                    '@CmsModule/Shop/frontend/views' => '@frontend/themes/' . $current_theme . '/shop/frontend',
                    '@CmsModule/Shop/frontend/widgets/views' => '@frontend/themes/' . $current_theme . '/shop/frontend/widgets',
                    '@CmsModule/Shop/admin/views' => '@frontend/themes/' . $current_theme . '/shop/admin',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/catalog' => 'shop/frontend/catalog/index',
                '/catalog/<filters:(.*)+>' => 'shop/frontend/catalog/view',
                //'/catalog/<category:[\w_-]+>/<filters>' => 'shop/frontend/catalog/view',
                '/product/<code>' => 'shop/frontend/products/view',
                '/basket' => 'shop/frontend/basket/index',
                '/basket/<action>' => 'shop/frontend/basket/<action>',
                '/orders/<action>' => 'shop/frontend/orders/<action>',
                '/about' => 'site/about',
                '/delivery' => 'site/delivery',
                '/return' => 'site/return',
                '/contact' => 'site/contact',
                '/site/<action>' => 'site/<action>',
                '/reviews/<action>' => 'reviews/frontend/default/<action>',
                '/reviews' => 'reviews/frontend/default/index',
                '/news/<code>' => 'news/frontend/news/view',
                '/news' => 'news/frontend/news/index',
                '/favorite' => 'shop/frontend/favorite/index',
                '/subscribers/<action>' => 'subscribers/frontend/subscribers/<action>',
                [
                    'class' => GroupUrlRule::class,
                    'prefix' => 'api',
                    'routePrefix' => '/',
                    'rules' => [
                        '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/api/<_c>/index',
                        '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/api/<_c>/<_a>'
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
