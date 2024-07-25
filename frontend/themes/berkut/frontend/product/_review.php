<?php 

/** @var Infoblock $review */

use yii\helpers\Html;

?>

<div class="comment">
    <div class="comment-avatar">
        <svg width="18" height="18">
            <use xlink:href="#icon-input-user"></use>
        </svg>
    </div>
    <div class="comment-content">
        <div class="comment-header">
            <div class="comment-header__name"><?= Html::encode($review->client_name); ?></div>
            <div class="comment-header__date">
                <?= Html::encode((new DateTime($review->created_at))->format('d.m.Y')); ?>
            </div>
            <div class="stars">
                <span style="width: <?= 20 * $review->grade ?>%;"></span>
            </div>
        </div>
        <div class="comment-text">
            <?= Html::encode($review->text); ?>
        </div>
        <div class="comment-gallery">
            <?php foreach ($review->image as $photo) { ?>
                <?php 
                    $src = Yii::getAlias('@webroot') . Yii::$app->image->get($photo->value)->file;
                    $cachedName = Yii::$app->imageCache->resize($src, null, 75);
                    $cachedPath = Yii::$app->imageCache->relativePath($cachedName);
                ?>
                <a 
                    href="<?=  Yii::$app->image->get($photo->value)->file ?>"
                    data-fancybox
                    class="comment-gallery__item"
                >
                    <img src="<?= $cachedPath ?>" alt="" loading="lazy" />
                </a>
            <?php } ?>
        </div>
    </div>
</div>    