<?php


use frontend\assets\FavouriteAsset;
use frontend\models\CatalogPager;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/** @var ActiveDataProvider $dataProvider */
/** @var \yii\web\View $this */

FavouriteAsset::register($this);

?>

<?php
Pjax::begin([
    'id' => 'pjax-container-catalog',
    'timeout' => 6000,
    'options' => [
        'class' => 'js-catalog-pjax-container',
    ]
]);
?>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Каталог-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
            <li>Результаты поиска</li>
        </ul>
    </div>

    <div class="container">

        <h1>Результаты поиска</h1>

        <div class="layout">
            <div class="layout-content">

                <?php 
                    /**
                     * Shop product list and pagination
                     */
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_product',
                        'itemOptions' => [
                            'class' => 'column col-3 md-col-6'
                        ],
                        'layout' => '{items}',
                        'options' => [
                            'tag' => 'div',
                            'class' => 'columns columns--element'
                        ],
                    ]);
                ?>

                <div class="pagination">
                    <?php echo CatalogPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'options' => [
                            'tag' => 'div',
                            'class' => 'pagination-items'
                        ],
                        'linkContainerOptions' => [
                            'tag' => 'a',
                            'class' => 'pagination-item'
                        ],
                    ]) ?>
                </div>
                
            </div>
        </div>
    </div>

</div>

<!--🔥 КОНЕЦ ШАБЛОНА-->

<?php Pjax::end(); ?>