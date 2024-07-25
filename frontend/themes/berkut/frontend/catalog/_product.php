

<?php

use CmsModule\Shop\common\models\Products;
use frontend\widgets\ProductCardWidget;
use yii\helpers\Url;

if($model instanceof Products) {
    $model = [
        'id' => $model->id,
        'category_code' => $model->mainCategory->code,
        'code' => $model->code,
        'price' => 0.0,
        'articul' => $model->articul,
        'name' => $model->name,
        'active' => $model->active,
    ];
}
$productUrl = Url::to(['/product/view', 'code' => $model['code']]);
echo ProductCardWidget::widget([
    'id'      => $model['id'],
    'url'     => $productUrl,
    'price'   => $model['price'],
    'code'    => $model['code'],
    'articul' => $model['articul'],
    'name'    => $model['name'],
    'isActive'=> $model['active'],
]);

?>