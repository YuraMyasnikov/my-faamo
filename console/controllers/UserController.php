<?php

namespace console\controllers;

use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\console\Controller;
use cms\common\models\Profile;

class UserController extends Controller
{
    /**
     * Метод создает минимально-заполненного юзера
     */
    public function actionCreateUser(string $email, string $password, string $phone = null)
    {
        $user = new \cms\common\models\User();
        $user->username = $email;
        $user->email = $email;
        $user->status = \cms\common\models\User::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        if($user->validate()) {
            $user->save();
        } else {
            echo $user->getFirstErrors();
        }

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->name = 'Иванов';
        $profile->surname = 'Иван';
        $profile->patronymic = 'Иванович';
        $profile->phone = $phone;
        $profile->zip = '000000';
        $profile->city = 'Москва';
        $profile->address = '';
        $profile->organization = '';
        $profile->inn = '';
        $profile->kpp = '';
        $profile->rs = '';
        $profile->bank = '';
        $profile->ks = '';
        $profile->bic = '';
        $profile->birthday = '';
        $profile->type = 1;
        $profile->setScenario('individual');
        if($profile->validate()) {
            $profile->save();
        } else {
            $user->delete();
        }
    }

    /**
     * Метод создает отзыв
     */
    public function actionCreateReview(string $name, string $email, string $orderId, string $text, string $score)
    {
        /**
         * @var Infoblock $infoblock 
         */
        $infoblock = Infoblock::byCode('reviews');

        $model = $infoblock::create();
        $model->name = 'Новый отзыв от ' . $name;
        $model->code = Yii::$app->security->generateRandomString(16);

        $model->fio = $name;
        $model->email = $email;
        $model->order_number = $orderId;
        $model->review_text = $text;
        $model->grade = $score;
        $model->active = true;
        $model->save();
    }

    /**
     * Метод выводит список отзывав
     */
    public function actionShowReviews(int $count = 5)
    {
        /**
         * @var Infoblock $infoblock
         */
        $infoblock = Infoblock::byCode('reviews');
        $items = $infoblock::find()
            ->where(['active' => true])
            ->andWhere(['<', 'grade', 6])
            ->limit($count)
            ->orderBy(['created_at' => SORT_DESC])
            ->asArray()
            ->all();

        foreach($items as $infoblock) {
            /**
             * @var Infoblock $infoblock
             */
            $this->stdout(sprintf('- %s; %s; %s', $infoblock['fio'], $infoblock['email'], $infoblock['review_text']) . PHP_EOL);
        }       
    }
}