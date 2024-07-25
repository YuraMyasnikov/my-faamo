<?php

namespace frontend\handlers;

use cms\common\events\UserEvent;
use CmsModule\Shop\common\models\Orders;
use Yii;

class BeforeDeleteUserHandler
{
    public static function run(UserEvent $event)
    {
        $user_id = $event->user_id;

        Orders::deleteAll(['user_id' => $user_id]);
    }
}