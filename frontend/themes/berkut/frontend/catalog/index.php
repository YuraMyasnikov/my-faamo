<?php

use CmsModule\Shop\common\models\Categories;
use CmsModule\Shop\frontend\forms\ProductsFilterForm;
use frontend\assets\CatalogAsset;
use frontend\assets\FavouriteAsset;
use frontend\models\CatalogPager;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var \yii\web\View $this */
/** @var Categories $category */
/** @var ProductsFilterForm $searchModel */
/** @var ActiveDataProvider $dataProvider */

CatalogAsset::register($this);
FavouriteAsset::register($this);

?>

<?php
Pjax::begin([
    'id' => 'pjax-container-catalog',
    'timeout' => 6000,
    'options' => [
        'class' => 'js-catalog-pjax-container',
    ]
]);
?>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Каталог-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['site/index'])?>">Главная</a></li>
            <li>Весь каталог</li>
        </ul>
    </div>

    <div class="container">

        <h1>Весь каталог</h1>

        <div class="layout">
            <div class="layout-aside">
                <!--🤟 filter-->
                <form action="" class="filters-panel">
                <div class="filter">
                    <div class="filter-content">

                        <div class="filter-close js-filter-button lg-visible">
                            <svg width="16" height="16">
                                <use xlink:href="#icon-close"></use>
                            </svg>
                        </div>

                        <!--👉 filter-items -->
                        <div class="filter-items">

                            <!--👉 filter-category -->
                            <div class="filter-category">
                                <ul class="filter-category__list">
                                    <li><a href="" class="active">Весь каталог</a></li>
                                    <?php foreach($subCategories as $subCategory) { ?>
                                        <li><a href="<?= Url::to(['/catalog/view', 'filters' => $subCategory['code']]) ?>"><?= $subCategory['name'] ?></a></li>
                                    <?php } ?>                                                                
                                </ul>
                            </div>

                            
                            <?php foreach($searchModel->data['catalogs'] as $feature) { ?>
                                <div class="filter-item">
                                    <div class="filter-item__title"><?= $feature['name'] ?></div>    
                                    <ul class="filter-item__list">
                                        <?php foreach($feature['params'] as $param) { ?>
                                            <li>
                                                <label for="<?= Html::encode($param['code']); ?>" class="checkbox-label">
                                                    <input 
                                                        type="checkbox" 
                                                        name="<?= $feature['code'] ?>[]" 
                                                        id="<?= Html::encode($param['code']) ?>"
                                                        value="<?= Html::encode($param['code']) ?>"
                                                        data-catalog-sort="<?= $param['sort'] ?>"
                                                        data-sort="<?= $param['sort'] ?>"
                                                        class="checkbox"
                                                        <?= $param['isChecked'] ? "checked" : "" ?> 
                                                    >
                                                    <span class="checkbox-text"><?= $param['name'] ?></span>
                                                </label>
                                            </li>        
                                        <?php } ?>
                                    </ul>
                                </div>                                
                            <?php } ?>

                            <!---->
                            <div class="filter-footer">
                                <button type="button" class="btn btn--full js-filter-button__ filter-apply ">
                                    Применить фильтр
                                </button>
                                <a href="" class="btn btn--full btn--grey">
                                    <svg width="16" height="16">
                                        <use xlink:href="#icon-cancel"></use>
                                    </svg>
                                    Сбросить значения фильтр
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                </form>

                <div class="offset-top offset--min lg-hidden">
                    <!--👉 Подбор товара-->
                    <div class="choice choice--ver choice--small">
                        <div class="choice-content">
                            <div class="choice-content__title">Подбор товара</div>
                            <div class="choice-content__text">
                                Персональный менеджер проконсультирует по вопросу оптовых закупок,
                                расскажет об особенностях ассортимента, подберёт для вас наилучшее предложение.
                            </div>
                            <button type="button" class="btn btn--full" data-micromodal-trigger="modal-recall">Отправить заявку</button>
                        </div>
                        <div class="choice-image"></div>
                    </div>
                </div>

            </div>
            <div class="layout-content">

                <!--👉 кнопка показать фильтр-->
                <button type="button" class="filter-button lg-visible js-filter-button">
                    <svg width="20" height="20">
                        <use xlink:href="#icon-filter"></use>
                    </svg>
                    Фильтр товаров
                </button>

                <!--👉 catalog-header-->
                <div class="catalog-header">
                    <div class="catalog-header__item sm-hidden">
                        <div>
                            <b><?= $dataProvider->totalCount ?></b> наименований
                        </div>
                    </div>
                    <div class="catalog-header__item">
                        <div class="sm-hidden">Сортировать:</div>
                        <div class="catalog-header__sort" data-popup-link="sort-popup">
                            <span class="sort-active"><?= Html::encode($sortTitle) ?></span>
                            <svg width="9" height="4">
                                <use xlink:href="#icon-arrow-down"></use>
                            </svg>
                        </div>

                        <div class="header-popup header-popup--right" data-popup="sort-popup">
                            <ul class="header-popup__list">
                                <?php foreach ($searchModel->sortList as $code => $name) { ?>
                                    <li><a href="#" class="sort-variant" data-sort="<?= Html::encode($code) ?>"><?= Html::encode($name) ?></a></li>        
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php 
                    /**
                     * Shop product list and pagination
                     */
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_product',
                        'itemOptions' => [
                            'class' => 'column col-4 md-col-6'
                        ],
                        'layout' => '{items}',
                        'options' => [
                            'tag' => 'div',
                            'class' => 'columns columns--element'
                        ],
                    ]);
                ?>

                <div class="pagination">
                    <?php echo CatalogPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'options' => [
                            'tag' => 'div',
                            'class' => 'pagination-items'
                        ],
                        'linkContainerOptions' => [
                            'tag' => 'a',
                            'class' => 'pagination-item'
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!--🔥 КОНЕЦ ШАБЛОНА-->

<?php Pjax::end(); ?>