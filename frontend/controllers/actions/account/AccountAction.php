<?php 

namespace frontend\controllers\actions\account;

use frontend\models\Profile;
use frontend\forms\ProfileForm;
use Yii;
use yii\base\Action;
use yii\helpers\Url;

class AccountAction extends Action 
{
    public function run()
    {
        $profile = Profile::findOne(['user_id' => Yii::$app->user->identity?->id]);
        $profile->scenario = Profile::SCENARIO_INDIVIDUAL;
        $errors = [];

        if(Yii::$app->request->isPost) {
            $signup = new ProfileForm(Yii::$app->request->post('ProfileForm'));
            $signup->profile  = $profile;
            $signup->scenario = ProfileForm::SCENARIO_INDIVIDUAL_UPDATE;

            if($signup->validate())  {
                $profile->user->username = $signup->email; 
                $profile->user->email    = $signup->email; 
                $profile->user->save();

                [$surname, $name, $patronymic] = explode(' ', $signup->fio);
                $profile->surname    = !empty($surname) ? $surname : null;
                $profile->name       = !empty($name) ? $name : null;
                $profile->patronymic = !empty($patronymic) ? $patronymic : null;
                $profile->zip        = $signup->zip;
                $profile->city       = $signup->city;
                $profile->address    = $signup->address;
                $profile->phone      = $signup->phone;
                if($profile->validate() && $profile->save()) {
                    Yii::$app->session->setFlash('success', 'Профиль успешно изменен');
                } else {
                    $errors = array_merge($errors, $profile->errors);    
                    Yii::$app->session->setFlash('error', 'Есть ошибки в данных');
                }
            } else {
                $errors = array_merge($errors, $signup->errors);
                Yii::$app->session->setFlash('error', 'Есть ошибки в данных');
            }
        } else {
            $signup = ProfileForm::buildForm($profile);
        }

        return $this->controller->render('account', [
            'signup' => $signup,
            'errors' => $errors
        ]);
    }
}