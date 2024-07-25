<?php 

use frontend\assets\RegistrationAsset;
use frontend\forms\ProfileForm;
use yii\helpers\Url;

/** @var string $registrationMode */
/** @var ProfileForm $signup */

RegistrationAsset::register($this);

?>

<style>
    .has-error .input  {border-color: #dc3545;}
</style>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Регистрация-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="">Главная</a></li>
            <li>Регистрация</li>
        </ul>
    </div>

    <div class="container">

        <h1>Регистрация</h1>

        <!--🤟 order-header-->
        <div class="order-header">
            <div class="order-tabs">
                <a 
                    href="<?= $registrationMode === 'user' ? '' : Url::to(['/site/registration', 'change-mode' => 'user']) ?>" 
                    class="order-tabs__item <?= $registrationMode === 'user' ? 'active' : '' ?>">Физ. лицо</a>
                    
                <a 
                href="<?= $registrationMode === 'company' ? '' : Url::to(['/site/registration', 'change-mode' => 'company']) ?>" 
                    class="order-tabs__item <?= $registrationMode === 'company' ? 'active' : '' ?>">Юр. лицо</a>
            </div>
        </div>

        <?php if($registrationMode === 'company') { ?>
            <!--🤟 Юр. лицо-->
            <?= $this->render('registration-company', ['signup' => $signup]) ?>
        <?php } else { ?>    
            <!--🤟 Физ. лицо-->
            <?= $this->render('registration-user', ['signup' => $signup]) ?>
        <?php }  ?>    

    </div>

</div>


<!--🔥 КОНЕЦ ШАБЛОНА-->