<?php

?>

<h1>Заказ успешно оформлен, проверьте почту</h1>

<?php if ($order && $orderData && $orderViewModel) { ?>
    <?= $this->render('_invoice', [
        'order' => $order,
        'orderData' => $orderData,
        'orderViewModel' => $orderViewModel
    ]); ?>
<?php } ?>