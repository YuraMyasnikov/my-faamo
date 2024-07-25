<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use CmsModule\Shop\common\models\Products;

/** @var yii\web\View $this */
/** @var Products $product */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Изменение ТП';
$this->params['breadcrumbs'][] = ['label' => 'Торговые предложения', 'url' => ['index', 'product_id' => $sku->product_id]];
$this->params['breadcrumbs'][] = ['label' => $sku->name];
?>
<div style="margin: 10px 0"></div>

<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $this->title;?></h3>
        </div>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
        ]); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <?= $form->field($sku, 'name')->textInput(); ?>
                    <?= $form->field($sku, 'code')->textInput(); ?>
                    <?= $form->field($sku, 'description')->textarea(); ?>
                    <?= $form->field($sku, 'sort')->input('number'); ?>
                    <?= $form->field($sku, 'remnants')->input('number', ['min' => 0]); ?>
                    <?= $form->field($sku, 'price')->input('number', ['min' => 0]); ?>
                    <?= $form->field($sku, 'old_price')->input('number', ['min' => 0]); ?>
                    <?= $form->field($sku, 'price_square_meter')->input('number', ['min' => 0]); ?>
                    <?= $form->field($sku, 'old_price_square_meter')->input('number', ['min' => 0]); ?>
                    <?= $form->field($sku, 'active')->checkbox(); ?>
                </div>

                <div class="col-md-4">
                    <?php foreach ($optionsList as $options) { ?>
                        <?= $form->field($sku, 'options[' . $options['id'] . ']')->dropDownList($options['value'])->label($options['label']); ?>
                    <?php } ?>

                    <?php foreach ($multiOptionsList as $multi_options) { ?>
                        <?= $form->field($sku, 'multi_options[' . $multi_options['id'] . ']')
                            ->dropDownList($multi_options['value'], ['multiple' => true])
                            ->label($multi_options['label']);
                        ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::a('Отменить', ['sku/index', 'product_id' => $sku->product_id], ['class' => 'btn btn-default pull-left']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>

        </div>
        <?php $form::end(); ?>
    </div>
</div>
