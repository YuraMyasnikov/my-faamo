<?php 

namespace frontend\controllers\actions\products;

use CmsModule\Shop\frontend\viewModels\ProductViewModel;
use frontend\controllers\actions\product\ReviewsProductAction;
use frontend\models\OneClickOrderForm;
use frontend\models\shop\Products;
use frontend\models\shop\Sku;
use frontend\services\CalculatorService;
use Yii;
use yii\base\Action;
use yii\base\InvalidValueException;
use yii\web\NotFoundHttpException;


class ViewProductAction extends Action 
{
    public function run($code)
    {
        $product = $this->findProduct($code);
        $skus    = $this->findSkus($product);
        $skusCount = array_reduce($skus, function($totalRemnants, \CmsModule\Shop\common\models\Sku $sku) {
            $totalRemnants += $sku->remnants;
            return $totalRemnants;
        }, 0);
        $category= $this->findCategory($product);

        $productViewModel = new ProductViewModel;
        $productViewModel->product_id = $product->id;
        $productViewModel->product = $product;

        /**
         * Список отзывов
         */
        $productReviewsDataProvider = ReviewsProductAction::productReviewsDataProvider($product->id);

        /**
         * Форма для создания отзыва
         */
        $isShowProductReviewsForm = !Yii::$app->user->isGuest;
        $isHasNextReviewsPage = ReviewsProductAction::isHasNextPage($product->id);
        $productReviewsForm = $this->productReviewsForm($product->id);

        /**
         * Форма для покупки в "один клик"
         */
        
        if(!Yii::$app->user->isGuest) {
            $profile = Yii::$app->user->getIdentity()->profile;
            $oneClickOrderForm = new OneClickOrderForm([
                'name' => implode(' ', [$profile->surname, $profile->name, $profile->patronymic]),
                'email' => Yii::$app->user->getIdentity()->email,
                'phone' => $profile->phone
            ]);
        } else {
            $oneClickOrderForm = new OneClickOrderForm();
        }
        return $this->controller->render('view', [
            'isActive' => $skusCount > 0,
            'product' => $product, 
            'productPrice' => $this->productPrice($skus),
            'productPriceType' => $this->productPriceType(),
            'productViewModel' => $productViewModel, 
            'productCategory' => $category,
            'productReviewsDataProvider' => $productReviewsDataProvider,
            'productReviewsForm' => $productReviewsForm,
            'isFavorite' => $this->isFavorite($product),
            'isShowProductReviewsForm' => $isShowProductReviewsForm,
            'isHasNextReviewsPage' => $isHasNextReviewsPage,
            'oneClickOrderForm' => $oneClickOrderForm
        ]);
    }

    protected function findProduct(string $code): Products 
    {
        $product = Products::findOne(['code' => $code, 'active' => true]);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        return $product;
    }

    /**
     * @return array<int, \CmsModule\Shop\common\models\Sku>
     */
    protected function findSkus(Products $product): array 
    {
        $skus = $product->sku;
        if(!$skus) {
            throw new InvalidValueException('Not found skus for product');
        }
        $basketProducts = Yii::$app->basket->get();
        $basketSku = [];
        foreach($basketProducts as $item) {
            $basketSku[$item->sku_id] = $item->count;
        }
        foreach($skus as $sku) {
            if(!isset($basketSku[$sku->id])) {
                continue;
            }
            $sku->remnants -= $basketSku[$sku->id];
            if($sku->remnants < 0) {
                $sku->remnants = 0;
            }
        }

        return $skus;
    }

    protected function findCategory(Products $product): \CmsModule\Shop\common\models\Categories|null 
    {
        return $product->mainCategory;
    }

    protected function productReviewsForm(int $productId): \frontend\models\shop\ProductReviewsForm
    {
        $productReviewsForm = new \frontend\models\shop\ProductReviewsForm();
        $productReviewsForm->product_id = $productId;
        if(!Yii::$app->user->isGuest) {
            $productReviewsForm->fio = Yii::$app->user->identity->profile->fio;
            $productReviewsForm->email = Yii::$app->user->identity->email;
        }

        return $productReviewsForm;
    }

    protected function productPrice(array $skus): float 
    {
        $min = null;
        foreach($skus as $sku) {
            /** @var Sku $sku */
            if(!$min) {
                $min = $sku->price;
            }
            if($sku->price < $min) {
                $min = $sku->price;
            }
        }

        return round(floatval($min), 2);
    }

    protected function productPriceType(): string 
    {
        $calculator  = Yii::$container->get(CalculatorService::class);
        $basketToken = \CmsModule\Shop\common\models\Basket::getToken();
        $pricesTypes = $calculator->getPrices();
        $details     = $calculator->countDetails($basketToken, $pricesTypes, []);
        $calculate   = $calculator->calculate($details, $pricesTypes);
        
        return $calculate['price_type'];
    }

    protected function isAllPricesAreEqual(array $skus): bool
    {
        return true;
    }

    protected function isFavorite(Products $product): bool 
    {
        return Yii::$app->favorite->isFavorite($product->id);
    }
}