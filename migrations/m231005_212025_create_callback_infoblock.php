<?php

use CmsModule\Infoblocks\models\InfoblockProperties;
use CmsModule\Infoblocks\models\InfoblockTypes;
use yii\db\Migration;

/**
 * Class m231005_212025_create_callback_infoblock
 */
class m231005_212025_create_callback_infoblock extends Migration
{
    const IBLOCK_CODE = 'callback';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $model = new InfoblockTypes();
        $model->code = self::IBLOCK_CODE;
        $model->name = 'Обратный звонок';
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
        $prop->name = 'Номер телефона';
        $prop->code = 'phone';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Почта';
        $prop->code = 'email';
        $prop->type = 1;
        $prop->sort = 0;
        $prop->multi = 0;
        $prop->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'Комментарий';
        $prop->code = 'comment';
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
