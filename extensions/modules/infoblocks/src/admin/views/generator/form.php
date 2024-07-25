<?php
/**
 * This is the template for generating an action view file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\form\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
\dosamigos\tinymce\TinyMceAsset::register($this);
/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form ActiveForm */
<?= '?>' ?>


<?= '<?php ' ?>$form = ActiveForm::begin(); ?>


<?php foreach ($model->getAttributes() as $key => $attribute): ?>
    <?php if ('string' === $properties[$key]): ?>
        <?= '<?= ' ?>$form->field($model, '<?= $key ?>')->textInput() ?>
    <?php elseif ('text' === $properties[$key]): ?>
        <?= '<?= ' ?>$form->field($model, '<?= $key ?>')->widget(\dosamigos\tinymce\TinyMce::className(), [
        'language' => 'ru',
        'clientOptions' => [
        'paste_data_images' => true,
        'plugins' => 'image paste code textcolor fileupload autoresize visualblocks',
        'toolbar' => 'bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, alignjustify, fontsizeselect, bullist, numlist, outdent, indent, fileupload | undo, redo, removeformat, forecolor',
        'relative_urls' => false,
        'remove_script_host' => false,
        'convert_urls' => true
        ]
        ]) ?>
    <?php elseif ('image' === $properties[$key]): ?>
        <?= '<?= ' ?>$form->field($model, '<?= $key ?>')->fileInput() ?>
    <?php elseif ('checkbox' === $properties[$key]): ?>
        <?= '<?= ' ?>$form->field($model, '<?= $key ?>')->checkbox() ?>
    <?php elseif ('date' === $properties[$key]): ?>
        <?= '<?= ' ?>$form->field($model, '<?= $key ?>')->input('date') ?>
    <?php else: ?>
        <?= '<?= ' ?>$form->field($model, '<?= $key ?>') ?>
    <?php endif; ?>
<?php endforeach; ?>

<div class="form-group">
    <?= '<?= ' ?>Html::submitButton(<?= 'Sumbit' ?>, ['class' => 'btn btn-primary']) ?>
</div>
<?= '<?php ' ?>ActiveForm::end(); ?>

