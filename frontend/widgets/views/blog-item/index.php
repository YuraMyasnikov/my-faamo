<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <span>Статьи</span>
    </div>
</div>

    <?php if ($blog): ?>
    <div class="bx-center width-default">
        <div class="blog-item-page">
            <h1><?= $blog->blog_title?></h1>
            <?php
            setlocale(LC_TIME, 'ru_RU.UTF-8');
            $date = new DateTime($blog->created_at);

            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');

            $date->setDate($year, $month, $day);

            $formatter = new IntlDateFormatter(
                'ru_RU',
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE,
                'Europe/Moscow',
                IntlDateFormatter::GREGORIAN,
                'd MMMM y'
            );
            $date = $formatter->format($date);
            ?>

            <div class="blog-item-page-date"><?= $date?></div>
        </div>
        <div class="blog-item-page-content js-sticky-row">
            <div class="blog-item-page-left">
                <img class="blog-content-img" src="<?= Url::to(["uploads/content/blog/{$blog->id}/{$blog->blog_image}.jpeg"])?>"
                     alt="<?= $blog->blog_title?>"
                     title="<?= $blog->blog_title?>">
                <?= $blog->blog_text?>
            </div>

            <div class="blog-item-page-right">
                <div class="blog-item-page-right-content js-sticky-box" data-margin-top="30"
                     data-margin-bottom="30">
                    <p class="recomendation-title">Рекомендуемые товары</p>
                    <div class="blog-recomendation">
                        <div class="blog-recomendation-img">
                            <a href="item.html"><img src="/images/item01.jpg"
                                                     alt="Костюм тройка бежевый в клетку"></a>
                        </div>
                        <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                        клетку</span></a>
                        <div class="category-item-price">
                            17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                        </div>
                    </div>
                    <div class="blog-recomendation">
                        <div class="blog-recomendation-img">
                            <a href="item.html"><img src="/images/item02.jpg"
                                                     alt="Костюм тройка бежевый в клетку"></a>
                        </div>
                        <a href="item.html" class="category-item-name link-line"><span>Костюм тройка бежевый в
                                        клетку</span></a>
                        <div class="category-item-price">
                            17 750 ₽ <span class="category-item-price-old">27 750 ₽</span>
                        </div>
                    </div>
                    <div class="blog-recomendation">
                        <div class="blog-recomendation-img">
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
            </div>

        </div>
        <?php
            $blogsInfoblog = \CmsModule\Infoblocks\models\Infoblock::byCode('blog');
            $blogs = $blogsInfoblog::find()->where(['active' => true /*, 'blog_big_size' => true*/])->all();
        ?>
        <?php if($blogs): ?>
            <div class="blog-slider-wrp">
                <h3 class="weight">Возможно вам понравится</h3>
                <div class="blog-page-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($blogs as $item): ?>
                            <div class="swiper-slide">
                                <a href="blog-item.html" class="blog-page-item-img">
                                    <img src="<?= Url::to(["uploads/content/blog/{$item->id}/{$item->blog_image}.jpeg"])?>"
                                         alt="<?= $item->blog_title?>"
                                         title="<?= $item->blog_title?>">
                                </a>
                                <?php
                                setlocale(LC_TIME, 'ru_RU.UTF-8');
                                $date = new DateTime($item->created_at);

                                $year = $date->format('Y');
                                $month = $date->format('m');
                                $day = $date->format('d');

                                $date->setDate($year, $month, $day);

                                $formatter = new IntlDateFormatter(
                                    'ru_RU',
                                    IntlDateFormatter::LONG,
                                    IntlDateFormatter::NONE,
                                    'Europe/Moscow',
                                    IntlDateFormatter::GREGORIAN,
                                    'd MMMM y'
                                );
                                $date_item = $formatter->format($date);
                                ?>

                                <p class="blog-page-item-date"><?= $date_item?></p>
                                <p class="blog-page-item-title">
                                    <a href=""><?= $item->blog_title?></a>
                                </p>
                                <p class="blog-page-item-text"><?= $item->blog_description?></p>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <div class="blog-pagination">
                        <div class="slider-next"></div>
                        <div class="slider-prev"></div>
                    </div>
                </div>
            </div>

        <?php endif;?>
    </div>
<?php endif;?>
