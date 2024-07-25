<?php

namespace CmsModule\Infoblocks\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;

/**
 * Class ItemView
 * @package CmsModule\Infoblocks\widgets
 */
class ItemView extends Widget
{
    public $code;
    public $itemView;
    public $filter = [];

    public function run()
    {
        $class = Infoblock::byCode($this->code);
        $model = $class::findOne($this->filter);
        $itemView = $this->view->theme->pathMap['@extensions'] . '/modules/infoblocks/widgets/item-view/' . ($this->itemView ?: $this->code);

        return $this->render($itemView, ['model' => $model]);
    }
}
