<?php 

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $showcaseDataProvider */

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Shop\common\models\Categories;
use frontend\assets\FavouriteAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\forms\SubscribersForm as FormsSubscribersForm;

$subscribeForm = FormsSubscribersForm::buildForm();

FavouriteAsset::register($this);

?>

<!--👉showcase-->
<div class="showcase md-hidden">
    <div class="showcase-slider splide">
        <div class="splide__track">
            <div class="splide__list">
                <?php foreach($showcaseDataProvider->models as $model) { ?>
                    <?php 
                        /** @var Infoblock $model */ 
                        // banner_image
                        $src = Yii::$app->image->get($model->banner_image)->file;
                        // $src = Yii::getAlias('@webroot') . Yii::$app->image->get($model->banner_image)->file;
                        // $cachedName = Yii::$app->imageCache->resize($src, null, 75);
                        // $cachedPath = Yii::$app->imageCache->relativePath($cachedName);
                    ?>
                    <div class="splide__slide">
                        <div class="container">
                            <div class="showcase-slide">
                                <div class="showcase-slide__content">
                                    <div class="showcase-title">
                                        <?= $model->title ?>
                                    </div>
                                    <div class="showcase-text">
                                        <?= $model->description ?>
                                    </div>
                                    <div class="showcase-button">
                                        <a href="<?= $model->button_link ?>" class="btn"><?= $model->button_name ?></a>
                                    </div>
                                </div>
                                <div class="showcase-slide__image">
                                    <img src="<?= $src ?>" alt="" loading="lazy">
                                </div>
                            </div>
                        </div>
                    </div>    
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="showcase-panel"></div>
    </div>
</div>

<!-- 👉 Новые поступления -->
<div class="offset">
    <div class="container">
        <div class="head center">
            <div class="head-title">Новые поступления</div>
        </div>

        <?= $listViewContentForNewProduct ?>

    </div>
</div>

<!--👉 Наши преимущества / Категории-->
<div class="offset back-white">
    <div class="container">

        <!--👉 Наши преимущества-->
        <div class="advantages">
            <div class="advantages-cover">
                <div class="advantages-cover__image">
                    <img src="/img/advantahes-image.png" alt="" loading="lazy" width="358" height="467">
                </div>
                <div class="advantages-cover__title">
                    Наши <br>
                    преимущества
                </div>
            </div>
            <div class="advantages-item">
                <div class="advantages-item__image">
                    <img src="/img/advantages-1.svg" alt="" loading="lazy" width="60" height="60">
                </div>
                <div class="advantages-item__content">
                    <div class="advantages-item__title">Собственное производство</div>
                    <div class="advantages-item__text">
                        Производим высокопрочную одежду и обувь для работы, охоты, рыбалки, туризма. Работаем с
                        2008 года.
                    </div>
                </div>
            </div>
            <div class="advantages-item">
                <div class="advantages-item__image">
                    <img src="/img/advantages-2.svg" alt="" loading="lazy" width="60" height="60">
                </div>
                <div class="advantages-item__content">
                    <div class="advantages-item__title">Высокое качество</div>
                    <div class="advantages-item__text">
                        Износостойкая, дышащая, не стесняющая движений
                    </div>
                </div>
            </div>
            <div class="advantages-item">
                <div class="advantages-item__image">
                    <img src="/img/advantages-3.svg" alt="" loading="lazy" width="60" height="60">
                </div>
                <div class="advantages-item__content">
                    <div class="advantages-item__title">Доставка по всей России</div>
                    <div class="advantages-item__text">
                        Осуществляем доставку по Всей России транспортными компаниями
                    </div>
                </div>
            </div>
            <div class="advantages-item">
                <div class="advantages-item__image">
                    <img src="/img/advantages-4.svg" alt="" loading="lazy" width="60" height="60">
                </div>
                <div class="advantages-item__content">
                    <div class="advantages-item__title">Низкие оптовые цены</div>
                    <div class="advantages-item__text">
                        начиная от <?= Yii::$app->params['basket.calculator.price_types']['small_wholesale_price']['max'] + 1 ?> руб. Отгружаем товар в течении 3х дней.
                    </div>
                </div>
            </div>
            <div class="advantages-item">
                <div class="advantages-item__image">
                    <img src="/img/advantages-5.svg" alt="" loading="lazy" width="60" height="60">
                </div>
                <div class="advantages-item__content">
                    <div class="advantages-item__title">Маркетплейсы</div>
                    <div class="advantages-item__text">
                        Наш ассортимент представлен на ведущих торговых площадках страны:
                    </div>
                    <div class="market-items market-items--hor">
                        <a href="<?= Yii::$app->settings->getMarketplace('wildberries')?>" target="_blank">
                        <div class="market market--small wb">
                            <img src="/img/wb.svg" alt="" width="100">
                        </div>
                        </a>
                        <a href="<?= Yii::$app->settings->getMarketplace('yandex')?>" target="_blank">
                        <div class="market market--small ym">
                            <img src="/img/ym.svg" alt="" width="100">
                        </div>
                        </a>
                        <a href="<?= Yii::$app->settings->getMarketplace('ozon')?>" target="_blank">
                        <div class="market market--small ozon">
                            <img src="/img/ozon.svg" alt="" width="67">
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--👉 Категории-->
        <div class="offset-top offset--min">
            <div class="columns columns--element">
                <?php foreach(Categories::find()->where(['parent_id' => null, 'active' => 1])->limit(4)->all() as $category) { ?>
                    <?php 
                        /** 
                         * @var Categories $category 
                         * @var \cms\common\models\Images $image
                         */    
                        
                        $image = Yii::$app->image->get($category->image_id);
                        
                    ?>
                    <div class="column col-3 xl-col-6 sm-col-12">
                        <a href="<?= Url::to(['catalog/view', 'filters' => $category->code]) ?>" class="category-card">
                            <?php if($image) { ?>
                            <div class="category-card__image">
                                <img src="<?= $image->file ?>" alt="" width="117" height="140" loading="lazy" />
                            </div>
                            <?php } ?>
                            <div class="category-card__title"><?= $category->name ?></div>
                        </a>
                    </div>    
                <?php } ?>    
            </div>
        </div>
    </div>
</div>

<!--👉 Подбор товара-->
<div class="choice-block">
    <div class="container">
        <div class="choice">
            <div class="choice-content">
                <div class="choice-content__title">Подбор товара</div>
                <div class="choice-content__text">
                    Персональный менеджер проконсультирует по вопросу оптовых закупок, расскажет об особенностях
                    ассортимента, подберёт для вас наилучшее предложение.
                </div>
                <button type="button" class="btn btn--mobile" data-micromodal-trigger="modal-recall">Отправить заявку</button>
            </div>
            <div class="choice-image"></div>
        </div>
    </div>
</div>

<!--👉 Отзывы-->
<div class="offset-top offset--min">
    <div class="container">

        <!---->
        <div class="reviews-head">
            <div class="head-title">Отзывы <!-- <span class="reviews-head__count">291 отзыв</span> --></div>
            <div class="reviews-head__aside">
                <a href="<?= Url::to(['/reviews'])  ?>" class="reviews-head__link">Посмотреть все отзывы</a>
                <!---->
                
                <!-- <div class="reviews-market">
                    <a href="" class="reviews-market__item">Все</a>
                    <a href="" class="reviews-market__item">
                        <img src="img/icon-ya.svg" alt="" width="20" height="20" loading="lazy">
                        4.6
                    </a>
                    <a href="" class="reviews-market__item">
                        <img src="img/icon-google.svg" alt="" width="20" height="20" loading="lazy">
                        4.6
                    </a>
                </div> -->
                <div class="reviews-head__nav"></div>
            </div>
        </div>

        <div class="review-slider splide splide--header">
            <div class="splide__track">
                <div class="splide__list">
                    <?php foreach($reviews as $review) { ?>
                        <!---->
                        <div class="splide__slide">
                            <div class="review-box">
                                <div class="review-box__header">
                                    <div class="review-box__avatar">КК</div>
                                    <div>
                                        <div class="review-box__name"><?= $review->fio ?></div>
                                        <div class="review-box__content">
                                            <div class="review-box__date"><?= $review->created_at ?></div>
                                            <div class="stars">
                                                <span style="width: <?= $review->grade * 20 ?>%;"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-box__text">
                                    <?= $review->review_text ?>
                                </div>
                                <div class="review-box__from">Отзывы на сайте</div>
                            </div>
                        </div>
                    <?php } ?>

                    <!---->
                    <div class="splide__slide">
                        <div class="review-box">
                            <div class="review-box__header">
                                <div class="review-box__avatar">КК</div>
                                <div>
                                    <div class="review-box__name">Константин Константиновский</div>
                                    <div class="review-box__content">
                                        <div class="review-box__date">8 февраля 2023</div>
                                        <div class="stars">
                                            <span style="width: 80%;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="review-box__text">
                                Отличный магазин. Клиентоориентированный, чуткий персонал. Большая линейка
                                товара, возможность подобрать за адекватные деньги качественные вещи. Удобное
                                расположение.
                            </div>
                            <div class="review-box__from">Отзывы из Яндекс</div>
                        </div>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <div class="review-box">
                            <div class="review-box__header">
                                <div class="review-box__avatar">
                                    <img src="temp/review.jpg" alt="" loading="lazy">
                                </div>
                                <div>
                                    <div class="review-box__name">Константин Константиновский</div>
                                    <div class="review-box__content">
                                        <div class="review-box__date">8 февраля 2023</div>
                                        <div class="stars">
                                            <span style="width: 20%;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="review-box__text">
                                Здравствуйте! Получили костюм из твила "City" (Snow bars). Размер подошёл, все в
                                соответствии с заявленной размерной таблицей. Фурнитура хорошего качества, все
                                очень продумано и удобно. Очень приятная мягкая ткань, прочная, непродуваемая и
                                при этом "дышащая"...
                            </div>
                            <div class="review-box__from">Отзывы на сайте</div>
                        </div>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <div class="review-box">
                            <div class="review-box__header">
                                <div class="review-box__avatar">КК</div>
                                <div>
                                    <div class="review-box__name">Константин Константиновский</div>
                                    <div class="review-box__content">
                                        <div class="review-box__date">8 февраля 2023</div>
                                        <div class="stars">
                                            <span style="width: 100%;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="review-box__text">
                                Отличный магазин. Клиентоориентированный, чуткий персонал. Большая линейка
                                товара, возможность подобрать за адекватные деньги качественные вещи. Удобное
                                расположение.
                            </div>
                            <div class="review-box__from">Отзывы из Яндекс</div>
                        </div>
                    </div>
                    <!---->
                    <div class="splide__slide">
                        <div class="review-box">
                            <div class="review-box__header">
                                <div class="review-box__avatar">КК</div>
                                <div>
                                    <div class="review-box__name">Константин Константиновский</div>
                                    <div class="review-box__content">
                                        <div class="review-box__date">8 февраля 2023</div>
                                        <div class="stars">
                                            <span style="width: 100%;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="review-box__text">
                                Отличный магазин. Клиентоориентированный, чуткий персонал. Большая линейка
                                товара, возможность подобрать за адекватные деньги качественные вещи. Удобное
                                расположение.
                            </div>
                            <div class="review-box__from">Отзывы из Яндекс</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="center">
            <a href="<?= Url::to(['reviews-frontend/index'])?>" class="btn">Оставить отзыв</a>
        </div>

    </div>
</div>

<!-- 👉 Выбор покупателей -->
<div class="offset">
    <div class="container">
        <div class="head center">
            <div class="head-title">Выбор покупателей</div>
        </div>

        <?= $listViewContentForPopularProduct ?>

    </div>
</div>

<!--👉 about-->
<div class="offset back-white">
    <div class="container">

        <div class="layout">
            <div class="layout-aside">

                <!---->
                <div class="subscribe-showcase">
                    <div class="subscribe-showcase__title">Будьте в курсе акций и скидок магазина</div>
                    <div class="subscribe-showcase__text">подпишитесь на обновления каталога товаров</div>
                    <?php
                    $form = ActiveForm::begin([
                        'action' => Url::to(['/site/subscribe']),
                        'method' => 'POST',
                        'options' => ['class' => 'subscribe-form']]);
                    ?>
                    <div class="subscribe-group">
                        <?php
                        echo $form
                            ->field($subscribeForm, 'email', [
                                'options' => [],
                                'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-email"></use></svg></div>{error}{hint}'
                            ])
                            ->textInput([
                                'class' => 'input input--icon subscribe-input',
                                'placeholder' => 'Введите ваш Email...'
                            ]);
                        ?>
                        <button type="submit" class="subscribe-button">
                            <svg width="16" height="16">
                                <use xlink:href="#icon-submit"></use>
                            </svg>
                        </button>
                    </div>
                    <?php $form::end(); ?>
                </div>

                <div class="offset-top offset--min">
                    <h2>Мы на маркетплейсах</h2>

                    <div class="market-items">
                        <a href="<?php echo Yii::$app->settings->getMarketplace('wildberries') ?>" class="market wb" target="_blank">
                            <img src="img/wb.svg" alt="" loading="lazy">
                        </a>
                        <a href="<?php echo Yii::$app->settings->getMarketplace('yandex') ?>" class="market ym" target="_blank">
                            <img src="img/ym.svg" alt="" loading="lazy">
                        </a>
                        <a href="<?php echo Yii::$app->settings->getMarketplace('ozon') ?>" class="market ozon" target="_blank">
                            <img src="img/ozon.svg" alt="" loading="lazy">
                        </a>
                    </div>

                </div>

            </div>
            <div class="layout-content">
                <div class="text">
                    <?php echo Yii::$app->settings->getDescription() ?>
                </div>

            </div>
        </div>

    </div>
</div>