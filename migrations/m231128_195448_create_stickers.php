<?php

use CmsModule\Shop\common\models\OptionItems;
use CmsModule\Shop\common\models\Options;
use yii\db\Migration;

/**
 * Class m231128_195448_create_stickers
 */
class m231128_195448_create_stickers extends Migration
{
    const OPTION_CODE = 'sticker';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $option = Yii::$container->get(Options::class);
        $option->name = 'Стикер';
        $option->code = self::OPTION_CODE;
        $option->type = 1;
        $option->active = 1;
        $option->filter = 1;
        $option->multi = 1;
        $option->save();

        $optionItem = Yii::$container->get(OptionItems::class);
        $optionItem->option_id = $option->id;
        $optionItem->name = 'Хит';
        $optionItem->code = 'hit';
        $optionItem->active = 1;
        $optionItem->save();

        $optionItem = Yii::$container->get(OptionItems::class);
        $optionItem->option_id = $option->id;
        $optionItem->name = 'Новинка';
        $optionItem->code = 'new';
        $optionItem->active = 1;
        $optionItem->save();

        $optionItem = Yii::$container->get(OptionItems::class);
        $optionItem->option_id = $option->id;
        $optionItem->name = 'Акция';
        $optionItem->code = 'discount';
        $optionItem->active = 1;
        $optionItem->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Options::deleteAll(['code' => self::OPTION_CODE]);
    }
}
