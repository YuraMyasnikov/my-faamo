<?php

//use nterms\pagesize\PageSize;
use yii\{helpers\Url, grid\GridView, web\View};
use CmsModule\Infoblocks\admin\widgets\treeview\Widget as TreeView;
use CmsModule\Infoblocks\models\{InvoltaIblockFolders, InfoblockTypes};

/**
 * @var View $this
 * @var InfoblockTypes $itemDataProvider
 */

$this->title = 'Контент';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="iblock content-page">
    <div class="col-12">
        <div>
            <div class="fll content-page--tree">
                <?php
                try {
                    echo TreeView::widget([
                        'items' => InvoltaIblockFolders::getTreeArray(Url::to(['index']), Yii::$app->request->get('folder'))
                    ]);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                ?>
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
                    'dataProvider' => $itemDataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
            <div class="card-body table-responsive p-0">
                <?php try {
                    echo GridView::widget(
                    [
                        'id' => 'seo-list',
                        'dataProvider' => $itemDataProvider,
                        'rowOptions' => static function ($model) {
                            return [
                                'onclick' => 'window.location = "' . Url::to(['iblock', 'code' => $model->code]) . '"'
                            ];
                        },
                        'filterSelector' => 'select[name="per-page"]',
                        'layout' => '{items}{pager}',
                        'emptyTextOptions' => ['tag' => 'p', 'class' => 'text-center text-danger'],
                        'emptyText' => 'По вашему запросу ничего не найдено',
                        'pager' => [
                            'firstPageLabel' => 'В начало',
                            'lastPageLabel' => 'В конец',
                        ],
                        'tableOptions' => ['class' => 'table table-bordered table-striped dataTable'],
                        'options' => ['class' => 'box-body table-responsive no-padding'],
                        'columns' => [
                            'name',
                            'code'
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
                    'dataProvider' => $itemDataProvider,
                    'layout' => '{pager}',
                    'options' => ['class' => 'card-tools']
                ]) ?>
            </div>
        </div>
    </div>
    <script>
        let tabs = document.querySelectorAll('.tree-view-item a');
        tabs.forEach((tab) => { if (!tab.innerHTML) tab.style.display = 'none'});
    </script>
</section>