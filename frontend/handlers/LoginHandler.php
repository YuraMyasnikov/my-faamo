<?php

namespace frontend\handlers;

use CmsModule\Shop\common\models\Basket as Basket;
use Exception;
use Yii;
use yii\web\UserEvent;

/**
 * Class LoginHandler
 * @package extensions\modules\shop
 */
class LoginHandler
{
    /**
     * @param UserEvent $event
     */
    public static function run(UserEvent $event)
    {
        $user = $event->identity;

        $newBasketId = Basket::getToken();
        $oldBasket = Basket::find()->where(['user_id' => $user->id])->indexBy('sku_id')->all();
        $newBasket = Basket::find()->where(['token' => $newBasketId, 'user_id' => null])->indexBy('sku_id')->all();

        try {
            foreach ($oldBasket as $sku_id => $old_element) {
                $old_element->token = $newBasketId;
                if (isset($newBasket[$sku_id])) {
                    $old_element->count += $newBasket[$sku_id]->count;
                    $newBasket[$sku_id]->delete();
                }
                $old_element->save();
            }

            Basket::updateAll(['user_id' => $user->id], ['user_id' => null, 'token' => $newBasketId]);
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    }
}
