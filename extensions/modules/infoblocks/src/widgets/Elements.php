<?php

namespace CmsModule\Infoblocks\widgets;

use CmsModule\Infoblocks\base\IblockListWidget;

/**
 * Class Elements
 * @package CmsModule\Infoblocks\widgets
 */
class Elements extends IblockListWidget
{
    public function viewFolder()
    {
        return $this->iblock;
    }
}
