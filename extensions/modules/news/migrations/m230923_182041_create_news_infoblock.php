<?php

use CmsModule\Infoblocks\models\InfoblockProperties;
use CmsModule\Infoblocks\models\InfoblockTypes;
use yii\db\Migration;

/**
 * Class m230923_182041_create_news_infoblock
 */
class m230923_182041_create_news_infoblock extends Migration
{
    const IBLOCK_CODE = 'news';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $model = new InfoblockTypes();
        $model->code = self::IBLOCK_CODE;
        $model->name = 'Новости';
        $model->type = 1;
        $model->save();

        $propPhoto = new InfoblockProperties();
        $propPhoto->iblock = $model->id;
        $propPhoto->name = 'Главное изображение';
        $propPhoto->code = 'main_img';
        $propPhoto->type = 3;
        $propPhoto->sort = 0;
        $propPhoto->multi = 0;
        $propPhoto->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Описание';
        $prop->code = 'desc';
        $prop->type = 2;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Текст новости';
        $prop->code = 'text';
        $prop->type = 2;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();
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