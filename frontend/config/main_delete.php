<?php

use cms\common\models\User as ModelsUser;
use CmsModule\Shop\common\models\Settings;
use CmsModule\Shop\frontend\viewModels\SettingsViewModel;
use frontend\components\PriceComponent;
use frontend\components\PriceListComponent;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use modules\delivery\Delivery;
use modules\delivery\DeliveryInterface;
use yii\web\GroupUrlRule;
use yii\web\View;

require __DIR__ . '/events.php';

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$current_theme = 'alfa';

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'container' => [
        'singletons' => [
            DeliveryInterface::class => [
                'class' => Delivery::class,
                'addreses' => [
                    'default' => [
                        'zip' => '153000',
                        'fiasId' => 'Иваново',
                        'kladrId' => '3700000100000'
                    ]
                ],
                'adapters' => [
                    'delin' => [
                        'class' => 'modules\delivery\Adapters\DELIN',
                        'key' => '9F3CA3DA-00CC-11E6-A0A6-00505683A6D3'
                    ],
                    'pochta' => [
                        'class' => 'modules\delivery\Adapters\Pochta',
                        'login' => 'IPHNGKpfF7fTKnbrcOGeRUDQApEqfOYc',
                        'key' => 'c2FsZXNAbm92aWtvdjI0LnJ1OnY4dGQyREUzSTlTUw'
                    ],
                ]
            ]
        ]],
     
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => ModelsUser::class,
            'loginUrl' => ['/site/login'],
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
            'siteKeyV3' => $params['reCaptcha']['siteKeyV3'] ?? null,
            'secretV3' => $params['reCaptcha']['secretV3'] ?? null
        ],
        'prices' => function() {
            return new PriceComponent(10, 25);
        },
        'priceList' => function() {
            return new PriceListComponent();
        },
        'settings' => function() {
            return SettingsViewModel::make(Settings::find()->one());
        },
        'imageCache' => function() {
            $absolutePath = Yii::getAlias('@webroot') . '/uploads/cache';
            $relativePath = '/uploads/cache';
            return new \frontend\components\ImageCacheComponent($absolutePath, $relativePath);
        },
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'class' => View::class,
            'theme' => [
                'basePath' => '@frontend/themes/' . $current_theme,
                'baseUrl' => '@web/themes/' . $current_theme,
                'pathMap' => [
                    '@app/views'=> '@frontend/themes/' . $current_theme . '/frontend',
                    '@extensions/modules/shop/src/frontend/views' => '@frontend/themes/' . $current_theme . '/shop',
                    '@extensions/modules/shop/src/admin/views' => '@frontend/themes/' . $current_theme . '/shop_admin',
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
                '' => 'site/index',
                '/catalog' => 'catalog/index',
                '/catalog/<filters>' => 'catalog/view',
                /*
                '/product/<code>/add-review' => 'product/add-review', 
                '/product/<code>/reviews' => 'product/reviews', 
                '/basket' => 'shop/frontend/basket/index',
                '/basket/<action>' => 'shop/frontend/basket/<action>',
                '/orders/<action>' => 'shop/frontend/orders/<action>',*/
                '/about' => 'site/about',
                '/clients' => 'site/clients',
                '/politics' => 'site/politics',
                '/requisites' => 'site/requisites',
                /*
                '/delivery/calculate' => 'delivery/calculate',
                '/delivery/points' => 'delivery/points',*/
                '/delivery' => 'site/delivery',
                /*
                '/return' => 'site/return',*/
                '/contact' => 'site/contact',
                '/login' => 'site/login',
                '/registration' => 'site/registration',
                '/site/<action>' => 'site/<action>',
                /*
                '/reviews' => 'reviews-frontend/index',
                '/reviews/<action>' => 'reviews-frontend/<action>',
                '/reviews/list' => 'reviews-frontend/list',
                '/news/<code>' => 'news/frontend/news/view',
                '/news' => 'news/frontend/news/index',
                '/favorite' => 'shop/frontend/favorite/index',
                '/subscribers/<action>' => 'subscribers/frontend/subscribers/<action>',
                '/products/<code>' => 'product/view',*/
                '/account' => 'account/index',
                /*
                '/account/<action>' => 'account/<action>',
                '/search' => 'search/index',
                */
                [
                    'class' => GroupUrlRule::class,
                    'prefix' => 'api',
                    'routePrefix' => '/',
                    'rules' => [
                        '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/api/<_c>/index',
                        '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/api/<_c>/<_a>'
                    ]
                ],
                /*
                '/<filters:(.*)+>' => 'catalog/view',*/
            ],
        ],
    ],
    'params' => $params,
];
