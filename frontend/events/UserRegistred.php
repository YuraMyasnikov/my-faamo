<?php

namespace frontend\events;

use yii\base\Event;

class UserRegistred extends Event
{
    public $user_id;
}