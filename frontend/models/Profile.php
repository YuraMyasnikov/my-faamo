<?php

namespace frontend\models;

use cms\common\models\Profile as ModelsProfile;
use cms\common\validators\PhoneValidator;

class Profile extends ModelsProfile
{
    public function scenarios()
    {
        return [
            self::SCENARIO_INDIVIDUAL => ['name', 'surname', 'patronymic', 'phone', 'zip', 'city', 'address'],
        ];
    }

    public function rules(): array
    {
        return [
            [['phone', 'email', 'password', 'verifyPassword'], 'required', 'message' => 'Не заполнено поле {attribute}', 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['name'], 'match', 'pattern' => '/^[А-Яа-я\-]+$/ui', 'message' => 'Имя должно содержать только русские буквы.', 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['surname'], 'match', 'pattern' => '/^[А-Яа-я]+$/ui', 'message' => 'Фамилия должна содержать только русские буквы.', 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['patronymic'], 'match', 'pattern' => '/^[А-Яа-я]+$/ui', 'message' => 'Отчество должно содержать только русские буквы.', 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['city'], 'match', 'pattern' => '/^[0-9\-А-Яа-я ]+$/ui', 'message' => 'Город должен содержать только русские буквы.', 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['address'], 'match', 'pattern' => '/^[0-9\-А-Яа-я.,\s]+$/ui', 'message' => 'Адрес должен содержать только русские буквы.', 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['phone'], PhoneValidator::class, 'on' => [self::SCENARIO_INDIVIDUAL]],
            [['zip'], 'match', 'pattern' => '/^[0-9]{6}/i', 'message' => 'Индекс должен содержать 6 цифр.', 'on' => [self::SCENARIO_INDIVIDUAL]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'     => 'Имя', 
            'surname'  => 'Фамилия', 
            'patronymic' => 'Отчество', 
            'phone'    => 'Контактный номер', 
            'zip'      => 'Индекс', 
            'city'     => 'Город', 
            'address'  => 'Адрес', 
        ];
    }
}