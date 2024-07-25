<?php

namespace frontend\forms;

use cms\common\models\User;
use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $verifyNewPassword;

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'verifyNewPassword'], 'required'],
            [['newPassword'], 'match', 'pattern' => '/[\d\w\W]{6,}/', 'message' => 'В пароле должно быть 6 и более символов.'],
            [['verifyNewPassword'], 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Введенные пароли не совпадают.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'verifyNewPassword' => 'Повторите новый пароль'
        ];
    }

    public function save()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);

        if (!$user || !$user->validatePassword($this->currentPassword)) {
            return false;
        }

        $user->setPassword($this->newPassword);
        $user->generateAuthKey();
        return $user->save();
    }
}