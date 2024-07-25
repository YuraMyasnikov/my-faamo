<?php

namespace frontend\models;

use cms\common\validators\PhoneValidator;
use yii\db\ActiveRecord;

class OrderData extends ActiveRecord
{
    public static function tableName()
    {
        return 'module_shop_order_data';
    }

    public function rules()
    {
        return [
            [['fio', 'email', 'phone'], 'required'],
            [['fio', 'email', 'phone'], 'string', 'max' => 255],
            [['phone'], PhoneValidator::class],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
        ];
    }
}