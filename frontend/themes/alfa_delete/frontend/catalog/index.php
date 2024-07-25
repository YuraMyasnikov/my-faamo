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
/*dd($category, $subCategories, $dataProvider,$searchModel, $searchModel->data['catalogs'], $sortTitle);*/
?>



<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Каталог</span>
    </div>
    <div class="catalog-page">
        <h1>Мужские свадебные костюмы</h1>
        <div class="description">
            <div class="description-top">
                <p>Независимо от того, будете ли вы в центре свадебной вечеринки или будете сидеть сзади, наш
                    выбор
                    мужских свадебных костюмов прикроет вашу спину независимо от дресс-кода. От костюмов-двойок
                    в
                    стиле лаунж до роскошного черного галстука, от обычного кроя до более строгих узких силуэтов
                    — в
                    нашем ассортименте свадебных мужских костюмов вы сможете выглядеть соответствующе всего за
                    несколько кликов.</p>
            </div>
            <div class="description-links">
                <?php foreach($searchModel->data['catalogs'] as $catalog): ?>
                    <?php foreach ($catalog['params'] as $param) :?>
                    <a href="<?php /*= Url::to(['/catalog/view',"{$catalog['code']}/{$param['code']}"])*/?>"
                       class="link-underline">
                        <?= $param['name']?>
                    </a>
                    <?php endforeach;?>
                <?php endforeach;?>
                <!--<a href="#" class="link-underline">Костюмы для женихов</a>
                <a href="#" class="link-underline">Костюмы жениха</a>
                <a href="#" class="link-underline">Свадебные смокинги</a>
                <a href="#" class="link-underline">Свадебные рубашки</a>
                <a href="#" class="link-underline">Свадебные галстуки</a>
                <a href="#" class="link-underline">Свадебные аксессуары</a>
                <a href="#" class="link-underline">Свадебная обувь</a>-->
            </div>
        </div>
    </div>
</div>
<div class="filter-wrp">
    <div class="bx-center width-full">
        <div class="filters">
            <div class="filters-left">
                <div class="filters-left-all">Все фильтры</div>
                    <div class="filters-left-item fsize">
                        <div class="dropdown filter" tabindex="1">
                            <div class="select radius">4</div>
                            <ul class="dropdown-menu radius">
                                <li class="checkbox-item">
                                    <input type="checkbox" id="fsize1" />
                                    <label for="fsize1">5</label>
                                </li>
                            </ul>
                        </div>
                    </div>

                <div class="filters-left-item ">
                    <div class="dropdown filter" tabindex="1">
                        <div class="select radius">Соответствовать</div>
                        <ul class="dropdown-menu radius">
                            <li class="checkbox-item">
                                <input type="checkbox" id="sootv1" />
                                <label for="sootv1">Название чекбокса</label>
                            </li>
                            <li class="checkbox-item">
                                <input type="checkbox" id="sootv2" />
                                <label for="sootv2">Название чекбокса</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="filters-left-item color">
                    <div class="dropdown filter" tabindex="1">
                        <div class="select radius">Цвет</div>
                        <ul class="dropdown-menu radius">
                            <li class="checkbox-item">
                                <input type="checkbox" id="color1" />
                                <label for="color1">Белый</label>
                            </li>
                            <li class="checkbox-item">
                                <input type="checkbox" id="color2" />
                                <label for="color2">Чёрный</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="filters-left-item brand">
                    <div class="dropdown filter" tabindex="1">
                        <div class="select radius">Бренд</div>
                        <ul class="dropdown-menu radius">
                            <li class="checkbox-item">
                                <input type="checkbox" id="brand1" />
                                <label for="brand1">Название чекбокса</label>
                            </li>
                            <li class="checkbox-item">
                                <input type="checkbox" id="brand2" />
                                <label for="brand2">Название чекбокса</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="filters-left-item textile">
                    <div class="dropdown filter" tabindex="1">
                        <div class="select radius">Ткань</div>
                        <ul class="dropdown-menu radius">
                            <li class="checkbox-item">
                                <input type="checkbox" id="textile1" />
                                <label for="textile1">Название чекбокса</label>
                            </li>
                            <li class="checkbox-item">
                                <input type="checkbox" id="textile2" />
                                <label for="textile2">Название чекбокса</label>
                            </li>
                            <li class="checkbox-item">
                                <input type="checkbox" id="textile3" />
                                <label for="textile3">Название чекбокса</label>
                            </li>
                            <li class="checkbox-item">
                                <input type="checkbox" id="textile4" />
                                <label for="textile4">Название чекбокса</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="filters-right">
                <div class="filters-right-count"><?= $dataProvider->totalCount ?> позиции</div>
                <div class="dropdown sort select" tabindex="1">
                    <div class="select radius">Сортировка</div>
                    <input type="hidden" name="seo-update" value="По популярности">
                    <ul class="dropdown-menu radius">
                        <li id="По популярности">По популярности</li>
                        <li id="По цене возрастанию">По цене возрастанию</li>
                        <li id="По цене убыванию">По цене убыванию</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-page-items width-default bx-center">
    <div class="category-wrp d-flex" >
        <!--<div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item01.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item02.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>-->
        <?php
        try {
            echo ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'emptyText' => 'Товаров нет',
                    'itemView' => function ($model) {
                        return $this->render('catalog_item', ['product' => $model]);
                    },
                    /*'options' => [
                        'div' => ['class' => 'category-wrp yura']
                    ],
                    'itemOptions' => [
                        'class' => 'catalog__cards-container'
                    ],*/
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
        <!--<div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item03.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item04.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item05.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item06.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item07.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item08.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>
        <div class="category-item">
            <div class="category-item-img">
                <a href="item.html"><img src="images/item09.jpg" alt="Костюм тройка бежевый в клетку"></a>
            </div>
            <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                            клетку</span></a>
            <div class="category-item-price">
                17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
            </div>
        </div>-->
    </div>
</div>
<div class="pagination width-default bx-center">
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
</div>
<div class="seo-text width-less bx-center">
    <div class="seo-links">
        <a href="#" class="link-underline">Свадебные костюмы для гостей</a>
        <a href="#" class="link-underline">Костюмы для женихов</a>
        <a href="#" class="link-underline">Костюмы жениха</a>
        <a href="#" class="link-underline">Свадебные смокинги</a>
        <a href="#" class="link-underline">Свадебные рубашки</a>
        <a href="#" class="link-underline">Свадебные галстуки</a>
        <a href="#" class="link-underline">Свадебные аксессуары</a>
        <a href="#" class="link-underline">Свадебная обувь</a>
        <a href="#" class="link-underline">Свадебные костюмы для гостей</a>
        <a href="#" class="link-underline">Костюмы для женихов</a>
        <a href="#" class="link-underline">Костюмы жениха</a>
        <a href="#" class="link-underline">Свадебные смокинги</a>
        <a href="#" class="link-underline">Свадебные рубашки</a>
        <a href="#" class="link-underline">Свадебные галстуки</a>
        <a href="#" class="link-underline">Свадебные смокинги</a>
        <a href="#" class="link-underline">Свадебные рубашки</a>
        <a href="#" class="link-underline">Свадебные галстуки</a>
        <a href="#" class="link-underline">Свадебные аксессуары</a>
        <a href="#" class="link-underline">Свадебная обувь</a>
    </div>

    <h4>Alfa Collection – интернет-магазин мужских классических костюмов</h4>
    <p>Добро пожаловать в интернет-магазин Alfa Collection! Здесь есть повседневные и торжественные комплекты
        для мужчин. У нас вы найдете свой образ. Наша одежда – удобный вариант как для повседневной носки, так и
        для более формальных мероприятий.</p>
    <h5>Купить классические мужские костюмы в Санкт-Петербурге</h5>
    <p>В Alfa Collection есть костюмы двойки, тройки, дополнения к ним, повседневные и праздничные комбинации, а
        также:</p>
    <p>Одежда для свадьбы, выпускного, повседневной носки. Наша одежда изготовлена из прочных и дышащих
        материалов. Это значит, что носка будет комфортной, а одежда будет выглядеть как новая. Наши комплекты
        надевают для официальных вечеров, деловых встреч, других случаев, когда требуется строгий дресс-код.</p>
    <p>Разнообразие стилей, моделей. В Alfa Collection есть классика, другие виды одежды, такие как смокинги,
        пальто, парки, рубашки, брюки, аксессуары. Есть различные стили, модели, чтобы каждый подобрал одежду,
        которая соответствует его вкусам.</p>
    <p>Обновления каталога. Мы постоянно обновляем каталог, следим за тенденциями в моде. Наша команда стилистов
        составит уникальный образ, создаст стиль.</p>
    <h5>Купить мужской классический костюм с аксессуарами</h5>
    <p>Но взять костюм без аксессуаров - это приобрести только половину образа. Аксессуары играют важную роль
        при подготовке к вечеру. Они сделают из простого образа завершенную композицию.</p>
    <p>В Alfa Collection есть аксессуары для создания лука на любой вечер – деловой или встречу с друзьями.
        Например:</p>
    <p>Галстук или бабочка. Они могут добавить яркости, индивидуальности вашему образу. В магазине есть
        галстуки, бабочки различных цветов, фактур, чтобы жених, выпускник мог найти аксессуар для своего
        комплекта. Наши галстуки, бабочки изготавливаются из прочных материалов. Они выдерживают стирку и не
        задерживают на себе пятна. А плотная ткань сохраняет форму даже при активной подвижности.</p>
    <p>Ремни. Это функциональный аксессуар, элемент стиля. В интернет-магазине есть ремни разных фактур, цветов,
        материалов, чтобы найти подходящий. Наши ремни сделаны из качественной кожи. Это долговечность, удобная
        посадка брюк, джинсов. Кожа выдерживает стирку на интенсивных режимах и высоких температурах, не теряет
        форму, цвет и плотность.</p>
    <h5>Классические молодежные костюмы для мужчин с дополнениями</h5>
    <p>В нашем магазине есть и другие комплекты:</p>
    <p>Смокинги, которые надевают для торжественных мероприятий. Смокинг можно надеть на свадьбу, день рождения,
        выпускной или встречу в компании друзей.</p>
    <p>Парки, пальто, которые защитят вас от холодной погоды. Плотные ткани выдерживают холод, дышат, легко
        отстирываются вручную и в стиральных машинах.</p>
</div>

    <!--<div class="bg-gray">
        <div class="popular-category width-default bx-center">
            <h2>Категории</h2>
            <div class="category-wrp popular">
                <?php /*foreach ($subCategories as $category):*/?>
                    <div class="category-item <?php /*= $category['id'] == 1 ? 'full' : ''*/?>">
                        <div class="category-item-img">
                            <a href="<?php /*=  Url::to(["/catalog/{$category['code']}", 'filters' => $category['code']]) */?>">
                                <img src="<?php /*= Url::to(["@web/uploads/category/{$category['id']}/{$category['image_id']}.jpg"])*/?>" alt="<?php /*= $category['name']*/?>">
                            </a>
                        </div>
                        <a href="<?php /*= Url::to(["/catalog/{$category['code']}", 'filters' => $category['code'] ]) */?>" class="category-item-name link-line">
                            <span><?php /*= $category['name']*/?></span></a>
                    </div>
                <?php /*endforeach;*/?>
            </div>
        </div>
    </div>-->

