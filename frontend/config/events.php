<?php

use cms\common\models\User;
use frontend\handlers\BeforeDeleteUserHandler;
use frontend\handlers\LoginHandler;
use frontend\handlers\LogoutHandler;
use yii\base\Event;
use yii\web\User as WebUser;

Event::on(User::class, User::EVENT_BEFORE_DELETE, [BeforeDeleteUserHandler::class, 'run']);
Event::on(WebUser::class, WebUser::EVENT_BEFORE_LOGIN, [LoginHandler::class, 'run']);
Event::on(WebUser::class, WebUser::EVENT_AFTER_LOGOUT, [LogoutHandler::class, 'run']);