<?php

namespace CmsModule\Infoblocks\models;

use CmsModule\Infoblocks\helpers\SchemaHelper;
use Yii;
use yii\{db\ActiveQuery, db\ActiveRecord};

/**
 * This is the model class for table "cms_iblock_property".
 *
 * @property integer $id
 * @property integer $iblock
 * @property string $name
 * @property string $code
 * @property integer $type
 * @property string $multi
 * @property integer $sort
 *
 * @property InfoblockTypes $iblock0
 * @property InfoblockLinks[] $involtaIblockPropertyLinks
 * @property InfoblockValidators[] $involtaIblockPropertyValidators
 */
class InfoblockProperties extends ActiveRecord
{
    public const TYPE_STRING = 1,
        TYPE_TEXT = 2,
        TYPE_IMAGE = 3,
        TYPE_ATTACH = 4,
        TYPE_CHECKBOX = 5,
        TYPE_DATE = 6,
        TYPE_DATETIME = 7,
        TYPE_FILE = 8;

    /**
     * database types
     * @var array
     */
    public static $ATTRIBUTE_TYPES = [
        self::TYPE_STRING => 'string',
        self::TYPE_TEXT => 'text',
        self::TYPE_IMAGE => 'integer',
        self::TYPE_ATTACH => 'integer',
        self::TYPE_CHECKBOX => 'boolean',
        self::TYPE_DATE => 'date',
        self::TYPE_DATETIME => 'datetime',
        self::TYPE_FILE => 'string'
    ];
    /**
     * types for name of field
     * @var array
     */
    public static $TYPES = [
        self::TYPE_STRING => 'string',
        self::TYPE_TEXT => 'text',
        self::TYPE_IMAGE => 'image',
        self::TYPE_ATTACH => 'attach',
        self::TYPE_CHECKBOX => 'checkbox',
        self::TYPE_DATE => 'date',
        self::TYPE_DATETIME => 'datetime',
        self::TYPE_FILE => 'file'
    ];
    private $oldModel;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_iblock_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iblock', 'code'], 'required'],
            [['code'], 'notIblockFields'],
            [['iblock', 'type', 'sort'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255]
        ];
    }

    public function notIblockFields($attribute): bool
    {
        if (in_array($this->$attribute, ['id', 'iblock_id', 'code', 'name', 'active', 'created_at', 'updated_at',])) {
            $this->addError($attribute, "��� \"{$this->$attribute}\" ��� ������������");
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iblock' => 'Iblock',
            'name' => 'Name',
            'code' => 'Code',
            'type' => 'Type',
            'multi' => 'Multi',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getInfoblockType(): ActiveQuery
    {
        return $this->hasOne(InfoblockTypes::class, ['id' => 'iblock']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPropertyLink(): ActiveQuery
    {
        return $this->hasOne(InfoblockLinks::class, ['property_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPropertyValidators(): ActiveQuery
    {
        return $this->hasMany(InfoblockValidators::class, ['property_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (!$this->oldModel) {
            $this->oldModel = self::findOne($this->id) ?: new self();
        }
        return parent::beforeValidate();
    }


    public function afterSave($insert, $changetAttributes)
    {
        if (!$this->oldModel->isNewRecord && $this->oldModel->code != $this->code) {
            SchemaHelper::renameTable('__iblock_content_' . $this->infoblockType->code . '_multi_' . $this->oldModel->code,
                '__iblock_content_' . $this->infoblockType->code . '_multi_' . $this->code);
        }

        if ((int)$this->multi === 1) {

            if (!$this->oldModel->isNewRecord) {
                SchemaHelper::dropColumn('__iblock_content_' . $this->infoblockType->code, $this->oldModel->code);
            }

            SchemaHelper::createTable('__iblock_content_' . $this->infoblockType->code . '_multi_' . $this->code,
                ['id' => 'pk', 'content_id' => 'integer', 'value' => self::$ATTRIBUTE_TYPES[$this->type]]);
            SchemaHelper::addColumn('__iblock_content_' . $this->infoblockType->code . '_multi_' . $this->code, 'value',
                self::$ATTRIBUTE_TYPES[$this->type], true);
        } else {
            SchemaHelper::addColumn('__iblock_content_' . $this->infoblockType->code, $this->code,
                self::$ATTRIBUTE_TYPES[$this->type], true);
        }
        return parent::afterSave($insert, $changetAttributes);
    }

    public function afterDelete()
    {
        SchemaHelper::dropTable('__iblock_content_' . $this->infoblockType->code . '_multi_' . $this->code);
        SchemaHelper::dropColumn('__iblock_content_' . $this->infoblockType->code, $this->code);
        return parent::afterDelete();
    }
}
