<?php

namespace frontend\models\shop\settings;

use yii\base\Model;


class ContactModel extends Model
{
    public string $code = '';
    public string $name = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $working_hours = '';
    public string $ya_map_widget = '';
}

