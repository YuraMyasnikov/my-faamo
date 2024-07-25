<?php

use yii\helpers\Url;

$back = urlencode(Yii::$app->request->url);
?>


<table class="table">
    <thead>
    <tr>
        <th style="width: 10px">#</th>
        <th>Функции</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1.</td>
        <td>
            <a href="<?= Url::to(['/admin/modules/infoblocks/types/entity', 'id' => $model->id]) ?>">
                Сгенерировать сущность
            </a>
        </td>
    </tr>
    <tr>
        <td>2.</td>
        <td>
            <a href="<?= Url::to(['/admin/modules/infoblocks/types/migration', 'id' => $model->id]) ?>">
                Создать миграцию
            </a>
        </td>
    </tr>
    <tr>
        <td>3.</td>
        <td>
            <a href="<?= Url::to(['/admin/modules/infoblocks/types/form', 'id' => $model->id]) ?>">
                Создать форму
            </a>
        </td>
    </tr>
    <tr>
        <td>4.</td>
        <td>
            <a href="<?= Url::to(['/admin/modules/infoblocks/content/export', 'code' => $model->code, 'back' => $back]) ?>">
                Сохранить записи инфоблока, для дальнейшего импорта
            </a>
        </td>
    </tr>
    </tbody>
</table>
