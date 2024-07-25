<?php

use CmsModule\Shop\frontend\helpers\BreadcrumbsHelper;
use frontend\assets\AppAsset;
use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\web\View;

/**
 * @var View $this
 */

$this->title = $category->name;

$breadcrumbsCategories = BreadcrumbsHelper::getTreeParentForCategory($category->id);
unset($breadcrumbsCategories[count($breadcrumbsCategories) - 1]['url']);
$this->params['breadcrumbs'] = $breadcrumbsCategories;

$dataProvider->prepare();
$pageCount = $dataProvider->pagination->getPageCount();
$currentPage = $dataProvider->pagination->page + 1;

ShowMoreAsset::register($this);
?>

<?php
Pjax::begin(
    [
        'id' => 'pjax-container-catalog',
        'timeout' => 6000,
        'options' => [
            'class' => 'js-catalog-pjax-container',
        ]
    ]
);
?>

<?php
$seoPage = Yii::$app->seo->page;
?>

<section class="catalog">
    <div class="container">
    <div class="catalog__content">
        <div class="catalog__filter">
            <button class="catalog__filter-button" type="button"></button>
            <form action="" class="filters-panel">
                <div class="catalog__header">
                    <div class="catalog-info">
                        <div class="catalog-info__title">Найдено</div>
                        <div class="catalog-info__title">товаров: </div>
                        <div class="catalog-count__value"><?= $dataProvider->pagination->totalCount ?></div>
                    </div>
                    <div class="catalog-info">
                        <div class="catalog-info__title">Сортировка:</div>
                        <select class="catalog-sort" name="sort">
                            <?php foreach ($searchModel->sortList as $code => $name) { ?>
                            <option value="<?= Html::encode($code) ?>" <?= $searchModel->orderBy === $code ? 'selected' : ''; ?>><?= Html::encode($name); ?></option>

                                sortList
                            <?php } ?>
                        </select>
                    </div>
                </div>

                    <?= $this->render(
                        'filter',
                        [
                            'model' => $searchModel,
                            'category' => $category
                        ]
                    ); ?>
            </form>
        </div>

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

        $layout = '<div class="catalog__cards js-products-container">{items}</div><div class="content-pagination"><div class="catalog-pagination">{pager}</div>';

        if (!$isLastPage && $nextPageLink) {
            $layout .= $nextLoadHref;
        }

        $layout .= '</div>';
        $dataProvider->pagination->pageSizeParam = false;
        ?>

        <div class="catalog__block">
            <?php
            try {
                echo ListView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'emptyText' => 'Товаров нет',
                        'itemView' => function ($model) {
                            return $this->render('catalog_item', ['product' => $model]);
                        },
                        'options' => [
                            'tag' => false
                        ],
                        'itemOptions' => [
                            'class' => 'catalog__cards-container'
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
        </div>

        <?php $this->registerJsFile('@web/js/filters.js'); ?>
    </div>



</section>

<?php if ($seoPage && $seoPage->large_text) { ?>
<section class="catalog-description">
    <div class="container">
        <div class="catalog-content">
            <div class="catalog-content__text text-block">
                <?= HtmlPurifier::process($seoPage->large_text); ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php Pjax::end(); ?>