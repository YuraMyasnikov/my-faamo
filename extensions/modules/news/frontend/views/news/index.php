<?php

use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = 'Новости и статьи';
$this->params['breadcrumbs'][] = 'Новости';

$dataProvider->prepare();
$pageCount = $dataProvider->pagination->getPageCount();
$currentPage = $dataProvider->pagination->page + 1;

ShowMoreAsset::register($this);
?>

<section class="articles-block">
    <?php
    Pjax::begin(
        [
            'id' => 'pjax-container-news',
            'timeout' => 6000,
            'options' => [
                'class' => 'container js-review-pjax-container',
            ]
        ]
    ); ?>
    <?php
    $url = Yii::$app->request->url;
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

    $layout = '<div class="news-block js-products-container">{items}</div><div class="content-pagination"><div class="catalog-pagination">{pager}</div>';

    if (!$isLastPage && $nextPageLink) {
        $layout .= $nextLoadHref;
    }

    $layout .= '</div>';
    $dataProvider->pagination->pageSizeParam = false;
    ?>

    <?php
    try {
        echo ListView::widget(
            [
                'dataProvider' => $dataProvider,
                'emptyText' => 'Новостей нет',
                'itemView' => function ($model) {
                    return $this->render('_elem', ['news' => $model]);
                },
                'options' => [
                    'tag' => false
                ],
                'itemOptions' => [
                    'class' => 'news-cards'
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
    } ?>

    <?php Pjax::end() ?>
</section>