<?php

use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\Url;
/*use yii\widgets\LinkPager;*/
use yii\bootstrap5\LinkPager;
use yii\widgets\Pjax;
use CmsModule\Reviews\frontend\widgets\ReviewsListWidget;

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = 'Отзывы';

$totalRiews = $countGrades[0]['count'];

if($count){
    $count_1 = 0;
    $count_2 = 0;
    $count_3 = 0;
    $count_4 = 0;
    $count_5 = 0;

    $percent_1 = 0;
    $percent_2 = 0;
    $percent_3 = 0;
    $percent_4 = 0;
    $percent_5 = 0;

    foreach ($count as $item){
        for($i=1; $i<=5; $i++){
            if($item['grade'] == $i){
                ${'count_' . $i} = $item['count'];
                ${'percent_' . $i} = (${'count_' . $i} / $totalRiews) * 100;
                ${'percent_' . $i} = round(${'percent_' . $i},0);
            }
        }
    }

}

ShowMoreAsset::register($this);

?>

    <div class="bx-center width-full">
        <div class="breadcrumbs">
            <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
            <span>Отзывы</span>
        </div>
    </div>
    <div class="catalog-page width-default bx-center">
        <h1 class="center-text">Отзывы</h1>
        <div class="cart-page reviews js-sticky-row">
            <div class="cart-page-left">

                <?= ReviewsListWidget::widget([
                    'reviews' => $reviews
                ])?>

                <div class="pagination reviews width-default bx-center">
                    <?= LinkPager::widget([
                        'pagination' => $pages,
                        'options' => ['class' => 'pagination-wrp'],
                        'linkOptions' => ['class' => 'pagination-wrp-item'],
                        'prevPageLabel' => '&nbsp;',
                        'nextPageLabel' => '&nbsp;',
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'prevPageCssClass' => 'prev',
                        'nextPageCssClass' => 'next',
                        'maxButtonCount' => 5,
                        'pageCssClass' => 'pagination-wrp-item',
                        'linkContainerOptions' => ['class' => 'pagination-wrp-item']
                    ]); ?>
                </div>
               <!-- <div class="pagination reviews width-default bx-center">
                    <div class="pagination-wrp">
                        <a href="#" class="pagination-wrp-item prev">&nbsp;</a>
                        <a href="#" class="pagination-wrp-item">1</a>
                        <span href="#" class="pagination-wrp-item active">2</span>
                        <a href="#" class="pagination-wrp-item">3</a>
                        <a href="#" class="pagination-wrp-item">4</a>
                        <a href="#" class="pagination-wrp-item">5</a>
                        <a href="#" class="pagination-wrp-item">...</a>
                        <a href="#" class="pagination-wrp-item next">&nbsp;</a>
                    </div>
                </div>-->
            </div>

            <div class="cart-page-right">
                <div class="review-page-right js-sticky-box" data-margin-top="30" data-margin-bottom="30">
                    <div class="review-page-right-total"><?= $countGrades[0]['avg']?? 0 ?></div>
                    <div class="review-page-right-item">
                        <div class="review-page-right-stars">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                        </div>
                        <div class="review-page-right-line">
                            <span class="review-page-right-line-fill" style="width:<?= $percent_5 ?? 0?>%;"></span>
                        </div>
                        <div class="review-page-right-num"><?= $percent_5 ?? 0?>%</div>
                    </div>
                    <div class="review-page-right-item">
                        <div class="review-page-right-stars">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-gray.svg">
                        </div>
                        <div class="review-page-right-line">
                            <span class="review-page-right-line-fill" style="width: <?= $percent_4 ?? 0?>%;"></span>
                        </div>
                        <div class="review-page-right-num"><?= $percent_4 ?? 0?>%</div>
                    </div>
                    <div class="review-page-right-item">
                        <div class="review-page-right-stars">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-gray.svg">
                            <img src="/images/icon-star-gray.svg">
                        </div>
                        <div class="review-page-right-line">
                            <span class="review-page-right-line-fill" style="width: <?= $percent_3 ?? 0?>%;"></span>
                        </div>
                        <div class="review-page-right-num"><?= $percent_3 ?? 0?>%</div>
                    </div>
                    <div class="review-page-right-item">
                        <div class="review-page-right-stars">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-gray.svg">
                            <img src="/images/icon-star-gray.svg">
                            <img src="/images/icon-star-gray.svg">
                        </div>
                        <div class="review-page-right-line">
                            <span class="review-page-right-line-fill" style="width: <?= $percent_2 ?? 0?>%;"></span>
                        </div>
                        <div class="review-page-right-num"><?= $percent_2 ?? 0?>%</div>
                    </div>
                    <div class="review-page-right-item">
                        <div class="review-page-right-stars">
                            <img src="/images/icon-star-black.svg">
                            <img src="/images/icon-star-gray.svg">
                            <img src="/images/icon-star-gray.svg">
                            <img src="/images/icon-star-gray.svg">
                            <img src="/images/icon-star-gray.svg">
                        </div>
                        <div class="review-page-right-line">
                            <span class="review-page-right-line-fill" style="width: <?= $percent_1 ?? 0?>%;"></span>
                        </div>
                        <div class="review-page-right-num"><?= $percent_1 ?? 0?>%</div>
                    </div>
                    <div class="review-btn send-review-button btn-bg full black radius">Оставить свой отзыв</div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-bg send-review-button-popup" role="alert">
        <div class="popup-bx-center">
            <div class="close popup-close"></div>
            <div class="popup-title">Оставить отзыв</div>

            <?= $this->render('form', ['modal' => $modal]) ?>
        </div>
    </div>
<!--<section class="reviews">
    <div class="container">
        <div class="reviews__wrap">
            <div class="reviews__list">
                <?php
/*                Pjax::begin(
                    [
                        'id' => 'pjax-container',
                        'timeout' => 6000,
                        'options' => [
                            'class' => 'js-review-pjax-container',
                            'data' => ['controller' => 'ReloaderController'],
                        ]
                    ]
                ); */?>
                <?php
/*                $url = Yii::$app->request->url;
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

                $layout = '<div class="js-products-container">{items}</div><div class="content-pagination"><div class="catalog-pagination">{pager}</div>';

                if (!$isLastPage && $nextPageLink) {
                    $layout .= $nextLoadHref;
                }

                $layout .= '</div>';
                $dataProvider->pagination->pageSizeParam = false;
                */?>

                <?php
/*                try {
                    echo ListView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'emptyText' => 'Отзывов нет',
                            'itemView' => function ($model) {
                                return $this->render('_elem', ['review' => $model]);
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
                } */?>

                <?php /*Pjax::end() */?>
            </div>
            <div class="reviews__rate">
                <div class="reviews__rate-title"><?php /*= number_format($avgStarsCount, 2); */?></div>
                <div class="rate-list">
                    <div class="rate-list__row">
                        <div class="rate-list__stars">
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                        </div>
                        <div class="rate-list__line">
                            <div class="rate-list__line-active"></div>
                        </div>
                        <div class="rate-list__count"><?php /*= $fiveStarsCount; */?></div>
                    </div>
                    <div class="rate-list__row">
                        <div class="rate-list__stars">
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star"></span>
                        </div>
                        <div class="rate-list__line">
                            <div class="rate-list__line-active"></div>
                        </div>
                        <div class="rate-list__count"><?php /*= $fourStarsCount; */?></div>
                    </div>
                    <div class="rate-list__row">
                        <div class="rate-list__stars">
                            <span class="icon-star active active"></span>
                            <span class="icon-star active active"></span>
                            <span class="icon-star active active"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <div class="rate-list__line">
                            <div class="rate-list__line-active"></div>
                        </div>
                        <div class="rate-list__count"><?php /*= $threeStarsCount; */?></div>
                    </div>
                    <div class="rate-list__row">
                        <div class="rate-list__stars">
                            <span class="icon-star active"></span>
                            <span class="icon-star active"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <div class="rate-list__line">
                            <div class="rate-list__line-active"></div>
                        </div>
                        <div class="rate-list__count"><?php /*= $twoStarsCount; */?></div>
                    </div>
                    <div class="rate-list__row">
                        <div class="rate-list__stars">
                            <span class="icon-star active"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <div class="rate-list__line">
                            <div class="rate-list__line-active"></div>
                        </div>
                        <div class="rate-list__count"><?php /*= $oneStarsCount; */?></div>
                    </div>
                </div>
                <button id="review-popup" class="reviews__button btn">Оставить свой отзыв</button>
            </div>
        </div>
    </div>
</section>-->

<?php /*= $this->render('form'/*, ['reviewForm' => $reviewForm]); */?>