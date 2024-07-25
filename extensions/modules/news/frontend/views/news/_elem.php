<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

?>

<div class="news-block__item">
<a class="news-block__img" href="<?= Url::to(['/news/' . $news->code]); ?>">
    <img width="400" height="225" src="<?= Yii::$app->image->getFile(Yii::$app->image->get($news->main_img)); ?>" alt="<?= Html::encode($news->name); ?>">
    <time datetime="MM:DD" class="news-block__date">
    <div class="news-block__date-day"><?= Yii::$app->formatter->asDate($news->created_at, 'php:d') ?></div>
    <div class="news-block__date-month"><?= Yii::$app->formatter->asDate($news->created_at, 'php:M') ?></div>
    </time>
</a>
<a class="news-block__title" href="<?= Url::to(['/news/' . $news->code]); ?>"><?= Html::encode($news->name); ?></a>
<div class="news-block__text"><?= HtmlPurifier::process($news->desc); ?></div>
</div>