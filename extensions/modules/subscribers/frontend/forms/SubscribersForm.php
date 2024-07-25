<?php

namespace CmsModule\Subscribers\frontend\forms;

use CmsModule\Infoblocks\models\Infoblock;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;
use Yii;
use yii\base\Model;

class SubscribersForm extends Model
{
    const TYPE = 'subscribers';
    const RECAPTCHA_CREATE_ACTION = 'subsribe';

    public $email;
    //public $reCaptcha;

    public function rules(): array
    {
        return [
            //[['reCaptcha'], ReCaptchaValidator3::class, 'secret' => Yii::$app->reCaptcha->secretV3, 'action' => self::RECAPTCHA_CREATE_ACTION, 'threshold' => 0.5],
            [['email'], 'required', 'message' => 'Необходимо заполнить'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Электронная почта'
        ];
    }

    public static function buildForm()
    {
        $form = Yii::$container->get(self::class);

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $form->email = $user->email;
        }

        return $form;
    }

    public function save()
    {
        $infoblock = Infoblock::byCode(self::TYPE);
        if ($infoblock::findOne(['email' => $this->email])) {
            Yii::$app->session->setFlash('error', 'Эта почта уже подписана на рассылку');
            return false;
        }
        $model = $infoblock::create();
        $model->name = $this->email;
        $model->code = Yii::$app->security->generateRandomString(16);
        $model->email = $this->email;
        $model->active = true;
        if ($model->save()) {
            return true;
        } else {
            Yii::error($model->getErrors());
            return false;
        }
    }
}