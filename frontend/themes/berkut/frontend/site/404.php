<?php 
 
 use yii\helpers\Url;

 ?>

 <!--📰 404-->
 <div class="content not-found">
            <div class="container">

                <!--🤟 Хлебные крошки-->
                <ul class="breadcrumbs">
                    <li><a href="<?= Url::to(['/site/index'])?>">Главная</a></li>
                    <li>404</li>
                </ul>

                <div class="not-found__content center">
                    <div class="not-found__404">404</div>
                    <div class="not-found__title">Страница не найдена</div>
                    <div class="not-found__text">
                        Такой страницы не существует, либо она была удалена? <br>
                        Чтобы найти нужную страницу воспользуйтесь поиском или перейдите в каталог
                    </div>
                    <a href="<?= Url::to(['/site/index'])?>" class="btn">Перейти на главную</a>
                </div>
            </div>
        </div>