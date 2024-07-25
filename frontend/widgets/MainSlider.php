<?php

namespace frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;

class MainSlider extends Widget
{
    const MAIN_SLIDER_CODE_IB = 'main_sliders';

    public function run()
    {
        $mainSliderInfoblock = Infoblock::byCode(self::MAIN_SLIDER_CODE_IB);

        $sliders = $mainSliderInfoblock::find()->where(['active' => true])->orderBy(['sort' => SORT_DESC])->all();

        return $this->render('main-slider/index', ['sliders' => $sliders]);
    }
}