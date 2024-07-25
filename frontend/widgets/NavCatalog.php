<?php

namespace frontend\widgets;

use CmsModule\Shop\common\models\Categories;
use Yii;
use yii\base\Widget;

class NavCatalog extends Widget
{
    public $view = 'index';
    public $viewFolder = 'nav-catalog/';

    public function run()
    {
        $categories = Categories::find()->where(['active' => true, 'parent_id' => null])->limit(8)->all();
        $data = $this->buildData($categories);

        return $this->render($this->viewFolder . $this->view, ['categories' => $data]);
    }

    protected function buildData(array $categories): array
    {
        $result = [];

        foreach ($categories as $category) {
            $categoryData = [
                'name' => $category->name,
                'url' => '/catalog/' . $category->code,
                'image' => $category->image_id ? Yii::$app->image->getFile($category->image_id) : Yii::$app->image->default(),
                'children' => []
            ];

            foreach ($category->children as $category_child) {
                $categoryData['children'][] = [
                    'name' => $category_child->name,
                    'url' => '/catalog/' . $category_child->code,
                ];
            }

            $result[] = $categoryData;
        }

        return $result;
    }
}