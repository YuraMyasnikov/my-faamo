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

$this->title = 'Создание товара';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];

$breadcrumbsCategories = BreadcrumbsHelper::getTreeParentForCategory($category_id);
$this->params['breadcrumbs'] = $breadcrumbsCategories;

$this->params['breadcrumbs'][] = ['label' => $this->title];

TinyMCEInitAsset::register($this);
?>

<div style="margin: 10px 0"></div>

<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title;?></h3>
        </div>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to(['products/create', 'category_id' => $category_id]),
            'options' => [
                'data-tinymce' => [
                    'selector' => 'textarea',
                    'upload-image_url' => Url::to(['/admin/upload']),
                    'csrf-token' => Yii::$app->request->csrfToken
                ]
            ]
        ]); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <?= $form->field($product, 'category_id')->dropDownList($categoriesList); ?>
                    <?= $form->field($product, 'name')->textInput(['id' => 'trans-val']); ?>
                    <?= $form->field($product, 'code')->textInput(['id' => 'trans-res']); ?>
                    <?= $form->field($product, 'articul')->textInput(); ?>
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
        </div>
        <div class="card-footer">
            <?= Html::a('Отменить', ['products/index'], ['class' => 'btn btn-default pull-left']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
            <?= Html::submitButton('Применить', ['class' => 'btn btn-success pull-right', 'name' => 'apply-button']) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>
