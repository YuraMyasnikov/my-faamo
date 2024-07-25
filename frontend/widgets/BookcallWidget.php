<?php

namespace frontend\widgets;

use frontend\forms\BookcallForm;
use yii\base\Widget;

class BookcallWidget extends Widget
{
    public $view = 'modal_form';

    public $viewFolder = 'bookcall/';

    public function run()
    {
        $bookcallForm = BookcallForm::buildForm();

        return $this->render($this->viewFolder . $this->view, ['bookcallForm' => $bookcallForm]);
    }
}