<?php
/** @var \yii\web\View $this */

use cms\common\widgets\AdminWidget;
use CmsModule\Shop\common\models\Categories;
use frontend\forms\CallbackForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

\frontend\assets\AppAsset::register($this);

$catalog = Categories::getCatalog(null);
$callbackForm = CallbackForm::buildForm();

$seo = Yii::$app->seo->getPage();

if ($seo && $seo->description) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $seo->description
    ]);
}

if ($seo && $seo->robots) {
    $this->registerMetaTag([
        'name' => 'robots',
        'content' => $seo->robots,
    ]);
}

if ($seo && $seo->title) {
    $title = $seo->title;
} else {
    $title = 'Интернет магазин berkut';
}

if ($seo && $seo->h1) {
    $this->title = $seo->h1;
}

\frontend\assets\MaskAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <?php $this->registerCsrfMetaTags() ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <?php $this->head() ?>

    <title><?= Html::encode($title) ?></title>
</head>

<body>
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= AdminWidget::widget(); ?>

    <?= $this->render('header', compact('catalog')); ?>

    <!--🤟 mobile panel-->
    <div class="mobile-panel lg-visible">
        <div class="mobile-panel__item js-catalog-menu">
            <div class="mobile-panel__link">
                <svg width="24" height="24">
                    <use xlink:href="#icon-panel-menu"></use>
                </svg>
                Меню
            </div>
        </div>
        <div class="mobile-panel__item">
            <a href="<?= Url::to(['/shop/frontend/favorite/index'])?>" class="mobile-panel__link">
                <div class="mobile-panel__count"><?= Yii::$app->favorite->count() ?></div>
                <svg width="24" height="24">
                    <use xlink:href="#icon-panel-favorite"></use>
                </svg>
                Избранное
            </a>
        </div>
        <div class="mobile-panel__item">
            <a href="<?= Url::to(['/shop/frontend/basket/index'])?>" class="mobile-panel__link">
                <div class="mobile-panel__count"><?= Yii::$app->basket->count() ?></div>
                <svg width="24" height="24">
                    <use xlink:href="#icon-basket"></use>
                </svg>
                Корзина
            </a>
        </div>
        <div class="mobile-panel__item">
            <div class="mobile-panel__link" data-popup-link="user-popup">
                <svg width="24" height="24">
                    <use xlink:href="#icon-panel-user"></use>
                </svg>
                Кабинет
            </div>
            <div class="header-popup header-popup--bottom header-popup--right" data-popup="user-popup">
                <ul class="header-popup__list">
                    <li><a href="<?= Url::to(['/account'])?>">Персональные данные</a></li>
                    <li><a href="<?= Url::to(['/account/orders'])?>">История заказов</a></li>
                    <li><a href="<?= Url::to(['/site/logout'])?>">Выход</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 🤟 wrap -->
    <main class="wrap">
        <!--🔥 НАЧАЛО ШАБЛОНА-->

        <?= $content ?>

        <!--🔥 КОНЕЦ ШАБЛОНА-->
    </main>

    <?= $this->render('footer'); ?>
</div>

<!-- 🤟 Modals -->
<div id="modal-recall" aria-hidden="true" class="modal">
    <div data-micromodal-close class="modal-overlay">
        <div class="modal-main">
            <div class="modal-title">Отправить сообщение</div>
            <div class="modal-close" data-micromodal-close></div>
            <div class="modal-content">
            <?php
                $form = ActiveForm::begin([
                    'action' => Url::to([CallbackForm::FORM_ACTION]),
                    'method' => 'post'
                ]);
            ?>
            <div class="columns">
                <div class="column col-6 sm-col-12">
                    <?php
                        echo $form
                            ->field($callbackForm, 'fio',   [
                                'options' => ['class' => 'input-group'],
                                'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-user"></use></svg></div>{error}{hint}'
                            ])
                            ->textInput([
                                'class' => 'input input--icon',
                                'placeholder' => 'Ваше имя...'
                            ]);
                    ?>
                    <?php
                        echo $form
                            ->field($callbackForm, 'phone', [
                                'options' => ['class' => 'input-group phone-mask',],
                                'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-phone"></use></svg></div>{error}{hint}'

                            ])
                            ->textInput([
                                'class' => 'input input--icon',
                                'id' => 'mask'
                            ]);
                    ?>
                    <?php
                        echo $form
                            ->field($callbackForm, 'email', [
                                'options' => ['class' => 'input-group'],
                                'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-email"></use></svg></div>{error}{hint}'
                            ])
                            ->textInput([
                                'class' => 'input input--icon',
                                'placeholder' => 'Введите ваш Email...'
                            ]);
                    ?>
                </div>
                <div class="column col-6 sm-col-12">
                    <?php
                        echo $form
                            ->field($callbackForm, 'comment', [
                                'options' => ['class' => 'input-group input-group--textarea'],
                                'template' => '{input}{error}{hint}'
                            ])
                            ->textarea([
                                'class' => 'input textarea',
                                'placeholder' => 'Текст сообщения. Необязательно...'
                            ]);
                    ?>
                </div>
            </div>
            <ul class="form-footer">
                <li>
                    Нажимая кнопку «Отправить», вы соглашаетесь с <a href="<?= Url::to(['/site/politics'])?>">политикой конфиденциальности</a>
                </li>
                <li>
                    <button type="submit" class="btn">Отправить</button>
                </li>
            </ul>

            <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- 🤟 Sprites-->
<div class="symbols">
    <svg xmlns="http://www.w3.org/2000/svg">
        <symbol id="icon-arrow-down" viewBox="0 0 9 4">
            <path d="M1 1L4.5 3L8 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-location" viewBox="0 0 13 18">
            <path d="M11.9214 7.32C12 7.08 12 6.84 12 6.6C12 3.48 9.56429 1 6.5 1C3.43571 1 1 3.48 1 6.6C1 6.84 1 7.08 1.07857 7.32C1.55 11.96 6.5 17 6.5 17C6.5 17 11.45 11.96 11.9214 7.32Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
            <path d="M6.5 8C7.32843 8 8 7.32843 8 6.5C8 5.67157 7.32843 5 6.5 5C5.67157 5 5 5.67157 5 6.5C5 7.32843 5.67157 8 6.5 8Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-favorite" viewBox="0 0 24 23">
            <path d="M12.0187 1L14.6262 9.01869H23.0654L16.2523 13.9813L18.8318 22L12.0187 17.0374L5.20561 22L7.81308 13.9813L1 9.01869H9.41121L12.0187 1Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-basket" viewBox="0 0 27 23">
            <path d="M21.609 3.3H2.20902C1.80902 3.3 1.40902 3.5 1.20902 3.8C1.00902 4.2 0.909017 4.6 1.10902 5L3.70902 12.1C4.00902 12.8 4.70902 13.3 5.40902 13.3H17.209C17.909 13.3 18.709 12.8 18.909 12.1L21.209 5.9L22.309 3C22.309 3 23.009 1 26.009 1"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
            <path d="M5.80898 16.1C4.40898 16.1 3.20898 17.3 3.20898 18.7C3.20898 20.1 4.40898 21.3 5.80898 21.3C7.20898 21.3 8.40898 20.1 8.40898 18.7C8.40898 17.3 7.30898 16.1 5.80898 16.1Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
            <path d="M16.6088 16.1C15.2088 16.1 14.0088 17.3 14.0088 18.7C14.0088 20.1 15.2088 21.3 16.6088 21.3C18.0088 21.3 19.2088 20.1 19.2088 18.7C19.2088 17.3 18.0088 16.1 16.6088 16.1Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-user" viewBox="0 0 20 22">
            <path d="M9.95123 9.82629C12.3885 9.82629 14.3644 7.85046 14.3644 5.41315C14.3644 2.97583 12.3885 1 9.95123 1C7.51392 1 5.53809 2.97583 5.53809 5.41315C5.53809 7.85046 7.51392 9.82629 9.95123 9.82629Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
            <path d="M15.9609 21C17.9327 21 19.3411 19.1221 18.7778 17.2441L18.3083 15.8357C17.651 13.77 15.7731 12.4554 13.7073 12.4554H6.1956C4.03598 12.4554 2.15805 13.8638 1.59466 15.8357L1.12518 17.2441C0.561801 19.1221 1.97025 21 3.94208 21H15.9609Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
        </symbol>
        <symbol id="icon-phone" viewBox="0 0 18 18">
            <path d="M1 4.13227C1 11.2434 6.75661 17 13.8677 17C14.4603 17 15.0529 17 15.6455 16.9153C16.4921 16.8307 17 16.0688 17 15.3069V13.0212C17 12.2593 16.4921 11.582 15.8148 11.328L13.0212 10.3968C12.3439 10.1429 11.582 10.5661 11.328 11.328C11.0741 12.2593 9.88889 12.5979 9.21164 11.9206L6.16402 8.78836C5.48677 8.11111 5.8254 6.92593 6.75661 6.67196C7.51852 6.50265 7.9418 5.74074 7.68783 4.97884L6.75661 2.18519C6.50265 1.50794 5.8254 1 5.06349 1H2.77778C1.93122 1 1.25397 1.59259 1.16931 2.3545C1 2.94709 1 3.53968 1 4.13227Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="133.333" stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-download" viewBox="0 0 19 17">
            <path d="M17.8456 10.6309V13.9698C17.8456 15.094 16.9396 16 15.8154 16H9.42282H3.0302C1.90604 16 1 15.094 1 13.9698V10.6309"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
            <path d="M9.43945 1V10.4295" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
            <path d="M5.61426 7.39262L9.4062 10.6309" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                  stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.2822 7.39262L9.49023 10.6309" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-search" viewBox="0 0 17 18">
            <path d="M7.5 14C11.0899 14 14 11.0899 14 7.5C14 3.91015 11.0899 1 7.5 1C3.91015 1 1 3.91015 1 7.5C1 11.0899 3.91015 14 7.5 14Z"
                  stroke="currentColor" stroke-width="1.5"/>
            <path d="M12 13L16 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-email" viewBox="0 0 15 11">
            <path d="M12.7419 10H2.25806C1.5871 10 1 9.38835 1 8.68932V2.31068C1 1.61165 1.5871 1 2.25806 1H12.7419C13.4129 1 14 1.61165 14 2.31068V8.68932C14 9.38835 13.4129 10 12.7419 10Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
            <path d="M11 4L7.52258 6L4 4" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-burger-open" viewBox="0 0 18 18">
            <g opacity="0.5">
                <path d="M16.0588 3H1.94118C1.42138 3 1 3.44772 1 4C1 4.55228 1.42138 5 1.94118 5H16.0588C16.5786 5 17 4.55228 17 4C17 3.44772 16.5786 3 16.0588 3Z"
                      fill="currentColor"/>
                <path d="M16.0588 8H1.94118C1.42138 8 1 8.44772 1 9C1 9.55228 1.42138 10 1.94118 10H16.0588C16.5786 10 17 9.55228 17 9C17 8.44772 16.5786 8 16.0588 8Z"
                      fill="currentColor"/>
                <path d="M16.0588 13H1.94118C1.42138 13 1 13.4477 1 14C1 14.5523 1.42138 15 1.94118 15H16.0588C16.5786 15 17 14.5523 17 14C17 13.4477 16.5786 13 16.0588 13Z"
                      fill="currentColor"/>
            </g>
        </symbol>
        <symbol id="icon-burger-close" viewBox="0 0 18 18">
            <g opacity="0.5">
                <path d="M13.8558 2.73572L3.13375 13.7713C2.73897 14.1776 2.73897 14.8363 3.13375 15.2427C3.52852 15.649 4.16858 15.649 4.56336 15.2427L15.2854 4.20713C15.6802 3.80081 15.6802 3.14204 15.2854 2.73572C14.8906 2.3294 14.2506 2.3294 13.8558 2.73572Z"
                      fill="currentColor"/>
                <path d="M14.8666 13.7929L4.14449 2.75737C3.74972 2.35105 3.10966 2.35105 2.71488 2.75737C2.32011 3.16369 2.32011 3.82246 2.71488 4.22877L13.4369 15.2643C13.8317 15.6706 14.4718 15.6706 14.8666 15.2643C15.2613 14.858 15.2613 14.1992 14.8666 13.7929Z"
                      fill="currentColor"/>
            </g>
        </symbol>
        <symbol id="icon-wa" viewBox="0 0 19 18">
            <path d="M4.08097 16.5425L4.44534 16.7611C5.82996 17.5628 7.4332 18 9.03644 18C13.9919 18 18.0729 13.9919 18.0729 8.96356C18.0729 6.5587 17.1255 4.29959 15.4494 2.62348C13.7004 0.947368 11.4413 0 9.03644 0C4.0081 0 0 4.0081 0 9.03644C0 10.7126 0.510121 12.3887 1.38462 13.8462L1.60324 14.2105L0.65587 17.4899L4.08097 16.5425ZM4.44534 4.37247C4.66397 4.08097 4.95547 4.0081 5.17409 4.0081C5.31984 4.0081 5.53846 4.0081 5.68421 4.0081C5.82996 4.0081 6.04858 3.93522 6.26721 4.44534C6.5587 5.02834 7.06883 6.34008 7.1417 6.48583C7.21457 6.63158 7.28745 6.77733 7.1417 6.99595C7.06883 7.1417 6.99595 7.28745 6.8502 7.4332C6.70445 7.57895 6.5587 7.79757 6.41296 7.94332C6.26721 8.08907 6.12146 8.23482 6.26721 8.52632C6.41296 8.81781 6.99595 9.69231 7.79757 10.4211C8.81781 11.3684 9.69231 11.6599 9.98381 11.7328C10.2753 11.8785 10.4211 11.8785 10.5668 11.6599C10.7126 11.5142 11.2227 10.8583 11.4413 10.5668C11.587 10.2753 11.8057 10.3482 12.0243 10.4211C12.2429 10.4939 13.6275 11.1498 13.8462 11.2955C14.1377 11.4413 14.2834 11.5142 14.3563 11.587C14.4291 11.7328 14.4291 12.2429 14.2105 12.8988C13.9919 13.5547 12.8988 14.1377 12.3887 14.2105C11.9514 14.2834 11.2955 14.2834 10.7126 14.1377C10.3482 13.9919 9.83806 13.8462 9.18219 13.5547C6.48583 12.3887 4.66397 9.61943 4.59109 9.47368C4.51822 9.32794 3.49798 8.01619 3.49798 6.70445C3.49798 5.24696 4.22672 4.59109 4.44534 4.37247Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-tg" viewBox="0 0 19 16">
            <path d="M1.18832 6.96805L12.4733 2.21897C13.5921 1.72428 17.3862 0.141253 17.3862 0.141253C17.3862 0.141253 19.1373 -0.55132 18.9914 1.13064C18.9427 1.82322 18.5536 4.24722 18.1644 6.86911L16.9484 14.6358C16.9484 14.6358 16.8511 15.7736 16.0242 15.9715C15.1973 16.1694 13.8353 15.2789 13.5921 15.081C13.3975 14.9326 9.94392 12.7065 8.67922 11.6182C8.33872 11.3214 7.94959 10.7277 8.72786 10.0352C10.479 8.40266 12.5706 6.37441 13.8353 5.08821C14.419 4.49457 15.0027 3.10943 12.5706 4.79139L5.71205 9.49099C5.71205 9.49099 4.93377 9.98569 3.47451 9.54046C2.01524 9.09524 0.312765 8.5016 0.312765 8.5016C0.312765 8.5016 -0.806005 7.75956 1.18832 6.96805Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-vk" viewBox="0 0 20 12">
            <path d="M10.5103 12C3.88966 12 0.165517 7.53103 0 0H3.31034C3.3931 5.46207 5.7931 7.86207 7.77931 8.27586V0H10.8414V4.71724C12.7448 4.55172 14.731 2.31724 15.3931 0H18.4552C18.2069 1.24138 17.7103 2.4 16.9655 3.3931C16.2207 4.46897 15.3103 5.29655 14.2345 5.95862C15.5586 6.62069 16.5517 7.44828 17.3793 8.44138C18.2069 9.51724 18.869 10.6759 19.2 12H15.8069C15.0621 9.68276 13.2414 7.94483 10.8414 7.69655V12H10.5103Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-submit" viewBox="0 0 16 17">
            <path d="M13.9296 6.87211L2.55928 1.14331C2.26302 0.968669 1.89815 0.953076 1.58629 1.09965C1.27443 1.24622 1.05613 1.53625 1 1.87617C1.00312 1.98844 1.03119 2.10071 1.08108 2.2005L3.24537 7.43033C3.35452 7.77649 3.41377 8.13825 3.42001 8.50312C3.41065 8.86799 3.35452 9.22663 3.24537 9.5759L1.08108 14.8026C1.03119 14.9055 1.00624 15.0147 1 15.1269C1.05613 15.4669 1.27755 15.7569 1.58629 15.9004C1.89815 16.0469 2.2599 16.0313 2.55616 15.8567L13.9264 10.1279C14.5595 9.83475 14.9649 9.19856 14.9649 8.5C14.968 7.80144 14.5626 7.16525 13.9296 6.87211Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="128" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </symbol>

        <symbol id="icon-input-user" viewBox="0 0 18 18">
            <path d="M16.5607 14.3151L16.5718 14.3258L16.5834 14.3361C16.713 14.4513 16.8098 14.638 16.8641 14.8334C16.8716 14.8603 16.8777 14.8855 16.8827 14.9084V15.1224C16.8827 16.4164 16.0089 17.25 15.1837 17.25H2.44898C1.63134 17.25 0.766148 16.4317 0.750223 15.1582C0.751906 15.1446 0.754526 15.1253 0.758402 15.1016C0.768542 15.0394 0.786516 14.9508 0.816753 14.8518C0.881422 14.6402 0.980534 14.4516 1.11052 14.3361L1.12699 14.3214L1.14258 14.3058C1.83348 13.6149 3.11217 13.1282 4.63038 12.8276C6.11411 12.5337 7.68497 12.4439 8.81633 12.4439C9.9475 12.4439 11.5187 12.5337 13.0106 12.8277C14.5344 13.1279 15.8361 13.6155 16.5607 14.3151ZM16.9018 15.0384C16.9018 15.0384 16.9018 15.0373 16.9018 15.0354C16.9019 15.0375 16.9019 15.0385 16.9018 15.0384ZM12.7806 4.71429C12.7806 6.87786 11.0284 8.67857 8.81633 8.67857C6.66538 8.67857 4.85204 6.8778 4.85204 4.65306C4.85204 2.50853 6.64631 0.75 8.81633 0.75C10.9799 0.75 12.7806 2.50217 12.7806 4.71429Z"
                  stroke="currentColor" stroke-width="1.5"/>
        </symbol>
        <symbol id="icon-input-phone" viewBox="0 0 17 19">
            <path d="M15.6976 14.7505L15.6814 14.7006L15.6585 14.6536C15.465 14.2571 14.5012 12.6429 13.7849 11.7228C13.6299 11.5124 13.489 11.3644 13.3714 11.2611C13.3117 11.2086 13.2583 11.168 13.2126 11.1367C13.1897 11.1211 13.1688 11.1079 13.1501 11.0968C13.1407 11.0912 13.1319 11.0861 13.1237 11.0816L13.1118 11.0751L13.1061 11.0722L13.1034 11.0707L13.1021 11.07L13.1014 11.0697C13.1011 11.0695 13.1007 11.0694 12.7587 11.7368L13.1007 11.0694L12.9397 10.9868H12.7587C12.5953 10.9868 12.4562 11.0273 12.3652 11.0591C12.2711 11.092 12.1798 11.1337 12.1064 11.1685C12.0639 11.1886 12.0233 11.2082 11.9832 11.2276C11.8691 11.2828 11.7585 11.3363 11.6183 11.3938L11.5071 11.4393L11.4157 11.5174C11.0478 11.8315 10.6249 11.9518 10.2763 11.9518C9.84316 11.9518 9.53576 11.834 9.27216 11.5639L8.7354 12.0877L9.27216 11.5639L9.18656 11.4762L9.034 11.3198L6.83593 8.72092C6.37595 8.12187 6.22052 7.07463 7.19931 6.1385L7.20866 6.12956L7.21769 6.12031C7.39286 5.94081 7.54961 5.76994 7.66623 5.60734C7.77898 5.45014 7.91347 5.22137 7.93226 4.93246C7.95294 4.61466 7.8265 4.37094 7.71343 4.21196C7.62097 4.08196 7.49789 3.95609 7.40741 3.86355C7.40621 3.86233 7.40502 3.86111 7.40384 3.8599C7.04257 3.44518 6.5412 2.93241 6.10229 2.5046C5.87837 2.28634 5.66601 2.0856 5.49102 1.92637C5.36232 1.80926 5.22837 1.69093 5.12101 1.60837L5.07761 1.5639C5.07756 1.56385 5.07736 1.56362 5.07701 1.56322C5.06629 1.55096 4.91653 1.37964 4.68697 1.30123C4.57546 1.26314 4.47741 1.2553 4.42475 1.25246C4.37824 1.24995 4.32706 1.24998 4.29228 1.25C4.28942 1.25 4.28667 1.25 4.28404 1.25C3.56817 1.25 2.87554 1.67028 2.29968 2.25783C1.32227 3.20833 1.15659 4.73705 1.29069 6.07689C1.42934 7.46217 1.91093 8.9078 2.52874 9.93083C2.90093 10.5503 3.76488 11.7038 4.64239 12.7894C5.51289 13.8664 6.473 14.9717 7.04769 15.4632C7.65274 15.9875 8.47813 16.5503 9.38462 16.9854C10.2854 17.4177 11.3207 17.75 12.3307 17.75C13.1582 17.75 13.8072 17.534 14.35 17.2003L14.3933 17.1737L14.4327 17.1414C14.9113 16.7491 15.2553 16.3551 15.4711 15.9864C15.5789 15.8024 15.663 15.6105 15.7096 15.4171C15.754 15.2326 15.7767 14.9936 15.6976 14.7505Z"
                  stroke="currentColor" stroke-width="1.5"/>
        </symbol>
        <symbol id="icon-input-email" viewBox="0 0 19 15">
            <rect x="0.5" y="0.5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.5"/>
            <path d="M15 5L9.53548 8L4 5" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
        </symbol>

        <symbol id="icon-fill-phone" viewBox="0 0 15 16">
            <path d="M14.1924 13.848C14.0053 13.4737 12.9761 11.7895 12.2275 10.8538C11.9468 10.4795 11.7597 10.386 11.7597 10.386C11.5726 10.386 11.2919 10.5731 10.824 10.7602C10.2626 11.2281 9.60765 11.4152 9.04625 11.4152C8.39128 11.4152 7.82987 11.2281 7.36204 10.7602L7.26847 10.6667L7.08133 10.4795L4.64859 7.67251C3.90005 6.73684 3.71291 5.1462 5.11642 3.83626C5.86496 3.08772 5.77139 2.99415 5.30356 2.52632C4.55502 1.68421 3.15151 0.374269 2.87081 0.187135L2.77724 0.0935673C2.68367 0 2.68367 0 2.49654 0C2.0287 0 1.4673 0.280702 0.905896 0.842105C-0.684747 2.33918 0.0637912 6.08187 1.28017 8.04678C2.0287 9.26316 4.92929 12.8187 6.05209 13.7544C7.26847 14.7836 9.42052 16 11.2919 16C12.0404 16 12.6018 15.8129 13.0696 15.5322C14.0053 14.7836 14.286 14.1287 14.1924 13.848Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-fill-location" viewBox="0 0 13 17">
            <path d="M6.18084 -0.000488281C2.78084 -0.000488281 -0.000976562 2.78133 -0.000976562 6.18133C-0.000976562 6.41315 -0.000976562 6.72224 0.0762962 6.95406C0.539933 11.5904 5.40811 16.5359 5.63993 16.7677C5.79448 16.9222 5.94902 16.9995 6.18084 16.9995C6.41266 16.9995 6.5672 16.9222 6.72175 16.7677C6.95357 16.5359 11.8217 11.5904 12.2854 6.95406C12.3627 6.72224 12.3627 6.41315 12.3627 6.18133C12.3627 2.78133 9.58084 -0.000488281 6.18084 -0.000488281ZM6.18084 8.42224C4.94448 8.42224 3.93993 7.41769 3.93993 6.18133C3.93993 4.94497 4.94448 3.94042 6.18084 3.94042C7.4172 3.94042 8.42175 4.94497 8.42175 6.18133C8.42175 7.41769 7.4172 8.42224 6.18084 8.42224Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-fill-time" viewBox="0 0 17 17">
            <path d="M15.7257 1.23838C14.4869 -0.000488281 12.4909 -0.000488281 8.49902 -0.000488281C4.47271 -0.000488281 2.47676 -0.000488281 1.23789 1.23838C-0.000976562 2.47724 -0.000976562 4.4732 -0.000976562 8.49951C-0.000976562 12.4914 -0.000976562 14.4874 1.23789 15.7606C2.47676 16.9995 4.47271 16.9995 8.49902 16.9995C12.4909 16.9995 14.4869 16.9995 15.7602 15.7606C16.999 14.5218 16.999 12.5258 16.999 8.49951C16.9646 4.4732 16.9646 2.47724 15.7257 1.23838ZM11.0456 11.0461C10.8047 11.287 10.3917 11.287 10.1508 11.0461L8.05165 8.94688C7.94842 8.84364 7.87959 8.67158 7.87959 8.49951V5.09263C7.87959 4.7485 8.15489 4.4732 8.49902 4.4732C8.84315 4.4732 9.11846 4.7485 9.11846 5.09263V8.22421L11.0456 10.1513C11.3209 10.3922 11.3209 10.8052 11.0456 11.0461Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-fill-email" viewBox="0 0 19 13">
            <path d="M17.1658 -0.000488281H1.83031C0.819788 -0.000488281 -0.00195312 0.849335 -0.00195312 1.89439V11.1046C-0.00195312 12.1497 0.819788 12.9995 1.83031 12.9995H17.1658C18.1763 12.9995 18.998 12.1497 18.998 11.1046V1.89439C18.998 0.849335 18.1763 -0.000488281 17.1658 -0.000488281ZM16.3774 4.74244L10.0144 8.16471C9.85895 8.25658 9.68127 8.29103 9.5036 8.29103C9.32592 8.29103 9.14825 8.24509 8.99279 8.16471L2.61874 4.74244C2.07461 4.45534 1.86362 3.75481 2.14124 3.19209C2.41885 2.62937 3.09623 2.41117 3.64036 2.69828L9.5036 5.8564L15.3557 2.70976C15.8999 2.42266 16.5661 2.64085 16.8549 3.20358C17.1325 3.7663 16.9215 4.45534 16.3774 4.74244Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-cancel" viewBox="0 0 15 14" fill="none">
            <path d="M7.5 0C3.64607 0 0.5 3.14607 0.5 7C0.5 10.8539 3.64607 14 7.5 14C11.3539 14 14.5 10.8539 14.5 7C14.5 3.14607 11.3539 0 7.5 0ZM7.5 1.57303C8.67978 1.57303 9.85955 1.96629 10.7247 2.67416L3.17416 10.2247C2.46629 9.35955 2.07303 8.17978 2.07303 7C2.07303 4.01124 4.51124 1.57303 7.5 1.57303ZM7.5 12.427C6.32022 12.427 5.14045 12.0337 4.27528 11.3258L11.9045 3.69663C12.5337 4.64045 13.0056 5.74157 13.0056 6.92135C12.927 9.98876 10.4888 12.427 7.5 12.427Z"
                  fill="currentColor" fill-opacity="0.5"/>
        </symbol>
        <symbol id="icon-arrow-right" viewBox="0 0 20 20" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M6.91083 16.4227C6.58539 16.0972 6.58539 15.5696 6.91083 15.2442L12.1549 10.0001L6.91083 4.756C6.58539 4.43057 6.58539 3.90293 6.91083 3.57749C7.23626 3.25205 7.7639 3.25205 8.08934 3.57749L13.9227 9.41083C14.2481 9.73626 14.2481 10.2639 13.9227 10.5893L8.08934 16.4227C7.7639 16.7481 7.23626 16.7481 6.91083 16.4227Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 12 12" fill="none">
            <path d="M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M6 1V11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-minus" viewBox="0 0 12 12" fill="none">
            <path d="M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-comment" viewBox="0 0 16 15" fill="none">
            <path d="M4.46629 15C4.80337 15 5.05618 14.8315 5.47753 14.4101L8.00562 12.2191H12.6404C14.8315 12.2191 15.927 11.0393 15.927 8.93258V3.28652C16.0112 1.17978 14.8315 0 12.6404 0H3.28652C1.17978 0 0 1.17978 0 3.28652V8.84832C0 10.9551 1.17978 12.1348 3.28652 12.1348H3.6236V13.9888C3.70787 14.6629 3.96067 15 4.46629 15ZM4.7191 13.6517V11.5449C4.7191 11.1236 4.55056 11.0393 4.21348 11.0393H3.37079C1.9382 11.0393 1.17978 10.2809 1.17978 8.84832V3.28652C1.17978 1.85393 1.85393 1.09551 3.37079 1.09551H12.6404C14.073 1.09551 14.8315 1.85393 14.8315 3.28652V8.84832C14.8315 10.2809 14.1573 11.0393 12.6404 11.0393H8.00562C7.58427 11.0393 7.41573 11.1236 7.07865 11.3764L4.7191 13.6517Z"
                  fill="currentColor"/>
        </symbol>
        <symbol id="icon-login" viewBox="0 0 16 17" fill="none">
            <path d="M1.55957 13.05C1.55957 14.38 2.67957 15.5 4.00957 15.5H12.3396C13.6696 15.5 14.7896 14.38 14.7896 13.05V3.95C14.7896 2.62 13.6696 1.5 12.3396 1.5H3.93957C2.60957 1.5 1.55957 2.62 1.55957 3.95"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
            <path d="M6.18066 11.37L9.05066 8.50001L6.18066 5.63" stroke="black" stroke-width="1.5"
                  stroke-miterlimit="10"/>
            <path d="M9.05 8.5H1" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
        </symbol>
        <symbol id="icon-time" viewBox="0 0 19 19" fill="none">
            <path d="M9.5 4.52386V9.2191L11.8476 10.6762M18 9.30005C18 13.9953 14.1952 17.8 9.5 17.8C4.80476 17.8 1 13.9143 1 9.30005C1 4.60481 4.80476 0.800049 9.5 0.800049C14.1143 0.800049 18 4.60481 18 9.30005Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                  stroke-linejoin="round"/>
        </symbol>
        <symbol id="icon-close" viewBox="0 0 16 16" fill="none">
            <path d="M1 14.7886L14.7886 1" stroke="currentCOlor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M14.7886 14.7886L0.999992 1" stroke="currentCOlor" stroke-width="1.6" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-return" viewBox="0 0 21 15" fill="none">
            <path d="M6.15139 13.7886L1.07373 7.5M6.15122 1L1.07356 7.28857" stroke="currentColor" stroke-width="1.6"
                  stroke-linecap="round"/>
            <path d="M20.106 7.5H1.60596" stroke="currentColor" stroke-width="1.6"/>
        </symbol>
        <symbol id="icon-panel-menu" viewBox="0 0 25 24" fill="none">
            <path d="M4 6.6C4 5.02599 5.27599 3.75 6.85 3.75H7.75C9.32401 3.75 10.6 5.02599 10.6 6.6V7.5C10.6 9.07401 9.32401 10.35 7.75 10.35H6.85C5.27599 10.35 4 9.07401 4 7.5V6.6ZM13.9 6.6C13.9 5.02599 15.176 3.75 16.75 3.75H17.65C19.224 3.75 20.5 5.02599 20.5 6.6V7.5C20.5 9.07401 19.224 10.35 17.65 10.35H16.75C15.176 10.35 13.9 9.07401 13.9 7.5V6.6ZM4 16.5C4 14.926 5.27599 13.65 6.85 13.65H7.75C9.32401 13.65 10.6 14.926 10.6 16.5V17.4C10.6 18.974 9.32401 20.25 7.75 20.25H6.85C5.27599 20.25 4 18.974 4 17.4V16.5ZM13.9 16.5C13.9 14.926 15.176 13.65 16.75 13.65H17.65C19.224 13.65 20.5 14.926 20.5 16.5V17.4C20.5 18.974 19.224 20.25 17.65 20.25H16.75C15.176 20.25 13.9 18.974 13.9 17.4V16.5Z"
                  stroke="currentColor" stroke-width="1.5"/>
        </symbol>
        <symbol id="icon-panel-favorite" viewBox="0 0 25 24" fill="none">
            <path d="M9.42164 8.94341L3.20632 9.85473C2.77224 9.91417 2.59466 10.4491 2.91036 10.7462L7.42879 15.1642L6.36331 21.3849C6.28439 21.8208 6.7382 22.1378 7.1131 21.9397L12.6773 18.9878L18.2415 21.9397C18.6164 22.1378 19.0702 21.8208 18.9913 21.3849L17.9258 15.1642L22.4245 10.7661C22.7205 10.4689 22.5626 9.93398 22.1285 9.87454L15.9132 8.96322L13.1311 3.29717C12.9535 2.90094 12.4011 2.90094 12.2037 3.29717L10.8556 6.03291"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-panel-basket" viewBox="0 0 25 24" fill="none">
            <path d="M8.84235 7.04688H6.3032C5.86433 7.04688 5.51951 7.36035 5.45682 7.79922L4.26561 18.3633C4.07752 20.3069 5.61355 21.9997 7.5571 21.9997H12.3846H17.1808C19.1243 21.9997 20.6604 20.3069 20.4723 18.3633L19.2811 7.83056C19.2497 7.3917 18.8736 7.07822 18.4347 7.07822H11.1307"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
            <path d="M15.896 10.1504V5.54227C15.896 3.59872 14.3286 2 12.3537 2H12.385C10.4415 2 8.84277 3.56738 8.84277 5.54227V10.1504"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-panel-user" viewBox="0 0 25 24" fill="none">
            <path d="M12.8387 11.0909C13.527 11.0909 15.6953 11.0909 16.3317 7.42173C16.9682 3.75255 15.4659 2 12.8387 2C10.2116 2 8.70926 3.7451 9.3531 7.41427C9.99694 11.0835 12.1505 11.0909 12.8387 11.0909Z"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
            <path d="M16.389 13.8184C16.389 13.8184 18.111 14.7354 20.8281 15.6524C21.6934 15.9448 22.0742 17.3282 21.8838 19.3599C21.8406 19.85 21.529 20.2927 21.0531 20.5377C18.985 21.597 15.2035 22.0002 12.8412 22.0002C10.4789 22.0002 6.69745 21.597 4.62933 20.5377C4.16206 20.3006 3.85054 19.8579 3.79862 19.3599C3.60825 17.3203 3.98034 15.9448 4.85431 15.6524C7.57142 14.7354 9.29341 13.8184 9.29341 13.8184"
                  stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
        </symbol>
        <symbol id="icon-filter" viewBox="0 0 19 21" fill="none">
            <path d="M1 3.10001H13.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M1 10.4H7.3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M11.5 10.4H17.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M5.19995 17.7H17.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M15.6 5.2C16.7598 5.2 17.7 4.2598 17.7 3.1C17.7 1.9402 16.7598 1 15.6 1C14.4402 1 13.5 1.9402 13.5 3.1C13.5 4.2598 14.4402 5.2 15.6 5.2Z"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M9.40005 12.5C10.5598 12.5 11.5 11.5598 11.5 10.4C11.5 9.24019 10.5598 8.29999 9.40005 8.29999C8.24025 8.29999 7.30005 9.24019 7.30005 10.4C7.30005 11.5598 8.24025 12.5 9.40005 12.5Z"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M3.1 19.8C4.2598 19.8 5.2 18.8598 5.2 17.7C5.2 16.5402 4.2598 15.6 3.1 15.6C1.9402 15.6 1 16.5402 1 17.7C1 18.8598 1.9402 19.8 3.1 19.8Z"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </symbol>
    </svg>
</div>

<style>


.service-bar{
    padding:7px 0;
    background-color:#EBEBEB
}
.service-bar__content{
    display:-webkit-box;
    display:-ms-flexbox;
    display:flex;
    -webkit-box-orient:horizontal;
    -webkit-box-direction:normal;
    -ms-flex-flow:row wrap;
    flex-flow:row wrap;
    -webkit-box-pack:center;
    -ms-flex-pack:center;
    justify-content:center
}
.service-bar__item{
    display:-ms-flex;
    display:-webkit-box;
    display:-ms-flexbox;
    display:flex;
    -webkit-box-orient:horizontal;
    -webkit-box-direction:normal;
    -ms-flex-flow:row wrap;
    flex-flow:row wrap;
    -webkit-box-pack:start;
    -ms-flex-pack:start;
    justify-content:flex-start;
    padding:10px 16px;
    background-color:#fff;
    border-radius:3px;
    -webkit-transition:background-color 0.3s ease;
    -o-transition:background-color 0.3s ease;
    transition:background-color 0.3s ease
}
@media screen and (min-width: 990px){
    .service-bar__item:hover{
        background-color:#F8F8F8
    }
}
.service-bar__item:not(:last-of-type){
    margin-right:8px
}
.service-bar__icon{
    margin-right:11px
}
.service-bar__title{
    color:#000
}
@media screen and (max-width: 576px){
    .service-bar__icon{
        margin-right:0
    }
    .service-bar__title{
        display:none
    }
}
.overlay-seo { display: none; }



</style>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>