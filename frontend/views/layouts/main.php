<?php

use cms\common\widgets\AdminWidget;
use yii\helpers\Html;

/** @var \yii\web\View $this */

\frontend\assets\AppAsset::register($this);

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
    $title = 'Интернет магазин faamo.ru';
}
if ($seo && $seo->h1) {
    $this->title = $seo->h1;
}

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

        <link rel="preload" href="/fonts/CeraPro-Regular.woff2" as="font" type="font/woff" crossorigin>
        <link rel="preload" href="/fonts/CeraPro-Medium.woff2" as="font" type="font/woff" crossorigin>

        <?= $this->head() ?>
        <title><?= Html::encode($title) ?></title>
    </head>
    <body>
        <?= AdminWidget::widget(); ?>

        <?php $this->beginBody() ?>
        <header>
            <?= $this->render('header'); ?>
        </header>

        <?php if (isset($this->blocks['main-slider'])): ?>
            <?= $this->blocks['main-slider'] ?>
        <?php endif; ?>

        <article class="<?= Yii::$app->controller->route === 'site/index' ? '' : 'page'?> <?= $this->blocks['article-css'] ?? '' ?>" >
            <?= $content ?>
        </article>

        <footer>
            <?= $this->render('footer')?>
        </footer>
        <?php $this->endBody() ?>

    </body>
</html>
<?php $this->endPage() ?>