<?php

use yii\helpers\Html;
use yii\helpers\Url;

$nav = [
    [
        'label' => 'Профиль',
        'url' => Url::to(['/account'])
    ],
    [
        'label' => 'Смена пароля',
        'url' => Url::to(['/account/change-password'])
    ],
    [
        'label' => 'История заказов',
        'url' => Url::to(['/account/orders', 'status_group' => Yii::$app->request->get('status_group')])
    ],
    [
        'label' => 'Выйти',
        'url' => Url::to(['/user/logout'])
    ],
];

?>

<div class="lk-page-right">
    <div class="lk-page-right-wrp js-sticky-box" data-margin-top="30" data-margin-bottom="30">
        <?php foreach ($nav as $item) { ?>
            <a href="<?= Html::encode($item['url']) ?>"
               class="lk-menu <?= Yii::$app->request->url === $item['url'] ? 'active' : ''; ?>">
                <?= Html::encode($item['label']); ?>
            </a>
        <?php } ?>
    </div>
</div>
