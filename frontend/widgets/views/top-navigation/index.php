<?php

use cms\common\models\Images;
use frontend\services\CityCodeResolver;
use yii\helpers\Html;

$countCategories = is_array($categories) ? count($categories) : 0;
$countCategoriesForBar = $countCategories >= 2 ? round($countCategories / 2) : $countCategories;
$countSubCategories = is_array($subCategories) ? count($subCategories) : 0;

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>

<?php if($countCategories) { ?>
    <?php 
        $_index = 1;     
    ?>
    <div class="sub-menu-bx-left">
    <ul>
    <?php foreach($categories as $category) { ?>
        <?php
            /** @var \yii\db\ActiveRecord $category */ 
            $name1     = $category->getAttribute('name');    
            $name2     = $category->getAttribute('name_in_head_navigation');    
            $name      = !empty($name2) ? $name2 : $name1;
            $url       = $cityCodeResolver->getUrlPrefixForCurrentCity() . $category->getAttribute('url');    
        ?>
        <?php if($_index > $countCategoriesForBar ) { $_index = 0; ?>
            </ul><ul>
        <?php } ?>    
        <li><a href="<?= $url ?>" class="link-line" title="<?= Html::encode($name) ?>"><span><?= $name ?></span></a></li>
        <?php $_index++; ?>
    <?php } ?> 
    </ul>
    </div>   
<?php } ?>    

<?php if($countSubCategories) { ?>
    <div class="sub-menu-bx-right">
    <?php foreach($subCategories as $category) { ?>
        <?php 
            /** @var \yii\db\ActiveRecord $category */ 
            $name1     = $category->getAttribute('name');    
            $name2     = $category->getAttribute('name_in_head_sub_navigation');    
            $name      = !empty($name2) ? $name2 : $name1;
            $url       = $cityCodeResolver->getUrlPrefixForCurrentCity() . $category->getAttribute('url');   
            $image     = Images::findOne(['id' => (int) $category->getAttribute('img_square')]);     
        ?>
        <div class="sub-menu-bx-right-item">
            <div class="sub-menu-bx-right-item-img">
                <a href="<?= $url ?>">
                    <img src="<?= $image?->file ?>" alt="<?= Html::encode($name) ?>" title="<?= Html::encode($name) ?>">
                </a>
            </div>
            <a href="<?= $url ?>" class="link-line white" title="<?= Html::encode($name) ?>"><span><?= $name ?></span></a>
        </div>
    <?php } ?>    
    </div>
<?php } ?>    