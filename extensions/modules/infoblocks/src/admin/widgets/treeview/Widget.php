<?php

namespace CmsModule\Infoblocks\admin\widgets\treeview;

use yii\helpers\{
    ArrayHelper,Html
};
use yii\base\Widget as BaseWidget;
use function implode;
use function is_array;

/**
 * Class Widget
 * @package cms\admin\widgets\treeview
 */
class Widget extends BaseWidget
{
    public $tag = 'ul';
    public $itemTag = 'li';
    public $class = 'tree-view-list';
    public $itemClass = 'tree-view-item';

    public $rootClass = 'root';

    public $items = [];
    public $options = [];

    public function run()
    {
        $baseListClass = is_array($this->class) ? $this->class : [$this->class];
        $class = ArrayHelper::merge($baseListClass, is_array($this->rootClass) ? $this->rootClass : [$this->rootClass]);

        return $this->renderTree($this->items, implode(' ', $class), $this->options);
    }

    private function renderTree($items, $class, $options)
    {
        if (!is_array($options)) {
            $options = [];
        }

        $parentOptions['class'] = $class;
        $parentOptions = ArrayHelper::merge($parentOptions, $options);

        $content = '';
        foreach ($items as $item) {
            $content .= $this->renderItem($item);
        }

        return Html::tag($this->tag, $content, $parentOptions);
    }

    private function renderItem($item)
    {
        $options['class'] = (isset($item['class']) && !empty($item['class'])) ? $item['class'] : $this->itemClass;

        if (isset($item['options']) && !empty($item['options'])) {
            $options = ArrayHelper::merge($options, $item['options']);
        }

        if (isset($item['items']) && is_array($item['items'])) {
            $content = $this->renderTree($item['items'], $this->class, $item['items']['itemOptions'] ?? []);
        } else {
            $content = '';
        }

        if (isset($item['active'])) {
            $options['class'] .= ' active';
        }

        return Html::tag($this->itemTag, $this->renderTemplate($item, $content), $options);
    }

    private function renderTemplate($item, $content)
    {
        if (isset($item['template']) && !empty($item['template'])) {
            $template = $item['template'];
        } else {
            $template = Html::a($item['label'] ?? '', $item['url'] ?? '');
        }

        return $template . $content;
    }
}
