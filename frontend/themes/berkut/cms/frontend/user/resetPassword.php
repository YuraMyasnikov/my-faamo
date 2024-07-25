<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \cms\common\forms\ResetPasswordForm $model */

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;

?>

<!--🔥 НАЧАЛО ШАБЛОНА-->

<!--📰 Авторизация-->
<div class="content">

    <!--🤟 Хлебные крошки-->
    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?= Url::to(['/']) ?>">Главная</a></li>
            <li>Новый пароль</li>
        </ul>
    </div>

    <div class="container">

        <h1>Новый пароль</h1>

        <div class="auth-block">
            <?php
                $form = ActiveForm::begin([
                    'id' => 'auth-form',
                    'options' => [
                        'class' => 'auth-block__container'
                    ]
                ]); 
            ?>
                <div class="auth-block__item">
                    <?php  
                        echo $form
                            ->field(
                                $model, 
                                'password',
                                [
                                    'options' => [
                                        'class' => 'input-group'
                                    ],
                                    'template' => '<div class="input-label required">Новый пароль:</div>{input}{hint}{error}',
                                ]
                            )
                            ->passwordInput([
                                'autofocus' => true,
                                'class' => 'input',
                            ]);
                    ?>
                    
                    <div class="input-group">
                        <ul class="form-footer form-footer--hor">
                            <li>
                                <button type="submit" class="btn">Сохранить</button>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!--🔥 КОНЕЦ ШАБЛОНА-->
