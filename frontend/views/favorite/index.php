<?php

use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = 'Избранное';

$pageCount = $dataProvider->pagination->getPageCount();
$currentPage = $dataProvider->pagination->page + 1;

ShowMoreAsset::register($this);
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
                    return $this->render('_elem', ['favorite_element' => $model]);
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

<!--<div class="container">

    <?php
/*    $url = Yii::$app->request->url;
    $nextPageLink = false;
    if ($currentPage < $pageCount) {
        if (stristr($url, 'page=') !== false) {
            $nextPageLink = str_replace('page=' . $currentPage, 'page=' . ($currentPage + 1), $url);
        } elseif (stristr($url, '?') !== false) {
            $nextPageLink = $url . '&page=' . ($currentPage + 1);
        } else {
            $urlArr = explode('?', $url);
            $urlArr[0] .= '?page=' . ($currentPage + 1);
            $nextPageLink = implode('?', $urlArr);
        }
    }

    $isLastPage = $dataProvider->pagination->page + 1 === $dataProvider->pagination->pageCount;
    $nextLoadHrefContainer = Html::tag('div', '', ['class' => 'catalog-more']);

    if (!$isLastPage && $nextPageLink) {
        $nextLoadHref = '<button class="show-more__button js-catalog-more" type="button" name="button" data-next-page-link="' . Url::to($nextPageLink) . '">Показать еще</button>';
    }

    $layout = '<div class="catalog__cards js-products-container">{items}</div><div class="content-pagination"><div class="catalog-pagination">{pager}</div>';

    if (!$isLastPage && $nextPageLink) {
        $layout .= $nextLoadHref;
    }

    $layout .= '</div>';
    $dataProvider->pagination->pageSizeParam = false;
    */?>

    <div class="catalog__block">
        <?php
/*        try {
            echo ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'emptyText' => 'Товаров нет',
                    'itemView' => function ($model) {
                        return $this->render('_elem', ['favorite_element' => $model]);
                    },
                    'options' => [
                        'tag' => false
                    ],
                    'layout' => $layout,
                    'pager' => [
                        'linkOptions' => ['class' => 'pagination__link'],
                        'linkContainerOptions' => ['class' => 'pagination__item'],
                        'options' => ['class' => 'pagination'],
                        'prevPageCssClass' => 'prev-link',
                        'nextPageCssClass' => 'next-link',
                        'activePageCssClass' => 'current',
                        'nextPageLabel' => '',
                        'prevPageLabel' => '',
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
        } */?>
    </div>
</div>-->