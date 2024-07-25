<?php

use yii\helpers\Html;
use yii\helpers\Url;
use CmsModule\Shop\frontend\forms\ProductsFilterForm;

/**
 * @var ProductsFilterForm $model
 */

?>
<div class="filter-block__wrap">
    <div class="filter-block">
        <h2 class="filter-title"><?= $category->name ?>:</h2>
        <div class="filter-block__radio">
            <div class="filter-list__row">
                <input class="filter-radio" type="radio" id="pile1" name="filter_category" checked="<?= $category->code === $model->filter_category || $model->filter_category === null; ?>" value="<?= Html::encode($category->code); ?>">
                <label class="filter-radio__label" for="pile1">Все</label>
            </div>
            <?php foreach ($category->children as $child_category) { ?>
                <div class="filter-list__row">
                    <input class="filter-radio" type="radio" id="<?= $child_category->code ?>" <?= $child_category->code === $model->filter_category ? 'checked' : ''; ?> name="filter_category" value="<?= Html::encode($child_category->code); ?>">
                    <label class="filter-radio__label" for="<?= $child_category->code ?>"><?= Html::encode($child_category->name); ?></label>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="filter-price__block filter-block">
        <h2 class="filter-title">Цена, <span class="icon-rub"></span></h2>
        <div class="price-block__fields">
            <div class="price-block__field">
                <input class="price-block__field-value" id="min-price-value" type="number" name="min-price" value="<?= $model->minPrice ?>">
            </div>
            <div class="price-block__field">
                <input class="price-block__field-value" id="max-price-value" type="number" name="max-price" value="<?= $model->maxPrice ?>">
            </div>
        </div>
        <div class="range-wrap">
            <div class="range-line"></div>
            <input id="min-pr" class="range-line__item" type="range" name="prices" min="0" max="9999999" step="1" value="<?= $model->minPrice ?>">
            <input id="max-pr" class="range-line__item" type="range" name="prices1" min="0" max="9999999" step="1" value="<?= $model->maxPrice ?>">
        </div>
    </div>
    <?php
    if (is_array($model->data['catalogs'])) {
        foreach ($model->data['catalogs'] as $key => $prop) { ?>
            <details class="filter-block" open>
                <summary id="<?= $prop['code'] ?>" data-friendly="<?= $prop['friendly_url']; ?>" class="filter-title"><?= Html::encode($prop['name']); ?> <span class="check-places"></span></summary>
                <div class="filter-list">
                    <?php foreach ($prop['params'] as $param) { ?>
                        <div class="filter-list__row">
                            <input 
                                <?= $param['isChecked'] ? "checked" : "" ?> 
                                class="filter-checkbox"
                                type="checkbox"
                                id="<?= Html::encode($param['code']); ?>"
                                value="<?= Html::encode($param['code']); ?>"
                                name="<?= Html::encode($prop['code']); ?>"
                                data-catalog-sort="<?= $prop['sort']; ?>"
                                data-sort="<?= $param['sort']; ?>"
                            >
                            <label for="<?= Html::encode($param['code']); ?>"><?= Html::encode($param['name']); ?></label>
                        </div>
                    <?php } ?>
                </div>
            </details>
        <?php } ?>
    <?php } ?>

    <div class="filter-buttons__wrap">
        <button class="filter-show">Показать выбранное</button>
        <a class="filter-clear" data-pjax="0" href="<?= Url::to(['/catalog/' . $category->code]); ?>">Сбросить фильтр</a>
    </div>
</div>