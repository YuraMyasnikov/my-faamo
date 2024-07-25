<?php

use CmsModule\Shop\admin\assets\TinyMCEInitAsset;
use CmsModule\Shop\admin\helpers\BreadcrumbsHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use CmsModule\Shop\common\models\Products;

/** @var yii\web\View $this */
/** @var Products $product */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Изменение товара';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];

$breadcrumbsCategories = BreadcrumbsHelper::getTreeParentForCategory($product->mainCategory->id);
$this->params['breadcrumbs'] = $breadcrumbsCategories;
$this->params['breadcrumbs'][] = ['label' => $product->name];

TinyMCEInitAsset::register($this);

?>


<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Основные</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Фото</a>
            </li>
            <li class="nav-item">
                <a 
                    class="nav-link" 
                    href="<?= Url::to(['products/related-products', 'id' => $product->id]); ?>" 
                    role="tab" 
                    aria-selected="false"
                >Сопутствующие товары (<?= $relatedProductsCount ?>)</a>
            </li>
            <li class="nav-item">
                <a 
                    class="nav-link" 
                    href="<?= Url::to(['products/related-categories', 'id' => $product->id]); ?>" 
                    role="tab" 
                    aria-selected="false"
                >Дополнительные категории (<?= $relatedCategoriesCount ?>)</a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['sku/index', 'product_id' => $product->id]); ?>" class="nav-link">Торговые предложения (<?= count($product->sku); ?>)</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-four-tabContent">
            <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'action' => Url::to(['products/update', 'id' => $product->id]),
                    'options' => [
                        'data-tinymce' => [
                            'selector' => 'textarea',
                            'upload-image_url' => Url::to(['/admin/upload']),
                            'csrf-token' => Yii::$app->request->csrfToken
                        ]
                    ]
                ]); ?>
                <div class="row">
                    <div class="col-md-7">
                        <?= $form->field($product, 'category_id')->dropDownList($categoriesList); ?>
                        <?= $form->field($product, 'name')->textInput(['id' => 'trans-val']); ?>
                        <?= $form->field($product, 'code')->textInput(['id' => 'trans-res']); ?>
                        <?= $form->field($product, 'articul')->textInput(); ?>
                        <?= $form->field($product, 'price')->input('number', ['min' => 0]) ?>
                        <?= $form->field($product, 'description')->textarea(); ?>
                        <?= $form->field($product, 'description_composition_and_care')->textarea()->label('Состав и уход'); ?>
                        <?= $form->field($product, 'description_pilling')->textarea(); ?>  
                        <?= $form->field($product, 'description_measurements')->textarea(); ?>  
                        <?= $form->field($product, 'sort')->input('number'); ?>
                        <?= $form->field($product, 'active')->checkbox(); ?>
                        <?= $form->field($product, 'use_multi_sku')->checkbox(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php foreach ($optionsList as $options) { ?>
                            <?= $form->field($product, 'options[' . $options['id'] . ']')->dropDownList($options['value'])->label($options['label']); ?>
                        <?php } ?>

                        <?php foreach ($multiOptionsList as $multi_options) { ?>
                            <?= $form->field($product, 'multi_options[' . $multi_options['id'] . ']')
                                ->dropDownList($multi_options['value'], ['multiple' => true])
                                ->label($multi_options['label']);
                            ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-footer">
                    <?= Html::a('Отменить', ['products/index'], ['class' => 'btn btn-default pull-left']) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
                    <?= Html::submitButton('Применить', ['class' => 'btn btn-success pull-right', 'name' => 'apply-button']) ?>
                </div>
            
                <?php $form::end(); ?>
            </div>
            <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                <section class="item--block item--photos-block">
                    <article class="gray-block photos">
                    <?= $this->render('images', ['product' => $product]) ?>
                    </article>
                </section>
            </div>
        </div>
    </div>

</div>