<?php 

namespace frontend\controllers\actions\site;

use CmsModule\Shop\common\models\Basket;
use CmsModule\Shop\common\models\Promocodes;
use frontend\forms\PromocodeForm;
use frontend\models\shop\CalculateItem;
use frontend\services\CalculatorService;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;


class ApplyPromocode extends Action 
{
    public function run()
    {
        $basketProducts = Yii::$app->basket->get();
        if (!$basketProducts) {
            if (Yii::$app->request->isAjax) {
                return $this->redirect('/basket/index');
            }

            return $this->render('empty');
        }
        $basketProductsUnavailableForOrder = [];
        foreach($basketProducts as $basketProduct) {
            /** @var Basket $basketProduct */
            $remnants = $basketProduct->sku->remnants;
            if($basketProduct->count > $remnants) {
                $bp = clone $basketProduct;
                $bp->count = $basketProduct->count - $remnants;
                array_push($basketProductsUnavailableForOrder, $bp);

                $basketProduct->count = $remnants;
            }
        }
        $countItemsInBasket = array_reduce($basketProducts, function($count, Basket $item) {
            $count += $item->count;
            return $count; 
        }, 0);
        if ($countItemsInBasket < 1) {
            throw new NotFoundHttpException();
        }
        $skusIds = array_reduce($basketProducts, function($result, Basket $item) {
            $result[] = $item->sku_id;
            return $result;
        }, []);
        $skusLimit = array_reduce($basketProducts, function($result, Basket $item) {
            $result[$item->sku_id] = $item->count;
            return $result;
        }, []);
        $basketToken = Basket::getToken();
        $calculator  = Yii::$container->get(CalculatorService::class);
        $pricesTypes = $calculator->getPrices();
        $details     = $calculator->countDetails($basketToken, $pricesTypes, $skusIds, $skusLimit);
        $calculate   = $calculator->calculate($details, $pricesTypes);
        $pricesType  = $calculate['price_type'];
        $pricesTypeTitle = $pricesTypes[$pricesType]['title']; 
        $totalPriceWithoutDiscount = array_reduce($details, function($result, CalculateItem $item) {
            $result += $item->price * $item->count;
            return $result;
        }, 0);
        $totalPriceWithDiscount = $calculate['sum'];
        
        $promocodeForm = Yii::$container->get(PromocodeForm::class);
        if(Yii::$app->request->isPost && $promocodeForm->load(Yii::$app->request->post())) {
            if(!$promocodeForm->validate()) {
                Yii::$app->session->remove(Promocodes::SESSION_NAME);
                Yii::$app->session->setFlash('error', 'Неизвестный промокод');

                $this->controller->redirect(Yii::$app->request->referrer);
                return;
            } 
            $promocode = $promocodeForm->promocode 
                ? Promocodes::findActivePromocodeByCode($promocodeForm->promocode) 
                : null;
            if(!$promocode) {
                Yii::$app->session->remove(Promocodes::SESSION_NAME);
                Yii::$app->session->setFlash('error', 'Неизвестный промокод');

                $this->controller->redirect(Yii::$app->request->referrer);
                return;
            } 
            if($promocode && $totalPriceWithDiscount <= floatval($promocode->min_threshold ?? null)) {
                Yii::$app->session->remove(Promocodes::SESSION_NAME);
                Yii::$app->session->setFlash('error', 'Миниальный заказ для применения этого промокода ' . $promocode->min_threshold . ' руб.');

                $this->controller->redirect(Yii::$app->request->referrer);
                return;
            }
            Yii::$app->session->setFlash('success', 'Промокод ОК');
            Yii::$app->session->set(Promocodes::SESSION_NAME, $promocode->promocode);
        }
        $this->controller->redirect(Yii::$app->request->referrer);
    }
}