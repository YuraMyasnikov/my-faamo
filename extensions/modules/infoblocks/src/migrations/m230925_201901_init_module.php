<?php

use cms\common\models\CmsModules;
use yii\db\Migration;

/**
 * Class m230925_201901_init_module
 */
class m230925_201901_init_module extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $admin = Yii::$app->authManager->getRole('admin');
        
        $accessSettingsModule = $auth->createPermission('accessInfoblocksModule');
        $accessSettingsModule->description = "Доступ к модулю 'Инфоблоки'";        
        $auth->add($accessSettingsModule);
        $auth->addChild($admin, $accessSettingsModule);

        $module = Yii::$container->get(CmsModules::class);
        $module->module = "infoblocks";
        $module->title = "Инфоблоки";
        $module->save();

        $child_module = Yii::$container->get(CmsModules::class);
        $child_module->module = "content";
        $child_module->title = "Контент";
        $child_module->parent_id = $module->id;
        $child_module->save();

        $child_module = Yii::$container->get(CmsModules::class);
        $child_module->module = "types";
        $child_module->title = "Типы инфоблоков";
        $child_module->parent_id = $module->id;
        $child_module->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        CmsModules::deleteAll(['module' => 'infoblocks']);
        $premission = Yii::$app->authManager->getPermission('accessInfoblocksModule');
        Yii::$app->authManager->remove($premission);
    }
}
