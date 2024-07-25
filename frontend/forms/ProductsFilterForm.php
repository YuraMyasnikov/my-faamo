<?php 

namespace frontend\forms;

use frontend\models\shop\Categories;
use frontend\models\shop\Products;
use frontend\services\FiltersService;
use yii\data\ActiveDataProvider;

class ProductsFilterForm extends \CmsModule\Shop\frontend\forms\ProductsFilterForm
{

    public $limit = 12;
    public $page = 1;
    public $orderBy = '';
    /** @var FiltersService */
    private $filterService;
    private $_data = [];

    public $sortList = [
        "sort" => 'По умолчанию',
        "price" => 'Цена по возрастанию',
        "-price" => 'Цена по убыванию'
    ];

    public function __construct(FiltersService $filterService, $config = [])
    {
        $this->filterService = $filterService;
        parent::__construct($filterService, $config);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param array $params
     * @return ApiDataProvider|DataProviderInterface
     * @throws InvalidConfigException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function search(array $params = [])
    {
        $this->load($params, '');
        $this->parseRoute($params);
        $this->_data = $this->buildData();

        $dataProvider = $this->buildDataProvider();

        $dataProvider->pagination->pageSize = $this->limit;
        $dataProvider->pagination->defaultPageSize = $this->limit;
        $dataProvider->sort->sortParam = 'orderBy';
        $dataProvider->sort->attributes = [
            'price' => [
                'asc' => 'price',
                'desc' => '-price',
                'label' => 'Цене',
                'active' => in_array($this->orderBy, ['price', '-price']),
            ],
            'creation_time' => [
                'asc' => ['creation_time' => SORT_ASC, 'sort' => SORT_DESC],
                'desc' => ['-creation_time' => SORT_DESC, 'sort' => SORT_DESC],
                'label' => 'По дате поступления',
                'active' => in_array($this->orderBy, ['creation_time', '-creation_time']),
            ],
            'sort' => [
                'asc' => 'sort',
                'desc' => '-sort',
                'label' => 'Популярности',
                'active' => in_array($this->orderBy, ['sort', '-sort']),
            ],
        ];

        return $dataProvider;
    }

    /**
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    private function buildDataProvider()
    {
        
        $parentCategory = null;
        if(!$parentCategory && $this->filter_category) {
            $parentCategory = Categories::findOne(['code' => $this->filter_category]);
        }
        if(!$parentCategory && $this->category) {
            $parentCategory = Categories::findOne(['code' => $this->category->code]);
        }
        if($parentCategory instanceof Categories) {
            $categoryIdList = array_merge(
                Categories::find()->select('id')->where(['parent_id' => $parentCategory->id])->column(),
                [$parentCategory->id]
            );
        } else {
            $categoryIdList = Categories::find()->select('id')->where(['active' => true])->column();
        }

        $result = Products::getFiltered(
            $categoryIdList,
            $this->filters,
            $this->minPrice,
            $this->maxPrice,
            $this->limit,
            $this->page,
            $this->orderBy,
            $this->useCache,
        );

        return new ActiveDataProvider([
            'query' => $result
        ]);
    }

    /**
     * @return array
     * @throws HttpException
     * @throws NotFoundHttpException
     * @todo преобразовать данные во viewmodel
     */
    private function buildData()
    { 
        return $this->filterService->getFilters(
            $this->category?->id ?? null,
            $this->filters,
            $this->minPrice,
            $this->maxPrice,
            $this->orderBy
        );
    }
}