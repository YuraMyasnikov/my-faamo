<?php

use frontend\assets\ShowMoreAsset;
use frontend\services\CityCodeResolver;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$dataProvider->prepare();
$pageCount = $dataProvider->pagination->getPageCount();
$currentPage = $dataProvider->pagination->page + 1;

ShowMoreAsset::register($this);

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>


<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index', 'city' => $cityCodeResolver->getCodeForCurrentCity()]) ?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Личный кабинет</span>
    </div>
</div>
<div class="catalog-page width-default bx-center">
    <h1 class="center-text">Личный кабинет</h1>
    <div class="lk-page js-sticky-row">
        <div class="lk-page-left">
            <h3>История заказов</h3>
            <div class="orders-sort">
                <a href="<?= Url::to(['/account/orders']); ?>"
                   class="orders-sort-item <?= $status_group === null ? 'active' : ''; ?>"
                >
                    Все
                </a>
                <?php foreach ($status_groups as $status_group_item) { ?>
                        <a href="<?= Url::to(['/account/orders', 'status_group' => $status_group_item->id]); ?>"
                           class="orders-sort-item <?= $status_group == $status_group_item->id ? 'active' : ''; ?>"
                        >
                            <?= Html::encode($status_group_item->name); ?>
                        </a>
                <?php } ?>

            </div>

            <div class="js-accordion a-history-wrp">
                <?php
                Pjax::begin(
                    [
                        'id' => 'pjax-container',
                        'timeout' => 6000,
                        'options' => [
                            'class' => 'js-review-pjax-container',
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
                    // $nextLoadHref = '<button class="show-more__button js-catalog-more" type="button" name="button" data-next-page-link="' . Url::to($nextPageLink) . '">Показать еще</button>';
                }

                $layout = '<div class="js-products-container">{items}</div><div class="content-pagination"><div class="pagination reviews width-default bx-center">{pager}</div>';

                if (!$isLastPage && $nextPageLink) {
                    // $layout .= $nextLoadHref;
                }

                $layout .= '</div>';
                $dataProvider->pagination->pageSizeParam = false;
                ?>

                <?php
                try {
                    echo ListView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'emptyText' => 'Заказов нет',
                            'itemView' => function ($model) {
                                return $this->render('_order_item', ['model' => $model]);
                            },
                            'options' => [
                                'tag' => false
                            ],
                            'layout' => $layout,
                            'pager' => [
                                'linkOptions' => ['tag' => false],
                                'linkContainerOptions' => ['class' => 'pagination-wrp-item example_pagination-wrp-item'],
                                'options' => ['tag' => 'div', 'class' => 'pagination-wrp'],
                                'prevPageCssClass' => 'prev',
                                'nextPageCssClass' => 'next',
                                'activePageCssClass' => 'active',
                                'nextPageLabel' => '',
                                'prevPageLabel' => '',
                                'maxButtonCount' => 3,
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

            </div>
        </div>
        <?= $this->render('_nav'); ?>
    </div>
</div>
