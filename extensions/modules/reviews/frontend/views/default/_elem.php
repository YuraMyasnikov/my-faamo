<?php

use yii\helpers\Html;

?>

<div class="one-review">
    <div class="one-review__img"></div>
    <div class="one-review__content">
        <div class="one-review__author"><?= Html::encode($review->fio); ?></div>
        <time class="one-review__date" datetime="YYYY:MM:DD"><?= Html::encode($review->created_at); ?></time>
        <div class="one-review__rate">
            <?php for ($i=1;$i<6;$i++) { ?>
                <span class="icon-star <?= $i <= $review->grade ? 'active' : ''; ?>"></span>
            <?php } ?>
        </div>
        <div class="one-review__text"><?= Html::encode($review->review_text); ?></div>
        <div class="one-review__gallery slider__gallery">
            <?php foreach ($review->photo as $photo) { ?>
                <div class="one-review__gallery-item"><img src="<?= Yii::$app->image->get($photo->value)->file ?>" alt=""></div>
            <?php } ?>
        </div>
    </div>
</div>