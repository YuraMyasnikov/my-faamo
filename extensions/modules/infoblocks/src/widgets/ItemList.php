<?php

namespace CmsModule\Infoblocks\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;

/**
 * Class ItemList
 * @package CmsModule\Infoblocks\widgets
 */
class ItemList extends Widget
{
    public $code;
    public $filter = [];
    public $filter2 = [];
    public $listOptions = [];
    public $providerOptions = [];

    public function run()
    {
        $class = Infoblock::byCode($this->code);
        $query = $class::find();

        if ($this->filter) {
            $query->andFilterWhere($this->filter);
        }
        if ($this->filter2) {
            $query->andFilterWhere($this->filter2);
        }

        $providerOptions = ArrayHelper::merge([
            'query' => $query,
        ], $this->providerOptions);

        $dataProvider = new ActiveDataProvider($providerOptions);

        $listOptions = ArrayHelper::merge([
            'dataProvider' => $dataProvider,
            'layout' => '{items}'
        ], $this->listOptions);

        $listOptions['itemView'] = $this->view->theme->pathMap['@extensions'] . '/modules/infoblocks/widgets/item-list/' . (array_key_exists('itemView', $listOptions) && $listOptions['itemView'] ? $listOptions['itemView'] : $this->code);

        return ListView::widget($listOptions);
    }
}
