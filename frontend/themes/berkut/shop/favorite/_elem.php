
<?php

use frontend\widgets\ProductCardWidget;
use yii\helpers\Url;

echo ProductCardWidget::widget([
    'id'      => $favorite->item->id,
    'url'     => Url::to(['/product/view', 'code' => $favorite->item->code]),
    'price'   => $favorite->item->sku[0]->price,
    'code'    => $favorite->item->code,
    'articul' => $favorite->item->articul,
    'name'    => $favorite->item->name,
    'isActive'=> $favorite->item->active,
]);

?>


