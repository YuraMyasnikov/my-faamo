<?php 

namespace frontend\controllers\actions\site;

use frontend\models\Profile;
use cms\common\models\User;
use frontend\events\UserRegistred;
use frontend\forms\ProfileForm;
use frontend\handlers\UserRegistredHandler;
use Yii;
use yii\base\Action;
use yii\helpers\Url;

class  RegistrationAction extends Action
{
    const USER_REGISTRED = 'user-registreg';

    public function init() 
    {
        $this->on(static::USER_REGISTRED, [UserRegistredHandler::class, 'run']);

        parent::init();
    }

    public function run()
    {
        $signup = new ProfileForm();
        $errors = [];

        if($this->controller->request->isPost) {
            $signup->scenario = ProfileForm::SCENARIO_INDIVIDUAL_CREATE;
            $signup->load($this->controller->request->post());

            $isValid = $signup->validate();

            if(!$isValid)  {
                Yii::$app->session->setFlash('error', 'Поизошла ошибка');
                
                $errors = array_merge($errors, $signup->errors);

            }
            if($isValid) {
                $user = $this->createUser($signup);
            }
            if($user ?? null) {
                Yii::$app->session->setFlash('success', 'Ваш аккаунт создан. Инструкция отправлена на ваш email');

                $this->trigger(static::USER_REGISTRED, new UserRegistred(['user_id' => $user->id]));

                Yii::$app->response->redirect(Url::to(['/site/login']));
                return;
            }
        }

        return $this->controller->render('registration', [
            'signup' => $signup,
            'errors' => $errors,
        ]);
    }

    protected function name(ProfileForm $data): array 
    {
        $nameInfo = explode(' ', $data->fio);
        return [
            $nameInfo[1] ?? null, 
            $nameInfo[0] ?? null, 
            $nameInfo[2] ?? null, 
        ];
    }

    protected function createUser(ProfileForm $data): ?User
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /**
             * Create user
             */
            $user = new User();    
            $user->status        = User::STATUS_INACTIVE;
            $user->auth_key      = Yii::$app->getSecurity()->generateRandomString();
            $user->username      = $data->email;
            $user->email         = $data->email;
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($data->password);
            if(!$user->validate() || !$user->save()) {
                throw new \yii\base\Exception('User not saved');
            }
            
            /**
             * Create profile 
             */
            [$name, $surname, $patronymic] = $this->name($data);
            $profile = new Profile();
            $profile->scenario   = Profile::SCENARIO_INDIVIDUAL;
            $profile->user_id    = $user->id;
            $profile->type       = Profile::INDIVIDUAL_TYPE;
            $profile->name       = $name;
            $profile->surname    = $surname;
            $profile->patronymic = $patronymic;
            $profile->phone      = $data->phone;
            if(!$profile->validate() || !$profile->save()) {
                throw new \yii\base\Exception('Profile not saved');
            }

            /**
             * Set role
             */
            Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $user->id);

            $transaction->commit();
        } catch(\Exception $e) {
            dd($e);
            $user = null;
            $transaction->rollBack();
        }

        return $user;
    }    
}