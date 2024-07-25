<?php

use CmsModule\Infoblocks\admin\helpers\UrlHelper;
use CmsModule\Infoblocks\entity\InfoblockTableProperty;
use CmsModule\Infoblocks\forms\InfoblockTableForm;
use CmsModule\Infoblocks\forms\InfoblockTablePropertyForm;
use yii\helpers\Html;
use yii\web\View;
use cms\common\frontend\ui\ActiveForm;

/** @var View $this */
/** @var InfoblockTableForm $tableForm */
/** @var InfoblockTablePropertyForm $tablePropertyForm */
/** @var InfoblockTableProperty[] $tableProperties */
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($tableForm, 'name')->textInput(['placeholder' => true]) ?>
<?= $form->field($tableForm, 'code')->textInput(['placeholder' => true]) ?>

<table class="table table-bordered">
    <caption>Поля табличной части</caption>
    <thead>
    <tr>
        <td>Название</td>
        <td>Код</td>
        <td></td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($tableProperties as $tableProperty) { ?>
        <tr>
            <td><?= $tableProperty->getName() ?></td>
            <td><?= $tableProperty->getCode() ?></td>
            <td><a href="<?= UrlHelper::deleteInfoblockTableProperty($tableProperty) ?>">Удалить</a></td>
        </tr>
    <?php } ?>
    <tr>
        <td><?= $form->field($tablePropertyForm, 'name')->textInput()->label(false) ?></td>
        <td><?= $form->field($tablePropertyForm, 'code')->textInput()->label(false) ?></td>
    </tr>
    </tbody>
</table>


<?= Html::submitButton('Сохранить', ['class' => 'btn']) ?>

<?php $form::end() ?>
