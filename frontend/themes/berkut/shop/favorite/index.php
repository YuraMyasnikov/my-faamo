<?php

use frontend\assets\FavouriteAsset;
use frontend\assets\ShowMoreAsset;
use frontend\models\CatalogPager;
use yii\helpers\Url;
use yii\widgets\ListView;

/**
 * @var \yii\web\View $this
 */

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = 'Избранное';

ShowMoreAsset::register($this);
FavouriteAsset::register($this);

?>

<div class="content">

   <!--🤟 Хлебные крошки-->
   <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/']) ?>">Главная</a></li>
            <li>Закладки</li>
        </ul>
    </div>

   <div class="container">

      <h1>Закладки</h1>

      <div class="layout">
         <div class="layout-content">

            <?php
               echo ListView::widget([
                  'dataProvider' => $dataProvider,
                  'emptyText' => 'Товаров нет',
                  'itemView' => function ($model) {
                        return $this->render('_elem', ['favorite' => $model]);
                  },
                  'itemOptions' => [
                     'class' => 'column col-4 md-col-6'
                  ],
                  'options' => [
                        'tag' => 'div',
                        'class' => 'layout-content'
                  ],
                  'layout' => '<div class="columns columns--element">{items}</div>',
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