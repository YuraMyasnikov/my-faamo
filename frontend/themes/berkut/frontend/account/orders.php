<?php

use CmsModule\Shop\common\models\Orders;
use frontend\assets\ShowMoreAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$dataProvider->prepare();
$pageCount = $dataProvider->pagination->getPageCount();
$currentPage = $dataProvider->pagination->page + 1;

// ShowMoreAsset::register($this);

?>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 История заказов-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="">Главная</a></li>
            <li>Личный кабинет</li>
        </ul>
    </div>

    <div class="container">

        <h1>Личный кабинет</h1>

        <div class="layout">
            <div class="layout-aside">

                <div class="layout-sticky">
                    <div class="lk-menu">
                        <a href="<?= Url::to(['/account']) ?>" class="lk-menu__item">Профиль</a>
                        <a href="<?= Url::to(['/account/change-password']) ?>" class="lk-menu__item">Смена пароля</a>
                        <a href="" class="lk-menu__item active">История заказов</a>
                        <a href="<?= Url::to(['/site/logout']) ?>" class="lk-menu__item link">Выйти</a>
                    </div>
                </div>

            </div>
            <div class="layout-content">

                <div class="lk-box">
                    <div class="lk-box__title">История заказов</div>

                    <!--👉 history-tags-->
                    <div class="history-tag">
                        <a href="<?= Url::to(['/account/orders']) ?>" class="history-tag__item <?= !intval($status_group) ? 'active' : '' ?>">Все</a>
                        <?php foreach ($status_groups as $status_group_item) { ?>
                            <a 
                                href="<?= Url::to(['/account/orders', 'status_group' => $status_group_item->id]); ?>" 
                                class="history-tag__item <?= $status_group == $status_group_item->id ? 'active' : ''; ?>"
                            >
                                <?= Html::encode($status_group_item->name); ?>
                            </a>
                            
                        <?php } ?>
                        
                    </div>

                    <?php
                        echo ListView::widget([
                            'dataProvider' => $dataProvider,
                            'emptyText' => 'Заказов нет',
                            'itemView' => function (Orders $model) {
                                return $this->render('_order_item', ['model' => $model]);
                            },
                            'options' => [
                                'tag' => false
                            ],                            
                        ]);
                    ?>                    
                </div>

            </div>
        </div>


    </div>


</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->


















<!-- <section class="lk">
    <div class="container">
        <div class="lk__wrap">
            

            <div class="lk__content">
<div class="lk-tab">
                <h2 class="lk__title">История заказов</h2>
                <ul class="orders-nav__list">
                    <li class="orders-nav__item <?= $status_group === null ? 'active' : ''; ?>">
                        <a href="<?= Url::to(['/profile/orders']); ?>" class="orders-nav__link">Все</a>
                    </li>
                    <?php foreach ($status_groups as $status_group_item) { ?>
                        <li class="orders-nav__item <?= $status_group == $status_group_item->id ? 'active' : ''; ?>">
                            <a href="<?= Url::to(['/profile/orders', 'status_group' => $status_group_item->id]); ?>" class="orders-nav__link"><?= Html::encode($status_group_item->name); ?></a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="orders-list">
                <?php
                Pjax::begin(
                    [
                        'id' => 'pjax-container',
                        'timeout' => 6000,
                        'options' => [
                            'class' => 'js-review-pjax-container',
                        ]
                    ]
                ); ?>
                <?php
                $url = Yii::$app->request->url;
                $nextPageLink = false;
                if ($currentPage < $pageCount) {
                    if (stristr($url, 'page=') !== false) {
                        $nextPageLink = str_replace('page=' . $currentPage, 'page=' . ($currentPage + 1), $url);
                    } elseif (stristr($url, '?') !== false) {
                        $nextPageLink = $url . '&page=' . ($currentPage + 1);
                    } else {
                        $urlArr = explode('?', $url);
                        $urlArr[0] .= '?page=' . ($currentPage + 1);
                        $nextPageLink = implode('?', $urlArr);
                    }
                }

                $isLastPage = $dataProvider->pagination->page + 1 === $dataProvider->pagination->pageCount;
                $nextLoadHrefContainer = Html::tag('div', '', ['class' => 'catalog-more']);

                if (!$isLastPage && $nextPageLink) {
                    $nextLoadHref = '<button class="show-more__button js-catalog-more" type="button" name="button" data-next-page-link="' . Url::to($nextPageLink) . '">Показать еще</button>';
                }

                $layout = '<div class="js-products-container">{items}</div><div class="content-pagination"><div class="catalog-pagination">{pager}</div>';

                if (!$isLastPage && $nextPageLink) {
                    $layout .= $nextLoadHref;
                }

                $layout .= '</div>';
                $dataProvider->pagination->pageSizeParam = false;
                ?>

                <?php
                try {
                     ListView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'emptyText' => 'Заказов нет',
                            'itemView' => function ($model) {
                                return $this->render('_order_item', ['model' => $model]);
                            },
                            'options' => [
                                'tag' => false
                            ],
                            'layout' => $layout,
                            'pager' => [
                                'linkOptions' => ['class' => 'pagination__link'],
                                'linkContainerOptions' => ['class' => 'pagination__item'],
                                'options' => ['class' => 'pagination'],
                                'prevPageCssClass' => 'prev-link',
                                'nextPageCssClass' => 'next-link',
                                'activePageCssClass' => 'current',
                                'nextPageLabel' => '',
                                'prevPageLabel' => '',
                                'maxButtonCount' => 3,
                            ]
                        ]
                    );
                } catch (Exception $e) {
                    if (YII_DEBUG) {
                        echo $e->getMessage();
                        Yii::error($e->getMessage());
                    } else {
                        echo 'Возникла ошибка';
                    }
                } ?>

                <?php Pjax::end() ?>

                </div>
            </div>
        </div>
    </div>
</section> -->