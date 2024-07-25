<?php

use CmsModule\Infoblocks\models\InfoblockProperties;
use CmsModule\Infoblocks\models\InfoblockTypes;
use yii\db\Migration;

/**
 * Class m231008_191137_create_main_sliders_infoblock
 */
class m231008_191137_create_main_sliders_infoblock extends Migration
{
    const IBLOCK_CODE = 'main_sliders';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $model = new InfoblockTypes();
        $model->code = self::IBLOCK_CODE;
        $model->name = 'Главный слайдер';
        $model->type = 1;
        $model->save();
        
        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Заголовок';
        $prop->code = 'title';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Описание';
        $prop->code = 'description';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Название кнопки';
        $prop->code = 'button_name';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Ссылка кнопки';
        $prop->code = 'button_link';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $propPhoto = new InfoblockProperties();
        $propPhoto->iblock = $model->id;
        $propPhoto->name = 'Банер';
        $propPhoto->code = 'banner_image';
        $propPhoto->type = 3;
        $propPhoto->sort = 0;
        $propPhoto->multi = 0;
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
