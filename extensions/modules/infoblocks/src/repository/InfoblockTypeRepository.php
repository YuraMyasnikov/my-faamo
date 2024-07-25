<?php


namespace CmsModule\Infoblocks\repository;


use Cms;
use cms\common\Core;
use CmsModule\Infoblocks\models\InfoblockTypes;
use CmsModule\Infoblocks\models\InvoltaIblockAccess;
use Yii;
use yii\data\ActiveDataProvider;

class InfoblockTypeRepository
{
    public static function findByParams(string $userRole, array $params)
    {
        if ($userRole === 'admin') {
            $query = InfoblockTypes::find()->where(['type' => 1, 'folder_id' => $params['folder'] ?? null]);
        } else {
            $query = InfoblockTypes::find()->alias('it')
                ->innerJoin(InvoltaIblockAccess::tableName() . ' ia', 'ia.iblock_id=it.id')
                ->where(['it.type' => 1, 'it.folder_id' => $params['folder'], 'ia.role_name' => $userRole]);
        }
        // if (!Yii::$app->user->can('access_module_infoblocks_types')) {
        //     $query
        //         ->innerJoinWith('sites')
        //         ->andWhere(['in', 'cms_sites.id', array_keys(Core::getUserSites())]);
        // }

        return $query;
    }
}