<?php

use yii\helpers\Html;
use yii\helpers\Url;

$nav = [
    [
        'label' => 'Профиль',
        'url' => Url::to(['/profile'])
    ],
    [
        'label' => 'Смена пароля',
        'url' => Url::to(['/profile/change-pass'])
    ],
    [
        'label' => 'История заказов',
        'url' => Url::to(['/profile/orders'])
    ],
    [
        'label' => 'Выйти',
        'url' => Url::to(['/user/logout'])
    ],
];

?>

<div class="lk-nav">
    <ul class="lk-nav__list">
        <?php foreach ($nav as $item) { ?>
            <li class="lk-nav__item <?= Yii::$app->request->url === $item['url'] ? 'active' : ''; ?>">
                <a class="lk-nav__item-title" href="<?= Html::encode($item['url']); ?>"><?= Html::encode($item['label']); ?></a>
            </li>
        <?php } ?>
    </ul>
</div>