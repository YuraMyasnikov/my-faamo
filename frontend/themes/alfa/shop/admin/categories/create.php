<?php

use CmsModule\Shop\admin\assets\TinyMCEInitAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use CmsModule\Shop\common\categorys\Categories;

/** @var yii\web\View $this */
/** @var Categories $category */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

TinyMCEInitAsset::register($this);

?>
<div style="margin: 10px 0"></div>
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title;?></h3>
        </div>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to(['categories/create']),
            'options' => [
                'data-tinymce' => [
                    'selector' => '.tmc',
                    'upload-image_url' => Url::to(['/admin/upload']),
                    'csrf-token' => Yii::$app->request->csrfToken
                ]
            ]
        ]); ?>
        <div class="card-body">
            <?= $form->field($category, 'parent_id')->dropDownList($categoriesList); ?>
            <?= $form->field($category, 'name')->textInput(['id' => 'trans-val']); ?>
            <?= $form->field($category, 'code')->textInput(['id' => 'trans-res']); ?>
            <?= $form->field($category, 'description')->textarea(['class' => 'tmc']); ?>
            <?= $form->field($category, 'seo_links')->textarea(['rows' => 15])->label('Перелинковка'); ?>
            <?= $form->field($category, 'active')->checkbox(); ?>
        </div>
        <div class="card-footer">
            <?= Html::a('Отменить', ['categories/index'], ['class' => 'btn btn-default pull-left']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>