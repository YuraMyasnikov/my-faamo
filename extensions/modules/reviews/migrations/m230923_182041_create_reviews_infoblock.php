<?php

use CmsModule\Infoblocks\models\InfoblockProperties;
use CmsModule\Infoblocks\models\InfoblockTypes;
use yii\db\Migration;

/**
 * Class m230923_182041_create_reviews_infoblock
 */
class m230923_182041_create_reviews_infoblock extends Migration
{
    const IBLOCK_CODE = 'reviews';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $model = new InfoblockTypes();
        $model->code = self::IBLOCK_CODE;
        $model->name = 'Отзывы';
        $model->type = 1;
        $model->save();
        
        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'ФИО';
        $prop->code = 'fio';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'E-mail';
        $prop->code = 'email';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Номер заказа';
        $prop->code = 'order_number';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Текст отзыва';
        $prop->code = 'review_text';
        $prop->type = 2;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Оценка';
        $prop->code = 'grade';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();
        
        $propPhoto = new InfoblockProperties();
        $propPhoto->iblock = $model->id;
        $propPhoto->name = 'Фото к отзыву';
        $propPhoto->code = 'photo';
        $propPhoto->type = 3;
        $propPhoto->sort = 0;
        $propPhoto->multi = 1;
        $propPhoto->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $model = InfoblockTypes::find()->where(['code' => self::IBLOCK_CODE])->one();
        foreach ($model->properties as $property) {
            $property->delete();
        }
        $model->delete();
    }        
}