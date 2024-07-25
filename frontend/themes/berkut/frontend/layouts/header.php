<?php 

use yii\helpers\Url;
use CmsModule\Shop\common\models\Categories;
use yii\helpers\Html;

/** @var Categories $catalog */

?>

<!-- 🤟 header -->
<header class="header">
    <div class="header-top-wrap">
        <div class="container">
            <div class="header-top">

                <!--👉-->
                <div class="header-location">
                    <svg width="13" height="18">
                        <use xlink:href="#icon-location"></use>
                    </svg>
                    <div class="header-location__title sm-hidden">Ваш город:</div>
                    <div class="header-location__city">
                        <span>Санкт-Петербург</span>
                        <svg width="9" height="4">
                            <use xlink:href="#icon-arrow-down"></use>
                        </svg>
                    </div>
                </div>

                <!--👉-->
                <div class="header-menu lg-hidden">
                    <ul class="header-menu__list">
                        <li><a href="<?= Url::to(['/site/about']) ?>">О компании</a></li>
                        <li><a href="<?= Url::to(['/site/delivery'])?>">Доставка и оплата</a></li>
                        <li><a href="<?= Url::to(['/reviews-frontend/index'])?>">Отзывы</a></li>
                        <li><a href="<?= Url::to(['/site/how-to-order'])?>">Как сделать заказ</a></li>
                        <li><a href="<?= Url::to(['/site/contact'])?>">Контакты</a></li>
                    </ul>
                </div>

                <!--👉-->
                <div class="header-phone header-phone--mobile lg-visible">
                    <div class="header-phone__image">
                        <svg width="18" height="18">
                            <use xlink:href="#icon-phone"></use>
                        </svg>
                    </div>
                    <div class="header-phone__content">
                        <div class="header-phone__header">
                            <div class="header-phone__value">
                                <a href="tel:<?= Yii::$app->settings->getPhone()?>"><?= Yii::$app->settings->getPhone()?></a>
                            </div>
                            <div class="header-phone__select">
                                <svg width="9" height="4" data-popup-link="phone-popup">
                                    <use xlink:href="#icon-arrow-down"></use>
                                </svg>
                                <div class="header-popup header-popup--right" data-popup="phone-popup">
                                    <ul class="header-popup__list">
                                        <li>
                                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone2()) ?>">
                                                <b>Анна</b>
                                                <?= Yii::$app->settings->getPhone2()?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>">
                                                <b>Михаил</b>
                                                <?= Yii::$app->settings->getPhone()?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone3()) ?>">
                                                <b>Рабочий</b>
                                                <?= Yii::$app->settings->getPhone3()?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a href="" class="header-phone__email">
                            <svg width="15" height="15">
                                <use xlink:href="#icon-email"></use>
                            </svg>
                            <?= Yii::$app->settings->getEmail() ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="header-main">

            <!--👉-->
            <a href="<?= Url::to(['/site/index'])?>" class="logotype">
                <div class="logotype-image">
                    <img src="/img/logotype.svg" alt="" width="59" height="83">
                </div>
                <div class="logotype-title">BERKUT.IV</div>
                <div class="logotype-text">
                    Одежда и обувь для охоты, <br>
                    рыбалки и туризма
                </div>
            </a>

            <!--👉-->
            <div class="header-phone lg-hidden">
                <div class="header-phone__image">
                    <svg width="18" height="18">
                        <use xlink:href="#icon-phone"></use>
                    </svg>
                </div>
                <div class="header-phone__content">
                    <div class="header-phone__header">
                        <div class="header-phone__value">
                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>"><?= Yii::$app->settings->getPhone() ?></a>
                        </div>
                        <div class="header-phone__select">
                            <svg width="9" height="4" data-popup-link="phone-popup">
                                <use xlink:href="#icon-arrow-down"></use>
                            </svg>
                            <div class="header-popup" data-popup="phone-popup">
                                <ul class="header-popup__list">
                                    <li>
                                        <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone2()) ?>">
                                            <b>Анна</b>
                                            <?= Yii::$app->settings->getPhone2()?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>">
                                            <b>Михаил</b>
                                            <?= Yii::$app->settings->getPhone()?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone3()) ?>">
                                            <b>Рабочий</b>
                                            <?= Yii::$app->settings->getPhone3()?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="mailto:<?php echo Yii::$app->settings->getEmail() ?>" class="header-phone__email">
                        <svg width="15" height="15">
                            <use xlink:href="#icon-email"></use>
                        </svg>
                        <?= Yii::$app->settings->getEmail() ?>
                    </a>
                </div>
            </div>

            <!--👉-->
            <a href="<?= Url::to(['/site/pricelist']) ?>" class="header-price lg-hidden">
                <div class="header-price__image">
                    <svg width="18" height="18">
                        <use xlink:href="#icon-download"></use>
                    </svg>
                </div>
                <div class="header-price__content">
                    <div class="header-price__title">Скачать прайс-лист</div>
                    <div class="header-price__info">.xlsx, 17.6 Kb</div>
                </div>
            </a>

            <!--👉-->
            <div class="header-user">
                <div class="header-user__item lg-hidden">
                    <div class="header-user__count"><?= Yii::$app->favorite->count() ?></div>
                    <a href="<?= Url::to(['/favorite']) ?>" class="header-user__link">
                        <svg width="24" height="24">
                            <use xlink:href="#icon-favorite"></use>
                        </svg>
                    </a>
                </div>
                <div class="header-user__item lg-hidden">
                    <div class="header-user__count basket-counter"><?= Yii::$app->basket->count() ?></div>
                    <a href="<?= Url::to(['/basket']) ?>" class="header-user__link">
                        <svg width="26" height="24">
                            <use xlink:href="#icon-basket"></use>
                        </svg>
                    </a>
                </div>
                <?php if (Yii::$app->user->isGuest) { ?>
                <div class="header-user__item lg-hidden">
                    <a href="<?= Url::to(['/site/login']) ?>" class="header-user__link">
                        <svg width="20" height="22">
                            <use xlink:href="#icon-user"></use>
                        </svg>
                    </a>
                </div>
                <?php } else { ?>    
                <div class="header-user__item lg-hidden">
                    <div class="header-user__link" data-popup-link="user-popup">
                        <svg width="20" height="22">
                            <use xlink:href="#icon-user"></use>
                        </svg>
                    </div>
                    <div class="header-popup header-popup--right" data-popup="user-popup">
                        <ul class="header-popup__list">
                            <li><a href="<?= Url::to(['/account']) ?>">Персональные данные</a></li>
                            <li><a href="<?= Url::to(['/account/orders']) ?>">История заказов</a></li>
                            <li><a href="<?= Url::to(['/site/logout']) ?>">Выход</a></li>
                        </ul>
                    </div>
                </div>
                <?php } ?>
                <div class="header-user__item lg-visible">
                    <div class="header-user__link" data-popup-link="search-popup">
                        <svg width="20" height="22">
                            <use xlink:href="#icon-search"></use>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="header-bottom">

            <!--👉-->
            <button type="button" class="btn header-burger lg-hidden" data-popup-link="catalog-popup">
                <svg width="18" height="18">
                    <use xlink:href="#icon-burger-open" class="header-burger__open"></use>
                    <use xlink:href="#icon-burger-close" class="header-burger__close"></use>
                </svg>
                Каталог
            </button>

            <!--👉-->
            <div class="search" data-popup="search-popup">
                <form action="<?= Url::to(['/search']) ?>">
                    <input type="text" name="q" value="<?= Html::encode(Yii::$app->request->get('q')) ?>" class="search-input" placeholder="Поиск среди 500 000 товаров...">
                    <button type="submit" class="search-button">
                        <svg width="16" height="16">
                            <use xlink:href="#icon-search"></use>
                        </svg>
                    </button>
                </form>
            </div>

            <!--👉-->
            <div class="header-category">
                <ul class="header-category__list">
                    <?php foreach($catalog as $category) { ?>
                        <?php 
                            /** @var \CmsModule\Shop\common\models\Categories $category */    
                        ?>
                        <li><a href="<?= Url::to(['/catalog/view', 'filters' => $category->code]) ?>"><?= $category->name ?></a></li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </div>


    <!-- 🤟 catalog-popup -->
    <div class="container">
        <div class="catalog-popup" data-popup="catalog-popup">
            <div class="catalog-popup__overlay"></div>
            <div class="catalog-popup__layout">
                <div class="catalog-popup__aside">

                    <div class="catalog-popup__close js-catalog-menu lg-visible">
                        <svg width="16" height="16">
                            <use xlink:href="#icon-close"></use>
                        </svg>
                    </div>

                    <!--👉-->
                    <ul class="catalog-popup__list catalog-popup__list--category">
                        <?php foreach($catalog as $i => $category) { ?>
                            <?php /** @var Categories $category */ ?>
                            <li>
                                <a href="<?= Url::to(['/catalog/view', 'filters' => $category->code]) ?>" data-catalog-code="<?= $category->code ?>" class="catalog-popup__item <?= !$i ? 'active' : '' ?>">
                                    <?php if($category->icon) { ?>
                                    <img src="<?= Yii::getAlias('@web' . $category->icon->file) ?>"
                                            alt="" width="20" height="20" loading="lazy">
                                    <?php } ?>
                                    <?= $category->name ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="" data-catalog-code="">
                                <img src="/img/icon-hits.svg"
                                        alt="" width="20" height="20" loading="lazy">
                                Товары по акции
                            </a>
                        </li>
                        <!--
                        <li>
                            <a href="" data-catalog-code="letnjaja-specodezhda" class="catalog-popup__item active">
                                <img src="/img/icon-letnjaja-specodezhda.svg"
                                        alt="" width="20" height="20" loading="lazy">
                                Летняя спецодежда
                            </a>
                        </li>
                        <li>
                            <a href="" data-catalog-code="demisezonnaja-specodezhdag" class="catalog-popup__item">
                                <img src="/img/icon-demisezonnaja-specodezhdag.svg"
                                        alt="" width="20" height="20" loading="lazy">
                                Демисезонная спецодежда
                            </a>
                        </li>
                        <li>
                            <a href="" data-catalog-code="zimnjaja-specodezhdaa" class="catalog-popup__item">
                                <img src="/img/icon-zimnjaja-specodezhdaa.svg"
                                        alt="" width="20" height="20" loading="lazy">
                                Зимняя спецодежда
                            </a>
                        </li>
                        <li>
                            <a href="" data-catalog-code="obuvka" class="catalog-popup__item">
                                <img src="/img/icon-obuvka.svg"
                                        alt="" width="20" height="20" loading="lazy">
                                Обувь
                            </a>
                        </li>
                        <li>
                            <a href="" data-catalog-code="">
                                <img src="/img/icon-hits.svg"
                                        alt="" width="20" height="20" loading="lazy">
                                Товары по акции
                            </a>
                        </li>
                        -->
                    </ul>

                    <!--👉-->
                    <ul class="catalog-popup__list md-visible">
                        <li><a href="<?= Url::to(['/site/about']) ?>">О компании</a></li>
                        <li><a href="<?= Url::to(['/site/delivery'])?>">Доставка и оплата</a></li>
                        <li><a href="<?= Url::to(['/reviews-frontend/index'])?>">Отзывы</a></li>
                        <li><a href="<?= Url::to(['/site/how-to-order'])?>">Как сделать заказ</a></li>
                        <li><a href="<?= Url::to(['/site/contact'])?>">Контакты</a></li>
                    </ul>

                    <div class="catalog-popup__footer lg-visible">
                        <!--👉-->
                        <div class="header-phone">
                            <div class="header-phone__image">
                                <svg width="18" height="18">
                                    <use xlink:href="#icon-phone"></use>
                                </svg>
                            </div>
                            <div class="header-phone__content">
                                <div class="header-phone__header">
                                    <div class="header-phone__value">
                                        <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>"><?= Yii::$app->settings->getPhone() ?></a>
                                    </div>
                                    <div class="header-phone__select">
                                        <svg width="9" height="4" data-popup-link="phone-popup">
                                            <use xlink:href="#icon-arrow-down"></use>
                                        </svg>
                                        <div class="header-popup" data-popup="phone-popup">
                                            <ul class="header-popup__list">
                                                <li>
                                                    <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone2()) ?>">
                                                        <b>Анна</b>
                                                        <?= Yii::$app->settings->getPhone2() ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>">
                                                        <b>Михаил</b>
                                                        <?= Yii::$app->settings->getPhone() ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone3()) ?>">
                                                        <b>Рабочий</b>
                                                        <?= Yii::$app->settings->getPhone3() ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <a href="mailto:<?php echo Yii::$app->settings->getEmail() ?>" class="header-phone__email">
                                    <svg width="15" height="15">
                                        <use xlink:href="#icon-email"></use>
                                    </svg>
                                    <?= Yii::$app->settings->getEmail() ?>
                                </a>
                            </div>
                        </div>

                        <!--👉-->
                        <a href="<?= Url::to(['/site/pricelist']) ?>" class="header-price">
                            <div class="header-price__image">
                                <svg width="18" height="18">
                                    <use xlink:href="#icon-download"></use>
                                </svg>
                            </div>
                            <div class="header-price__content">
                                <div class="header-price__title">Скачать прайс-лист</div>
                                <div class="header-price__info">.xlsx, 17.6 Kb</div>
                            </div>
                        </a>

                    </div>
                </div>
                <div class="catalog-popup__content">
                    <?php foreach($catalog as $i => $category) { ?>
                        <?php /** @var Categories $category */ ?>
                        
                        <div class="catalog-popup__sublist <?= !$i ? 'active' : '' ?> active" data-popup-code="<?= $category->code ?>">
                            <div class="catalog-popup__close js-catalog-return lg-visible">
                                <svg width="22" height="22">
                                    <use xlink:href="#icon-return"></use>
                                </svg>
                            </div>
                            <ul>
                                <?php foreach($category['child'] as $subCatalog) { ?>
                                    <?php /** @var Categories $subCatalog */ ?>
                                    <li>
                                        <a href="<?= Url::to(['/catalog/view', 'filters' => $subCatalog->code]) ?>">
                                            <?= $subCatalog->name ?>
                                        </a>
                                    </li>
                                <?php } ?>    
                                <!--
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Брюки</a></li>
                                <li><a href="">Брюки</a></li>
                                <li><a href="">Брюки</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Костюмы «Горка»</a></li>
                                <li><a href="">Брюки</a></li>
                                <li><a href="">Брюки</a></li>
                                <li><a href="">Брюки</a></li>
                                -->
                            </ul>
                        </div>
                    <?php } ?>

                    
                    <div class="catalog-popup__sublist" data-popup-code="demisezonnaja-specodezhdag">
                        <div class="catalog-popup__close js-catalog-return lg-visible">
                            <svg width="22" height="22">
                                <use xlink:href="#icon-return"></use>
                            </svg>
                        </div>
                        <ul>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                        </ul>
                    </div>
                    <div class="catalog-popup__sublist" data-popup-code="zimnjaja-specodezhdaa">
                        <div class="catalog-popup__close js-catalog-return lg-visible">
                            <svg width="22" height="22">
                                <use xlink:href="#icon-return"></use>
                            </svg>
                        </div>
                        <ul>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Брюки</a></li>
                        </ul>
                    </div>
                    <div class="catalog-popup__sublist" data-popup-code="obuvka">
                        <div class="catalog-popup__close js-catalog-return lg-visible">
                            <svg width="22" height="22">
                                <use xlink:href="#icon-return"></use>
                            </svg>
                        </div>
                        <ul>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Костюмы «Горка»</a></li>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Брюки</a></li>
                            <li><a href="">Брюки</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
