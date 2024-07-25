<?php

/**
 * @var array<ActiveRecord> $blogs
 */

use Codeception\Lib\Interfaces\ActiveRecord;

use cms\common\models\Images;
use yii\helpers\Url;
use yii\helpers\Html;

?>

<?php if ($blogs) { ?>
    <?php foreach ($blogs as $blogKey => $blog) { ?>
        <?php
            $url = Url::to(['/blogs/frontend/default/item', 'code' => $blog->code]);
        ?>
        <div class="blog-page-item">
            <a href="<?= $url ?>" class="blog-page-item-img">
                <img src="<?= Images::findOne(['id' => $blog->blog_image])?->file ?>" alt="<?= Html::encode($blog->blog_title)?>"" title="<?= Html::encode($blog->blog_title)?>"" />
            </a>

            <?php if($blogKey <= 3):?>
                <p class="blog-page-item-title">
                    <a href="<?= $url; ?>" title=" <?= Html::encode($blog->blog_title)?>" ><?= $blog->blog_title?></a></p>
                <p class="blog-page-item-date"><?= Yii::$app->formatter->asDate($blog->created_at) ?></p>
            <?php else:?>
                <p class="blog-page-item-date"><?= Yii::$app->formatter->asDate($blog->created_at) ?></p>
                <p class="blog-page-item-title"><a href="<?= $url; ?>" title="<?= Html::encode($blog->blog_title)?>"" ><?= $blog->blog_title?></a></p>
            <?php endif;?>

            <p class="blog-page-item-text"><?= $blog->blog_description?></p>

        </div>
    <?php } ?>
<?php } ?>