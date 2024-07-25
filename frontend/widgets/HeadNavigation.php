<?php

namespace frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;
use yii\db\ActiveRecord;

class HeadNavigation extends Widget
{
    const CODE = 'catalog';

    public function run()
    {
        /** @var ActiveRecord $mainSliderInfoblock */
        $mainSliderInfoblock = Infoblock::byCode(self::CODE);

        $categories = $mainSliderInfoblock::find()
            ->where(['active' => true])
            ->andWhere(['is_show_in_head_navigation' => 1])
            ->orderBy("sort DESC")
            ->all();

        $subCategories = $mainSliderInfoblock::find()
            ->where(['active' => true])
            ->andWhere(['is_show_in_head_sub_navigation' => 1])
            ->orderBy("sort DESC")
            ->limit(2)
            ->all();

        return $this->render('top-navigation/index', ['categories' => $categories, 'subCategories' => $subCategories]);
    }
}