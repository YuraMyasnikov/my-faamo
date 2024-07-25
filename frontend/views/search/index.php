
<?php

use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$pageCount = $dataProvider->pagination->getPageCount();
$currentPage = $dataProvider->pagination->page + 1;

ShowMoreAsset::register($this);

$this->title = 'ПОИСК ТОВАРОВ ПО ЗАПРОСУ: «' . Html::encode($q) . '»';

$this->params['breadcrumbs'][] = 'Поиск';
?>

<div class="main-page-items width-default bx-center"
    <div class="catalog-wrp">
            <?php
            try {
                echo ListView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'emptyText' => 'Товаров не найдено',
                        'itemView' => function ($model) {
                            return $this->render('_item_search', ['product' => $model]);
                        },
                        'options' => [
                            'tag' => false,
                        ],
                        'layout' => '<div class="category-wrp">{items}</div>
                        <div class="pagination">{pager}</div>',
                        'itemOptions' => [
                            'tag' => false,
                        ],
                        'pager' => [
                            'linkOptions' => ['class' => 'pagination-wrp-item'],
                            'linkContainerOptions' => ['class' => 'pagination-wrp-item'],
                            'options' => ['class' => 'pagination-wrp', 'style' => ['padding' => 0]],
                            'prevPageCssClass' => 'prev',
                            'nextPageCssClass' => 'next',
                            'activePageCssClass' => 'active',
                            'nextPageLabel' => '&nbsp;',
                            'prevPageLabel' => '&nbsp;',
                            'maxButtonCount' => 12,
                        ]
                    ]
                );
            } catch (Exception $e) {
                if (YII_DEBUG) {
                    echo $e->getMessage();
                    Yii::error($e->getMessage());
                } else {
                    echo 'Возникла ошибка';
                }
            }

            ?>
    </div>
</div>