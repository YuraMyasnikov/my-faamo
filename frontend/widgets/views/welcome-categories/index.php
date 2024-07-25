<?php

use cms\common\models\Images;
use frontend\services\CityCodeResolver;
use yii\helpers\Html;

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

<?php foreach ($categories as $category) { ?>
    <?php
        /** @var \yii\db\ActiveRecord $category */ 
        $name1     = $category->getAttribute('name');    
        $name2     = $category->getAttribute('name_in_main_navigation');    
        $name      = !empty($name2) ? $name2 : $name1;
        $url       = $cityCodeResolver->getUrlPrefixForCurrentCity() . $category->getAttribute('url');    
        $isFull    = (bool) $category->getAttribute('is_show_in_main_navigation_like_full');
        $image     = Images::findOne(['id' => (int) $category->getAttribute('img_default')]);
        $imageFull = $isFull ? Images::findOne(['id' => (int) $category->getAttribute('img_full')]) : null;
    ?>
<div class="category-item <?= $isFull ? 'full' : ''?>">
    <div class="category-item-img">
        <a href="<?= $url ?>" title="<?= Html::encode($name)?>">
            <img src="<?= $isFull ? $imageFull?->file : $image?->file ?>" alt="<?= Html::encode($name)?>" title="<?= Html::encode($name)?>">
        </a>
    </div>
    <a href="<?= $url ?>" class="category-item-name link-line" title="<?= Html::encode($name)?>"><span><?= $name ?></span></a>
</div>
<?php } ?>