<?php 

namespace frontend\controllers\actions\catalog;

use yii\base\Action;
use Yii;
use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\common\models\OptionItems;
use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\frontend\forms\ProductsFilterForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ViewCatalogAction extends Action 
{
    public function run($filters)
    {

        $route = $this->parseFilters($filters);
        $category = $this->findCategory($route['category']);
        
        /** @var ProductsFilterForm  $searchModel */
        $searchModel = Yii::$container->get(ProductsFilterForm::class, [], ['category' => $category]);
        $dataProvider = $searchModel->search($route['filters']);
        $dataProvider->prepare();
        /*dd($route, $category, $searchModel, $dataProvider, $dataProvider->prepare());*/
        return $this->controller->render('view', [
            'category' => $category,
            'subCategories' => $this->findSubCategories($category->id),
            'dataProvider' => $dataProvider, 
            'searchModel' => $searchModel,
            'sortTitle' => $this->defaultSortTitle($searchModel, $route['filters'])
        ]);
    }

    protected function findCategory(string $categoryCode): Categories 
    {
        $category = Categories::findOne(['code' => $categoryCode, 'active' => true]);
        if (!$category) {
            throw new NotFoundHttpException();
        }

        return $category;
    }

    protected function findSubCategories(int $parentCategoryId): array 
    {
        $sql = "SELECT * FROM module_shop_categories c WHERE c.parent_id = :parent_id AND c.active = 1;";
        $sqlParams = [ ':parent_id' => $parentCategoryId, ];
        return Yii::$app->db->createCommand($sql, $sqlParams)->queryAll();
    }

    protected function defaultSortTitle(ProductsFilterForm $filterForm, array $filters): string 
    {
        $sort = in_array($filters['sort'] ?? null, ['balance_in_stock', '-balance_in_stock', 'title', '-title', 'price', '-price']) ? $filters['sort'] : '-balance_in_stock';
        $defaultTitle = 'По умолчанию';
        foreach($filterForm->sortList as $key => $title) {
            if($sort === $key) {
                $defaultTitle = $title;
            }
        }
        return $defaultTitle;
    }

    protected function parseFilters($route)
    {
        $routeArray = explode('/', $route);

        $category = $routeArray[0];

        $resultFilters = [];
        $request = Yii::$app->request->get();
        if (!empty($routeArray[1])) {
            $filters = explode('-', $routeArray[1]);

            $optionItems = OptionItems::find()->where(['code' => $filters])->all();
            
            foreach ($optionItems as $optionItem) {
                $itemCode = $optionItem->code;
                if (!empty($request[$optionItem->option->code])) {
                    $itemCode = implode(',', [$itemCode, $request[$optionItem->option->code]]);
                }

                $resultFilters[$optionItem->option->code] = $itemCode;
            }
        }

        $resultArray = [
            'category' => $category,
            'filters'  => ArrayHelper::merge($request, $resultFilters),
        ];

        return $resultArray;
    }
}