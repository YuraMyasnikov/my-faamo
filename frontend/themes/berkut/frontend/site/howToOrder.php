 <?php 
 
 use yii\helpers\Url;

 ?>
 
 <!--📰 Как сделать заказ-->
 <div class="content">

<!--🤟 Хлебные крошки-->
<div class="container">
    <ul class="breadcrumbs">
        <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
        <li>Как сделать заказ</li>
    </ul>
</div>

<div class="container">

    <h1>Как сделать заказ</h1>

    <div class="how-text">
        <p>Зарегистрируйтесь на нашем сайте! Чтобы товары добавленные в корзину сохранялись в ней до
            подтверждения заказа.</p>
        <p>Без регистрации товары сохраняются в корзине 24 часа (при условии непрерывной работы
            браузера).</p>
        <p>При работе с нашим сайтом, мы рекомендуем использовать быстрый и современный браузер Google
            Chrome.</p>
    </div>

    <div class="how-item">
        <div class="how-item__header">
            <span>1</span>
            Введите нужное вам количество товара в соответствующее поле и добавьте товар в корзину.
        </div>
        <div class="how-item__content">
            <img src="/img/how-item-1.jpg" alt="" loading="lazy">
        </div>
    </div>

    <div class="how-item">
        <div class="how-item__header">
            <span>2</span>
            После этого можно перейти к выбору других товаров или оформить заказ перейдя в корзину.
        </div>
    </div>

    <div class="how-item">
        <div class="how-item__header">
            <span>3</span>
            Перейдя в корзину, проверьте состав заказа, если все верно, нажмите оформить заказ
        </div>
        <div class="how-item__content">
            <img src="/img/how-item-2.jpg" alt="" loading="lazy">
        </div>
    </div>

    <div class="how-item">
        <div class="how-item__header">
            <span>4</span>
            Далее заполните все обязательные поля, выберите способ оплаты и доставки и нажмите оформить заказ
        </div>
        <div class="how-item__content">
            <img src="/img/how-item-3.jpg" alt="" loading="lazy">
        </div>
    </div>

    <div class="how-item">
        <div class="how-item__header">
            <span>5</span>
            Нажмите оформить заказ
        </div>
        <div class="how-item__content">
            <img src="/img/how-item-4.jpg" alt="" loading="lazy">
        </div>
    </div>

    <div class="how-item">
        <div class="how-item__header">
            <span>6</span>
            Ваш заказ оформлен! На почту придет бланк заказа и скоро мы свяжемся с Вами.
        </div>
    </div>

</div>


</div>