<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

?>

<div class="articles-list__wrap">
    <h3 class="articles-list__title"><?= Html::encode($title); ?></h3>
    <div class="articles-list">
        <?php foreach ($news as $news_item) { ?>
            <div class="news-block__item">
                <a class="news-block__img" href="<?= Url::to(['/news/' . $news_item->code]); ?>">
                    <img width="258" height="145" src="<?= Yii::$app->image->getFile(Yii::$app->image->get($news_item->main_img)); ?>" alt="<?= Html::encode($news_item->name); ?>">
                    <time datetime="MM:DD" class="news-block__date">
                        <div class="news-block__date-day"><?= Yii::$app->formatter->asDate($news_item->created_at, 'php:d') ?></div>
                        <div class="news-block__date-month"><?= Yii::$app->formatter->asDate($news_item->created_at, 'php:M') ?></div>
                    </time>
                </a>
                <a class="news-block__title" href="<?= Url::to(['/news/' . $news_item->code]); ?>"><?= Html::encode($news_item->name); ?></a>
                <div class="news-block__text"><?= HtmlPurifier::process($news_item->desc); ?></div>
            </div>
        <?php } ?>
    </div>
</div>