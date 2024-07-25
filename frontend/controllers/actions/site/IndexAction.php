<?php 

namespace frontend\controllers\actions\site;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Reviews\frontend\forms\ReviewsForm;
use frontend\models\shop\Products;
use frontend\widgets\ProductCardWidget;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\ListView;

class IndexAction extends Action 
{
    public function run()
    {
        return $this->controller->render('index', [
            'listViewContentForNewProduct' => $this->listViewContentForNewProduct($this->dataProviderForNewProducts()),
            'listViewContentForPopularProduct' => $this->listViewContentForNewProduct($this->dataProviderForPopularProducts()),
            'showcaseDataProvider' => $this->showcaseDataProvider(),
            'reviews' => $this->reviews(),
        ]);
    }

    protected function listViewContentForNewProduct(ActiveDataProvider $activeDataProvider): string 
    {
        return ListView::widget([
            'dataProvider' => $activeDataProvider,
            'emptyText' => 'Товаров нет',
            'itemView' => function ($model) {
                  return ProductCardWidget::widget([
                      'id'      => $model->id,
                      'url'     => Url::to(['/product/view', 'code' => $model->code]),
                      'price'   => $model->sku[0]->price,
                      'code'    => $model->code,
                      'articul' => $model->articul,
                      'name'    => $model->name,
                      'isActive'=> $model->active,
                  ]);
            },
            'itemOptions' => [
               'class' => 'column col-3 md-col-6'
            ],
            'options' => [
                  'tag' => 'div',
                  'class' => 'layout-content'
            ],
            'layout' => '<div class="columns columns--element">{items}</div>',
         ]);
    }

    protected function dataProviderForNewProducts(): ActiveDataProvider 
    {
        $query = Products::getQueryProductsActive()->orderBy(['sort' => SORT_DESC])
            ->leftJoin(['mo' => 'module_shop_products_multi_options'], 'mo.product_id = product.id')
            ->leftJoin(['oi' => 'module_shop_option_items'], 'oi.id = mo.option_item_id')
            ->andWhere("oi.code = 'new'")
            ->orderBy(['sort' => SORT_DESC])
            ->limit(8);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 8,
                'page'     => 0
            ],
        ]);
    }

    protected function dataProviderForPopularProducts(): ActiveDataProvider 
    {
        $query = Products::getQueryProductsActive()
            ->leftJoin(['mo' => 'module_shop_products_multi_options'], 'mo.product_id = product.id')
            ->leftJoin(['oi' => 'module_shop_option_items'], 'oi.id = mo.option_item_id')
            ->andWhere("oi.code = 'hit'")
            ->orderBy(['sort' => SORT_DESC])
            ->limit(4);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 4,
                'page'     => 0
            ],
        ]);
    }

    protected function showcaseDataProvider(): ActiveDataProvider 
    {
        $infoblock = Infoblock::byCode('main_sliders');
        $productReviews = $infoblock::find()->where(['active' => 1])->orderBy(['sort' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $productReviews,
            'pagination' => [
                'pageSize' => 15,
                'page'     => 0
            ],
        ]);
    }    

    protected function reviews(int $page = 1, $countOnPage = 10): array
    {
        $offset = ($page * $countOnPage) - $countOnPage;

        /** @var \yii\db\ActiveRecord $infoBlock */
        $infoBlock = Infoblock::byCode(ReviewsForm::TYPE);
        $reviews = $infoBlock::find()
            ->where(['active' => true])
            ->andWhere(['<', 'grade', 6])
            ->limit($countOnPage)
            ->offset($offset)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $reviews;
    }
}