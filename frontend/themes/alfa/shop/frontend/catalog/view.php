<?php

use CmsModule\Shop\frontend\helpers\BreadcrumbsHelper;
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
$seoPage = Yii::$app->seo->page;

\frontend\assets\CatalogAsset::register($this);

?>

<?php if(1) { ?>
<style>
    @media screen and (max-width: 768px) {
        .filters-left-item-hide-mobile { display: none !important; }
    }
</style>
<?php } ?>  


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

    <div class="bx-center width-full">
        <div class="breadcrumbs">
            <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
            <a href="<?= Url::to(['/shop/frontend/catalog/index'])?>" class="breadcrumbs-item link-line"><span>Каталог</span></a>
            <span><?= $category->name?></span>
        </div>
        <div class="catalog-page">
            <h1><?= !empty($seoPage->h1) ? $seoPage->h1 : $category->name ?></h1>
            <div class="description">
                <div class="description-top">
                    <p><?= $category->description ?></p>
                </div>
                <div class="description-links">
                    
                </div>
            </div>
        </div>
    </div>

    <form action="" class="filters-panel">
        <div class="filter-wrp">
            <div class="bx-center width-full">
                <div class="filters">
                    <div class="filters-left">
                        <div class="filters-left-all">Все фильтры</div>
                        <?php 
                            $filterTabIndex = 1;
                        ?>
                        <?php foreach($searchModel->data['catalogs'] as $feature) { ?>
                            <?php 
                                $featureName   = $feature['name'] ?? '';
                                $featureCode   = $feature['code'] ?? '';
                                $featureParams = is_array($feature['params'] ?? null) ? $feature['params'] : [];
                            ?>     
                              
                            <div class="filters-left-item filters-left-item-hide-mobile">
                                <div class="dropdown filter" tabindex="<?= $filterTabIndex ?>">
                                    <div class="select radius"><?= $featureName ?></div>
                                    <ul class="dropdown-menu radius">
                                        <?php foreach ($featureParams as $param) { ?>
                                            <?php
                                                $htmlId1 = Html::encode('f' . '_' .$param['code'] . '_' . $param['id']);    
                                                $htmlId2 = Html::encode('m' . '_' .$param['code'] . '_' . $param['id']);    
                                            ?>
                                            <li class="checkbox-item">
                                                <input 
                                                    type="checkbox"  
                                                    name="<?= $feature['code'] ?>" 
                                                    id="<?= $htmlId2 ?>"
                                                    class="filter-checkbox"
                                                    value="<?= Html::encode($param['code']) ?>"
                                                    data-catalog-sort="<?= $param['sort'] ?>"
                                                    data-sort="<?= $param['sort'] ?>"
                                                    data-related="<?= $htmlId1 ?>"

                                                    <?= $param['isChecked'] ? "checked" : "" ?> 
                                                />
                                                <label for="<?= $htmlId2 ?>"><?= $param['name'] ?></label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <?php 
                                $filterTabIndex ++;
                            ?>
                        <?php } ?>   
                    </div>
                    <div class="filters-right">
                        <div class="filters-right-count"><?= $dataProvider->pagination->totalCount ?> позиции</div>
                        <div class="dropdown sort select" tabindex="1">
                            <div class="select radius">Сортировка</div>
                            <input type="hidden" name="sort" value="<?= $_GET['sort'] ?? null ?>">
                            <ul class="dropdown-menu radius">
                                <?php foreach ($searchModel->sortList as $sortMode => $sortTitle) { ?>
                                    <li class="sort-variant" data-sort="<?= $sortMode ?>"><?= Html::encode($sortTitle); ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="main-page-items width-default bx-center">
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
                        'layout' => '<div class="category-wrp">{items}</div><div class="pagination width-default bx-center">{pager}</div>',
                        'itemOptions' => [
                            'tag' => false,
                        ],
                        'summary' => '',
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
                    // dd($e);
                    echo $e->getMessage();
                    Yii::error($e->getMessage());
                } else {
                    echo 'Возникла ошибка';
                }
            } 
        ?>
    </div>

    <?php Pjax::end(); ?>

    

    <div class="seo-text width-less bx-center">
        <?php if($category->seo_links) { ?>
        <div class="seo-links">
            <?= $category->seo_links ?>
        </div>
        <?php } ?>

        <?= ($seoPage?->large_text) ?>
    </div>

    <div class="popup-bg filter-popup" role="alert">
        <div class="popup-bx-right">
            <div class="close popup-close"></div>
            <div class="popup-bx-right-clear link-line"><span>Очистить</span></div>
            <div class="popup-bx-right-title">Фильтры</div>
            <div class="popup-bx-right-info">
                <div class="js-accordion filter">
                    <?php foreach($searchModel->data['catalogs'] as $feature) { ?>
                        <?php 
                            $featureName   = $feature['name'] ?? '';
                            $featureCode   = $feature['code'] ?? '';
                            $featureParams = is_array($feature['params'] ?? null) ? $feature['params'] : [];
                        ?>
                        <div class="js-accordion-item a-filter-item">
                            <div class="js-accordion-header a-filter-item-header"><?= $featureName ?></div>
                            <div class="js-accordion-body a-filter-item-body cols2" style="display: none;">
                                <?php foreach ($featureParams as $param) { ?>
                                    <?php
                                        $htmlId1 = Html::encode('f' . '_' .$param['code'] . '_' . $param['id']);    
                                        $htmlId2 = Html::encode('m' . '_' .$param['code'] . '_' . $param['id']);    
                                    ?>
                                    <div class="checkbox-item col50">
                                        <input 
                                            type="checkbox" 
                                            name="<?= $feature['code'] ?>" 
                                            value="<?= Html::encode($param['code']) ?>"
                                            id="<?= $htmlId1 ?>" 
                                            class="filter-checkbox"
                                            data-catalog-sort="<?= $param['sort'] ?>"
                                            data-sort="<?= $param['sort'] ?>"
                                            data-related="<?= $htmlId2 ?>"
                                            <?= $param['isChecked'] ? "checked" : "" ?> 
                                        />
                                        <label for="<?= $htmlId1 ?>"><?= $param['name'] ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>   
                </div>
            </div>
        </div>
    </div>
