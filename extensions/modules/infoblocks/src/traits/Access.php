<?php

namespace CmsModule\Infoblocks\traits;

use Cms;
use Yii;
use yii\base\Exception;

/**
 * Trait Access
 * @package CmsModule\Infoblocks\traits
 */
trait Access
{
    /**
     * Добавление RBAC к модулю
     * @throws Exception
     */
    public function setAccess(): void
    {
        $auth = Yii::$app->authManager;

        $permissions = [
            ['code'=>'access_module_infoblocks', 'name'=>'Доступ к модулю Инфоблоки', 'roles'=>['admin']],
            ['code'=>'access_module_infoblocks_types', 'name'=>'Доступ к модулю Инфоблоки - Типы инфоблоков', 'roles'=>['admin']],
            ['code'=>'access_module_infoblocks_content', 'name'=>'Доступ к модулю Инфоблоки - Контент', 'roles'=>['admin']],
        ];

        foreach ($permissions as $permission) {
            $data = $auth->createPermission($permission['code']);
            $data->description = $permission['name'];
            $auth->add($data);

            foreach ($permission['roles'] as $role) {
                $userRole = $auth->getRole($role);
                $auth->addChild($userRole, $data);
            }
        }
    }

    /**
     * Удаление RBAC у модуля
     */
    public function removeAccess(): void
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        foreach ($roles as $role) {
            if (($permission = $auth->getPermission('access_module_infoblocks')) && $permission !== null) {
                $auth->removeChild($role, $permission);
                $auth->remove($permission);
            }
            if (($permission = $auth->getPermission('access_module_infoblocks_types')) && $permission !== null) {
                $auth->removeChild($role, $permission);
                $auth->remove($permission);
            }
            if (($permission = $auth->getPermission('access_module_infoblocks_content')) && $permission !== null) {
                $auth->removeChild($role, $permission);
                $auth->remove($permission);
            }
        }
    }
}