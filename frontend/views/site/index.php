<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $showcaseDataProvider */

use yii\helpers\Url;
use frontend\models\shop\Products;
use frontend\models\shop\Sku;
use frontend\services\CityCodeResolver;
use yii\helpers\Html;

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);
?>

<?php $this->beginBlock('main-slider'); ?>
<?= \frontend\widgets\MainSlider::widget() ?>
<?php $this->endBlock(); ?>

<div class="bg-gray">
    <div class="popular-category width-default bx-center">
        <h2>Популярные категории</h2>
        <div class="category-wrp popular">
            <?= \frontend\widgets\WelcomeCategories::widget() ?>
        </div>
    </div>
</div>
<div class="main-page-items width-default bx-center">
    <div class="category-wrp">
        <?php foreach(Products::find()->where(['active' => 1])->andWhere(['>', 'image_id', 0])->orderBy(['sort' => SORT_DESC])->limit(6)->all() as $product) { ?>
            <?php 
                /** @var Products $product */    
                /** @var Sku|null $sku */    
                $sku = $product->sku[0] ?? null;
            ?>
            <div class="category-item">
                <div class="category-item-img">
                    <a href="<?= Url::to(['/shop/frontend/products/view', 'code' => $product->code, 'city' => 'msk']) ?>">
                        <img src="<?= Yii::$app->image->get($product->image_id)->file; ?>" alt="<?= Html::encode($product->name) ?>" title="<?= Html::encode($product->name) ?>">
                    </a>
                </div>
                <a 
                    href="<?= Url::to(['/shop/frontend/products/view', 'code' => $product->code]) ?>" 
                    class="category-item-name link-line"
                    title="<?= Html::encode($product->name) ?>"
                >
                    <span><?= $product->name ?></span>
                </a>
                <div class="category-item-price">
                    <?= $sku?->price ?> ₽ <span class="category-item-price-old"><?= $sku?->old_price ?> ₽</span>
                </div>
            </div>
        <?php } ?>    
    </div>
</div>
<div class="fabrics width-default bx-center">
    <h3>Образцы тканей</h3>
    <div class="fabrics-wrp">
        <div class="fabrics-item radius"><img src="images/fabric01.jpg" alt="Образцы тканей"></div>
        <div class="fabrics-item radius"><img src="images/fabric02.jpg" alt="Образцы тканей"></div>
        <div class="fabrics-item radius"><img src="images/fabric03.jpg" alt="Образцы тканей"></div>
    </div>
</div>
<div class="blog-wrp width-default bx-center">
    <div class="blog-item">
        <div class="blog-item-text">
            <h3>Натуральные ткани</h3>
            <p><strong>Шерсть, Кашемир, Лен, Шелк, Хлопок</strong></p>
            <p>Прочность, долговечность и гигроскопичность — основные требования. Но с материалом высокого
                качества, помимо всего прочего должно быть легко работать. Тогда можно будет воплощать любые
                идеи, даже со сложным кроем, не опасаясь за результат.</p>
            <p class="btn white radius appointment-button">Записаться на примерку</p>
        </div>
        <div class="blog-item-img radius">
            <img src="images/article01.jpg" alt="Натуральные ткани">
        </div>
    </div>
    <div class="blog-item">
        <div class="blog-item-text">
            <h3>Купить мужской классический костюм с примеркой</h3>
            <p>Вы можете посмотреть товары в нашем шоуруме, заказать примерку. Мы гарантируем, что вы получите
                нашу одежду в лучшем качестве, сможете оценить все ее преимущества.</p>
            <p>Кроме того, наши стилисты окажут вам консультацию, помогут выбрать идеальный комплект.</p>
            <p><a href="catalog.html" class="btn white radius">Посмотреть костюмы</a></p>
        </div>
        <div class="blog-item-img radius">
            <img src="images/article02.jpg" alt="Натуральные ткани">
        </div>
    </div>
</div>
<div class="infographic bx-center">
    <div class="infographic-item radius">
        <img src="images/infographics01.svg">
        <p>Богатый ассортимент моделей различных фасонов стилей, размеров</p>
    </div>
    <div class="infographic-item radius">
        <img src="images/infographics02.svg">
        <p>Одежда пошита по уникальным лекалам из качественных тканей</p>
    </div>
    <div class="infographic-item radius">
        <img src="images/infographics03.svg">
        <p>Примерка в магазине в центре города</p>
    </div>
    <div class="infographic-item radius">
        <img src="images/infographics04.svg">
        <p>Подшив костюмов в подарок</p>
    </div>
    <div class="infographic-item radius">
        <img src="images/infographics05.svg">
        <p>Доставка по всей России</p>
    </div>
</div>
<div class="width-default bx-center">
    <h2>Индивидуальный подшив костюма бесплатно</h2>
    <div class="sewing">
        <div class="sewing-item"><img src="images/sewing01.jpg"></div>
        <div class="sewing-item"><img src="images/sewing02.jpg"></div>
        <div class="sewing-item"><img src="images/sewing03.jpg"></div>
        <div class="sewing-item"><img src="images/sewing04.jpg"></div>
        <div class="sewing-item"><img src="images/sewing05.jpg"></div>
    </div>
</div>
<div class="seo-text width-less bx-center">
    <?php $seo = Yii::$app->seo->getPage(); ?>
    <?php if(!empty($seo->large_text)) { ?>
        <?= $seo->large_text ?>
    <?php } ?>    
</div>
