<?php 

namespace frontend\controllers\actions\account;

use frontend\forms\ChangePasswordForm;
use Yii;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\Session;


class ChangePasswordAction extends Action 
{
    public function run()
    {
        $changePasswordForm = Yii::$container->get(ChangePasswordForm::class);
        $session = Yii::$container->get(Session::class);
        $errors = [];

        if (Yii::$app->request->post()) {

            if ($changePasswordForm->load(Yii::$app->request->post()) && $changePasswordForm->validate() && $changePasswordForm->save()) {
                $session->setFlash('success', 'Пароль успешно изменен');
                $this->controller->redirect(Url::to(['/login'])); 
            } else {                
                $changePasswordForm = $changePasswordForm::instance(true);
                $session->setFlash('error', 'Произошла ошибка');
            }
        }

        return $this->controller->render('change-password', ['changePasswordForm' => $changePasswordForm, 'errors' => $errors]);
    }  
}