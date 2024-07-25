

<?php

use frontend\widgets\ProductCardWidget;
use yii\helpers\Url;

echo ProductCardWidget::widget([
    'id'      => $model['id'],
    'url'     => Url::to(['/product/view', 'code' => $model['code']]),
    'price'   => $model['price'] ?? 0,
    'code'    => $model['code'],
    'articul' => $model['articul'],
    'name'    => $model['name'],
    'isActive'=> $model['active'],
]);

?>