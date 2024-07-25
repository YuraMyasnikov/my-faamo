<?php

/**
 * @var $this View
 * @var string $content
 * @var Infoblock $model
 * @var array $siteList
 */

use dosamigos\datetimepicker\DateTimePicker;
use yii\{helpers\Html, helpers\Url, widgets\ActiveForm, web\View, helpers\Json};
use CmsModule\Infoblocks\{models\Infoblock, admin\assets\ContentAsset};

//$model->site_id = Core::getCurrentSiteId();
ContentAsset::register($this);
$this->title = $model->isNewRecord ? 'Создание элемента инфоблока' : 'Изменение элемента «' . $model->name . '»';
$this->params['breadcrumbs'][] = [
    'label' => 'Инфоблоки',
    'url' => '/admin/infoblocks/content'
];
$this->params['breadcrumbs'][] = [
    'label' => $model->infoblockType->name,
    'url' => Url::to(['/admin/infoblocks/content/iblock', 'code' => $model->infoblockType->code])
];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="edit-element">
    <script>
        var CONTENT = <?=Json::encode($content)?>;

        var MULTIDELETE = {};
    </script>

</section>


<!--------------------------------------------------------------------------------->

<div style="margin: 10px 0"></div>
<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title;?></h3>
        </div>
        <div class="form iblock-form">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'ng-controller' => 'IBlockContentCtrl',
                    'ng-init' => 'name = "' . $model->infoblockType->code . '"'
                ],
            ]); ?>
            <input type="hidden" name="content" ng-value="content | json">
            <input type="hidden" name="multiDelete" ng-value="multiDelete | json">
            <input type="hidden" name="delete" ng-value="delete | json">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'name')->textInput(['id' => 'iblockName']); ?>
                        <?= $form->field($model, 'code')->textInput(['id' => 'iblockCode']); ?>
                    </div>
                    <div class="col-md-4">
                        <?php
                        try {
                            echo $form->field($model, 'created_at')->widget(DateTimePicker::class, [
                                'language' => Yii::$app->language,
                                'template' => '{input}',
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd hh:ii:00',
                                    'todayBtn' => true,
                                ],
                                'options' => [
                                    'id' => 'dynamic_created_at'
                                ]
                            ]);
                            // echo Html::activeLabel($model, 'created_at');
                            // echo Html::error($model, 'created_at');
                            // echo DateTimePicker::widget([
                            //     'model' => $model,
                            //     'language' => Yii::$app->language,
                            //     'template' => '{input}',
                            //     'attribute' => 'created_at',
                            //     'clientOptions' => [
                            //         'autoclose' => true,
                            //         'format' => 'yyyy-mm-dd hh:ii:00',
                            //         'todayBtn' => true,
                            //     ],
                            //     'options' => [
                            //         'id' => 'dynamic_created_at'
                            //     ]
                            // ]);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        ?>
                        <?php
                        try {
                            echo $form->field($model, 'updated_at')->widget(DateTimePicker::class, [
                                'language' => Yii::$app->language,
                                'template' => '{input}',
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd hh:ii:00',
                                    'todayBtn' => true,
                                ],
                                'options' => [
                                    'id' => 'dynamic_updated_at'
                                ]
                            ]);

                            // echo Html::activeLabel($model, 'updated_at');
                            // echo DateTimePicker::widget([
                            //     'model' => $model,
                            //     'language' => Yii::$app->language,
                            //     'template' => '{input}',
                            //     'attribute' => 'updated_at',
                            //     'clientOptions' => [
                            //         'autoclose' => true,
                            //         'format' => 'yyyy-mm-dd hh:ii:00',
                            //         'todayBtn' => true,
                            //     ],
                            //     'options' => [
                            //         'id' => 'dynamic_updated_at'
                            //     ]

                            // ]);
                            // echo Html::error($model, 'updated_at');
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        ?>
                        <hr>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'sort')->input('number') ?>
                        <div class="form-check">
                            <?= $form->field($model, 'active')->hiddenInput(['ng-value' => 'iblockActive?1:0'])->label(false) ?>
                            <?= Html::checkbox(false, (int)$model->active === 1, [
                                'class' => 'form-check-input',
                                'id' => 'infoblockreviews-activecb',
                                'ng-model' => 'iblockActive',
                                'ng-init' => 'iblockActive=' . ((int)$model->active === 1 ? 'true' : 'false'),
                                'style' => 'margin:40px 0 0 0;'
                            ]) ?>
                            <?php echo Html::label('Активность', 'infoblockreviews-activecb',
                                ['class' => 'checkbox-label', 'onclick' => '', 'style' => 'margin:37px 0 0 25px;']); ?>
                            <?php echo Html::error($model, 'code'); ?>
                        </div>
                    </div>
                    <div class="input-group" ng-repeat="(contentIndex, i) in content track by contentIndex">
                        <div class="form-group col-xs-12 field-servicescategory-descr">
                            <label class="control-label" ng-bind="i.name"></label>
                            <article ng-if="!i.multi" ng-switch="i.type">
                                <input ng-model="i.value" ng-switch-when="1" type="text" size="60" maxlength="255" class="form-control"> <!--Оформление полей ФИО, емайл и номер заказ-->

                                <textarea ui-tinymce="tinymceOptions" ng-model="i.value" ng-switch-when="2"
                                          style="height: 200px; width: 470px;" class="form-control"></textarea> <!--Оформление полей текст отзыва, новости и описание -->

                                <div class="buttons-block">
                                    <input fileread="i.value" ng-switch-when="3" type="file" id="i.id" style="display: block" class="form-control"><br>
                                    <div class="btn btn-danger" ng-click="deleteVal(i.id)" ng-switch-when="3">Удалить</div>
                                </div> <!--Форма для изображения новости-->

                                <img ng-switch-when="3" ng-class="{{ 'content' + i.id }}" ng-src="{{ i.value }}" width="150px"> <!--Загреженное фото-->

                                <input ng-model="i.value" ng-switch-default type="text"> <!--ХЗ зачем оно-->

                                <input ng-model="i.value" ng-switch-when="4" type="text" readonly size="60" maxlength="255"> <!--ХЗ зачем оно-->

                                <div class="icon-btn update" ng-switch-when="4" ng-click="changeIblickElementLink(contentIndex)"></div>
                                <span ng-switch-when="4" ng-bind="i.title"></span> <!--ХЗ зачем оно-->

                                <input id="cb_{{i.id}}" class="checkbox" type="checkbox" ng-model="i.value" ng-checked="i.value==1"
                                       ng-switch-when="5" style="height: 300px">
                                <label for="cb_{{i.id}}" class="checkbox-label" ng-switch-when="5"></label>

                                <input type="date" ng-model="i.value" ng-switch-when="6">

                                <input type="datetime-local" ng-model="i.value" ng-value="i.value" ng-switch-when="7">

                                <input class="iblock-file" type="file" name="{{i.code}}" ng-switch-when="8">
                                <a href="{{ i.value }}" ng-switch-when="8" target="_blank">Скачать данный файл</a>
                                <div ng-repeat="e in i.errors" ng-bind="e"></div>
                            </article>

                            <article ng-if="i.multi">
                                <table>
                                    <tr ng-repeat="(valueIndex, v)  in i.value track by valueIndex">
                                        <td>
                                            <div ng-switch="i.type">
                                                <input ng-model="v.value" ng-switch-when="1" type="text" class="form-control">
                                                <textarea ui-tinymce="tinymceOptions" ng-model="v.value" ng-switch-when="2"
                                                          style="height: 300px" class="form-control"></textarea>
                                                <input fileread="v.value" class="iblock-multi-file form-control" ng-switch-when="3" type="file">
                                                <img ng-switch-when="3" ng-src="{{ v.value }}" width="150px">
                                                <input ng-model="v.value" ng-switch-when="4" type="text" readonly class="form-control">
                                                <div class="icon-btn update flr" ng-switch-when="4"
                                                     ng-click="changeIblickMultiElementLink(contentIndex, valueIndex)"></div>
                                                <span ng-switch-when="4" ng-bind="v.title"></span>
                                                <input ng-if="!v.value" class="iblock-multi-file form-control" ng-switch-when="8" type="file">
                                                <a ng-if="v.value" ng-switch-when="8" ng-href="{{ v.value }}" download>Скачать файл</a>

                                                <div class="icon-btn delete"
                                                     ng-click="deleteContentValue($parent.$index, $index)"></div>

                                                <input ng-model="v.value" ng-switch-default type="text" class="form-control">
                                            </div>
                                        </td>
                                        <td valign="top">
                                            <div ng-repeat="e in v.errors" ng-bind="e"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="btn btn-secondary" ng-click="addContentValue($index)">Добавить</div>
                                        </td>
                                    </tr>
                                </table>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer input-group input-group--wide">
                <?= Html::a('Отменить', ['options/index'], ['class' => 'btn btn-default pull-left']) ?>
                <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success  pull-right']) ?>
            </div>
            <?php $form::end(); ?>
        </div>
    </div>
</div>
