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
            <li><?= $category->name ?></li>
        </ul>
    </div>

    <div class="container">

        <h1><?= $category->name ?></h1>

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
                                    <li><a href="" class="active">Вся <?= mb_strtolower($category->name) ?></a></li>
                                    <?php foreach($subCategories as $subCategory) { ?>
                                        <li><a href="<?= Url::to(['/catalog/view', 'filters' => $subCategory['code']]) ?>"><?= $subCategory['name'] ?></a></li>
                                    <?php } ?>                                                                                                    
                                </ul>
                            </div>
                            <?php if(false) { ?>
                                <div class="filter-block__radio">
                                    <div class="filter-list__row">
                                        <input class="filter-radio" type="radio" id="pile1" name="filter_category" checked="<?= $category->code === $searchModel->filter_category || $searchModel->filter_category === null; ?>" value="<?= Html::encode($category->code); ?>">
                                        <label class="filter-radio__label" for="pile1">Все</label>
                                    </div>
                                    <?php foreach ($subCategories as $subCategory) { ?>
                                        <div class="filter-list__row">
                                            <input class="filter-radio" type="radio" id="<?= $subCategory['code'] ?>" <?= $subCategory['code'] === $searchModel->filter_category ? 'checked' : ''; ?> name="filter_category" value="<?= Html::encode($subCategory['code']); ?>">
                                            <label class="filter-radio__label" for="<?= $subCategory['code'] ?>"><?= Html::encode($subCategory['code']); ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            
                            <?php foreach($searchModel->data['catalogs'] as $feature) { ?>
                                <div class="filter-item">
                                    <div 
                                        class="filter-item__title"
                                        id="<?= $feature['code'] ?>" 
                                        data-friendly="<?= $feature['friendly_url']; ?>"
                                        >
                                            <?= $feature['name'] ?>
                                    </div>    
                                    <ul class="filter-item__list">
                                        <?php foreach($feature['params'] as $param) { ?>
                                            <li>
                                                <label for="<?= Html::encode($param['code']); ?>" class="checkbox-label">
                                                    <input 
                                                        type="checkbox" 
                                                        name="<?= $feature['code'] ?>" 
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
                        'emptyText' => '<h2>Тут пока пусто.</h2>',
                        'emptyTextOptions' => [
                            'class' => 'column',
                        ],
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
                
                <!--👉 seo-->
                <!-- <div class="offset-top">
                    <div class="text">
                        <h2>В BERKUT.IV можно недорого купить спецодежду от производителя</h2>
                        <p>СЕО-блок. Добро пожаловать в интернет-магазин спецодежды компании «BERKUT.IV»! Мы
                            производим высокопрочную одежду и обувь для работы, охоты, рыбалки, туризма.
                            Работаем с 2008 года. Наши предприятия базируются в Ивановской области — в городах
                            Иваново и Вичуга. Работаем с физическими и юридическими лицами. Продукция
                            представлена в магазинах по всей России, а также на маркетплейсах — Wildberries,
                            Ozon, Aliexpress.</p>
                        <h3>Ежемесячно мы выпускаем 5000 костюмов и 4000 пар обуви. В ассортименте нашего
                            бренда</h3>
                        <ol>
                            <li>Летняя спецодежда</li>
                            <li>Зимняя спецодежда</li>
                            <li>Демисезонные комплекты</li>
                            <li>Куртки</li>
                            <li>Обувь</li>
                            <li>Фурнитура</li>
                        </ol>
                        <h3>В интернет-магазине BERKUT.IV можно купить спецодежду с доставкой по всей
                            России</h3>
                        <p>Приобрести продукцию «БЕРКУТ.ИВ» удобно на нашем сайте. К каждому товару приложены
                            фотографии с описанием. Весь ассортимент продаем поштучно. Оптовые цены действуют на
                            заказы от 10000 рублей. Предоплата для оптовых покупателей — 100 %. Подробнее о том,
                            как совершить, оплатить и получить заказ, читайте на нашем сайте в разделах
                            «Доставка», а также «Оплата и условия».</p>
                        <ul>
                            <li>В числе наших клиентов — крупные сервисные и производственные компании.
                                Спецодежда «БЕРКУТ.ИВ» — это прекрасный вариант униформы для предприятий.
                            </li>
                            <li>Одежда защитит рыбаков и охотиков от холода, пыли, влаги, грязи, механических
                                воздействий.
                            </li>
                            <li>Обратите внимание, мы не только выпускаем продукцию под своим брендом, но также
                                выполняем индивидуальные заказы для партнеров. Под заказ мы создаем вещи с
                                корпоративной символикой и фирменными знаками.
                            </li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

</div>

<!--🔥 КОНЕЦ ШАБЛОНА-->

<?php Pjax::end(); ?>