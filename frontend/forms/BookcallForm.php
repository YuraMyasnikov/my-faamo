<?php

namespace frontend\forms;

use cms\common\helpers\FormatterHelper;
use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\base\Model;

class BookcallForm extends Model
{
    const TYPE = 'callback';
    const FORM_ACTION = '/site/bookcall';

    public $fio;
    public $phone;
   /* public $email;
    public $comment;*/

    public function rules(): array
    {
        return [
            [['fio', 'phone'], 'required', 'message' => 'Необходимо заполнить'],

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'fio' => 'Ваше имя',
            'phone' => 'Номер телефона',

        ];
    }

    public static function buildForm()
    {
        $form = Yii::$container->get(self::class);

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $profile = $user->profile;
            $form->fio = $profile->fio;
            $form->phone = FormatterHelper::echoPhone($profile->phone);
        }

        return $form;
    }

    public function save()
    {
        $infoblock = Infoblock::byCode(self::TYPE);
        $model = $infoblock::create();
        $model->name = 'Запрос на запись на примерку от ' . $this->fio;
        $model->code = Yii::$app->security->generateRandomString(16);
        $model->fio = $this->fio;
        $model->phone = $this->phone;


        if ($model->save()) {
            return true;
        } else {
            Yii::error($model->getErrors());
            return false;
        }
    }}