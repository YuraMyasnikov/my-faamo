<?php

use frontend\models\shop\Products;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Сопутствующие товары';
$this->params['breadcrumbs'][] = $this->title;

\frontend\assets\admin\RelatedProductsAsset::register($this);

?>
<div class="row">
    <div class="col-12">
        <div style="margin: 10px 0">
            <?= Html::a('Назад к товару', Url::to(['products/update', 'id' => $product->id]), ['class' => 'btn btn-default']); ?>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $this->title; ?></h3>
                <?= GridView::widget([
                    'pager' => [
                        'maxButtonCount' => 7,
                        'firstPageLabel' => 'Начало',
                        'lastPageLabel' => 'Конец',
                        'options' => ['class' => 'pagination pagination-sm float-right'],
                        'linkOptions' => ['class' => 'page-link'],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'disabledListItemSubTagOptions' => ['class' => 'page-link disabled'],
                    ],
                    'dataProvider' => $productsDataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
            <div class="card-body table-responsive p-0">
                <?= GridView::widget(
                    [
                        'id' => 'related-products-list',
                        'dataProvider' => $productsDataProvider,
                        'emptyTextOptions' => ['tag' => 'p', 'class' => 'text-center text-danger'],
                        'emptyText' => 'По вашему запросу ничего не найдено',
                        'filterModel' => $searchModel,
                        'filterSelector' => 'select[name="per-page"]',
                        'layout' => '{items}',
                        'pager' => [
                            'firstPageLabel' => 'В начало',
                            'lastPageLabel'  => 'В конец',
                        ],
                        'tableOptions' => ['class' => 'table table-bordered table-striped dataTable'],
                        'options' => ['class' => 'box-body table-responsive no-padding'],
                        'columns' => [
                            [
                                'attribute' => 'checkbox',
                                'format'    => 'raw',
                                'label'     => '',
                                'value'     => function($model) use($product, $relatedProductsIds) {
                                    $isChecked = array_search($model->id, $relatedProductsIds);

                                    return Html::checkbox('p['.$model->id.']', $isChecked !== false, [
                                        'class' => 'related-product-checkbox',
                                        'data-add-url'    => Url::to(['products/related-product-add',    'base_product_id' => $product->id, 'related_product_id' => $model->id]),
                                        'data-delete-url' => Url::to(['products/related-product-delete', 'base_product_id' => $product->id, 'related_product_id' => $model->id]),
                                    ]);
                                },
                            ],
                            [
                                'attribute' => 'articul',
                            ],
                            [
                                'attribute' => 'name',
                                'format'    => 'raw',
                            ],
                        ]
                    ]) ?>
            </div>
            <div class="card-footer clearfix">
                <?= GridView::widget([
                    'pager' => [
                        'maxButtonCount' => 7,
                        'firstPageLabel' => 'Начало',
                        'lastPageLabel' => 'Конец',
                        'options' => ['class' => 'pagination pagination-sm m-0 float-left'],
                        'linkOptions' => ['class' => 'page-link'],
                        'linkContainerOptions' => ['class' => 'page-item'],
                        'disabledListItemSubTagOptions' => ['class' => 'page-link disabled'],
                    ],
                    'dataProvider' => $productsDataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
        </div>
    </div>
</div>