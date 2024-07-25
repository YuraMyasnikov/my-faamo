<?php

use nterms\pagesize\PageSize;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>

<?= PageSize::widget([
    'template' => '{list}',
    'options' => [
        'class' => 'drop-down-per-page fll'
    ],
    'sizes' => [20 => 20, 50 => 50, 100 => 100]
]) ?>

<?php
Pjax::begin();

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{summary}{pager}{items}{pager}',
    'filterSelector' => 'select[name="per-page"]',
    'rowOptions' => static function ($model) {
        return ['onclick' => 'iblockElementSelectCallback(' . $model->id . ', "' . str_replace('"', '\"', $model->name) . '")'];
    },
    'columns' => [
        'name'
    ],
]);

Pjax::end();
?>

<SCRIPT LANGUAGE="JavaScript">
    window.addEventListener('load', function () {
        if (typeof(iblockElementSelectCallback) == "undefined")
            self.close();
    })
</SCRIPT>
