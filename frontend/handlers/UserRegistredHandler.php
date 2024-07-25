<?php

namespace frontend\handlers;

use cms\common\models\User;
use frontend\events\UserRegistred;
use CmsModule\Shop\common\models\Orders;
use \Yii;
use yii\base\Model;

class UserRegistredHandler extends Model
{
    public int $userId = 0;

    public static function run(UserRegistred $event)
    {
        $handler = new static(['userId' => $event->user_id]);
        $handler->sendEmail();
    }

    public function sendEmail(): void 
    {
        $user = User::findOne($this->userId);

        $mailer = Yii::$app->sender->email;
        $mailer->viewPath = Yii::getAlias('@cms/common/mail/');

        $subject = 'Подтверждение регистрации на сайте ' . Yii::$app->urlManager->hostInfo;

        $params = [
            'from' => Yii::$app->params['adminEmail'],
            'to' => $user->email,
            'subject' => $subject,
            'view' => 'email-confirm',
            'trans' => [
                'user' => $user
            ],
        ];

        try {
            $mailer->send($params);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }
}