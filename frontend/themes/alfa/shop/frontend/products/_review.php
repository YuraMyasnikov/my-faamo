<?php 

/** @var Infoblock $review */

use yii\helpers\Html;

$img = Yii::$app->db->createCommand("
    SELECT value
    FROM __iblock_content_product_reviews_multi_image
    WHERE content_id = $review->id
")->queryOne();

?>

<div class="reviews-wrp-item">
    <div class="reviews-wrp-item-top">
        <span class="reviews-wrp-item-top-name"><?= Html::encode($review->client_name); ?><!--, Москва--></span>
        <?php if ($review->grade) :?>
            <?php for ($i = 0; $i <= 5; $i++): ?>
                <?php if ($i < $review->grade): ?>
                    <img src="/images/icon-star-black.svg">
                <?php elseif ($i > $review->grade): ?>
                    <img src="/images/icon-star-gray.svg">
                <?php endif;?>
            <?php endfor; ?>
        <?php endif;?>
    </div>
    <div class="reviews-wrp-item-text example_p_review">
        <p><?= Html::encode($review->text); ?></p>
    </div>

    <?php if ($img): ?>
        <?php $imagePathPart = "/uploads/content/product_reviews/" . $review->id . "/" . $img['value'];?>
        <?php $imagePathSearch = Yii::getAlias("@webroot") . "/uploads/content/product_reviews/" . $review->id . "/" . $img['value'];?>
        <?php $extensions = ['png', 'jpeg', 'jpg'];?>

        <?php foreach ($extensions as $extension): ?>
            <?php $imagePath = $imagePathPart . '.' . $extension; ?>
            <?php $imagePathSearchExt = $imagePathSearch . '.' . $extension; ?>

            <?php if ( file_exists( $imagePathSearchExt) ): ?>
                <a href="<?= $imagePath ?>" data-fancybox="catalog-item-gallery" class="review-add-img-item">
                    <img style="width: 4rem; height: 4rem;" src="<?= $imagePath ?>">
                </a>
                <?php break;?>
            <?php endif;?>

        <?php endforeach;?>
    <?php endif;?>
</div>

