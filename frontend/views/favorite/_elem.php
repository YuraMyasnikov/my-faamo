<?php

use CmsModule\Shop\frontend\widgets\CatalogItem;

?>

<?= CatalogItem::widget([
    'product_id' => $favorite_element->product_id
]) ?>