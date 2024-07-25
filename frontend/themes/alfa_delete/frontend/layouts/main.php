<?php
/** @var \yii\web\View $this */

use cms\common\widgets\AdminWidget;
use CmsModule\Shop\common\models\Categories;
use frontend\forms\CallbackForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

\frontend\assets\AppAsset::register($this);

/*$catalog = Categories::getCatalog(null);
$callbackForm = CallbackForm::buildForm();

$seo = Yii::$app->seo->getPage();

if ($seo && $seo->description) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $seo->description
    ]);
}

if ($seo && $seo->robots) {
    $this->registerMetaTag([
        'name' => 'robots',
        'content' => $seo->robots,
    ]);
}

if ($seo && $seo->title) {
    $title = $seo->title;
} else {
    $title = 'Интернет магазин berkut';
}

if ($seo && $seo->h1) {
    $this->title = $seo->h1;
}

\frontend\assets\MaskAsset::register($this);*/

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <?php $this->registerCsrfMetaTags() ?>

    <link rel="preload" href="fonts/CeraPro-Regular.woff2" as="font" type="font/woff" crossorigin>
    <link rel="preload" href="fonts/CeraPro-Medium.woff2" as="font" type="font/woff" crossorigin>

    <?= $this->head() ?>

    <title><?php /*= Html::encode($title) */?> Не забудь</title>
</head>

<body>
<?= AdminWidget::widget(); ?>

<?php $this->beginBody() ?>
<header>
    <?= $this->render('header'); ?>
</header>

<?php if (Yii::$app->controller->route == '/site/index'):?>
<section class="main-banner">
    <div class="main-banner-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide main-banner-slider-item">
                <div class="main-banner-slider-item-info right">
                    <div class="main-banner-slider-item-info-title">Акция -50%</div>
                    <p class="main-banner-slider-item-info-text">17 750 ₽ вместо 35 500 ₽</p>
                    <p><a href="#" class="btn-bg padding black radius">Получить скидку</a></p>
                </div>
                <div class="main-banner-slider-item-img right">
                    <img src="images/main-banner1.jpg">
                </div>
            </div>
            <div class="swiper-slide main-banner-slider-item">
                <div class="main-banner-slider-item-info left">
                    <div class="main-banner-slider-item-info-title">Акция -50%</div>
                    <p class="main-banner-slider-item-info-text">17 750 ₽ вместо 35 500 ₽</p>
                    <p><a href="#" class="btn-bg padding black radius">Получить скидку</a></p>
                </div>
                <div class="main-banner-slider-item-img left">
                    <img src="images/main-banner2.jpg">
                </div>
            </div>
        </div>
        <div class="main-next"></div>
        <div class="main-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="subscription">
        <div class="subscription-bx">
            <p class="weight less-size">Оставьте свой email для получения эксклюзивных скидок и информации о новых
                коллекциях</p>
            <form class="subscription-bx-form">
                <input id="subscription-mail" class="subscription-bx-form-input" name="subscription-mail"
                       type="text" placeholder="Ваша электронная почта" autocomplete="off" required>
                <button type="button" class="subscription-bx-form-button">Подписаться</button>
        </div>
        </form>
    </div>
    </div>
</section>
<?php endif;?>

<article class="<?= Yii::$app->controller->route === '/site/index' ? '' : 'page'?>" >
    <?= $content ?>
</article>

<footer>
    <?= $this->render('footer')?>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>