<?php

use CmsModule\Shop\Module as ShopModule;
use CmsModule\Shop\admin\Module as ShopAdminModule;
use CmsModule\Shop\frontend\Module as ShopFrontendModule;
use CmsModule\Shop\api\Module as ShopApiModule;
use extensions\modules\shop\src\components\Basket;
use CmsModule\Infoblocks\Module as InfoblocksModule;
use CmsModule\Infoblocks\admin\Module as InfoblocksAdminModule;
use CmsModule\Reviews\Module as ReviewsModule;
use CmsModule\Reviews\frontend\Module as ReviewsFrontendModule;
use CmsModule\Blogs\Module as BlogsModule;
use CmsModule\Blogs\frontend\Module as BlogsFrontendModule;
use CmsModule\Quests\Module as QuestsModule;
use CmsModule\Quests\frontend\Module as QuestsFrontendModule;
use CmsModule\News\Module as NewsModule;
use CmsModule\News\frontend\Module as NewsFrontendModule;
use CmsModule\Shop\components\Favorite;
use CmsModule\Shop\extensions\components\search\Component as SearchComponent;
use frontend\controllers\shop\admin\SkuController;
use CmsModule\Subscribers\Module as SubscribersModule;
use CmsModule\Subscribers\frontend\Module as SubscribersFrontendModule;
use common\components\InvoiceGeneratorComponent;
use frontend\components\SeoComponent;

return [
    'name' => 'Faamo',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@extensions' => __DIR__ . '/../../extensions',
        '@modules/delivery' => '@extensions/modules/delivery',
        '@CmsModule/Shop' => '@extensions/modules/shop/src',
        '@CmsModule/Infoblocks' => '@extensions/modules/infoblocks/src',
        '@CmsModule/Reviews' => '@extensions/modules/reviews/',
        '@CmsModule/Blogs' => '@extensions/modules/blogs/',
        '@CmsModule/Quests' => '@extensions/modules/quests/',
        '@CmsModule/News' => '@extensions/modules/news/',
        '@CmsModule/Subscribers' => '@extensions/modules/subscribers/',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'shop' => [
            'class' => ShopModule::class,
            'modules' => [
                'admin' => [
                    'class' => ShopAdminModule::class,
                    'controllerMap' => [
                        'sku' => SkuController::class
                    ]
                ],
                'frontend' => [
                    'class' => ShopFrontendModule::class
                ],
                'api' => [
                    'class' => ShopApiModule::class
                ]
            ]
        ],
        'infoblocks' => [
            'class' => InfoblocksModule::class,
            'modules' => [
                'admin' => [
                    'class' => InfoblocksAdminModule::class
                ],
                'frontend' => [
                    'class' => ShopFrontendModule::class
                ],
                'api' => [
                    'class' => ShopApiModule::class
                ]
            ]
        ],
        'reviews' => [
            'class' => ReviewsModule::class,
            'modules' => [
                'frontend' => [
                    'class' => ReviewsFrontendModule::class
                ]
            ]
        ],
        'blogs' => [
            'class' => BlogsModule::class,
            'modules' => [
                'frontend' => [
                    'class' => BlogsFrontendModule::class
                ]
            ]
        ],
        'quests' => [
            'class' => QuestsModule::class,
            'modules' => [
                'frontend' => [
                    'class' => QuestsFrontendModule::class
                ]
            ]
        ],
        'news' => [
            'class' => NewsModule::class,
            'modules' => [
                'frontend' => [
                    'class' => NewsFrontendModule::class
                ]
            ]
        ],
        'subscribers' => [
            'class' => SubscribersModule::class,
            'modules' => [
                'frontend' => [
                    'class' => SubscribersFrontendModule::class
                ]
            ]
        ]
    ],
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'components' => [
        'formatter' => [
            'thousandSeparator' => ',',
            'currencyCode' => 'RUR',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'favorite' => [
            'class' => Favorite::class
        ],
        'basket' => [
            'class' => Basket::class
        ],
        'invoiceGenerator' => function() {
            return new InvoiceGeneratorComponent();
        },
        'search' => [
            'class' => SearchComponent::class
        ],
        'image' => [
            'class' =>  cms\extensions\components\image\Component::class
        ],
        'seo' => [
            'class' => SeoComponent::class
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@frontend/views/mails/',
            'useFileTransport' => false,
            'transport' => [
                "scheme" => 'smtps',
                'host' => 'smtp.mail.ru',
                'username' => 'test@mail.ru',
                'password' => 'password',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
    ],
];
