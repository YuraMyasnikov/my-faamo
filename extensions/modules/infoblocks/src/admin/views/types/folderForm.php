<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h2>Редактирование папки инфоблока <?= $model->name ?></h2>
<div style="margin: 10px 0"></div>
<div class="form" ng-controller="IBlockTypeCtrl">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= $this->title;?></h3>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <div class="card-body">
                <?php echo Html::activeLabel($model, 'name'); ?>
                <?php echo Html::activeTextInput($model, 'name', ['size' => 60, 'maxlength' => 255]); ?>
                <?php echo Html::error($model, 'name'); ?>
            </div>
            <div class="card-footer">
                <?php echo Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['ng-show' => 'canSave()', 'class' => 'btn btn-success pull-right']); ?>
            </div>
            <?php $form::end(); ?>
        </div>
    </div>
</div>