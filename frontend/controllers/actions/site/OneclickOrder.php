<?php 

namespace frontend\controllers\actions\site;

use cms\common\models\Profile;
use cms\common\models\User;
use CmsModule\Shop\common\models\Sku;
use frontend\models\OneClickOrderForm;
use Yii;
use yii\base\Action;


class OneclickOrder extends Action 
{
    public function run()
    {
        $oneClickOrderForm = Yii::$container->get(OneClickOrderForm::class);
        if(Yii::$app->request->isPost && $oneClickOrderForm->load(Yii::$app->request->post())) {
            if(!$oneClickOrderForm->validate()) {
                Yii::$app->session->setFlash('error', 'Ошибка. Недопустимые данные');
                $this->controller->redirect(Yii::$app->request->referrer);
                return;
            }

            $user = $this->findUserByEmail($oneClickOrderForm->email, $oneClickOrderForm->phone);
            try {   
                $this->createOrder(
                    $user, 
                    $oneClickOrderForm->name, 
                    $oneClickOrderForm->phone, 
                    $oneClickOrderForm->email,
                    (int) Yii::$app->request->post('sku_id', 0),
                    (int) Yii::$app->request->post('sku_count', 0),
                    $oneClickOrderForm->message
                );
            } catch(\Exception $e) {
                Yii::$app->session->setFlash('error', 'Ошибка.');
            }

            Yii::$app->session->setFlash('success', 'Ваш заказ принят');
        }
        $this->controller->redirect(Yii::$app->request->referrer);
    }

    private function findUserByEmail(string $email, string $phone): User|null
    {
        $phone = preg_replace('/[^0-9]/', "", $phone);
        $user = User::find()
            ->where('username = :email OR email = :email', [':email' => $email])
            ->one();
        if(!$user) {
            $profile = Profile::find()->where('phone = :phone', [':phone' => $phone])->one();
            if($profile) {
                $user = $profile->user;
            }
        }

        return $user;
    }

    private function createOrder(User|null $user, string $name, string $phone, string $email, int $skuId, int $count, string $message)
    {
        $sku = Sku::findOne($skuId);

        Yii::$app->db->transaction(function() use($user, $name, $phone, $email, $sku, $count, $message) {
            if(!$user instanceof User) {
                $authKey = Yii::$app->getSecurity()->generateRandomString();
                Yii::$app->db->createCommand()->insert('user', [
                    'username' => $email,
                    'auth_key' => $authKey,
                    'password_hash' => Yii::$app->getSecurity()->generatePasswordHash($authKey),
                    'email' => $email,
                    'status' => 10,
                    'created_at' => (new \DateTime())->getTimestamp(),
                    'updated_at' => (new \DateTime())->getTimestamp(),
                ])->execute();    
                $userId = Yii::$app->db->lastInsertID;

                $parts = explode(' ', $name);
                $surname = $name = $patronymic = '';
                if(count($parts) === 1) {
                    $name = $parts[0];
                } else if(count($parts) === 2) {
                    $surname = $parts[0];
                    $name = $parts[1];
                } else if(count($parts) === 3) {
                    $surname = $parts[0];
                    $name = $parts[1];
                    $patronymic = $parts[1];
                }
                Yii::$app->db->createCommand()->insert('profile', [
                    'user_id' => $userId,
                    'surname' => $surname,
                    'name' => $name,
                    'patronymic' => $patronymic,
                    'phone' => preg_replace('/[^0-9]/', "", $phone),
                    'type' => 1
                ])->execute();

                $user = User::findOne($userId);
            }    

            Yii::$app->db->createCommand()->insert('module_shop_orders', [
                'user_id' => $user->id,
                'comment' => $message,
                'status_id' => 1,
                'state_id' => 1,
                'delivery_price' => 0,
                'discount' => 0,
                'total_discount_price' => round($sku->price * $count),
                'total_price' => round($sku->price * $count),
            ])->execute();
            $orderId = Yii::$app->db->lastInsertID;
            Yii::$app->db->createCommand()->insert('module_shop_order_data', [
                'order_id' => $orderId,
                'fio' => $name,
                'email' => $email,
                'phone' => preg_replace('/[^0-9]/', "", $phone),
                'type' => 1,
            ])->execute();
            Yii::$app->db->createCommand()->batchInsert('module_shop_order_sku', [
                'sku_id', 'order_id', 'count', 'price'
            ], [
                [$sku->id, $orderId, $count, $sku->price]
            ])->execute();
            Yii::$app->db
                ->createCommand('update module_shop_sku set remnants = remnants - '.$count.' where id = '.$sku->id.';')
                ->execute();
        });
        
    }

}