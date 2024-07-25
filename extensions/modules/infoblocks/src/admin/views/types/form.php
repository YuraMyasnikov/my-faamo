<?php

/** @var View $this */
/** @var InfoblockTypes $model */


use CmsModule\Infoblocks\{admin\assets\TypeAsset, models\InfoblockTypes, models\InvoltaIblockFolders};
use yii\{helpers\Html, helpers\Json, widgets\ActiveForm, web\View};
use CmsModule\Infoblocks\admin\widgets\treeview\Widget as TreeView;

$this->title = $model->isNewRecord ? 'Новый тип' : 'Редактирование типа «' . $model->name . '»';
$this->params['breadcrumbs'][] = [
    'label' => 'Типы инфоблоков',
    'url' => '/admin/infoblocks/types'
];

$this->params['breadcrumbs'][] = $this->title;
TypeAsset::register($this);
?>
<script>
    var PHPCONFIG = <?= Json::encode($properties) ?>;
</script>

<div class="form" ng-controller="IBlockTypeCtrl">
    <div style="margin: 10px 0"></div>
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= $this->title;?></h3>
            </div>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'name')->textInput(); ?>
                        <?= $form->field($model, 'code')->textInput(); ?>

                        <div class="fll">
                            <h5>Папка инфоблока</h5>
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
                            <h5>Роли пользователей</h5>

                            <div class="form-check">
                                <?php foreach ($roles as $k => $role): ?>
                                    <?= Html::checkbox("roles[{$role->name}]", isset($modelRoles[$role->name]), ['class' => 'form-check-input', 'id' => "roles_{$role->name}"]) ?>
                                    <?= Html::label($role->name, "roles_{$role->name}", ['class' => 'checkbox-label',]) ?><br>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <input type="hidden" name="deleteProps" value="{{deleteProps | json}}">
                        <h5>Свойства</h5>
                        <div class="box-body table-responsive no-padding">
                            <table class="skuitems-table table table-bordered table-striped dataTable">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Код</th>
                                <th>Множ.</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>

                            <tr ng-repeat="p in properties">
                                <td>
                                    <input name="Properties[{{getCode(p)}}][relation]" type="hidden" ng-value="p.relation | json" class="form-control">
                                    <input name="Properties[{{getCode(p)}}][validators]" type="hidden" ng-value="p.validators | json" class="form-control">
                                    <input name="Properties[{{getCode(p)}}][name]" type="text" ng-model="p.name" class="form-control">
                                </td>
                                <td>
                                    <input type="hidden" name="Properties[{{getCode(p)}}][type]" value="{{p.type}}" class="form-control">
                                    <select ng-model="p.type" ng-options="t.id as t.name for t in types" class="form-control"></select>
                                </td>
                                <td>
                                    <input name="Properties[{{getCode(p)}}][code]" type="text" ng-model="p.new_code" class="form-control">
                                </td>
                                <td>
                                    <input id="multi{{ $index }}" class="checkbox" name="Properties[{{getCode(p)}}][multi]" type="checkbox"
                                           ng-model="p.multi" class="form-control">
                                    <label for="multi{{ $index }}" class="checkbox-label"></label>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" ng-click="updateProp($index)" onclick="return false;"
                                       class="update icon-btn"></a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" ng-click="deleteProp($index)" onclick="return false;"
                                       class="delete icon-btn"></a>
                                </td>
                            </tr>

                            <tr style="border-top:1px solid #e4e4e4">
                                <td colspan="6" style="padding:0 0 0 8px;">
                                    <a class="flat-btn flr" href="javascript:void(0)" ng-click="addProperty()" onclick="return false;">Добавить
                                        свойство</a>
                                </td>
                            </tr>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?= Html::a('Отменить', ['/admin/infoblocks/types'], ['class' => 'btn btn-default pull-left']) ?>
                <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['ng-show' => 'canSave()', 'class' => 'btn btn-success']) ?>
            </div>
            <?php $form::end(); ?>
        </div>
    </div>
</div>
