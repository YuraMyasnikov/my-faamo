<?php

use CmsModule\Reviews\frontend\forms\ReviewsForm;
use frontend\assets\ReviewsAsset;
use yii\helpers\Url;

/** @var ReviewsForm $reviewForm */
/** @var \yii\db\ActiveRecord[] $reviews */
/** @var int $reviewsCount */
/** @var float $avgStarsCount */
/** @var int $oneStarsCount */
/** @var int $twoStarsCount */
/** @var int $threeStarsCount */
/** @var int $fourStarsCount */
/** @var int $fiveStarsCount */
/** @var \yii\web\View $this */


ReviewsAsset::register($this);

function percent (int $count, int $reviewsCount): int|float {
    return round(($count*100) / $reviewsCount, 2);
}

?>

 <!--📰 О компании-->
 <div class="content">

<!--🤟 Хлебные крошки-->
<div class="container">
    <ul class="breadcrumbs">
        <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
        <li>Отзывы</li>
    </ul>
</div>

<div class="container">

    <h1>Отзывы</h1>

    <div class="layout">
        <div class="layout-content">    
            <!--👉 comments-->
            <div class="comments" id="comments--list">
                <?php foreach($reviews as $review):?>
                    <?php if($review->active): ?>
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
                                        <?php
                                            $src = Yii::getAlias('@webroot') . Yii::$app->image->get($photo->value)->file;
                                            $cachedName = Yii::$app->imageCache->resize($src, null, 75);
                                            $cachedPath = Yii::$app->imageCache->relativePath($cachedName);
                                        ?>
                                        <a
                                            href="<?= Yii::$app->image->get($photo->value)->file ?>"
                                            data-fancybox
                                            class="comment-gallery__item"
                                        >
                                            <img src="<?= $cachedPath ?>" alt="" loading="lazy" />
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach;?>
            </div>

            <?php if($isHasNextPage) {  ?>
            <div class="comments"> &nbsp; </div>

            <div class="comments">
                <div class="comment-more center">
                    <button
                            type="button"
                            class="btn btn--line"
                            id="comments--pull"
                            data-fetch-url="<?= Url::to(['reviews/list']) ?>"
                            data-text-go="Показать еще"
                            data-text-await="Идет загрузка"
                    >Показать еще</button>
                </div>
            </div>
            <?php } ?>

        </div>
        <div class="layout-aside layout-aside--long">
            <div class="layout-sticky">

                <div class="layout-box">
                    <div class="review-chart__count"><?= number_format($avgStarsCount,2)?></div>

                    <div class="review-chart">
                        <div class="review-chart__item">
                            <div class="stars">
                                <span style="width: 100%;"></span>
                            </div>
                            <div class="review-chart__line">
                                <span style="width: <?= percent($fiveStarsCount, $reviewsCount) ?>%;"></span>
                            </div>
                            <div class="review-chart__value"><?= $fiveStarsCount ?></div>
                        </div>
                        <div class="review-chart__item">
                            <div class="stars">
                                <span style="width: 80%;"></span>
                            </div>
                            <div class="review-chart__line">
                                <span style="width: <?=percent($fourStarsCount, $reviewsCount) ?>%;"></span>
                            </div>
                            <div class="review-chart__value"><?= $fourStarsCount ?></div>
                        </div>
                        <div class="review-chart__item">
                            <div class="stars">
                                <span style="width: 60%;"></span>
                            </div>
                            <div class="review-chart__line">
                                <span style="width: <?=percent($threeStarsCount, $reviewsCount) ?>%;"></span>
                            </div>
                            <div class="review-chart__value"><?= $threeStarsCount ?></div>
                        </div>
                        <div class="review-chart__item">
                            <div class="stars">
                                <span style="width: 40%;"></span>
                            </div>
                            <div class="review-chart__line">
                                <span style="width: <?=percent($twoStarsCount, $reviewsCount) ?>%;"></span>
                            </div>
                            <div class="review-chart__value"><?= $twoStarsCount ?></div>
                        </div>
                        <div class="review-chart__item">
                            <div class="stars">
                                <span style="width: 20%;"></span>
                            </div>
                            <div class="review-chart__line">
                                <span style="width: <?=percent($oneStarsCount, $reviewsCount) ?>%;"></span>
                            </div>
                            <div class="review-chart__value"><?= $oneStarsCount ?></div>
                        </div>
                    </div>

                    <button type="button"
                            class="btn btn--full"
                            data-micromodal-trigger="modal-review">
                        Написать отзыв
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <!--🤟 Модальное окно Написать отзыв-->
    <div id="modal-review" aria-hidden="true" class="modal">
        <div data-micromodal-close class="modal-overlay">
            <div class="modal-main">
                <div class="modal-title">Написать отзыв</div>
                <div class="modal-close" data-micromodal-close></div>
                <div class="modal-content">
                    <?= $this->render('create', ['reviewForm' => $reviewForm]); ?>
                </div>
            </div>
        </div>
    </div>

</div>


</div>