<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Сопутствующие товары';
$this->params['breadcrumbs'][] = $this->title;

\frontend\assets\admin\RelatedCategoriesAsset::register($this);

?>
<div class="row">
    <div class="col-12">
        <div style="margin: 10px 0">
            <?= Html::a('Назад к товару', Url::to(['products/update', 'id' => $product->id]), ['class' => 'btn btn-default']); ?>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $this->title; ?></h3>
            </div>
            <div class="card-body table-responsive p-0">
                <?= GridView::widget(
                    [
                        'id' => 'related-products-list',
                        'dataProvider' => $dataProvider,
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
                                'value'     => function($model) use($product, $relatedCategories) {
                                    /** @var frontend\models\shop\Products $product */
                                    $modelId = $model['id'];
                                    $isChecked = array_search($modelId, $relatedCategories);
                                    if($modelId == $product->category_id) {
                                        $isChecked = true;
                                    }
                                    return Html::checkbox('c['.$modelId.']', $isChecked !== false, [
                                        'class'           => 'related-categories-checkbox',
                                        'disabled'        => $modelId == $product->category_id,
                                        'data-add-url'    => Url::to(['products/related-category-add',    'product_id' => $product->id, 'category_id' => $modelId]),
                                        'data-delete-url' => Url::to(['products/related-category-delete', 'product_id' => $product->id, 'category_id' => $modelId]),
                                    ]);
                                },
                            ],
                            [
                                'attribute' => 'title',
                                'format'    => 'raw',
                                'value'     => function($model) {
                                    $level = intval($model['level'] ?? 0); 
                                    return ($level ? (str_repeat('-', $level) . ' ') : '') . $model['title'];
                                }
                            ],
                        ]
                    ]) ?>
            </div>
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
</div>