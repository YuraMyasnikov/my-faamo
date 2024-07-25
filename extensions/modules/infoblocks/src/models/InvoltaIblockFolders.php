<?php

namespace CmsModule\Infoblocks\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "cms_iblock_folders".
 *
 * @property integer $id
 * @property string $name
 */
class InvoltaIblockFolders extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_iblock_folders';
    }

    /**
     * @param null $parent
     * @param null $active
     * @return array
     */
    public static function getEditTreeArray($parent = null, $active = null): array
    {
        $models = self::find()->where(['parent_id' => $parent])->all();
        $result = [];

        if ($parent === null) {
            $result[] = [
                'label' => 'Главная страница',
                'url' => 'javascript:void(0)',
                'options' => [
                    'ng-click' => 'folder_id=null',
                    'ng-class' => '{active:folder_id==null}',
                    'onclick' => 'return false;',
                ]
            ];
        }

        foreach ($models as $model) {
            $item = [
                'label' => $model->name,
                'url' => 'javascript:void(0)',
                'options' => [
                    'ng-click' => 'folder_id=' . $model->id,
                    'ng-class' => '{active:folder_id==' . $model->id . '}',
                    'onclick' => 'return false;',
                ]
            ];

            if ((int)$active === (int)$model->id) {
                $item['active'] = true;
            }

            if ($subItems = self::getEditTreeArray($model->id, $active)) {
                $item['items'] = $subItems;
            }

            if ($parent === null) {
                $result[1]['items'][] = $item;
            }
            else {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * @param string $baseUrl
     * @param null $active
     * @param null $parent
     * @return array
     */
    public static function getTreeArray($baseUrl, $active = null, $parent = null): array
    {
        $models = self::find()->where(['parent_id' => $parent])->all();
        $result = [];
        if ($parent === null) {
            $result[] = [
                'label' => 'Главная страница',
                'url' => $baseUrl,
                'active' => $active === null
            ];
        }
        foreach ($models as $model) {
            $item = [
                'label' => $model->name,
                'url' => $baseUrl . '?folder=' . $model->id,
            ];

            if ((int)$active === (int)$model->id) {
                $item['active'] = true;
            }

            if ($subItems = self::getTreeArray($baseUrl, $active, $model->id)) {
                $item['items'] = $subItems;
            }

            if ($parent === null) {
                $result[1]['items'][] = $item;
            }
            else {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя папки',
        ];
    }
}
