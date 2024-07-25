<?php

use CmsModule\Infoblocks\models\InfoblockProperties;
use CmsModule\Infoblocks\models\InfoblockTypes;
use yii\db\Migration;

/**
 * Class m230923_182041_create_subscribers_infoblock
 */
class m230923_182041_create_subscribers_infoblock extends Migration
{
    const IBLOCK_CODE = 'subscribers';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $model = new InfoblockTypes();
        $model->code = self::IBLOCK_CODE;
        $model->name = 'Подписки на рассылку';
        $model->type = 1;
        $model->save();

        $prop = new InfoblockProperties();
        $prop->iblock = $model->id;
        $prop->name = 'E-mail';
        $prop->code = 'email';
        $prop->type = 1;
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