<?php

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var array $reviews
 */

function image ($review, $photo) {
    $basePath = "/uploads/content/reviews/{$review->id}/{$photo->value}";
    $jpg = $basePath . ".jpg";
    $png = $basePath . ".png";
    $jpeg = $basePath . ".jpeg";

    if(file_exists(Yii::getAlias('@webroot').$jpg)){
        return $jpg;
    }
    elseif (file_exists(Yii::getAlias('@webroot').$jpeg)){
        return $jpeg;
    }
    elseif (file_exists(Yii::getAlias('@webroot').$png)){
        return $png;
    }
    else{
        return "/";
    }

}

?>
<?php if($reviews): ?>
    <?php foreach ($reviews as $review): ?>
    <div class="review-page-item">
    <div class="review-page-item-user-icon"></div>
    <div class="review-page-item-body">
        <div class="review-page-item-body-top">
            <span class="r-name"><?= $review->fio?></span>
            <?php
            $find_date = new DateTime($review->created_at);
            $date = $find_date->format('d.m.Y');
            ?>
            <span class="r-date"><?= $date?></span>
            <span class="r-stars">
                <?php if ($review->grade) :?>
                    <?php for ($i = 0; $i <= 5; $i++): ?>
                        <?php if ($i < $review->grade): ?>
                            <img src="/images/icon-star-black.svg">
                        <?php elseif ($i > $review->grade): ?>
                            <img src="/images/icon-star-gray.svg">
                        <?php endif;?>
                    <?php endfor; ?>
                <?php endif;?>
            </span>
        </div>
        <div class="review-page-item-body-text">
            <p><?= $review->review_text ?></p>
            <div class="review-page-item-body-text-images">
                <?php if ($review->photo):?>
                    <?php foreach ($review->photo as $photo): ?>
                    <a href='<?php echo image($review,$photo) ?>' class="radius" data-fancybox="catalog-item-gallery"><img src='<?php echo image($review,$photo) ?>' ></a>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

    <?php endforeach; ?>
<?php endif; ?>


<!--<div class="section-header">
    <h2 class="block-title">Отзывы</h2>
    <div class="section-controls">
        <a class="section-header__link" href="<?php /*= Url::to(['/reviews']); */?>">Все отзывы</a>
    </div>
</div>
<div class="reviews__content">
    <?php /*foreach ($reviews as $review) { */?>
        <div class="one-review">
            <div class="one-review__body">
                <div class="one-review__img"></div>
                <div class="one-review__content">
                    <div class="one-review__author"><?php /*= Html::encode($review->fio); */?></div>
                    <time class="one-review__date" datetime="YYYY:MM:DD"><?php /*= Html::encode($review->created_at); */?></time>
                    <div class="one-review__rate">
                        <?php /*for ($i=1;$i<6;$i++) { */?>
                            <span class="icon-star <?php /*= $i <= $review->grade ? 'active' : ''; */?>"></span>
                        <?php /*} */?>
                    </div>
                    <div class="one-review__text"><?php /*= Html::encode(mb_strimwidth($review->review_text, 0, 300, '...')); */?></div>
                </div>
            </div>
        </div>
    <?php /*} */?>
</div>-->