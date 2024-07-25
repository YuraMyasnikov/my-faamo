<?php

use yii\helpers\Html;
use yii\web\View;
use CmsModule\Shop\common\models\Categories;

/**
 * @var View $this
 * @var array $categories
 */

?>

<div class="nav-catalog">
    <ul class="nav-catalog__list">
        <?php foreach ($categories as $category) { ?>
            <li class="nav-catalog__list-item">
                <a class="nav-catalog__item-link" href="<?= Html::encode($category['url']); ?>">
                    <div class="nav-catalog__item-img"><img src="<?= $category['image'] ?>" alt=""></div>
                    <div class="nav-catalog__item-title"><?= Html::encode($category['name']); ?></div>
                </a>

                <?php if (!empty($category['children'])) { ?>
                    <div class="submenu__wrap">
                        <button class="submenu__button" type="button"></button>
                        <ul class="submenu">
                            <?php foreach ($category['children'] as $category_child) { ?>
                                <li class="submenu__item">
                                    <a href="<?= Html::encode($category_child['url']); ?>" class="submenu__item-link"><?= Html::encode($category_child['name']); ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</div>