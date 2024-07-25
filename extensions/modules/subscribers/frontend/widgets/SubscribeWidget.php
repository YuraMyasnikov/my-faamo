<?php

namespace CmsModule\Subscribers\frontend\widgets;

use CmsModule\Subscribers\frontend\forms\SubscribersForm;
use yii\base\Widget;

class SubscribeWidget extends Widget
{
    public $view = 'alfafooter';
    public $viewFolder = 'subscribe/';

    public function run()
    {
        $subscribeForm = SubscribersForm::buildForm();

        return $this->render($this->viewFolder . $this->view, ['subscribeForm' => $subscribeForm]);
    }
}