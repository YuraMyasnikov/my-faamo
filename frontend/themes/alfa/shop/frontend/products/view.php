<?php

use frontend\models\shop\viewModels\ProductViewModel;
use yii\helpers\Html;
use frontend\services\CityCodeResolver;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;


/**
 * @var Products $product
 * @var Categories $category
 * @var ProductViewModel $productViewModel
 */

 \frontend\assets\ProductAsset::register($this);

$h1         = $productViewModel->seoH1;
$skus       = $productViewModel->availableSkus;
$firstSkuId = array_key_first(array_filter($skus, function($item) { return $item['is_filled'] ?? false === true; }));
$firstSku   = $skus[$firstSkuId] ?? null;
$currentColorCode = $firstSku['options']['color'][0] ?? null;
// $allSizesForCurrentColor = array_values(
//     array_unique(
//         array_column(
//             array_map(
//                 function($sku) {
//                     return is_array($sku['options']['size'] ?? null) ? $sku['options']['size'] : [];
//                 }, 
//                 array_filter($skus, function($sku) use($currentColorCode) {
//                     $skuColors = isset($sku['options']['color']) && is_array($sku['options']['color']) ? $sku['options']['color'] : [];
//                     return in_array($currentColorCode, $skuColors); 
//                 })
//             ), 0
//         )
//     )
// );
[$total, $average, $iterations] = $productViewModel->reviewStatistics;
$mainCategory = $productViewModel->category;
// dd($productViewModel->relatedProductsDataProvider->models);

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);
?>

<script 
    id="data" 
    data-skus='<?= Json::encode($skus) ?>'
    data-options='<?= Json::encode($productViewModel->availableSkusOptions) ?>'
    />
</script>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <a href="<?= Url::to(["/shop/frontend/catalog/view", "filters" => "", "city" => $cityCodeResolver->getCodeForCurrentCity()]) ?>" class="breadcrumbs-item link-line"><span>Каталог</span></a>
        <?php if($mainCategory) { ?>
            <a 
                href="<?= Url::to(["/shop/frontend/catalog/view", "filters" => $mainCategory->code, "city" => $cityCodeResolver->getCodeForCurrentCity()]) ?>" 
                class="breadcrumbs-item link-line"
            >
                <span><?= $mainCategory->name ?></span>
            </a>
        <?php } ?>
        <span><?= $product->name ?></span>
    </div>
</div>
<div class="width-default bx-center">
    <div class="item-page js-sticky-row">
        <div class="item-page-left">
            <div class="swiper-wrapper item-page-left-slider">
                <?php foreach ($productViewModel->images as $image) { ?>
                <div class="swiper-slide item-page-left-img">
                    <a href="<?= $image['file'] ?>" class="radius" data-fancybox="catalog-item-gallery" title="<?= $product->name ?>">
                        <img src="<?= $image['file'] ?>" alt="<?= $product->name ?>" title="<?= $product->name ?>"/>
                    </a>
                </div>
                <?php } ?>
            </div>
            <div class="item-next"></div>
            <div class="item-prev"></div>
        </div>
        <div class="item-page-right">
            <div class="item-page-right-wrp js-sticky-box" data-margin-top="30" data-margin-bottom="30">
                <h1><?= $h1 ?></h1>
                <div class="item-page-right-wrp-top">
                    <?php if($productViewModel->firstSku) { ?>
                    <div class="item-page-right-wrp-top-price">
                        <?= $productViewModel->firstSku->price ?> ₽
                    </div>
                    <?php } else { ?>
                        <div class="item-page-right-wrp-top-price">
                            <span>Нет в наличии</span>    
                        </div>
                    <?php } ?>    
                    <div class="item-page-right-wrp-top-article">Артикул: <span><?= $productViewModel->product->articul ?></span></div>
                </div>
                <?php if(count($productViewModel->availableSkusOptions['color']['options'] ?? [])) { ?>
                <div class="item-page-right-wrp-colors item">
                    <?php foreach($productViewModel->availableSkusOptions['color']['options'] as $code => $color) { ?>
                        <div 
                            class="item-page-right-wrp-colors-item item <?php if($currentColorCode === $code) { ?>selected<?php } ?> js-color" 
                            style="background: <?= $code ?>;"
                            data-color-code="<?= $code ?>"
                        ></div>
                    <?php } ?>
                </div>
                <?php } ?>

                <?php if($productViewModel->firstSku) { ?>
                <div class="dropdown select size">
                    <div class="select radius">
                        <span data-default-title="Выберите размер">Выберите размер</span>
                    </div>
                    <input type="hidden" name="item-size">
                    
                    <ul class="dropdown-menu radius">
                    </ul>
                </div>
                <?php } ?>
                
                <div class="item-page-right-wrp-buttons">
                    <div class="item-page-right-wrp-buttons-add">
                        <button 
                            type="button" 
                            class="btn-bg full black radius js-btn-add-basket"
                            <?php if(!$productViewModel->firstSku) { ?>
                                disabled="disabled"
                            <?php } ?>    
                        >Добавить в корзину</button>
                    </div>
                    <div
                        role="button"
                        class="item-page-right-wrp-buttons-favorite <?= Yii::$app->favorite->isFavorite($product->id) ? 'active' : ''; ?>"
                        data-url-add="<?= Url::to(['/shop/api/favorite/add-product', 'product_id' => $product->id]) ?>"
                        data-url-remove="<?= Url::to(['/shop/api/favorite/delete-product', 'product_id' => $product->id]) ?>"
                    >
                    </div>
                </div>
                <div class="item-page-right-wrp-size razmer border-b">Подберите свой точный размер</div>
                <div class="item-page-right-wrp-desc">
                    <p>Артикул: <?= $productViewModel->product->articul ?></p>
                    <p><?= $productViewModel->product->description ?></p>
                </div>
                <?php if(false) { ?>
                <div class="item-page-right-wrp-size nalichie">Наличие в магазинах</div>
                <?php } ?>
                <div class="item-page-right-wrp-size obmeri">Обмеры изделия</div>
                <div class="item-page-right-wrp-size sostav">Состав и уход</div>
                <?php if(!empty($productViewModel->pilling)) { ?>
                <div class="item-page-right-wrp-size cols pillinguemost">
                    <span class="name">Пиллингуемость</span>
                    <span class="info"><?= $productViewModel->pilling ?></span>
                </div>
                <?php } ?>
                <div class="item-page-right-wrp-size cols reviews-item-page">
                    <span class="name">Отзывы</span>
                    <span class="stars">
                        <?php for ($i = 0; $i <= 5; $i++): ?>
                            <?php if ($i < $iterations): ?>
                                <img src="/images/icon-star-black.svg">
                            <?php elseif ($i > $iterations): ?>
                                <img src="/images/icon-star-gray.svg">
                            <?php endif;?>
                        <?php endfor; ?>
                        <?= $productViewModel->getReviewDataProvider()->getCount() != 0 ? $productViewModel->getReviewDataProvider()->getCount() : '' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-item-slider bx-center">
    <h3 class="weight">Весь образ на фото</h3>
    <div class="page-item-slider-wrp">
    <?php 
        echo ListView::widget(
            [
                'dataProvider' => $productViewModel->randomProductsDataProvider,
                'itemView' => function ($model) {
                    return $this->render('_product_card', ['product' => $model]);
                },
                'options' => [
                    'tag' => false,
                ],
                'layout' => '<div class="swiper-wrapper">{items}</div><div class="slider-next"></div><div class="slider-prev"></div>',
                'itemOptions' => [
                    'tag' => false,
                ]
            ]
        );
    ?>
    </div>
</div>

<div class="page-item-slider bx-center">
    <h3 class="weight">Возможно вам понравится</h3>
    <div class="page-item-slider-wrp">
    <?php 
        echo ListView::widget(
            [
                'dataProvider' => $productViewModel->relatedProductsDataProvider,
                'itemView' => function ($model) {
                    return $this->render('_product_card', ['product' => $model]);
                },
                'options' => [
                    'tag' => false,
                ],
                'layout' => '<div class="swiper-wrapper">{items}</div><div class="slider-next"></div><div class="slider-prev"></div>',
                'itemOptions' => [
                    'tag' => false,
                ]
            ]
        );
    ?>
    </div>
</div>

<!-- <div class="page-item-slider bx-center">
    <h3 class="weight">Весь образ на фото</h3>
    <div class="page-item-slider-wrp">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item01.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item02.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item03.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item04.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item05.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-next"></div>
        <div class="slider-prev"></div>
    </div>
</div>
<div class="page-item-slider bx-center">
    <h3 class="weight">Возможно вам понравится</h3>
    <div class="page-item-slider-wrp">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item01.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item02.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item03.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item04.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="category-item">
                    <div class="category-item-img">
                        <a href="item.html"><img src="/images/item05.jpg"
                                                 alt="Костюм тройка бежевый в клетку"></a>
                    </div>
                    <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                    клетку</span></a>
                    <div class="category-item-price">
                        17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-next"></div>
        <div class="slider-prev"></div>
    </div>
</div> -->

<div class="popup-bg razmer-popup" role="alert">
    <div class="popup-bx-right">
        <div class="close popup-close"></div>
        <div class="popup-bx-right-title">Подберите свой точный размер</div>
        <div class="popup-bx-right-info">
            <input class="tabs-radio" id="one" name="group" type="radio" checked>
            <input class="tabs-radio" id="two" name="group" type="radio">
            <div class="tabs">
                <label class="tabs-item" id="one-tab" for="one">Таблица размеров</label>
                <label class="tabs-item" id="two-tab" for="two">Обмеры изделия</label>
            </div>
            <div class="panels">
                <div class="panels-item" id="one-panel">
                    <?= $this->render('_size_chart', []); ?>
                </div>
                <div class="panels-item" id="two-panel">
                    <?= $productViewModel->product->description_measurements ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(false) { ?>
<div class="popup-bg nalichie-popup" role="alert">
    <div class="popup-bx-right">
        <div class="close popup-close"></div>
        <div class="popup-bx-right-title">Наличие в магазинах</div>
        <div class="popup-bx-right-info">
        </div>
    </div>
</div>
<?php } ?>
<div class="popup-bg obmeri-popup" role="alert">
    <div class="popup-bx-right">
        <div class="close popup-close"></div>
        <div class="popup-bx-right-title">Обмеры изделия</div>
        <div class="popup-bx-right-info">
            <?= $productViewModel->product->description_measurements ?>
        </div>
    </div>
</div>
<div class="popup-bg pillinguemost-popup" role="alert">
    <div class="popup-bx-right">
        <div class="close popup-close"></div>
        <div class="popup-bx-right-title">Пиллингуемость</div>
        <div class="popup-bx-right-info">
            <?= $productViewModel->product->description_pilling ?>
        </div>
    </div>
</div>
<div class="popup-bg sostav-popup" role="alert">
    <div class="popup-bx-right">
        <div class="close popup-close"></div>
        <div class="popup-bx-right-title">Состав и уход</div>
        <div class="popup-bx-right-info">
            <?= $productViewModel->product->description_composition_and_care ?>
        </div>
    </div>
</div>
<div class="popup-bg reviews-popup" role="alert">
    <div class="popup-bx-right">
        <div class="close popup-close"></div>
        <div class="popup-bx-right-title">Отзывы</div>
        <div class="popup-bx-right-info">
            <div class="reviews-top">
                <div class="reviews-top-num"><?= round($average) ?></div>
                <div class="reviews-top-text"><?= $productViewModel->getReviewDataProvider()->getCount() ?> оценок · Средняя оценка</div>
                <div class="reviews-top-stars">
                    <?php for ($i = 0; $i <= 5; $i++): ?>
                        <?php if ($i < $iterations): ?>
                            <img src="/images/icon-star-black.svg">
                        <?php elseif ($i > $iterations): ?>
                            <img src="/images/icon-star-gray.svg">
                        <?php endif;?>
                    <?php endfor; ?>
                </div>
            </div>

            <?php 
                Pjax::begin([
                    'id' => 'pjax-reviews',
                    'timeout' => 6000,
                    'options' => [
                        'class' => 'js-pjax-reviews',
                    ]
                ]);

                echo ListView::widget([
                    'dataProvider' => $productViewModel->getReviewDataProvider(),
                    'emptyText' => 'Отзывов нет',
                    'itemView' => function ($model) use($product){
                        return $this->render('_review', ['review' => $model, 'product' => $product]);
                    },
                    'options' => [
                        'tag' => false,
                    ],
                    'layout' => '<div class="reviews-wrp">{items}</div><div class="pagination">{pager}</div>',
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
                ]);

                Pjax::end();
            ?>
            
            <div class="item-page-reviews-all">
                <a href="<?= Url::to(['/reviews/frontend/default/index'])?>" class="link-underline">Смотреть все отзывы</a>
            </div>
            <div class="reviews-send">
                <p class="btn-bg full black radius send-review-button">Оставить свой отзыв</p>
            </div>
        </div>
    </div>
</div>

<div class="popup-bg send-review-button-popup" role="alert">
    <div class="popup-bx-center">
        <div class="close popup-close"></div>
        <div class="popup-title">Оставить отзыв</div>
        <?= $this->render('_review-form', ['product' => $product, 'productReviewsForm' => $productViewModel->productReviewsForm]) ?>

    </div>
</div>

<!--
<div class="popup-bg popup appointment-popup" role="alert">
    <div class="popup-bx-center">
        <div class="close popup-close"></div>
        <div class="popup-title">Записаться на примерку</div>
        <form class="popup-form">
            <div class="popup-form-item">
                <label for="get-appointment-fio">Ваше имя: *</label>
                <input id="get-appointment-fio" class="radius" name="get-appointment-fio" type="text"
                       autocomplete="off" required>
            </div>
            <div class="popup-form-item">
                <label for="get-appointment-phone">Телефон: *</label>
                <input id="get-appointment-phone" class="radius tel" name="get-appointment-phone"
                       placeholder="+7 (999) 999 9999" type="text" autocomplete="off" required>
            </div>
            <p class="center-text">
                <button type="button" class="btn-bg black radius">Записаться</button>
            </p>
            <div class="checkbox-wrp">
                <input type="checkbox" class="" name="checkboxGreen" id="get-appointment-check" checked />
                <label for="get-appointment-check">Нажимая на кнопку «Записаться», вы подтверждаете согласие на
                    обработку персональных данных в соответствии с <a href="policy.html"
                                                                      class="link-underline"><span>Пользовательским
                                соглашением</span></a></label>
            </div>
        </form>
    </div>
</div>

<div class="popup-bg popup getcall-popup" role="alert">
    <div class="popup-bx-center">
        <div class="close popup-close"></div>
        <div class="popup-title">Заказать звонок</div>
        <form class="popup-form">
            <div class="popup-form-item">
                <label for="get-appointment-fio">Ваше имя: *</label>
                <input id="get-appointment-fio" class="radius" name="get-appointment-fio" type="text"
                       autocomplete="off" required>
            </div>
            <div class="popup-form-item">
                <label for="get-appointment-phone">Телефон: *</label>
                <input id="get-appointment-phone" class="radius tel" name="get-appointment-phone"
                       placeholder="+7 (999) 999 9999" type="text" autocomplete="off" required>
            </div>
            <p class="center-text">
                <button type="button" class="btn-bg black radius">Отправить</button>
            </p>
            <div class="checkbox-wrp">
                <input type="checkbox" class="" name="checkboxGreen" id="get-appointment-check" checked />
                <label for="get-appointment-check">Нажимая на кнопку «Отправить», вы подтверждаете согласие на
                    обработку персональных данных в соответствии с <a href="policy.html"
                                                                      class="link-underline"><span>Пользовательским
                                соглашением</span></a></label>
            </div>
        </form>
    </div>
</div>
-->
<div class="choice-bg"></div>
<div class="menu-bg"></div>