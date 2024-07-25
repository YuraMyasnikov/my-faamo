<?php

/**
 * @var View $this
 * @var InvoltaIblockFolders $folderDataProvider
 * @var InfoblockTypes $itemDataProvider
 */

use CmsModule\Infoblocks\admin\helpers\UrlHelper;
use CmsModule\Infoblocks\models\{InfoblockTypes, InvoltaIblockFolders};
use yii\{grid\ActionColumn, helpers\Url, grid\GridView, helpers\Html, web\View};

$this->title = 'Типы инфоблоков';
// $this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = 'Типы инфоблоков';
?>

<section class="iblock">
    <div class="col-12">
        <div>
            <div class="fll content-page--tree">

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Папки инфоблоков</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <!--<a class="btn" href="<?/*//= UrlHelper::importInfoblockType() */?>">
                            <span class="plused-text">Импорт инфоблока</span>
                        </a>-->
                        <a class="btn btn-block bg-gradient-info btn-sm" href="<?= UrlHelper::createFolder() ?>">
                            <span class="plused-text">Новая папка</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <?php try {
                    echo GridView::widget(
                        [
                            'id' => 'seo-list',
                            'dataProvider' => $folderDataProvider,
                            'options' => ['class' => 'clickable-table'],
                            'filterSelector' => 'select[name="per-page"]',
                            'layout' => '{items}{pager}',
                            'emptyTextOptions' => ['tag' => 'p', 'class' => 'text-center text-danger'],
                            'emptyText' => 'По вашему запросу ничего не найдено',
                            'pager' => [
                                'firstPageLabel' => 'В начало',
                                'lastPageLabel' => 'В конец',
                            ],
                            'tableOptions' => ['class' => 'table table-bordered table-striped dataTable'],
                            'columns' => [
                                'id',
                                [
                                    'attribute' => 'name',
                                    'format' => 'raw',
                                    'value' => static function ($data) {
                                        return Html::a($data->name, ['index', 'folder' => $data->id]);
                                    }
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => static function ($url, $model) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['delete-folder', 'id' => $model->id]), [
                                                'title' => Yii::t('app', 'Delete'),
                                                'data' => [
                                                    'method' => 'post',
                                                    'confirm' => 'Вы действительно хотите удалить эту папку?',
                                                ]
                                            ]);
                                        },
                                    ],
                                ]
                            ],
                        ]
                    );
                } catch (Exception $e) {
                    echo $e->getMessage();
                } ?>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Типы инфоблоков</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <a class="btn btn-block bg-gradient-info btn-sm" href="<?= UrlHelper::createInfoblockType() ?>">
                            <span class="plused-text">Новый тип</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <?php try {
                    echo GridView::widget(
                        [
                            'id' => 'seo-list',
                            'dataProvider' => $itemDataProvider,
                            'options' => ['class' => 'clickable-table'],
                            'filterSelector' => 'select[name="per-page"]',
                            'layout' => '{items}{pager}',
                            'emptyTextOptions' => ['tag' => 'p', 'class' => 'text-center text-danger'],
                            'emptyText' => 'По вашему запросу ничего не найдено',
                            'pager' => [
                                'firstPageLabel' => 'В начало',
                                'lastPageLabel' => 'В конец',
                            ],
                            'tableOptions' => ['class' => 'table table-bordered table-striped dataTable'],
                            'columns' => [
                                [
                                    'class' => ActionColumn::class,
                                    'template' => '{update}',
                                    'options' => ['width' => 16],
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'template' => '{migrate}',
                                    'buttons' => [
                                        'migrate' => static function ($model) {
                                            return Html::a('<i class="fa fa-list" aria-hidden="true"></i>', $model);
                                        }
                                    ],
                                    'options' => ['width' => 16]
                                ],
                                [
                                    'class' => ActionColumn::class,
                                    'template' => '{delete}',
                                    'options' => ['width' => 16],
                                ],
                                'id',
                                'name',
                                'code',
                            ],
                        ]
                    );
                } catch (Exception $e) {
                    echo $e->getMessage();
                } ?>
            </div>
        </div>
    </div>
</section>