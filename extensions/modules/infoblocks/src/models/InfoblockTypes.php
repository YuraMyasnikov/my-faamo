<?php

namespace CmsModule\Infoblocks\models;

use CmsModule\Infoblocks\helpers\SchemaHelper;
use cms\common\models\InvoltaSites;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Migration;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "cms_infoblock_types".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $type
 * @property integer $folder_id
 *
 * @property InfoblockProperties[] $properties
 * @property $migrationPath string
 */
class InfoblockTypes extends ActiveRecord
{
    private $oldModel;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_infoblock_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['type'], 'integer'],
            [['folder_id'], 'safe'],
            [['code'], 'unique'],
            [['code'], 'notTableExists'],
            [['code', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @param $attribute
     * @return bool
     * @throws \yii\db\Exception
     */
    public function notTableExists($attribute): bool
    {
        if (SchemaHelper::tableExists('__iblock_content_' . $this->code) && $this->oldModel->code != $this->code) {
            $this->addError($attribute, "Код \"{$this->$attribute}\" уже используется");
            return false;
        }
        return true;
    }


    public function getTable(): string
    {
        return '__iblock_content_' . $this->code;
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Название',
            'type' => 'Тип инфоблока',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProperties(): ActiveQuery
    {
        return $this->hasMany(InfoblockProperties::class, ['iblock' => 'id']);
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (!$this->oldModel) {
            $this->oldModel = self::findOne($this->id);
        }
        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->oldModel !== null && !$this->oldModel->isNewRecord && $this->oldModel->code != $this->code) {
            SchemaHelper::renameTable('__iblock_content_' . $this->oldModel->code, '__iblock_content_' . $this->code);
        }

        $migration = new Migration();

        $tableName = '__iblock_content_' . $this->code;

        if (!SchemaHelper::tableExists($tableName)) {
            $migration->createTable($tableName, [
                'id' => $migration->primaryKey()->unsigned(),
                'iblock_id' => $migration->integer()->unsigned()->notNull(),
                'site_id' => $migration->integer(3)->unsigned(),
                'code' => $migration->string(),
                'name' => $migration->string(),
                'active' => $migration->boolean()->notNull()->defaultValue(true),
                'sort' => $migration->integer()->unsigned()->notNull()->defaultValue(500),
                'created_at' => $migration->dateTime(),
                'updated_at' => $migration->timestamp()->defaultExpression('NOW()'),
            ]);
        }

        if (($this->oldModel !== null) && $this->oldModel->isNewRecord === null) {
            $migration->addForeignKey('FK_' . Yii::$app->security->generateRandomString(), '__iblock_content_' . $this->code, 'iblock_id', 'cms_infoblock_types', 'id', 'CASCADE', 'CASCADE');
            $migration->addForeignKey('FK_' . Yii::$app->security->generateRandomString(), '__iblock_content_' . $this->code, 'site_id', 'cms_sites', 'id', 'CASCADE', 'CASCADE');
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Связь InvoltaIblockAccess
     *
     * @return ActiveQuery
     */
    public function getIblockAccess(): ActiveQuery
    {
        return $this->hasMany(InvoltaIblockAccess::class, ['iblock_id' => 'id']);
    }

    /**
     * Действия перед удалением
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            /** @var InvoltaIblockAccess $models */
            if (($models = $this->getIblockAccess()->all()) && $models !== null) {
                foreach ($models as $model) {
                    Yii::info('Удаляем запись из ' . InvoltaIblockAccess::tableName() . ' ' . $model->iblock_id . ' => ' . $model->role_name, 'infoblocks');
                    $model->delete();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Действия после удаления
     */
    public function afterDelete()
    {
        $tables = SchemaHelper::searchTables('__iblock_content_' . $this->code . '%');
        foreach ($tables as $table) {
            SchemaHelper::dropTable($table);
        }
        return parent::afterDelete();
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSites(): ActiveQuery
    {
        return $this->hasMany(InvoltaSites::class, ['id' => 'site_id'])
            ->viaTable(InvoltaIblockSites::tableName(), ['iblock_id' => 'id']);
    }

    /**
     * Получаем путь миграций
     *
     * @return bool|mixed|string
     * @throws Exception
     */
    public function getMigrationPath()
    {
        $migrationPath = '@frontend/migrations';
        $controllerMap = Yii::$app->controllerMap;

        if (isset($controllerMap['migrate']) && !empty($controllerMap['migrate']) && is_array($controllerMap['migrate']) && array_key_exists('migrationPath', $controllerMap['migrate'])) {
            $migrationPath = $controllerMap['migrate']['migrationPath'];
        }

        $migrationPath = Yii::getAlias($migrationPath);
        $this->createDirectory($migrationPath);

        return $migrationPath;
    }

    /**
     * Создание директории
     *
     * @param $migrationPath string
     * @return bool
     * @throws Exception
     */
    private function createDirectory($migrationPath): bool
    {
        return FileHelper::createDirectory($migrationPath);
    }
}
