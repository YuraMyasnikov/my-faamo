<?php

namespace CmsModule\Infoblocks\helpers;

use Yii;

/**
 * Class SchemaHelper
 * @package cms\common\components\helpers
 */
class SchemaHelper
{
    /**
     * @param $table
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getColumns($table)
    {
        if (self::tableExists($table)) {
            switch (Yii::$app->db->driverName) {
                case "pgsql":
                    $rows = Yii::$app->db->createCommand('SELECT column_name as Field, is_nullable as Null, column_default as Default FROM information_schema.columns WHERE table_name = :1', [':1' => $table])->queryAll();
                    break;
                case "mysql":
                    $rows = Yii::$app->db->createCommand("show columns from `{$table}`")->queryAll();
                    break;
                default:
                    $rows = [];
            }

            $columns = [];

            foreach ($rows as $row) {
                $columns[$row['Field'] ?? $row['field']] = [
                    'type' => $row['Type'] ?? $row['type'],
                    'isNull' => $row['Null'] ?? $row['null'],
                    'key' => $row['Key'] ?? $row['key'] ?? null,
                    'default' => $row['Default'] ?? $row['default'] ?? null,
                    'extra' => $row['Extra'] ?? $row['extra'] ?? null,
                ];
            }
            return $columns;
        }
        return [];
    }

    /**
     * @param $name
     * @param $table
     * @param $column
     * @param $refTable
     * @param $refColumns
     * @param string $onDelete
     * @param string $onUpdate
     * @throws \yii\db\Exception
     */
    public static function addForeignKey($name, $table, $column, $refTable, $refColumns, $onDelete = 'CASCADE', $onUpdate = 'CASCADE')
    {
        Yii::$app->db->createCommand()->addForeignKey($name, $table, $column, $refTable, $refColumns, $onDelete, $onUpdate)->execute();
    }

    /**
     * @param $name
     * @param $table
     * @throws \yii\db\Exception
     */
    public static function dropForeignKey($name, $table)
    {
        Yii::$app->db->createCommand()->dropForeignKey($name, $table)->execute();
    }

    /**
     * @param $table
     * @param $column
     * @param $type
     * @param bool $replace
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function addColumn($table, $column, $type, $replace = false)
    {
        if (self::tableExists($table)) {
            $columns = self::getColumns($table);

            if (isset($columns[$column]) && !$replace) {
                return false;
            }

            if (!isset($columns[$column])) {
                Yii::$app->db->createCommand()->addColumn($table, $column, $type)->execute();
            } else {
                Yii::$app->db->createCommand()->alterColumn($table, $column, $type)->execute();
            }

            return true;
        }

        return false;
    }

    /**
     * @param $table
     * @param $column
     * @throws \yii\db\Exception
     */
    public static function dropColumn($table, $column)
    {
        $columns = self::getColumns($table);
        if (isset($columns[$column])) {
            Yii::$app->db->createCommand()->dropColumn($table, $column)->execute();
        }
    }

    /**
     * @param $table
     * @throws \yii\db\Exception
     */
    public static function dropTable($table)
    {
        if (self::tableExists($table)) {
            Yii::$app->db->createCommand()->dropTable($table)->execute();
        }
    }

    /**
     * @param $table
     * @param $newName
     * @throws \yii\db\Exception
     */
    public static function renameTable($table, $newName)
    {
        if (self::tableExists($table)) {
            Yii::$app->db->createCommand()->renameTable($table, $newName)->execute();
        }
    }

    /**
     * @param $table
     * @param $columns
     * @throws \yii\db\Exception
     */
    public static function createTable($table, $columns)
    {
        if (!self::tableExists($table)) {
            Yii::$app->db->createCommand()->createTable($table, $columns)->execute();
        }
    }

    /**
     * @param $table
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function tableExists($table)
    {
        switch (Yii::$app->db->driverName) {
            case "pgsql":
                $data = Yii::$app->db->createCommand('SELECT table_name FROM information_schema.tables WHERE table_schema = :1 and table_name like :2', [':1' => 'public', ':2' => $table])->queryAll();
                $result = count($data);
                break;
            case "mysql":
                $data = Yii::$app->db->createCommand('SHOW TABLES LIKE :1', [':1' => $table])->queryAll();
                $result = count($data);
                break;
            default:
                $result = 0;
        }

        return $result > 0;
    }

    /**
     * @param $condition
     * @return array
     * @throws \yii\db\Exception
     */
    public static function searchTables($condition)
    {
        $rows = Yii::$app->db->createCommand("SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES where TABLE_NAME like '{$condition}';")->queryAll();
        $tables = [];
        foreach ($rows as $row) {
            $tables[] = $row['TABLE_NAME'];
        }
        return $tables;
    }

    /**
     * @param $sql
     * @param array $params
     * @return int
     * @throws \yii\db\Exception
     */
    public static function execute($sql, $params = [])
    {
        return Yii::$app->db->createCommand($sql, $params)->execute();
    }
}
