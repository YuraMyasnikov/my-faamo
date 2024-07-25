<?php
namespace CmsModule\Quests\frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;

class FaqWidget extends Widget
{
    const FAQ = 'faq';

    public function run()
    {
        $faqInfoblock = Infoblock::byCode(self::FAQ);

        $faqs = $faqInfoblock::find()->where(['active' => true])->all();

        return $this->render('faq/index', ['faqs' => $faqs]);
    }
}