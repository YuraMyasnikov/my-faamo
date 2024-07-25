<?php

use cms\common\helpers\FormatterHelper;
use cms\extensions\modules\settings\admin\models\SeoPages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use cms\extensions\modules\settings\admin\forms\SeoPagesSearch;
use yii\widgets\MaskedInput;
use CmsModule\Shop\admin\assets\OrderAsset;

/** @var yii\web\View $this */
/** @var SeoPagesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

OrderAsset::register($this);

$request = Yii::$app->request;
?>

<div class="row">
    <div class="col-12">
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
                    'dataProvider' => $dataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
            <div class="card-body table-responsive p-0">
                <?= GridView::widget(
                    [
                        'id' => 'seo-list',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'emptyTextOptions' => ['tag' => 'p', 'class' => 'text-center text-danger'],
                        'emptyText' => 'По вашему запросу ничего не найдено',
                        'filterSelector' => 'select[name="per-page"]',
                        'layout' => '{items}',
                        'pager' => [
                            'firstPageLabel' => 'В начало',
                            'lastPageLabel' => 'В конец',
                        ],
                        'tableOptions' => ['class' => 'table table-bordered table-striped dataTable'],
                        'options' => ['class' => 'box-body table-responsive no-padding'],
                        'columns' => [
                            'id',
                            [
                                'attribute' => 'fio',
                                'header' => "Фио",
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->orderData?->fio ? $model->orderData->fio : '';
                                }
                            ],
                            [
                                'attribute' => 'email',
                                'header' => "E-mail",
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return $model->orderData?->email ? $model->orderData->email : '';
                                }
                            ],
                            [
                                'attribute' => 'phone',
                                'header' => "Телефон",
                                'format' => 'raw',
                                'value' => function ($model) {

                                    return $model->orderData?->phone ? FormatterHelper::echoPhone($model->orderData->phone) : '';
                                }
                            ],
                            [
                                'attribute' => 'status_id',
                                'header' => "Статус",
                                'filter' => $orderStates,
                                'value' => fn($model) => $orderStates[$model->status_id] ?? '',
                            ],
                            [
                                'attribute' => 'created_at',
                                'filter' => '<div style="display: flex">'.Html::input('date', 'filter[from]', $request->get('filter')['from'] ?? null, ['placeholder' => 'От', 'class' => 'form-control',])
                                .'<span style="margin: 2px"></span>'.
                                Html::input('date', 'filter[to]', $request->get('filter')['to'] ?? null, ['placeholder' => 'До', 'class' => 'form-control']).'</div>',
                            ],
                            [
                                'attribute' => 'total_price',
                                'header' => "Итого: <b>" . $totalPrice . "</b>"
                            ],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{update} {send}',
                                'buttons' => [
                                    'view' => function () {
                                    },
                                    'delete' => function () {
                                    },
                                    'send' => function($url, $model, $key) {
                                        $html = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                                                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471z"/>
                                                </svg>';
                                        $url = Url::to(['/admin/shop/orders/send-invoice', 'id' => $model->id]);
                                        return $model->status_id === 1 ? Html::a($html, $url, [
                                            'class' => [ 'send-invoice-action' ]
                                        ]) : '';
                                    },
                                    
                                ],
                                'header' => 'Действия'
                            ],
                            
                        ],
                    ]
                ) ?>
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
                    'dataProvider' => $dataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
        </div>
    </div>
</div>