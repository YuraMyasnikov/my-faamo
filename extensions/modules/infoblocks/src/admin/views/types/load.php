<?php

use CmsModule\Infoblocks\admin\assets\TypeAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use CmsModule\Infoblocks\models\InvoltaIblockFolders;
use cms\admin\widgets\treeview\Widget as TreeView;

TypeAsset::register($this);
?>
<script>
    var PHPCONFIG = <?=Json::encode($properties)?>;
</script>

<h2>Редактирование инфоблока типа <?= $model->name ?></h2>

<div class="form" ng-controller="IBlockTypeCtrl">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <div class="input-group fll" style="margin-right:20px">
        <?php echo Html::activeTextInput($model, 'name', array('size' => 60, 'maxlength' => 255, 'style' => 'width:300px;')); ?>
        <?php echo Html::activeLabel($model, 'name'); ?>
        <?php echo Html::error($model, 'name'); ?>
    </div>
    <div class="input-group fll" style="margin-right:20px">
        <?php echo Html::activeTextInput($model, 'code', array('size' => 60, 'maxlength' => 255, 'style' => 'width:300px;')); ?>
        <?php echo Html::activeLabel($model, 'code'); ?>
        <?php echo Html::error($model, 'code'); ?>
    </div>


    <span class="clear"></span>
    <div class="fll">
        <span>Доступ для сайта</span>

        <div class="skuitems-item-regions" style="margin-right: 20px;">
            <?php foreach ($involtaSites as $involtaSiteIndex => $involtaSite): ?>
                <?= Html::checkbox("involtaSites[{$involtaSite->id}]", $modelSites[$involtaSite->id]['checked'], ['class' => 'checkbox', 'id' => "involtaSites_{$involtaSite->id}"]) ?>
                <?= Html::label($involtaSite->name, "involtaSites_{$involtaSite->id}", ['class' => 'checkbox-label',]) ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="fll">
        <span>Папка инфоблока</span>
        <?php echo Html::activeTextInput($model, 'folder_id', ['ng-model' => 'folder_id', 'style' => 'display:none']); ?>
        <div class="skuitems-item-regions" style="margin-right: 20px;"
             ng-init="folder_id=<?= $model->folder_id ?: 'null' ?>">
            <?= TreeView::widget([
                'items' => InvoltaIblockFolders::getEditTreeArray(null, $model->folder_id)
            ])
            ?>
        </div>
    </div>
    <div class="fll">
        <span>Роли пользователей</span>

        <div class="skuitems-item-regions" style="margin-right: 20px;">
            <?php foreach ($roles as $k => $role): ?>
                <?= Html::checkbox("roles[{$role->name}]", (bool)$modelRoles[$role->name], ['class' => 'checkbox', 'id' => "roles_{$role->name}"]) ?>
                <?= Html::label($role->description, "roles_{$role->name}", ['class' => 'checkbox-label',]) ?>
            <?php endforeach; ?>
        </div>
    </div>

    <span class="clear"></span>

    <?= $form->field($infoblockLoad, 'loadFile')->fileInput() ?>

    <?php echo Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['ng-show' => 'canSave()']); ?>


    <?php ActiveForm::end(); ?>

</div>
