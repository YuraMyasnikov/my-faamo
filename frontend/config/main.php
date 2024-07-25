<?php

use cms\common\models\User;
use frontend\services\CityCodeResolver;
use yii\web\GroupUrlRule;
use yii\web\View;

require __DIR__ . '/events.php';

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

$current_theme = 'alfa';
$default_city  = 'spb';

return [
    'id'        => 'app-frontend',
    'basePath'  => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'controllerMap' => [
        'favorite' => 'frontend\controllers\shop\controllers\FavoriteController',
    ],
    'container' => [
        'singletons' => [
            \CmsModule\Shop\frontend\viewModels\ProductViewModel::class => [
                'class' => \frontend\models\shop\viewModels\ProductViewModel::class,
            ]
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass'   => User::class,
            'loginUrl'        => ['/site/login'],
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class'  => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        /**
         * \frontend\models\shop\settings\SettingsViewModel
         */
        'settings' => function() {
            return new \frontend\models\shop\settings\SettingsViewModel();
        },
        'formatter' => [
            'locale'            => 'ru-RU',
            'dateFormat'        => 'dd.MM.yyyy',
            'decimalSeparator'  => ',',
            'thousandSeparator' => ' ',
            'currencyCode'      => 'RUR',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'class' => View::class,
            'theme' => [
                'basePath' => '@frontend/themes/' . $current_theme,
                'baseUrl'  => '@web/themes/' . $current_theme,
                'pathMap'  => [
                    '@app/views' => '@frontend/themes/' . $current_theme . '/frontend',
                    '@extensions/modules/shop/src/frontend/views' => '@frontend/themes/' . $current_theme . '/shop',
                    '@extensions/modules/shop/src/admin/views'    => '@frontend/themes/' . $current_theme . '/shop_admin',
                    '@cms/frontend/views' => '@frontend/themes/' . $current_theme . '/cms/frontend',
                    '@cms/common/mails'   => '@frontend/themes/' . $current_theme . '/cms/frontend',
                    '@CmsModule/Shop/frontend/views'         => '@frontend/themes/' . $current_theme . '/shop/frontend',
                    '@CmsModule/Shop/frontend/widgets/views' => '@frontend/themes/' . $current_theme . '/shop/frontend/widgets',
                    '@CmsModule/Shop/admin/views'            => '@frontend/themes/' . $current_theme . '/shop/admin',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                /**
                 * Каталог
                 */
                [
                    'pattern'  => '/<city:\w+>/catalog',
                    'route'    => 'shop/frontend/catalog/index',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/catalog/<filters>',
                    'route'    => 'shop/frontend/catalog/view',
                    'defaults' => ['city' => $default_city]
                ],

                /**
                 * Pages
                 */
                [
                    'pattern'  => '/<city:\w+>/about',
                    'route'    => 'site/about',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/clients',
                    'route'    => 'site/clients',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/politics',
                    'route'    => 'site/politics',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/requisites',
                    'route'    => 'site/requisites',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/delivery',
                    'route'    => 'site/delivery',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/return',
                    'route'    => 'site/return',
                    'defaults' => ['city' => $default_city]
                ],
                [
                    'pattern'  => '/<city:\w+>/contact',
                    'route'    => 'site/contact',
                    'defaults' => ['city' => $default_city]
                ],

                /**
                 * Review
                 */
                '/reviews' => 'reviews/frontend/default/index',
                '/reviews/<action>' => 'reviews/frontend/default/<action>',

                /**
                 * Blog
                 */
                '/blogs' => 'blogs/frontend/default/index',
                '/blogs/<code>' => 'blogs/frontend/default/item',
                '/blogs/<action>' => 'blogs/frontend/default/<action>',

                /**
                 * News
                 */
                '/news/<code>' => 'news/frontend/news/view',
                '/news'        => 'news/frontend/news/index',

                /**
                 * Faq
                 */
                '/quests' => 'quests/frontend/default/index',

                /**
                 * Favorites
                 */
                '/favorite' => 'favorite/index',
                
                /**
                 * Subscribers 
                 */
                '/subscribers/<action>' => 'subscribers/frontend/subscribers/<action>',

                /**
                 * Registration, login
                 */
                '/login' => 'site/login',
                '/registration' => 'site/registration',

                /**
                 * Account
                 */
                '/account' => 'account/index',
                '/account/<action>' => 'account/<action>',

                /**
                 * Product
                 */
                [
                    'pattern'  => '/<city:\w+>/products/<code>',
                    'route'    => 'shop/frontend/products/view',
                    'defaults' => ['city' => $default_city]
                ], 
                '/product/<code>/add-review' => 'product/add-review', 
                '/product/<code>/reviews'    => 'product/reviews',

                /**
                 * Basket
                 */
                
                '/basket' => 'shop/frontend/basket/index',
                '/basket/<action>' => 'shop/frontend/basket/<action>',

                /**
                 * Orders
                 */
                '/orders/<action>' => 'shop/frontend/orders/<action>',

                /**
                 * Search
                 */
                '/search' => 'search/index',

                [
                    'pattern'  => '/<city:\w+>',
                    'route'    => 'site/index',
                    'defaults' => ['city' => $default_city]
                ],
                
                /**
                 * Other
                 */
                '/site/<action>' => 'site/<action>',
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
