<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'loadFile')->fileInput() ?>

<?= Html::submitButton('Sumbit', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>