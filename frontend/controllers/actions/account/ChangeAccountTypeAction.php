<?php 

namespace frontend\controllers\actions\account;

use cms\common\models\Profile;
use Yii;
use yii\base\Action;
use yii\helpers\Url;

class ChangeAccountTypeAction extends Action 
{
    public function run()
    {
        $user = Yii::$app->user->identity;
        $profile = $user->profile;
        if($profile->type === Profile::ORGANIZATION_TYPE) {
            $this->changeStatus($user->id, Profile::INDIVIDUAL_TYPE);
        } else {
            $this->changeStatus($user->id, Profile::ORGANIZATION_TYPE);
        }
        
        Yii::$app->response->redirect(Url::to(['/account']));
    }  

    private function changeStatus(int $userId, int $status): void 
    {
        Yii::$app->db->createCommand()->update('profile', ['type' => $status], ['user_id' => $userId])->execute();
    }
}