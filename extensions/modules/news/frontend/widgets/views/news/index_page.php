<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/**
 * @var array $news
 * @var string $title
 */

?>

<div class="container">
        <div class="section-header">
          <h2 class="block-title"><?= Html::encode($title) ?></h2>
          <div class="section-controls">
            <a class="section-header__link" href="<?= Url::to('/news'); ?>">Архив новостей</a>
          </div>
        </div>
        <div class="news-block">
            <?php foreach ($news as $news_item) { ?>
                <div class="news-block__item">
                    <div class="news-block__item-content">
                    <a class="news-block__img" href="<?= Url::to(['/news/' . $news_item->code]); ?>">
                        <img width="400" height="225" src="<?= Yii::$app->image->getFile(Yii::$app->image->get($news_item->main_img)); ?>" alt="<?= Html::encode($news_item->name); ?>">
                        <time datetime="MM:DD" class="news-block__date">
                        <div class="news-block__date-day"><?= Yii::$app->formatter->asDate($news_item->created_at, 'php:d') ?></div>
                        <div class="news-block__date-month"><?= Yii::$app->formatter->asDate($news_item->created_at, 'php:M') ?></div>
                        </time>
                    </a>
                    <div class="news-block__title"><?= Html::encode($news_item->name); ?></div>
                    <div class="news-block__text"><?= HtmlPurifier::process($news_item->desc); ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
      </div>