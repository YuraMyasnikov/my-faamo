<?php

namespace frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;
use yii\db\ActiveRecord;

class WelcomeCategories extends Widget
{
    const CODE = 'catalog';

    public function run()
    {
        /** @var ActiveRecord $mainSliderInfoblock */
        $mainSliderInfoblock = Infoblock::byCode(self::CODE);

        $categories = $mainSliderInfoblock::find()
            ->where(['active' => true])
            ->andWhere(['is_show_in_main_navigation' => 1])
            ->orderBy("sort DESC")
            ->all();

        return $this->render('welcome-categories/index', ['categories' => $categories]);
    }
}