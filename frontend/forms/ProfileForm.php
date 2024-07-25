<?php

namespace frontend\forms;

use frontend\models\Profile;
use cms\common\models\User;
use cms\common\validators\PhoneValidator;
use Yii;
use yii\base\Model;

class ProfileForm extends Model
{
    const SCENARIO_INDIVIDUAL_CREATE = 'individual.create';
    const SCENARIO_INDIVIDUAL_UPDATE = 'individual.update';

    public Profile|null $profile = null;
    public $fio;
    public $phone;
    public $email;
    public $zip;
    public $city;
    public $address;
    public $password;
    public $verifyPassword;

    public function scenarios()
    {
        return [
            self::SCENARIO_INDIVIDUAL_CREATE   => ['fio', 'email', 'phone', 'password', 'verifyPassword'],
            self::SCENARIO_INDIVIDUAL_UPDATE   => ['fio', 'email', 'phone', 'zip', 'city', 'address'],
        ];
    }

    public function rules()
    {
        return [
            /**
             * fio
             */
            ['fio', 'required', 'message' => 'Должно быть заполнено', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],
            ['fio', 'match', 'pattern' => '/^[А-Яа-я\-, \s]+$/ui', 'message' => 'ФИО должно содержать только русские буквы.'],
            ['fio', 'validateFio'],


            /**
             * phone
             */
            ['phone', 'required', 'message' => 'Должно быть заполнено', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],
            ['phone', PhoneValidator::class, 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],
            ['phone', 'checkPhone', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],


            /**
             * email
             */
            ['email', 'required', 'message' => 'Должно быть заполнено', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],
            ['email', 'email', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],
            ['email', 'checkUserName', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE, self::SCENARIO_INDIVIDUAL_UPDATE]],

            /**
             * password
             */
            [['password', 'verifyPassword'], 'required', 'message' => 'Должно быть заполнено', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE]],
            [['password', 'verifyPassword'], 'string', 'min' => Yii::$app->params['user.passwordMinLength'], 'message' => 'В пароле должно быть'. Yii::$app->params['user.passwordMinLength'] . ' и более символов.', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE]],
            ['verifyPassword', 'compare', 'compareAttribute' => 'password', 'on' => [self::SCENARIO_INDIVIDUAL_CREATE], 'message' => 'Введенные пароли не совпадают.'],

            /**
             * address
             */
            [['address'], 'match', 'pattern' => '/^[0-9\-А-Яа-я.,\s]+$/ui', 'message' => 'Адрес должен содержать только русские буквы.', 'skipOnEmpty' => true,  'on' => [self::SCENARIO_INDIVIDUAL_UPDATE]],

            /**
             * zip
             */
            [['zip'], 'match', 'pattern' => '/^[0-9]{6}/i', 'message' => 'Индекс должен содержать 6 цифр.', 'skipOnEmpty' => true,  'on' => [self::SCENARIO_INDIVIDUAL_UPDATE]],

            /**
             * city
             */
            [['city'], 'match', 'pattern' => '/^[0-9\-А-Яа-я ]+$/ui', 'message' => 'Город должен содержать только русские буквы.', 'skipOnEmpty' => true, 'on' => [self::SCENARIO_INDIVIDUAL_UPDATE]],
        ];
    }

    public function attributeLabels()
    {
        return [    
            'fio'      => 'ФИО',
            'phone'    => 'Телефон',
            'email'    => 'E-mail',
            'zip'      => 'Индекс',
            'city'     => 'Город',
            'address'  => 'Адрес',
            'password' => 'Пароль',
            'verifyPassword' => 'Подтверждение пароля',
        ];
    }

    public function validateFio($attribute)
    {
        $fio = trim($this->{$attribute});
        $fio = preg_replace('/\s+/',  ' ', $fio);
        $fullName = explode(' ', $fio);
        if (count($fullName) < 2) {
            $this->addError($attribute, 'ФИО должно содержать фамилию и имя');
        }
    }

    public function checkUserName()
    {
        $model = User::find()->where(['username' => $this->email])->one();
        if ($model) {
            if($this->profile) {
                if($this->profile->user_id === $model->id) {
                    return true;
                }
            }
            $this->addError('email', 'Пользователь с таким email уже зарегистрирован.');
            return false;
        }
        return true;
    }

    public function checkPhone()
    {
        $model = Profile::find()->where(['phone' => $this->phone])->one();
        if ($model) {
            if($this->profile && $this->profile->phone === $model->phone) {
                return true;
            }
            $this->addError('phone', 'Пользователь с таким телефоном уже зарегистрирован.');
            return false;
        }
        return true;
    }

    public static function buildForm(Profile $profile): ProfileForm
    {
        $profileForm          = Yii::$container->get(ProfileForm::class);
        $profileForm->fio     = implode(' ', [$profile->surname, $profile->name, $profile->patronymic]);
        $profileForm->phone   = $profile->phone;
        $profileForm->email   = $profile->user->email;
        $profileForm->zip     = $profile->zip;
        $profileForm->city    = $profile->city;
        $profileForm->address = $profile->address; 

        return $profileForm;
    }

    public function save()
    {
        $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);

        if (!$profile) {
            return false;
        }

        $profileForm = $this;
        [$surname, $name, $patronymic] = explode(' ', $profileForm->fio);
        $profile->surname    = $surname ?? '';
        $profile->name       = $name ?? '';
        $profile->patronymic = $patronymic ?? '';
        $profile->zip        = $profileForm->zip;
        $profile->city       = $profileForm->city;
        $profile->address    = $profileForm->address;

        return $profile->save(false);
    }
}