<?php

namespace frontend\controllers;

/*use frontend\controllers\actions\products\AddReviewProductAction;*/
use frontend\controllers\actions\product\AddReviewProductAction;
/*use frontend\controllers\actions\products\ReviewsProductAction;*/
use frontend\controllers\actions\product\ReviewsProductAction;
use frontend\controllers\actions\products\ViewProductAction;
use frontend\models\shop\Products;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class ProductController extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'view' => [
                'class' => ViewProductAction::class
            ],
            'reviews' => [
                'class' => ReviewsProductAction::class
            ],
            'add-review' => [
                'class' => AddReviewProductAction::class
            ],
        ];
    }


    public function findProduct(string $code): Products 
    {
        $product = Products::findOne(['code' => $code, 'active' => true]);

        if (!$product) {
            throw new NotFoundHttpException();
        }

        return $product;
    }
}

