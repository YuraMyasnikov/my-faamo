<?php

use CmsModule\News\frontend\widgets\NewsWidget;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => '/news', 'class' => 'breadcrumbs-list__link'];
$this->params['breadcrumbs'][] = Html::encode($news->name);
Yii::$app->formatter->locale = 'ru-RU';

?>

<section class="article">
    <div class="container">
        <h1 class="article__title"><?= Html::encode($news->name); ?></h1>
        <div class="article__date">
            <time datetime="YYYY:MM:DD"><?= Yii::$app->formatter->asDate($news->created_at, 'medium') ?></time>
        </div>
        <div class="article__wrap">
            <div class="article__content">
                <div class="article__image">
                    <img width="500" src="<?= Yii::$app->image->get($news->main_img) ? Yii::$app->image->get($news->main_img)->file : null ?>" alt="<?= Html::encode($news->name); ?>" alt="<?= Html::encode($news->name); ?>">
                </div>
                <div class="article__text text-block">
                    <?= HtmlPurifier::process($news->text); ?>
                </div>
            </div>

            <?= NewsWidget::widget([
                'limit' => 4,
                'view' => 'news_card',
                'exceptions_ids' => [$news->id],
                'title' => 'Другие новости'
            ]); ?>
        </div>
    </div>
</section>