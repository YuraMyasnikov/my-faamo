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
class LogoutHandler
{
    /**
     * @param UserEvent $event
     */
    public static function run(UserEvent $event)
    {
        Basket::getToken(true);
    }
}
