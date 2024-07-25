<?php

use yii\helpers\Html;

/**
 * @var \CmsModule\Infoblocks\models\Infoblock $review
 */

?>
<div class="one-review">
    <div class="one-review__img"></div>
    <div class="one-review__content">
        <div class="one-review__author">
            <?= Html::encode($review->client_name); ?>
        </div>
        <time class="one-review__date" datetime="YYYY:MM:DD">
            <?= Html::encode($review->created_at); ?>
        </time>
        <div class="one-review__text">
            <?= Html::encode($review->text); ?>
        </div>
        <div style="display: flex;">
            <?php foreach ($review->image as $photo) { ?>
                <div class="">
                    <?= Html::img(Yii::$app->image->get($photo->value)->file, ['style' => 'max-width: 150px']) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>