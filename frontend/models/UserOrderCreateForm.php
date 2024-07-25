<?php

namespace CmsModule\Shop\frontend\forms;

use cms\common\forms\SignupForm;
use CmsModule\Shop\common\models\OrderData;
use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrderSku;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Json;
use cms\common\models\Profile;
use CmsModule\Shop\common\models\Delivery;
use CmsModule\Shop\common\services\PromocodesService;

class UserOrderCreateForm extends Model
{
    public $fio;
    public $email;
    public $phone;
    // public $zip;
    // public $city;
    // public $address;
    // public $comment;
    // public $promocode;
    // public $payment_id;
    // public $delivery_id;
    // public $inn;
    // public $rs;
    // public $kpp;
    // public $bank;
    // public $ks;
    // public $bic;
    // public $form_sob;
    // public $organization;

    public function rules()
    {
        return [
            [['email', 'fio', 'phone'], 'required'],
            [['email', 'phone'], 'string', 'max' => 255],
            // [['fio'], 'match', 'pattern' => '/^[А-Яа-я\-, \s]+$/ui', 'message' => 'ФИО должно содержать только русские буквы.'],
            // [['fio'], 'validateFio'],
        ];
        // return [
        //     [['email', 'address', 'city', 'zip', 'payment_id', 'delivery_id'], 'required'],
        //     [['comment'], 'string'],
        //     [['email', 'phone', 'city', 'promocode'], 'string', 'max' => 255],
        //     [['zip'], 'string', 'max' => 12],
        //     [['fio'], 'match', 'pattern' => '/^[А-Яа-я\-, \s]+$/ui', 'message' => 'ФИО должно содержать только русские буквы.'],
        //     [['address'], 'match', 'pattern' => '/^[0-9\-А-Яа-я.,\s]+$/ui', 'message' => 'Адрес должен содержать только русские буквы.'],
        //     [['fio'], 'validateFio'],
        // ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            // 'zip' => 'Индекс',
            // 'city' => 'Город',
            // 'address' => 'Адрес',
            // 'comment' => 'Комментарий',
            // 'promocode' => 'Промокод',
            // 'payment_id' => 'Способ оплаты',
            // 'delivery_id' => 'Способ доставки',
            // 'organization' => 'Название организации',
            // 'inn' => 'ИНН',
            // 'kpp' => 'КПП',
            // 'rs' => 'Расчетный счет',
            // 'bank' => 'Банк',
            // 'ks' => 'Корреспондентский счет',
            // 'bic' => 'БИК',
            // 'form_sob' => 'Форма собственности'
        ];
    }

    public function validateFio($attribute)
    {
        $fio = trim($this->{$attribute});
        $fio = preg_replace('/\s+/',  ' ', $fio);
        $fullName = explode(' ', $fio);
        if (count($fullName) < SignupForm::MIN_FULL_NAME_LENGTH) {
            $this->addError($attribute, 'ФИО должно содержать фамилию и имя');
        }
    }

    public static function buildForm()
    {
        $form = Yii::$container->get(self::class);
        if(Yii::$app->session->hasFlash('create-error')) {
            
            $errors = json_decode(Yii::$app->session->getFlash('create-error'), true);
            if(count($errors)) {
                $form->addErrors($errors);
            }
        }

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $profile = $user->profile;

            $form->fio = implode(' ', [$profile->surname, $profile->name, $profile->patronymic]);
            $form->email = $user->email;
            $form->phone = $profile->phone;
            // $form->zip = $profile->zip;
            // $form->city = $profile->city;
            // $form->address = $profile->address;
            // $form->inn = $profile->inn;
            // $form->rs = $profile->rs;
            // $form->kpp = $profile->kpp;
            // $form->bank = $profile->bank;
            // $form->ks = $profile->ks;
            // $form->bic = $profile->bic;
        }

        return $form;
    }

    public function save($user_id = null)
    {
        // $promocodesService = Yii::$container->get(PromocodesService::class);

        $orderModel = Yii::$container->get(Orders::class);
        $orderDataModel = Yii::$container->get(OrderData::class);

        $transaction = Yii::$app->db->beginTransaction();

        try {
        // $delivery = Delivery::findOne(['id' => $this->delivery_id]);

        $orderModel->user_id = $user_id ?? Yii::$app->user->id;
        $orderModel->status_id = 1;
        //$orderModel->payment_id = $this->payment_id;
        //$orderModel->delivery_id = $this->delivery_id;
        $orderModel->delivery_price = 0;
        // $orderModel->comment = $this->comment;
            if ($orderModel->save()) {
                $basket = Yii::$app->basket->get();
        
                $insertOrderSku = [];
        
                foreach ($basket as $basket_sku) {
                    $insertOrderSku[] = [
                        'sku_id' => $basket_sku->sku_id,
                        'order_id' => $orderModel->id,
                        'count' => $basket_sku->count,
                        //'comment' => $basket_sku->comment,
                        'price' => $basket_sku->sku->price,
                    ];
                }

                Yii::$app->db->createCommand()->batchInsert(OrderSku::tableName(), [
                    'sku_id',
                    'order_id',
                    'count',
                    //'comment',
                    'price'
                ], $insertOrderSku)->execute();
        
                $orderDataModel->order_id = $orderModel->id;
                $orderDataModel->fio = $this->fio;
                $orderDataModel->email = $this->email;
                $orderDataModel->phone = $this->phone;
                // $orderDataModel->zip = $this->zip;
                // $orderDataModel->city = $this->city;
                // $orderDataModel->address = $this->address;
                $orderDataModel->type = Profile::INDIVIDUAL_TYPE;
                $orderDataModel->save();
        
                if ($orderModel->save()) {
                    Orders::recalculationPrice($orderModel->id);

                    $transaction->commit();

                    // $promocodesService->applyPromocode($orderModel->id, $this->promocode);
                    // $promocodesService->applyDiscountToOrder($orderModel->id);

                    try {
                        $this->sendEmailCompleteOrder($orderModel->id);
                    } catch (Exception $e) {
                        Yii::error($e->getMessage());
                    }

                    return $orderModel->id;
                }
            }
            return false;
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }

    public function sendEmailCompleteOrder($orderId)
    {
        $sender = Yii::$app->sender->email;
        $sender->viewPath = Yii::getAlias('@common/mail/');

        $sender->send([
            'from' => [Yii::$app->params['adminEmail'] => Yii::$app->params['senderName']],
            'to' => Yii::$app->params['emailTo'],
            'subject' => 'Оформлен заказ на сайте ceramic-store',
            'view' => 'user_order_complete',
            'trans' => [
                'orderId' => $orderId,
            ]
        ]);

        $sender->send([
            'from' => [Yii::$app->params['adminEmail'] => Yii::$app->params['senderName']],
            'to' => $this->email,
            'subject' => 'Оформлен заказ на сайте ceramic-store',
            'view' => 'user_order_complete',
            'trans' => [
                'orderId' => $orderId,
            ]
        ]);
    }
}