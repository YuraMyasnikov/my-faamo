<?php

namespace frontend\models\shop;

use cms\common\models\Images;
use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\base\Model;

class ProductReviewsForm extends Model
{
    const TYPE = 'product_reviews';
    
    public $fio;
    public $product_id;
    public $email;
    public $review_text;
    public $grade;
    public $photo;

    public function rules(): array
    {
        return [
            [['fio', 'email'], 'required', 'message' => 'Необходимо заполнить'],
            [['product_id'], 'number'],
            [['grade'], 'required', 'message' => 'Поставте оценку'],
            [['photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 3],
            [['review_text'], 'safe']
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'fio' => 'ФИО',
            'email' => 'Электронная почта',
            'product_id' => 'Продукт',
            'review_text' => 'Текст отзыва',
            'grade' => 'Ваша оценкка: ',
            'photo' => 'Фото'
        ];
    }

    public function uploadPhoto($model)
    {
        foreach ($this->photo as $file) {
            $image_id = Images::uploadFileToDir("/content/product_reviews/{$model->id}/",$file);
            //$image_id = Images::uploadFile($file);
            $property = $model->multiProperty(self::TYPE, 'image');
            $property->content_id = $model->id;
            $property->value = $image_id;
            $property->save();
        }
        return true;
    }

    public function save()
    {
        $infoblock = Infoblock::byCode(self::TYPE);
        $model = $infoblock::create();
        $model->name = 'Новый отзыв от ' . $this->fio;
        $model->code = Yii::$app->security->generateRandomString(16);
        $model->fio = $this->fio;
        $model->email = $this->email;
        $model->product_id = $this->product_id;
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