<?php

namespace frontend\forms;

use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\base\Model;

class SubscribersForm extends Model
{
    const TYPE = 'subscribers';

    public $email;

    public function rules(): array
    {
        return [
            [['email'], 'required', 'message' => 'Необходимо заполнить'],
            ['email', 'email', 'message' => 'Укажите корректный email'],
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
            Yii::$app->session->setFlash('success', 'Вы подписаны');
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