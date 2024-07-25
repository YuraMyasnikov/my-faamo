<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\forms\SubscribersForm as FormsSubscribersForm;

$subscribeForm = FormsSubscribersForm::buildForm();


?>
<!-- 🤟 footer -->
    <!-- ☝️ Добавить класс footer--grey на главной странице-->
    <footer class="footer footer--grey">
        <div class="container">
            <div class="footer-top">
                <div class="columns columns--element">
                    <div class="column col-3 xl-col-6 md-col-12">
                        <div class="footer-top__column">
                            <!--👉-->
                            <div class="logotype logotype--small">
                                <a href="<?= Url::to(['site/index'])?>">
                                    <div class="logotype-image">
                                        <img src="/img/logotype.svg" alt="" width="59" height="83">
                                    </div>
                                    <div class="logotype-title">BERKUT.IV</div>
                                    <div class="logotype-text">
                                        Одежда и обувь для охоты, <br>
                                        рыбалки и туризма <br>
                                        от производителя
                                    </div>
                                </a>
                            </div>
                            <!--👉-->
                            <div class="footer-social">
                                <div class="social">
                                    <a href="<?php echo Yii::$app->settings->getSocial('whatsapp') ?>" class="social-item" target="_blank">
                                        <svg width="20" height="20">
                                            <use xlink:href="#icon-wa"></use>
                                        </svg>
                                    </a>
                                    <a href="<?php echo Yii::$app->settings->getSocial('tg') ?>" class="social-item" target="_blank">
                                        <svg width="20" height="20">
                                            <use xlink:href="#icon-tg"></use>
                                        </svg>
                                    </a>
                                    <a href="<?php echo Yii::$app->settings->getSocial('vk') ?>" class="social-item" target="_blank">
                                        <svg width="20" height="20">
                                            <use xlink:href="#icon-vk"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <!--👉-->
                            <div class="footer-subscribe">
                                <div class="subscribe">
                                    <div class="subscribe-title">Будьте в курсе актуальных акций!</div>


                                    <?php 
                                        $form = ActiveForm::begin([
                                            'action' => Url::to(['/site/subscribe']), 
                                            'method' => 'POST', 
                                            'options' => ['class' => 'subscribe-form']]); 
                                    ?>
                                    <div class="subscribe-group">
                                        <?php 
                                            echo $form
                                                ->field($subscribeForm, 'email', [
                                                    'options' => [],
                                                    'template' => '{input}<div class="input-icon"><svg width="20" height="20"><use xlink:href="#icon-input-email"></use></svg></div>{error}{hint}'
                                                ])
                                                ->textInput([
                                                    'class' => 'input input--icon subscribe-input',
                                                    'placeholder' => 'Введите ваш Email...'
                                                ]); 
                                        ?>
                                        <button type="submit" class="subscribe-button">
                                            <svg width="16" height="16">
                                                <use xlink:href="#icon-submit"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php $form::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column col-3 xl-col-6 md-col-12">
                        <div class="footer-top__column">
                            <div class="footer-top__title">Компания</div>

                            <!--👉-->
                            <ul class="footer-top__menu">
                                <li><a href="<?= Url::to(['/site/about']) ?>">О компании</a></li>
                                <li><a href="<?= Url::to(['/site/delivery'])?>">Доставка и оплата</a></li>
                                <li><a href="<?= Url::to(['/reviews-frontend/index'])?>">Отзывы</a></li>
                                <li><a href="<?= Url::to(['/site/how-to-order'])?>">Как сделать заказ</a></li>
                                <li><a href="<?= Url::to(['/site/contact'])?>">Контакты</a></li>
                            </ul>

                            <!--👉-->
                            <ul class="footer-top__list">
                                <li><a href="<?= Url::to(['/site/certificate'])?>">Сертификаты</a></li>
                                <li><a href="<?= Url::to(['/site/props'])?>">Реквизиты</a></li>
                                <li><a href="<?= Url::to(['/site/politics'])?>">Политика конфиденциальности</a></li>
                            </ul>

                        </div>
                    </div>
                    <div class="column col-6 xl-col-12">
                        <div class="footer-top__column">
                            <div class="footer-top__title">Контакты</div>

                            <div class="columns columns--element">
                                <div class="column col-6 md-col-12">
                                    <div class="footer-contacts footer-contacts--main">
                                        <div class="footer-contact">
                                            <div class="footer-contact__image">
                                                <svg width="15" height="16">
                                                    <use xlink:href="#icon-fill-phone"></use>
                                                </svg>
                                            </div>
                                            <div class="footer-contact__name">Михаил</div>
                                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone()) ?>" class="footer-contact__value">
                                                <?php echo Yii::$app->settings->getPhone() ?>
                                            </a>
                                        </div>
                                        <div class="footer-contact">
                                            <div class="footer-contact__name">Анна</div>
                                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone2()) ?>" class="footer-contact__value">
                                                <?php echo Yii::$app->settings->getPhone2() ?>
                                            </a>
                                        </div>
                                        <div class="footer-contact">
                                            <div class="footer-contact__name">Рабочий</div>
                                            <a href="tel:<?= str_replace(' ','', Yii::$app->settings->getPhone3()) ?>" class="footer-contact__value">
                                                <?php echo Yii::$app->settings->getPhone3() ?>
                                            </a>
                                        </div>
                                        <div class="footer-contact">
                                            <button class="btn btn--small btn--full"
                                                    data-micromodal-trigger="modal-recall">
                                                <svg width="14" height="14">
                                                    <use xlink:href="#icon-phone"></use>
                                                </svg>
                                                Заказать обратный звонок
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="column col-6 md-col-12">
                                    <div class="footer-contacts">
                                        <div class="footer-contact">
                                            <div class="footer-contact__image">
                                                <svg width="13" height="17">
                                                    <use xlink:href="#icon-fill-location"></use>
                                                </svg>
                                            </div>
                                            <div class="footer-contact__name">Производство:</div>
                                            <div class="footer-contact__value">
                                                <?php echo Yii::$app->settings->getAddress() ?>
                                            </div>
                                        </div>
                                        <div class="footer-contact">
                                            <div class="footer-contact__image">
                                                <svg width="17" height="17">
                                                    <use xlink:href="#icon-fill-time"></use>
                                                </svg>
                                            </div>
                                            <div class="footer-contact__name">График работы</div>
                                            <div class="footer-contact__value">с 10:00 до 17:00</div>
                                            <div class="footer-contact__info">Суббота, воскресенье – выходной</div>
                                        </div>
                                        <div class="footer-contact">
                                            <div class="footer-contact__image">
                                                <svg width="19" height="13">
                                                    <use xlink:href="#icon-fill-email"></use>
                                                </svg>
                                            </div>
                                            <div class="footer-contact__name">Электронная почта:</div>
                                            <a href="mailto:<?php echo Yii::$app->settings->getEmail() ?>" class="footer-contact__value">
                                                <?php echo Yii::$app->settings->getEmail() ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="columns columns--grid">
                    <div class="column col-9 lg-col-12">
                        <div class="footer-copyright">
                            В СООТВЕТСТВИИ СО СТАТЬЕЙ 1259 ГРАЖДАНСКОГО КОДЕКСА РФ ВСЕ МАТЕРИАЛЫ САЙТА ЯВЛЯЮТСЯ НАШЕЙ
                            ИНТЕЛЛЕКТУАЛЬНОЙ СОБСТВЕННОСТЬЮ. ЛЮБОЕ КОПИРОВАНИЕ И РЕРАЙТ МАТЕРИАЛОВ С САЙТА ЗАПРЕЩЁН!
                        </div>
                    </div>
                    <div class="column col-3 lg-col-12">
                        <div class="footer-create">
                            Разработано в
                            <a href="https://codi-way.ru/" target="_blank">
                                <img src="/img/create.svg" alt="" width="132" height="21" loading="lazy">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php $isSsuccess = Yii::$app->session->getFlash('success'); ?>
    <?php if($isSsuccess) { ?>
        <script>
            new Noty({
                type: 'success',
                layout: 'topRight',
                text: '<?= Html::encode( $isSsuccess ) ?>',
                timeout: 3000,
            }).show();
        </script>
    <?php } ?>    

    <?php $isError = Yii::$app->session->getFlash('error'); ?>
    <?php if($isError) { ?>
        <script>
            new Noty({
                type: 'error',
                layout: 'topRight',
                text: '<?= Html::encode( $isError ) ?>',
                timeout: 3000,
            }).show();
        </script>
    <?php } ?>    