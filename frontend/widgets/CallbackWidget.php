<?php

namespace frontend\widgets;

use frontend\forms\CallbackForm;
use yii\base\Widget;

class CallbackWidget extends Widget
{
    public $view = 'modal_form';
    public $viewFolder = 'callback/';

    public function run()
    {
        $callbackForm = CallbackForm::buildForm();

        return $this->render($this->viewFolder . $this->view, ['callbackForm' => $callbackForm]);
    }
}