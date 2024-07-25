<?php

namespace frontend\models\shop;

use CmsModule\Shop\common\helpers\UniqueAttributeValidator;
use CmsModule\Shop\common\models\Categories as ShopCategories;


class Categories extends ShopCategories
{
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['code'], UniqueAttributeValidator::class],
            [['parent_id'], 'integer'],
            [['active'], 'boolean'],
            [['description', 'seo_links', 'image_id'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'parent_id'   => 'Родительская категория',
            'name'        => 'Название',
            'code'        => 'Код',
            'description' => 'Описание',
            'seo_links'   => 'Перелинковка',
            'active'      => 'Активность',
        ];
    }
}