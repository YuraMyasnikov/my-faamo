<?php

namespace CmsModule\News\frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;

class NewsWidget extends Widget
{
    const IBLOCK_TYPE = 'news';

    public $limit = 15;
    public $view = 'index_page';
    public $exceptions_ids = [];
    public $title = 'Новости';

    public function run()
    {
        $infoblock = Infoblock::byCode(self::IBLOCK_TYPE);
        $news = $infoblock::find()->where(['active' => true])->andWhere(['not', ['id' => $this->exceptions_ids]])->limit($this->limit)->all();

        if ($news) {
            return $this->render('news/' . $this->view, ['news' => $news, 'title' => $this->title]);
        }
    }
}