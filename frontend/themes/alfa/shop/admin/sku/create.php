<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use CmsModule\Shop\common\models\Products;
use frontend\models\shop\Products as ShopProducts;

/** @var yii\web\View $this */
/** @var Products $sku */
/** @var yii\widgets\ActiveForm $form */

$product = ShopProducts::findOne(['id' => $product_id]);
if($product) {
    $sku->price = $product->price;
} else {
    $sku->price = 0;
    $sku->active = false; 
}
 

$this->title = 'Создание ТП';
$this->params['breadcrumbs'][] = ['label' => 'Торговые предложения', 'url' => ['index', 'product_id' => $product_id]];
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
            'action' => Url::to(['sku/create', 'product_id' => $product_id])
        ]); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <?= $form->field($sku, 'name')->textInput(); ?>
                    <?= $form->field($sku, 'code')->textInput(); ?>
                    <?= $form->field($sku, 'description')->textarea(); ?>
                    <?= $form->field($sku, 'sort')->input('number'); ?>
                    <?= $form->field($sku, 'price')->input('number', ['min' => 0, 'disabled' => true]); ?>
                    <?= $form->field($sku, 'active')->checkbox(); ?>
                </div>

                <div class="col-md-4">
                    <?php foreach ($optionsList as $options) { ?>
                        <?php 
                            echo $form
                                ->field($sku, 'options[' . $options['id'] . ']')
                                ->dropDownList($options['value'])->label($options['label']); 
                        ?>
                    <?php } ?>

                    <?php foreach ($multiOptionsList as $multi_options) { ?>
                        <?php  
                            echo $form
                                ->field($sku, 'multi_options[' . $multi_options['id'] . ']')
                                ->dropDownList($multi_options['value'], ['multiple' => true])
                                ->label($multi_options['label']);
                        ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::a('Отменить', ['sku/index', 'product_id' => $product_id], ['class' => 'btn btn-default pull-left']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>

        </div>
        <?php $form::end(); ?>
    </div>
</div>

