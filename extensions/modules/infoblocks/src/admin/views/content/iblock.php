<?php

use CmsModule\Infoblocks\admin\helpers\UrlHelper;
use nterms\pagesize\PageSize;
use yii\{grid\ActionColumn,
    helpers\Url,
    grid\GridView,
    helpers\Html,
    helpers\ArrayHelper,
    data\ActiveDataProvider,
    web\View,
    widgets\ActiveForm};
use CmsModule\Infoblocks\models\Infoblock;
use cms\common\{models\InvoltaSites, Core};
use yii\bootstrap5\LinkPager;

/**
 * @var View $this
 * @var string $code
 * @var Infoblock $filterModel
 * @var ActiveDataProvider $dataProvider
 */

$this->title = 'Инфоблок «' . $filterModel->infoblockType->name . '»';
$this->params['breadcrumbs'][] = [
    'label' => 'Инфоблоки',
    'url' => '/admin/infoblocks/content'
];
$this->params['breadcrumbs'][] = $this->title;
$request = Yii::$app->request;
?>

<script>
    function updateIblockElement(event, id, code) {
        if (event.target.className.indexOf("glyphicon-trash") >= 0)
            return false;
        window.location.href = "<?=Url::to(['update'])?>?id=" + id + "&code=" + code + '&back=' + encodeURIComponent(window.location.href);
    }
</script>

<div class="row">
    <div class="col-12">
        <div style="margin: 10px 0">
            <div class="buttons-block">
                <a class="btn btn-info"
                   href="<?= Url::to(['create', 'code' => $code, 'back' => urlencode($request->absoluteUrl)]) ?>">
                    <span class="plused-text">Новый элемент</span>
                </a>

                <a class="btn btn-primary"
                   href="<?= UrlHelper::exportContent($code) ?>">
                    <span class="plused-text">Выгрузить в excel</span>
                </a>

                <!-- <a class="btn btn-primary"
                   href="<?//= Url::to(['import', 'code' => $code, 'back' => urlencode($request->absoluteUrl)]) ?>">
                    <span class="plused-text">Загрузить из excel</span>
                </a> -->
            </div>
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
                    'dataProvider' => $dataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
            <div class="card-body table-responsive p-0">
                <?php try {
                echo GridView::widget(
                    [
                        'id' => 'grid-info-items',
                        'dataProvider' => $dataProvider,
                        'layout' => '{summary}{items}{pager}',
                        'pager' => [
                            'class' => LinkPager::class
                        ],
                        'filterModel' => $filterModel,
                        'filterSelector' => '.filter-switch',
                        'emptyTextOptions' => ['tag' => 'p', 'class' => 'text-center text-danger'],
                        'emptyText' => 'По вашему запросу ничего не найдено',
                        'tableOptions' => ['class' => 'table table-bordered table-striped dataTable'],
                        'options' => ['class' => 'box-body table-responsive no-padding clickable-table'],
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'filter' => Html::textInput('filter[name]', $filterModel->name, ['placeholder' => 'Поиск по названию', 'class' => 'form-control']),
                                'format' => 'raw',
                                'value' => static function ($data) {
                                    return Html::a($data->name, ['update', 'id' => $data->id, 'code' => Yii::$app->request->get('code'), 'back' => '']);
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'filter' => '<div style="display: flex">'.Html::input('date', 'filter[from]', $request->get('filter')['from'] ?? null, ['placeholder' => 'От', 'class' => 'form-control',])
                                    .'<span style="margin: 2px"></span>'.
                                    Html::input('date', 'filter[to]', $request->get('filter')['to'] ?? null, ['placeholder' => 'До', 'class' => 'form-control']).'</div>',
                                'value' => static function ($model) {
                                    return (new DateTime($model->created_at))->format('d.m.Y H:i:s');
                                },
                                'contentOptions' => ['class' => 'text-center'],
                                'headerOptions' => ['style' => 'max-width: 200px'],
                            ],
                            // [
                            //     'attribute' => 'site_id',
                            //     'value' => static function ($model) {
                            //         return InvoltaSites::findOne($model->site_id)->name;
                            //     },
                            //     'filter' => Html::dropDownList('filter[site_id]', $filterModel->site_id,
                            //         ArrayHelper::merge([null => 'Все'], Core::getUserSites()
                            //         ),
                            //         ['class' => 'filter-switch'])
                            // ],
                            [
                                'attribute' => 'sort',
                                'value' => static function ($model) {
                                    return $model->sort;
                                },
                                'filter' => Html::textInput('filter[sort]', $filterModel->sort, ['class' => 'form-control']),
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'attribute' => 'active',
                                'value' => static function ($model) {
                                    return (int)$model->active === 1 ? 'Да' : 'Нет';
                                },
                                'filter' => Html::dropDownList('filter[active]', $filterModel->active, [null => 'Все', 1 => 'Да', 2 => 'Нет'], ['class' => 'form-control']),
                                'contentOptions' => ['class' => 'text-center'],
                            ],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => static function ($url, $model) {
                                        return Html::a('<i class="fas fa-trash"></i>', Url::to(['delete', 'id' => $model->id, 'code' => Yii::$app->request->get('code'), 'back' => urlencode(Yii::$app->request->absoluteUrl)]), [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить элемент?',
                                                'method' => 'post'
                                            ],
                                        ]);
                                    }
                                ]
                            ]
                        ],
                    ]
                );
                } catch (Exception $e) {
                    echo $e->getMessage();
                } ?>
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
<script>
    function updateIblockElement(event, id, code) {
        if (event.target.className.indexOf("glyphicon-trash") >= 0)
            return false;
        window.location.href = "<?=Url::to(['update'])?>?id=" + id + "&code=" + code + '&back=' + encodeURIComponent(window.location.href);
    }
</script>