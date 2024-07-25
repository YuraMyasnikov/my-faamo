<?php

namespace CmsModule\Reviews\frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Reviews\frontend\forms\ReviewsForm;
use yii\base\Widget;

class ReviewsListWidget extends Widget
{
    public $limit = 15;
    public $viewFolder = 'reviews-list/';
    public $view = 'index';

    public $reviews;

    public function run()
    {
        if ($this->reviews) {
            return $this->render($this->viewFolder . $this->view, [
                'reviews' => $this->reviews,
            ]);
        }
    }
}