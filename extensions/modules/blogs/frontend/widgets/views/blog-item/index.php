<?php

use frontend\services\CityCodeResolver;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

    <?php if ($blog): ?>
    <div class="bx-center width-default">
        <div class="blog-item-page">
            <h1><?= $blog->blog_title?></h1>

            <div class="blog-item-page-date"><?= Yii::$app->formatter->asDate($blog->created_at) ?></div>
        </div>
        <div class="blog-item-page-content js-sticky-row">
            <div class="blog-item-page-left">
                <img class="blog-content-img" src="<?= Url::to(["/uploads/content/blog/{$blog->id}/{$blog->blog_image}.jpeg"])?>"
                     alt="<?= $blog->blog_title?>"
                     title="<?= $blog->blog_title?>">
                <?= $blog->blog_text?>
            </div>

            <div class="blog-item-page-right">
                <div class="blog-item-page-right-content js-sticky-box" data-margin-top="30"
                     data-margin-bottom="30">
                    <p class="recomendation-title">Рекомендуемые товары</p>
                    <?php foreach ($products as $product): ?>
                    <div class="blog-recomendation">
                        <div class="blog-recomendation-img">
                            <a href='<?= Url::to(["/shop/frontend/products/view", 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>'><img
                                src='<?= "/uploads/product/{$product->id}/{$product->image_id}.jpg"?>'
                                alt="<?= Html::encode($product->name)?>"
                                title="<?= Html::encode($product->name)?>">
                            </a>
                        </div>
                        <a href="<?= Url::to(["/shop/frontend/products/view", 'code' => $product->code, 'city' => $cityCodeResolver->getCodeForCurrentCity()])?>" class="category-item-name link-line" title="<?= $product->name?>" ><span><?= $product->name?></span></a>
                        <div class="category-item-price">
                            <?php  
                                $skus = $product->sku;
                            ?>
                            <?php if(count($skus)) { ?>
                                <?= $skus[0]->price?> ₽ <span class="category-item-price-old"><?= $skus[0]->old_price?> ₽</span>
                            <?php } ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <?php
            $blogsInfoblog = \CmsModule\Infoblocks\models\Infoblock::byCode('blog');
            $blogs = $blogsInfoblog::find()->where(['active' => true])->orderBy(['created_at' => SORT_DESC])->all();
        ?>
        <?php if($blogs): ?>
            <div class="blog-slider-wrp">
                <h3 class="weight">Возможно вам понравится</h3>
                <div class="blog-page-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($blogs as $item): ?>
                            <div class="swiper-slide">
                                <a href="<?= Url::to(['/blogs/frontend/default/item', 'code' => $item->code])?>" class="blog-page-item-img" title="<?= Html::encode($item->name)?>">
                                    <img src="<?= Url::to(["/uploads/content/blog/{$item->id}/{$item->blog_image}.jpeg"])?>"
                                         alt="<?= Html::encode($item->blog_title)?>"
                                         title="<?= Html::encode($item->blog_title)?>">
                                </a>
                            
                                <p class="blog-page-item-date"><?= Yii::$app->formatter->asDate($item->created_at) ?></p>
                                <p class="blog-page-item-title">
                                    <a href="<?= Url::to(['/blogs/frontend/default/item', 'code' => $item->code]); ?>" title="<?= Html::encode($item->blog_title)?>" ><?= $item->blog_title?></a>
                                </p>
                                <p class="blog-page-item-text"><?= $item->blog_description?></p>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <div class="blog-pagination">
                        <div class="slider-next"></div>
                        <div class="slider-prev"></div>
                    </div>
                </div>
            </div>

        <?php endif;?>
    </div>
<?php endif;?>
