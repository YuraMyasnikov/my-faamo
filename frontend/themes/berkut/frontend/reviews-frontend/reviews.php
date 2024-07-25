<?php

/** @var \yii\db\ActiveRecord[] $reviews */

?>
<?php foreach($reviews as $review):?>
    <div class="comment comment--page">
        <div class="comment-avatar">
            <svg width="18" height="18">
                <use xlink:href="#icon-input-user"></use>
            </svg>
        </div>
        <div class="comment-content">
            <div class="comment-header">
                <div class="comment-header__name"><?= $review->fio ?></div>
                <div class="comment-header__date"><?php echo (new DateTime( $review->created_at))->format('d.m.Y')?></div>
                <div class="stars">
                    <span style="width: <?= ($review->grade * 20)?>%;"></span>

                </div>
            </div>
            <div class="comment-text">
                <?= $review->review_text ?>
            </div>
            <div class="comment-gallery">
                <?php foreach ($review->photo as $photo):?>
                    <a href="<?= Yii::$app->image->get($photo->value)->file ?>"
                       data-fancybox
                       class="comment-gallery__item">
                        <img src="<?= Yii::$app->image->get($photo->value)->file ?>" alt="" loading="lazy">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endforeach;?>