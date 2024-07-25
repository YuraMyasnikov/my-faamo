<?php

namespace CmsModule\Infoblocks\repository;

use Cms;
use cms\common\Core;
use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\db\ActiveQuery;

class InfoblockContentRepository
{
    public static function findByParams(Infoblock $filterModel, Infoblock $class, array $filter)
    {
        if (!isset($filter['sort'])) {
            $filterModel->sort = null;
        }
        $query = $class::find();

        if (!Yii::$app->user->can('access_module_infoblocks_types')) {
            $query
                ->innerJoinWith('infoblockType.sites')
                ->where(['type' => 1])
                ->andWhere(['in', 'cms_sites.id', array_keys(Core::getUserSites())])
                ->andWhere(['in', $class::tableName() . '.site_id', array_keys(Core::getUserSites())]);
        }

        foreach ($filter as $field => $value) {
            if ($field === 'sort') {
                continue;
            }
            if (!($filterModel->hasAttribute($field) && $value)) {

                if ($value) {
                    if ($field === 'from') {
                        $query->andWhere(['>=', 'created_at', $value]);
                    }
                    if ($field === 'to') {
                        $query->andWhere(['<=', 'created_at', $value]);
                    }
                }

                continue;
            }
            if ($field === 'active' && (int)$value === 2) {
                $filterModel->{$field} = 2;
                $query->andWhere([$field => 0]);
            } elseif ($field === 'name') {
                $filterModel->{$field} = $value;
                $query->andWhere(['LIKE', $field, $value]);
            } else {
                $filterModel->$field = $value;
                $query->andWhere([$field => $value]);
            }
        }
        return $query;
    }
}