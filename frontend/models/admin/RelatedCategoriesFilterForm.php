<?php

namespace frontend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class RelatedCategoriesFilterForm extends Model
{
    
    private array $categories = [];

    public $title;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'safe'],
        ];
    }

    public function search(array $params = [])
    {
        $this->load($params);

        $this->categories(null, null,);
        if(!empty($this->title)) {
            $this->categories = array_filter($this->categories, function($item) {
                return str_contains(mb_strtolower($item['title']) , mb_strtolower($this->title));
            });
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->categories,
            // 'sort' => [
            //     'defaultOrder' => ['created_at' => SORT_DESC]
            // ],
            // 'pagination' => [
            //     'pageSizeLimit' => [20, 50, 100]
            // ],
        ]);
        return $dataProvider;
    }

    private function categories($parentCategoryId, $level): void 
    {
        $query = \frontend\models\shop\Categories::find()->where(['parent_id' => $parentCategoryId]);
        $categories = $query->all();
        if(!count($categories)) {
            return;
        }
        foreach($categories as $category) {
            /** @var \frontend\models\shop\Categories $category */
            $this->categories[$category->id] = [
                'id'    => $category->id,
                'level' => $level,
                'title' => $category->name,
            ];
            $this->categories($category->id, $level+1);
        }
    }

    
}
