<?php

use CmsModule\Shop\common\models\Basket;
use frontend\assets\MaskAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Url;

[$totalCount, $totalSum] = array_reduce($basketProducts, function($result, Basket $basketProduct) {
    $result[0] += $basketProduct->count;
    $result[1] += $basketProduct->price * $basketProduct->count;
    return $result; 
}, [0,0]);

MaskAsset::register($this);

?>

<?php $this->beginBlock('article-css'); ?>page bg-gray<?php $this->endBlock(); ?>

<div class="bx-center width-full">
    <div class="breadcrumbs">
        <a href="<?= Url::to(['/site/index'])?>" class="breadcrumbs-item link-line"><span>Главная</span></a>
        <a href="<?= Url::to(['/shop/frontend/basket/index'])?>" class="breadcrumbs-item link-line"><span>Корзина</span></a>
        <span>Оформление заказа</span>
    </div>
</div>

<?php $form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['orders/store'],
    'enableAjaxValidation' => true
]); ?>
<div class="catalog-page width-default bx-center">
    <h1 class="center-text">Оформление заказа</h1>
    <div class="cart-page js-sticky-row">
        <div class="cart-page-left">
            <div class="cart-page-left-order">
                <div class="cart-page-left-order-title">
                    <h3>Получатель товара</h3>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <div class="cart-page-left-order-title-account">
                            Уже есть аккаунт? <a href="<?= Url::to(['/site/login'])?>" class="link-line"><span>Авторизироваться</span></a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="popup-form-item">
                    <?php 
                        echo $form->field($orderDataForm, 'fio', [
                            'template'     => '{label}{input}{error}',
                            'labelOptions' => ['for' => 'order-fio', 'label' => 'Ф.И.О.: *'],
                            'inputOptions' => ['id'  => 'order-fio', 'class' => 'radius', 'autocomplete' => 'off', 'required' => true],
                            'options'      => ['tag' => false],
                        ])
                        ->textInput();
                    ?>
                </div>

                <div class="popup-form-item">
                    <?php 
                        echo $form->field($orderDataForm, 'email', [
                            'template'     => '{label}{input}{error}',
                            'labelOptions' => ['for' => 'order-mail', 'label' => 'Электронная почта: *'],
                            'inputOptions' => ['id'  => 'order-mail', 'class' => 'radius', 'autocomplete' => 'off', 'required' => true],
                            'options'      => ['tag' => false],
                        ])
                        ->textInput();
                    ?>
                </div>

                <div class="popup-form-item">
                    <?php 
                        echo $form->field($orderDataForm, 'phone', [
                            'template'     => '{label}{input}{error}',
                            'labelOptions' => ['for' => 'order-phone', 'label' => 'Телефон: *'],
                            'inputOptions' => ['id'  => 'order-phone', 'class' => 'radius mask-phone', 'autocomplete' => 'off', 'required' => true, 'value' => $orderDataForm->phone],
                            'options'      => ['tag' => false],
                            ])
                            ->textInput()->input('tel');
                            // ->widget(MaskedInput::class, [
                            //     'mask' => '+9 ([999]) [999]-[99]-[99]',
                            // ]); 
                    ?>
                </div>
            </div>
        </div>
        <div class="cart-page-right">
            <div class="cart-page-right-wrp js-sticky-box" data-margin-top="30" data-margin-bottom="30">
                <h3>Ваш заказ</h3>
                <div class="cart-page-right-wrp-row">
                    <div class="cart-page-right-wrp-row-col">Количество товаров</div>
                    <div class="cart-page-right-wrp-row-col"><?= $totalCount ?></div>
                </div>
                <div class="cart-page-right-wrp-total">
                    <div class="cart-page-right-wrp-total-col">Итого:</div>
                    <div class="cart-page-right-wrp-total-col"><?= Html::encode($totalSum); ?> ₽</div>
                </div>
                <button type="submit" class="btn-bg full black radius">Оформить заказ</button>
            </div>
        </div>
    </div>
</div>
<?php $form::end(); ?>


















<!-- 
<a href="<?= Url::to(['/orders/create']); ?>">Физ лицо</a><br>
<a href="<?= Url::to(['/orders/create-org']); ?>">Юр лицо</a>

<section>
    <h1>Оформление заказа</h1>
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => ['/orders/store'],
        'enableAjaxValidation' => true
    ]); ?>

    <h2>Данные заказчика:</h2>
    <?= $form->field($orderDataForm, 'fio')->textInput(); ?>
    <?= $form->field($orderDataForm, 'email')->textInput(); ?>
    <?= $form->field($orderDataForm, 'phone')->textInput()->input('tel')
            ->widget(MaskedInput::class, [
                'mask' => '+7 ([999]) [999]-[99]-[99]',
            ]); ?>

    <button>Оформить заказ</button>

    <input type="checkbox" id="main_fos_cbx">
    <label for="main_fos_cbx">Пользовательским соглашением</a>.</label>


    <?php $form::end(); ?>

</section> -->