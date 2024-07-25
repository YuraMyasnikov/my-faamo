<?php 

/** 
 * @var \frontend\models\shop\Products $product
 * @var \CmsModule\Shop\frontend\viewModels\ProductViewModel $productViewModel
 * @var \yii\data\ActiveDataProvider $productReviewsDataProvider
 * @var \yii\data\ActiveDataProvider $productReviewsDataProvider
 * @var \frontend\models\shop\ProductReviewsForm $productReviewsForm
 * @var \CmsModule\Shop\common\models\Categories|null $productCategory
 * @var \yii\web\View $this
 */

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Shop\common\helpers\PriceHelper;
use frontend\assets\ProductAsset;
use frontend\models\OneClickOrderForm;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

ProductAsset::register($this);

?>

<!--📰 Карточка товара-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/']) ?>">Главная</a></li>
            <?php if($productCategory) { ?>
                <li><a href="<?= sprintf('/catalog/%s', $productCategory->code) ?>"><?= $productCategory->name?></a></li>
            <?php } ?>
            <li><?= $product->name ?></li>
        </ul>
    </div>

    <div class="container">

        <h1><?= $product->name ?> <?= $isActive ? 1 : 0 ?></h1>

        <div class="columns">
            <div class="column col-5 xl-col-4 md-col-12">

                <!--👉 cart-image -->
                <div class="cart-image">

                    <div class="cart-image__thumbnails">
                        <div class="cart-thumbnail-slider splide">
                            <div class="splide__track">
                                <div class="splide__list">
                                    <?php foreach ($productViewModel->images as $image) { ?>
                                        <?php 
                                            $src = Yii::getAlias('@webroot') . $image['file'];
                                            $cachedName = Yii::$app->imageCache->resize($src, null, 175);
                                            $cachedPath = Yii::$app->imageCache->relativePath($cachedName);    
                                        ?>
                                        <!---->
                                        <div class="cart-image__thumbnail splide__slide">
                                            <img src="<?= $cachedPath ?>" alt="" loading="lazy">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cart-image__main">
                        <div class="cart-image-slider splide">
                            <div class="splide__track">
                                <div class="splide__list">
                                <?php foreach ($productViewModel->images as $image) { ?>
                                        <?php 
                                            $src = Yii::getAlias('@webroot') . $image['file'];
                                            $cachedName = Yii::$app->imageCache->resize($src, null, 570, 95);
                                            $cachedPath = Yii::$app->imageCache->relativePath($cachedName);    
                                        ?>
                                        <!---->
                                        <div class="splide__slide">
                                            <a href="<?= $image['file'] ?>"
                                            class="cart-image__slide"
                                            data-options='{"autoFocus" : false, "backFocus" : false}'
                                            data-fancybox="cart-images">
                                                <img src="<?= $cachedPath ?>" alt="" loading="lazy">
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="column col-7 xl-col-8 md-col-12">
                <?php if($isActive) { ?>
                <!--👉 js-cart 👈-->
                <div class="layout js-cart">
                    <div class="layout-content cart-content">

                        <!--👉 cart-thresholds -->
                        <div class="cart-thresholds">
                            <div class="cart-thresholds__item js-cart-threshold <?= $productPriceType === 'price' ? 'active' : '' ?>" data-type="price">
                                <div class="cart-thresholds__title">Розница</div>
                                <div class="cart-thresholds__price">
                                    <?= PriceHelper::format($productPrice) ?>
                                    <span class="cart-rubl">₽</span>
                                </div>
                            </div>
                            <div class="cart-thresholds__item js-cart-threshold <?= $productPriceType === 'small_wholesale_price' ? 'active' : '' ?>" data-type="small_wholesale_price">
                                <div class="cart-thresholds__title">Мелкий опт от <?= PriceHelper::format(Yii::$app->params['basket.calculator.price_types']['price']['max'] + 1) ?>₽</div>
                                <div class="cart-thresholds__price">
                                    <?= PriceHelper::format(Yii::$app->prices->getSmallWholesalePrice($productPrice)) ?>
                                    <span class="cart-rubl">₽</span>
                                </div>
                            </div>
                            <div class="cart-thresholds__item js-cart-threshold <?= $productPriceType === 'wholesale_price' ? 'active' : '' ?>" data-type="wholesale_price">                                
                                <div class="cart-thresholds__title">Крупный опт от <?= PriceHelper::format(Yii::$app->params['basket.calculator.price_types']['small_wholesale_price']['max'] + 1) ?>₽</div>
                                <div class="cart-thresholds__price">
                                <?= PriceHelper::format(Yii::$app->prices->getWholesalePrice($productPrice)) ?>
                                    <span class="cart-rubl">₽</span>
                                </div>
                            </div>
                        </div>

                        <!--👉 cart-table -->
                        <div class="cart-table">
                            <table>
                                <thead class="cart-table__header">
                                <tr>
                                    <th>Размер, рост</th>
                                    <th>Наличие</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                </tbody>
                                <tbody>
                                <!---->
                                <?php foreach($productViewModel->skuList as $sku) { ?>
                                    <tr 
                                        class="js-cart-item"
                                        data-sku-id="<?= $sku->id ?>"
                                        data-sku-name="<?= $sku->name ?>"
                                        data-sku-remnants="<?= $sku->remnants ?>"
                                        data-sku-price="<?= $sku->price ?>"
                                        data-sku-count=0
                                        data-sku-sum=0
                                    >
                                        <td><?= $sku->name ?></td>
                                        <td class="<?= $sku->remnants > 0 ? 'on' : 'off'?>"><?= $sku->remnants ?></td>
                                        <td>
                                            <div class="value">
                                                <div class="value-button minus js-item-minus">
                                                    <svg width="12" height="12">
                                                        <use xlink:href="#icon-minus"></use>
                                                    </svg>
                                                </div>
                                                <input 
                                                    readonly 
                                                    type="text" 
                                                    class="value-count js-item-count" 
                                                    value="0"
                                                    />
                                                <div class="value-button plus js-item-plus">
                                                    <svg width="12" height="12">
                                                        <use xlink:href="#icon-plus"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="cart-table__price">
                                                <span class="js-item-price">0</span>
                                                <span class="cart-rubl">₽</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tbody class="cart-table__footer">
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="value value--all">
                                            <div class="value-button minus js-cart-all-minus">
                                                <svg width="12" height="12">
                                                    <use xlink:href="#icon-minus"></use>
                                                </svg>
                                            </div>
                                            <div class="value-count">Весь ряд</div>
                                            <div class="value-button plus js-cart-all-plus">
                                                <svg width="12" height="12">
                                                    <use xlink:href="#icon-plus"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="layout-aside">

                        <!--👉 cart-showcase-->
                        <div class="cart-showcase">

                            <div class="cart-showcase__header">
                                <div class="available on">В наличии</div>
                                <div class="cart-showcase__article">
                                    <span>Артикул:</span> <?= $product->articul ?>
                                </div>
                            </div>

                            <div class="cart-showcase__content">

                                <div class="cart-price">
                                    <div class="cart-price__title">Итого:</div>
                                    <div class="cart-price__value">
                                        <span class="js-cart-total">0</span> <span class="cart-rubl">₽</span>
                                    </div>
                                </div>

                                <div class="cart-buttons">
                                    <button 
                                        type="button" 
                                        class="btn btn--full btn--shadow"
                                        id="add-to-cart"
                                        data-url="<?= Url::to(['shop/api/basket/add-skus']) ?>"
                                    >
                                        Добавить в корзину
                                    </button>

                                    <button type="button"
                                            data-micromodal-trigger="modal-one-click"
                                            class="btn btn--line btn--line-light btn--full">
                                        Купить в 1 клик
                                    </button>
                                </div>

                                <!--💡 Добавить класс active если в избранном-->
                                <div 
                                    role="button" 
                                    class="cart-favorite <?= $isFavorite ? 'active' : '' ?>"
                                    data-url-add="<?= Url::to(['/shop/api/favorite/add-product', 'product_id' => $product->id]) ?>"
                                    data-url-remove="<?= Url::to(['/shop/api/favorite/delete-product', 'product_id' => $product->id]) ?>"
                                >
                                    <div class="cart-favorite__icon">
                                        <svg width="18" height="18">
                                            <use xlink:href="#icon-favorite"></use>
                                        </svg>
                                    </div>
                                    Добавить в избранное
                                </div>

                                <!--👉 Купить на маркетплейсах-->
                                <div class="market-items">
                                    Купить на маркетплейсах
                                    <?php if($productViewModel->product->links):?> 
                                        <?php if($productViewModel->product->links->wildberries):?> 
                                            <a href="<?= $productViewModel->product->links->wildberries ?>" class="market wb" target="_blank">
                                                <img src="/img/wb.svg" alt="" loading="lazy">
                                            </a>
                                        <?php endif ?>
                                        <?php if($productViewModel->product->links->ozon):?> 
                                            <a href="<?= $productViewModel->product->links->ozon ?>" class="market ozon" target="_blank">
                                                <img src="/img/ozon.svg" alt="" loading="lazy">
                                            </a>
                                        <?php endif ?>
                                        <?php if($productViewModel->product->links->yandex_market):?> 
                                            <a href="<?= $productViewModel->product->links->yandex_market ?>" class="market ym" target="_blank">
                                                <img src="/img/ym.svg" alt="" loading="lazy">
                                            </a>
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>


                            </div>
                        </div>


                    </div>
                </div>
                <?php } else { ?>
                <!--👉 cart-empty-->
                <div class="cart-empty">
                    <div class="cart-empty__title">Данного товара нет <span>в наличии</span></div>
                    <div class="cart-empty__text">
                        Оставьте свой адрес электронной почты и мы оповестим вас, если товар появится
                        в продаже или оставьте заявку подбор товара и мы подберём для вас подходящий аналог
                    </div>
                    
                    
                    <?php 
                        $subscribeForm = \frontend\forms\SubscribersForm::buildForm();
                        $form = ActiveForm::begin([
                            'action' => Url::to(['/site/subscribe']), 
                            'method' => 'POST', 
                            'options' => ['class' => 'cart-empty__form']
                        ]); 
                        
                        echo $form
                            ->field($subscribeForm, 'email', [
                                'options' => [
                                    'class' => 'input-group col-12'
                                ],
                                'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-email"></use></svg></div>{error}{hint}'
                            ])
                            ->textInput([
                                'class' => 'input input--icon subscribe-input',
                                'placeholder' => 'Введите ваш Email...'
                            ]); 
                    ?>
                    <button type="submit" class="btn btn--mobile">Отправить</button>
                    <?php 
                        $form::end(); 
                    ?>
                    <ul class="form-footer">
                        <li>Нажимая кнопку «Отправить», вы соглашаетесь с <a href="">политикой
                            конфиденциальности</a></li>
                    </ul>
                </div>

                <!--👉 Подбор товара-->
                <div class="choice choice--small">
                    <div class="choice-content">
                        <div class="choice-content__title">Подбор товара</div>
                        <div class="choice-content__text">
                            Персональный менеджер проконсультирует по вопросу оптовых закупок,
                            расскажет об особенностях ассортимента, подберёт для вас наилучшее предложение.
                        </div>
                        <button type="button" class="btn btn--full" data-micromodal-trigger="modal-recall">Отправить заявку</button>
                    </div>
                    <div class="choice-image"></div>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="offset-top">

            <div class="layout">
                <div class="layout-content">

                    <!--👉 cart-information-->
                    <div class="cart-information">

                        <div class="cart-information__tabs">
                            <a href="#description">Описание</a>
                            <a href="#option">Характеристики</a>
                            <a href="#reviews">Отзывы <span><?= $productReviewsDataProvider->getTotalCount() ?></span></a>
                        </div>

                        <!--👉 Описание-->
                        <div id="description">
                            <h2>Описание</h2>
                            <?php if ($productViewModel->description) { ?>
                                <?= $productViewModel->description ?>
                            <?php } else { ?>
                                <P>Пусто</P>
                            <?php } ?>
                        </div>

                        <!--👉 Характеристики-->
                        <div id="option">
                            <h2>Характеристики</h2>
                            <div class="cart-information__table">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Артикул:</td>
                                            <td><?= $product->articul ?></td>
                                        </tr>
                                        <?php foreach($productViewModel->getOptionList() as $optionTitle => $optionValues) { ?>
                                        <tr>
                                            <td><?= $optionTitle ?>:</td>
                                            <td><?= implode(' ', $optionValues) ?></td>
                                        </tr>
                                        <?php } ?>    
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!--👉 Отзывы о товаре-->
                        <div id="reviews">

                            <div class="reviews-head reviews-head--small">
                                <div class="head-title">
                                    Отзывы о товаре <span class="reviews-head__count"><?= $productReviewsDataProvider->getTotalCount() ?></span>
                                </div>
                                <div class="reviews-head__aside">
                                    <?php if($isShowProductReviewsForm) { ?>
                                    <button type="button"
                                            data-micromodal-trigger="modal-review"
                                            class="btn btn--small btn--mobile">
                                        <svg width="16" height="16">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        Оставить свой отзыв
                                    </button>
                                    <?php } else { ?>
                                    <div class="reviews-head__info">
                                        Отзывы о товаре могут оставлять только зарегистрированные пользователи.
                                        <br>
                                        <a href="<?= Url::to(['/login']) ?>">Войдите</a> или <a href="<?= Url::to(['/registration']) ?>">Зарегистрируйтесь</a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!--👉 comments-->
                            <div class="comments comments-list">
                                <?php foreach($productReviewsDataProvider->getModels() as $review) {?>
                                    <?php /** @var Infoblock $review */  ?>
                                    <?= $this->render('_review', ['review' => $review]) ?>
                                <?php }?>    
                            </div>

                            <div class="comments">
                                <div class="comment"></div>
                                <?php if($isHasNextReviewsPage) { ?>
                                <div class="comment-more">
                                    <button 
                                        type="button" 
                                        class="btn btn--line js-load-more-reviews"
                                        data-page="2"
                                        data-fetch-url="<?= Url::to(['product/reviews', 'code' => $product->code]); ?>"
                                        data-text="Показать еще"
                                        data-await-text="Загрузка..."
                                    >
                                        Показать еще
                                    </button>
                                </div>
                                <?php } ?>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="layout-aside md-hidden">

                    <?php if($isActive) { ?>
                    <!--👉 Подбор товара-->
                    <div class="choice choice--ver choice--small">
                        <div class="choice-content">
                            <div class="choice-content__title">Подбор товара</div>
                            <div class="choice-content__text">
                                Персональный менеджер проконсультирует по вопросу оптовых закупок,
                                расскажет об особенностях ассортимента, подберёт для вас наилучшее предложение.
                            </div>
                            <button type="button" class="btn btn--full" data-micromodal-trigger="modal-recall">Отправить заявку</button>
                        </div>
                        <div class="choice-image"></div>
                    </div>
                    <?php } else { ?>
                    <!--👉 Купить на маркетплейсах-->
                    <div class="market-items">
                        Купить на маркетплейсах
                        <?php if($productViewModel->product->links):?> 
                            <?php if($productViewModel->product->links->wildberries):?> 
                                <a href="<?= $productViewModel->product->links->wildberries ?>" class="market wb" target="_blank">
                                    <img src="/img/wb.svg" alt="" loading="lazy">
                                </a>
                            <?php endif ?>
                            <?php if($productViewModel->product->links->ozon):?> 
                                <a href="<?= $productViewModel->product->links->ozon ?>" class="market ozon" target="_blank">
                                    <img src="/img/ozon.svg" alt="" loading="lazy">
                                </a>
                            <?php endif ?>
                            <?php if($productViewModel->product->links->yandex_market):?> 
                                <a href="<?= $productViewModel->product->links->yandex_market ?>" class="market ym" target="_blank">
                                    <img src="/img/ym.svg" alt="" loading="lazy">
                                </a>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                    <?php }  ?>

                </div>
            </div>

        </div>

        <!--🤟 Модальное окно Купить в 1 клик-->
        <div id="modal-one-click" aria-hidden="true" class="modal">
            <div data-micromodal-close class="modal-overlay">
                <!--💡 Добавить класс modal-main--small-->
                <div class="modal-main modal-main--small">
                    <div class="modal-title">Купить в 1 клик</div>
                    <div class="modal-close" data-micromodal-close></div>
                    <div class="modal-content">

                        <?php 
                            $form = ActiveForm::begin([
                                'action' => Url::to(['/site/oneclick-order']), 
                                'method' => 'POST', 
                            ]); 
                        ?>
                        <div 
                            class="one-click"
                            data-remnants=""
                        >
                            <div class="one-click__image">
                                <?php  
                                    $img = null;
                                    if(isset($productViewModel->images[0])) {
                                        $src = Yii::getAlias('@webroot') . $productViewModel->images[0]['file'];
                                        $cachedName = Yii::$app->imageCache->resize($src, null, 175);
                                        $cachedPath = Yii::$app->imageCache->relativePath($cachedName);    
                                        $img = $cachedPath;
                                    }
                                ?>
                                <img src="<?= $img ?>" alt="" loading="lazy">
                            </div>
                            <div class="one-click__content">
                            
                                <div class="one-click__title"><?= $product->name ?></div>
                                <ul class="one-click__sizes">
                                    <li>
                                        <div class="one-click__sizes-title">Размер, рост:</div>
                                        <select name="sku_id" class="input input--small select">
                                            <?php $_i = 0; ?>
                                            <?php foreach($productViewModel->skuList as $sku) { ?>
                                                <?php /** @var \CmsModule\Shop\common\models\Sku $sku */ ?>
                                                <option 
                                                    value="<?= $sku->id ?>"
                                                    data-sku-id="<?= $sku->id ?>"
                                                    data-sku-name="<?= $sku->name ?>"
                                                    data-sku-remnants="<?= $sku->remnants ?>"
                                                    data-sku-price="<?= $sku->price ?>"
                                                    <?php if($_i === 0) { ?>
                                                        selected
                                                    <?php } ?>    
                                                ><?= $sku->name ?></option>    
                                                <?php $_i++; ?>
                                            <?php } ?>
                                        </select>
                                    </li>
                                    <li>
                                        <div class="one-click__sizes-title">Количество:</div>
                                        <div class="value js-value">
                                            <div class="value-button value-button--large minus js-minus-count">
                                                <svg width="12" height="12">
                                                    <use xlink:href="#icon-minus"></use>
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                name="sku_count"
                                                readonly 
                                                class="value-count js-count" 
                                                value="1"
                                                data-remnants="<?= $productViewModel->skuList[0]->remnants ?>" />
                                            <div class="value-button value-button--large plus js-plus-count">
                                                <svg width="12" height="12">
                                                    <use xlink:href="#icon-plus"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <?php 
                            echo $form
                                ->field($oneClickOrderForm, 'name', [
                                    'options' => [],
                                    // 'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-email"></use></svg></div>{error}{hint}',
                                    'template' => '<div class="input-group">
                                    {input}
                                    <div class="input-icon">
                                        <svg width="20" height="20">
                                            <use xlink:href="#icon-input-user"></use>
                                        </svg>
                                    </div>
                                    {error}{hint}
                                </div>'
                                ])
                                ->textInput([
                                    'class' => 'input input--icon',
                                    'placeholder' => 'Ваше имя...'
                                ]); 
                        ?>

                        <?php 
                            echo $form
                                ->field($oneClickOrderForm, 'phone', [
                                    'options' => [],
                                    'template' => '<div class="input-group">
                                    {input}
                                    <div class="input-icon">
                                        <svg width="20" height="20">
                                            <use xlink:href="#icon-input-phone"></use>
                                        </svg>
                                    </div>
                                    {error}{hint}
                                </div>'
                                ])
                                ->textInput([
                                    'class' => 'input input--icon',
                                    'placeholder' => 'Ваш телефон...'
                                ]); 
                        ?>

                        <?php 
                            echo $form
                                ->field($oneClickOrderForm, 'email', [
                                    'options' => [],
                                    'template' => '<div class="input-group">
                                    {input}
                                    <div class="input-icon">
                                        <svg width="20" height="20">
                                            <use xlink:href="#icon-input-email"></use>
                                        </svg>
                                    </div>
                                    {error}{hint}
                                </div>'
                                ])
                                ->textInput([
                                    'class' => 'input input--icon',
                                    'placeholder' => 'Введите ваш Email...'
                                ]); 
                        ?>

                        <?php 
                            echo $form
                                ->field($oneClickOrderForm, 'message', [
                                    'options' => [],
                                    'template' => '<div class="input-group">
                                    {input}{error}{hint}
                                </div>'
                                ])
                                ->textInput([
                                    'class' => 'input textarea',
                                    'placeholder' => 'Текст сообщения. Необязательно...'
                                ]); 
                        ?>
                        <ul class="form-footer">
                            <li>
                                Нажимая кнопку «Отправить», вы соглашаетесь с <a href="">политикой
                                конфиденциальности</a>
                            </li>
                            <li>
                                <button type="submit" class="btn">Отправить</button>
                            </li>
                        </ul>
                        <?php $form::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!--🤟 Модальное окно Написать отзыв-->
        <div id="modal-review" aria-hidden="true" class="modal">
            <div data-micromodal-close class="modal-overlay">
                <div class="modal-main">
                    <?= $this->render('_review-form', compact('product', 'productReviewsForm')) ?>
                </div>
            </div>
        </div>

    </div>


</div>