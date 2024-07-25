<?php

namespace CmsModule\Reviews\frontend\forms;

use cms\common\models\Images;
use CmsModule\Infoblocks\models\Infoblock;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;
use Yii;
use yii\base\Model;

class ReviewsForm extends Model
{
    const TYPE = 'reviews';
    const RECAPTCHA_CREATE_ACTION = 'review_create';

    public $fio;
   /* public $order_number;*/
    public $email;
    public $review_text;
    public $grade;
    public $photo;
    /*public $reCaptcha;*/

    public function rules(): array
    {
        return [
            // [['reCaptcha'], ReCaptchaValidator3::class, 'secret' => Yii::$app->reCaptcha->secretV3, 'action' => self::RECAPTCHA_CREATE_ACTION, 'threshold' => 0.5],
            [['fio', 'email', 'review_text', 'grade'], 'required', 'message' => 'Необходимо заполнить'],
            /*[['order_number'], 'number'],*/
            [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 12]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'fio' => 'ФИО',
            'email' => 'Электронная почта',
            /*'order_number' => 'Номер заказа',*/
            'review_text' => 'Текст отзыва',
            'grade' => 'Ваша оценкка: ',
            'photo' => 'Фото'
        ];
    }

    public function uploadPhoto($model)
    {

        foreach ($this->photo as $file) {
            $image_id = Images::uploadFileToDir("content/reviews/{$model->id}/",$file);
            $property = $model->multiProperty(self::TYPE, 'photo');
            $property->content_id = $model->id;
            $property->value = $image_id . $file->type;
            $property->save();
        }
        return true;
    }

    public static function buildForm()
    {
        $form = Yii::$container->get(self::class);

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $profile = $user->profile;
            $form->fio = $profile->fio;
            $form->email = $user->email;
        }

        return $form;
    }

    public function save()
    {
        $infoblock = Infoblock::byCode(self::TYPE);
        $model = $infoblock::create();
        $model->name = 'Новый отзыв от ' . $this->fio;
        $model->code = Yii::$app->security->generateRandomString(16);
        $model->fio = $this->fio;
        $model->email = $this->email;
        /*$model->order_number = $this->order_number;*/
        $model->review_text = $this->review_text;
        $model->grade = $this->grade;
        $model->active = false;
        if ($model->save() && $this->uploadPhoto($model)) {
            return true;
        } else {
            Yii::error($model->getErrors());
            return false;
        }
    }


}