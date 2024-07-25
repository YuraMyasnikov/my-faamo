<?php

use CmsModule\Shop\frontend\widgets\CatalogItem;

?>

<?= CatalogItem::widget([
    'product_id' => $product['id']
]) ?>