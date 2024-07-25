<?php

namespace frontend\forms;

use CmsModule\Shop\common\models\Promocodes;
use Yii;
use yii\base\Model;

class PromocodeForm extends Model
{
    public $promocode;

    public $description = null;
    
    public function rules(): array
    {
        return [
            [['promocode'], 'required', 'message' => 'Необходимо заполнить'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'promocode' => 'Промокод',
        ];
    }

    public static function buildForm()
    {
        $form = Yii::$container->get(self::class);
        $form->promocode = Yii::$app->session->get('promocode' , null);


        $promocode = $form->promocode 
            ? Promocodes::findActivePromocodeByCode($form->promocode)
            : null;
        if($promocode) {
            $form->description = 'Скидка по промокоду ' . $promocode->discount . ($promocode->type == 0 ? '%' : '<span class="cart-rubl">₽</span>');
        }
        
        return $form;
    }

}