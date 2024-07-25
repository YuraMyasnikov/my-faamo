<?php

use CmsModule\Shop\admin\controllers\ProductsController as BaseAdminProductsController;
use CmsModule\Shop\admin\controllers\CategoriesController as BaseAdminCategoriesController;
use CmsModule\Shop\common\models\Products as ShopProducts;
use CmsModule\Shop\common\models\Categories as ShopCategories;
use CmsModule\Shop\common\models\Sku as ShopSku;
use CmsModule\Shop\common\models\Options as ShopOptions;
use CmsModule\Shop\common\models\OrderData;
use CmsModule\Shop\frontend\controllers\BasketController;
use CmsModule\Shop\frontend\controllers\CatalogController;
use CmsModule\Shop\frontend\controllers\OrdersController;
use CmsModule\Shop\frontend\controllers\ProductsController;
use CmsModule\Shop\frontend\forms\OrganizationOrderCreateForm;
use CmsModule\Shop\frontend\forms\ProductsFilterForm as ShopProductsFilterForm;
use CmsModule\Shop\frontend\forms\UserOrderCreateForm;
use CmsModule\Shop\frontend\services\FiltersService as ShopFiltersService;
use CmsModule\Shop\frontend\services\OrderService;
use frontend\forms\ProductsFilterForm;
use frontend\models\OrderData as ModelsOrderData;
use frontend\models\shop\Categories;
use frontend\models\shop\Options;
use frontend\models\shop\Products;
use frontend\models\shop\Sku;
use frontend\services\FiltersService;

$container = Yii::$container;

Yii::$classMap['products'] = Products::class;
Yii::$classMap['options'] = Options::class;
Yii::$classMap[Yii::getAlias(BaseAdminProductsController::class)] = Yii::getAlias('@frontend/controllers/admin/ProductsController.php');
Yii::$classMap[Yii::getAlias(BaseAdminCategoriesController::class)] = Yii::getAlias('@frontend/controllers/admin/CategoriesController.php');
Yii::$classMap[Yii::getAlias(\cms\extensions\modules\settings\admin\controllers\SeoController::class)] = Yii::getAlias('@frontend/controllers/admin/SeoController.php');
Yii::$classMap[Yii::getAlias(CatalogController::class)] = Yii::getAlias('@frontend/controllers/CatalogController.php');
Yii::$classMap[Yii::getAlias(ProductsController::class)] = Yii::getAlias('@frontend/controllers/ProductsController.php');
Yii::$classMap[Yii::getAlias(BasketController::class)] = Yii::getAlias('@frontend/controllers/BasketController.php');
Yii::$classMap[Yii::getAlias(OrdersController::class)] = Yii::getAlias('@frontend/controllers/OrdersController.php');
Yii::$classMap[Yii::getAlias(UserOrderCreateForm::class)] = Yii::getAlias('@frontend/models/UserOrderCreateForm.php');
Yii::$classMap[Yii::getAlias(OrganizationOrderCreateForm::class)] = Yii::getAlias('@frontend/models/OrganizationOrderCreateForm.php');
Yii::$classMap[Yii::getAlias(OrderService::class)] = Yii::getAlias('@frontend/services/OrderService.php');

$container->set(ShopSku::class, Sku::class);
$container->set(ShopProducts::class, Products::class);
$container->set(ShopCategories::class, Categories::class);
$container->set(ShopOptions::class, Options::class);
$container->set(ShopProductsFilterForm::class, ProductsFilterForm::class);
$container->set(ShopFiltersService::class, FiltersService::class);
$container->set(OrderData::class, ModelsOrderData::class);