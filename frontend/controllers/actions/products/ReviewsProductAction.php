<?php 

namespace frontend\controllers\actions\product;

use Yii;
use yii\base\Action;
use CmsModule\Infoblocks\models\Infoblock;
use frontend\models\shop\Products;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class ReviewsProductAction extends Action 
{
    const COUNT_REVIEWS_ON_PAGE = 10;

    public function run($code): Response|string
    {
        $product = $this->findProduct($code);
        
        /**
         * Список отзывов
         */
        $productReviewsDataProvider = static::productReviewsDataProvider($product->id);

        if(Yii::$app->request->isAjax) {
            $page = Yii::$app->request->get('page', 1);
            if($page >= 1) {
                $page -= 1;
            }
            
            return $this->controller->asJson([
                'isHasNextPage' => static::isHasNextPage($product->id, $page),
                'reviews' => trim($this->controller->renderPartial('reviews', [
                    'productReviewsDataProvider' => $productReviewsDataProvider,
                ]))
            ]);
        }
    
        return trim($this->controller->renderPartial('reviews', [
            'productReviewsDataProvider' => $productReviewsDataProvider,
        ]));
    }

    public static function isHasNextPage(int $productId, int $currentPage = 1): bool 
    {
        $infoblock = Infoblock::byCode('product_reviews');
        $productReviewsCount = $infoblock::find()->where(['active' => true, 'product_id' => $productId])->count();
        $totalPagesCount = ceil($productReviewsCount / ReviewsProductAction::COUNT_REVIEWS_ON_PAGE);
        
        return $totalPagesCount > $currentPage;
    }

    public static function productReviewsDataProvider(int $productId): ActiveDataProvider 
    {
        $infoblock = Infoblock::byCode('product_reviews');
        $productReviews = $infoblock::find()->where(['active' => true, 'product_id' => $productId])->orderBy(['created_at' => SORT_DESC]);

        $page = \Yii::$app->request->get('page', 1);
        if($page >= 1) {
            $page -= 1;
        }

        return new ActiveDataProvider([
            'query' => $productReviews,
            'pagination' => [
                'pageSize' => ReviewsProductAction::COUNT_REVIEWS_ON_PAGE,
                'page' => $page
            ],
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
}