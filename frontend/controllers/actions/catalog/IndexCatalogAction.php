<?php 

namespace frontend\controllers\actions\catalog;

use CmsModule\Shop\frontend\forms\ProductsFilterForm;
use yii\base\Action;
use Yii;
use CmsModule\Shop\common\models\Categories;


class IndexCatalogAction extends Action 
{
    public function run()
    {
        // dd('controller index');
        $filters = Yii::$app->request->get();

        /** @var ProductsFilterForm  $searchModel */
        $searchModel = Yii::$container->get(ProductsFilterForm::class, [], []);
        $dataProvider = $searchModel->search($filters);
        $dataProvider->prepare();

        return $this->controller->render('index', [
            'category'      => null, 
            'subCategories' => $this->findSubCategories(),
            'dataProvider'  => $dataProvider, 
            'searchModel'   => $searchModel,
            'sortTitle'     => $this->defaultSortTitle($searchModel, $filters),
        ]);
    }

    protected function findSubCategories(): array 
    {
        return Categories::find()
            ->with(['icon'])
            ->where(['parent_id' => null])->asArray()->all();
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
}