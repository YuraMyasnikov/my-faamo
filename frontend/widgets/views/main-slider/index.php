<?php

/**
 * @var $sliders
 */

use frontend\forms\SubscribersForm;
use frontend\services\CityCodeResolver;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var CityCodeResolver $cityCodeResolver */
$cityCodeResolver = Yii::$container->get(CityCodeResolver::class);

?>
<?php if ($sliders) { ?>
<section class="main-banner">
    <div class="main-banner-slider">
        <div class="swiper-wrapper">
            <?php foreach ($sliders as $slider) { ?>
                <div class="swiper-slide main-banner-slider-item">
                    <div class="main-banner-slider-item-info <?= $slider->side?>">
                        <div class="main-banner-slider-item-info-title"><?= Html::encode($slider->title); ?></div>
                        <p class="main-banner-slider-item-info-text"><?= Html::encode($slider->description); ?></p>
                        <p><a href="<?= Html::encode($cityCodeResolver->getUrlPrefixForCurrentCity() . $slider->button_link); ?>" class="btn-bg padding black radius"><?= Html::encode($slider->button_name); ?></a></p>
                    </div>
                    <div class="main-banner-slider-item-img right example_<?= $slider->side?>">
                        <img src="<?= Yii::$app->image->get($slider->banner_image)->file ?>">
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="main-next"></div>
        <div class="main-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="subscription">
        <div class="subscription-bx">
            <p class="weight less-size">Оставьте свой email для получения эксклюзивных скидок и информации о новых коллекциях</p>

            <?php
                try {
                    $model = SubscribersForm::buildForm();

                    $form = ActiveForm::begin([
                        'action' => Url::to(['/site/subscribe']), 
                        'method' => 'post',
                        'options'=> [
                            'class'  => 'subscription-bx-form',
                        ]
                    ]); 
                    
                    echo $form
                    ->field($model, 'email', [
                        'options' => [
                        ],
                        'template' => '
                            {input}<button type="submit" class="subscription-bx-form-button">Подписаться</button>
                            <!-- {error} {hint} -->',
                    ])
                    ->input('email', [
                        'id'           => 'subscription-mail', 
                        'class'        => 'subscription-bx-form-input',
                        'placeholder'  => 'Ваша электронная почта', 
                        'autocomplete' => 'off', 
                        'required'     => true
                    ])
                    ->label(false); 

                    ActiveForm::end();
                } catch (Exception $e) {
                    Yii::error($e->getMessage());
                    if (YII_DEBUG) {
                        echo 'Произошла ошибка в форме подписки на рассылку';
                    }
                }
            ?>
        </div>
    </div>
</section>
<?php } ?>

