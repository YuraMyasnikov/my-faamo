<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use CmsModule\Shop\common\models\Options;

/** @var yii\web\View $this */
/** @var Options $option */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Создание свойства';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div style="margin: 10px 0"></div>
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title;?></h3>
        </div>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => Url::to(['options/create'])
        ]); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">3333
                    <?= $form->field($option, 'type')->dropDownList(Options::getTypesList()); ?>
                    <?= $form->field($option, 'name')->textInput(['id' => 'trans-val']); ?>
                    <?= $form->field($option, 'code')->textInput(['id' => 'trans-res']); ?>
                    <?= $form->field($option, 'sort')->input('number'); ?>
                    <?= $form->field($option, 'active')->checkbox(); ?>
                    <?= $form->field($option, 'filter')->checkbox(); ?>
                    <?= $form->field($option, 'friendly_url')->checkbox(); ?>
                    <?= $form->field($option, 'multi')->checkbox(); ?>
                </div>
                <div class="col-md-7">
                    <?= $form->field($option, 'categoriesArray')->dropDownList($categoriesList,  ['multiple' => true, 'size' => '20']) ?>
                </div>

                <?php if ($categoriesList) { ?>
                    <?= $form->field($option, 'categoriesFriendlyUrlArray')->dropDownList($categoriesList, ['multiple' => true, 'size' => '20']); ?>
                <?php } ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::a('Отменить', ['options/index'], ['class' => 'btn btn-default pull-left']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
        </div>
        <?php $form::end(); ?>
    </div>
</div>
