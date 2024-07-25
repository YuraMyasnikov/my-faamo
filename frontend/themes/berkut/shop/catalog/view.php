<?php

use CmsModule\Shop\common\models\Products;
use CmsModule\Shop\common\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var Products $products
 * @var Categories $category
 */

?>
<div class="content">

   <!--🤟 Хлебные крошки-->
   <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['site/index']) ?>">Главная</a></li>
            <li><?= $category->name ?></li>
        </ul>
    </div>

    <div class="container">

        <h1><?= $category->name ?></h1>

        <div class="layout">
            <div class="layout-content">
            
                <?php foreach ($products as $product) { ?>
                    <p>
                        <a href="<?= Url::to(['/product/view', 'code' => $product->code]); ?>">
                            <?= Html::encode($product->name); ?>
                        </a>
                    </p>
                <?php } ?>
            
            </div>
        </div>
    </div>
</div>