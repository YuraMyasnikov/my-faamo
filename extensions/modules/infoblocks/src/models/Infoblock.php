<?php

namespace CmsModule\Infoblocks\models;

use Yii;
use cms\common\models\User;
use DateTime;
use Exception;
use yii\{base\InvalidConfigException, db\ActiveQuery, db\ActiveRecord, helpers\ArrayHelper, web\HttpException};
use yii\base\Model;

/**
 * Class Infoblock
 * @package CmsModule\Infoblocks\models
 */
class Infoblock extends ActiveRecord
{
    const TYPE_PROPERTY_IMAGE = 3;

    public $multiDelete = [];
    public $multiParams = [];

    /**
     * @param $code
     * @param $propCode
     * @return null
     * @throws HttpException
     */
    public function multiProperty($code, $propCode)
    {
        $property = InfoblockProperties::find()
            ->alias('property')
            ->innerJoinWith('infoblockType AS type')
            ->joinWith('propertyValidators AS validator')
            ->where(['type.code' => $code])
            ->andWhere(['property.code' => $propCode])
            ->cache()
            ->one();

        if (!$property) {
            return null;
        }

        $clname = 'Infoblock' . ucfirst($code) . 'Multi' . ucfirst($propCode);
        if (!class_exists("CmsModule\\Infoblocks\\models\\$clname")) {
            $class = <<<PHP
            namespace CmsModule\Infoblocks\models;
            //use cms\common\components\core\Model;
            use yii\db\ActiveRecord;
            use cms\common\models\Images;
            use CmsModule\Infoblocks\models\Infoblock;
            use CmsModule\Infoblocks\models\InfoblockProperties;
            use yii\helpers\ArrayHelper;
            use Yii;
            class $clname extends ActiveRecord {
                protected \$propCode = '{$propCode}';
                public static function infoblockCode()
                {
                    return '{$code}';
                }
                public static function tableName()
                {
                    return '__iblock_content_{$code}_multi_{$propCode}';
                }

                public function getContent() {
                    \$class=Infoblock::byCode('{$code}');
                    return \$this->hasOne(\$class::className(), ['id'=>'content_id']);
                }

                public function getProperty() {
                    return \$this->hasOne(InfoblockProperties::className(), ['code'=>'propCode']);
                }

                public function rules(){
                return [
                    [['content_id'], 'required'],
PHP;

            if ($property->propertyValidators) {
                foreach ($property->propertyValidators as $validator) {
                    $class .= <<<PHP
                        [['value'], '{$validator->name}'],
PHP;
                }
            }
            $class .= <<<PHP
                    ];
                }
PHP;
            if ((int)$property->type === 3) {
                $class .= <<<PHP
                public function beforeSave(\$insert){
                    if (strpos(\$this->value, 'base64')!==false) {
                        \$this->value = \cms\common\models\Images::saveBase64ToDir(
                            sprintf('content/%s/%s/', static::infoblockCode(), \$this->content_id), 
                            \$this->value, 
                            false
                        );
                    }
                    return parent::beforeSave(\$insert);
                }
PHP;
            }
            $class .= <<<PHP
            }
PHP;
            eval($class);
        }
        $clname = "CmsModule\\Infoblocks\\models\\$clname";
        /**
         * @var ActiveRecord $propertyObject
         */
        $propertyObject = new $clname();
        $propertyObject->populateRelation('property', $property);
        return $propertyObject;
    }

    /**
     * @param string $code
     * @return mixed
     * @throws HttpException
     * @throws InvalidConfigException
     */
    public static function byCode($code)
    {
        $model = InfoblockTypes::find()
            ->alias('type')
            ->joinWith('properties')
            ->joinWith('properties.propertyValidators')
            ->where(['type.code' => $code])
            ->one();
        if (!$model) {
            throw new HttpException(404, 'Инфоблок ' . $code . ' не найден');
        }


        if (property_exists(Yii::$app, 'request') && strpos(Yii::$app->request->getUrl(), 'admin')) {
            //$userRole = User::getRole(Yii::$app->user->id);
            $userRole = Yii::$app->user->role;
            if (($userRole !== 'admin') && !InvoltaIblockAccess::find()->where([
                                                                                   'role_name' => $userRole,
                                                                                   'iblock_id' => $model->id
                                                                               ])->exists()) {
                throw new HttpException(403, 'Нет доступа к инфоблоку ' . $model->id . ' ' . $userRole);
            }
        }

        $clname = 'Infoblock' . ucfirst($code);

        if (!class_exists("CmsModule\\Infoblocks\\models\\$clname")) {
            $class = <<<PHP
        namespace CmsModule\Infoblocks\models;
        use yii\helpers\ArrayHelper;
        use yii\db\Expression;

        class $clname extends Infoblock {
            private static \$_iblock_id = {$model->id};
            public \$aditionalParams;

            public static function create() {
                \$model = new \\CmsModule\\Infoblocks\\models\\$clname();
                \$model->iblock_id = self::\$_iblock_id;
                \$model->created_at = (new \\DateTime())->format('Y-m-d H:i:s');
                \$model->sort = 500;
                return \$model;
            }
            public static function infoblockCode()
            {
                return '{$code}';
            }
            public static function tableName()
            {
                return '__iblock_content_{$code}';
            }

            public function rules(): array
            {
                return [
                    [['name', 'code', 'iblock_id'], 'required'],
                    [['sort'], 'number'],
                    [['sort'], 'default', 'value'=>500],
                    [['active', 'created_at', 'updated_at', 'site_id'], 'safe'],
                    [['created_at', 'updated_at'], 'date', 'format' => 'yyyy-M-d H:m:s'],
PHP;
            foreach ($model->properties as $property) {
                if ((int)$property->multi) {
                    continue;
                }

                if ($property->propertyValidators) {
                    foreach ($property->propertyValidators as $validator) {
                        $i = $validator->name;
                        if ($i === 'type') {
                            $validatorName = $validator->param1;
                        } else {
                            $validatorName = $validator->name;
                        }
                        foreach (
                            ArrayHelper::merge(['ru' => 'Русский'],
                                               Yii::$app->params['languages']) as $languageKey => $languageName
                        ) {
                            $paramCode = $property->code . (Yii::$app->sourceLanguage === $languageKey ? '' : '_' . $languageKey);
                            $class .= <<<PHP
                    [['{$paramCode}'], '{$validatorName}'],
PHP;
                        }
                    }
                }
            }

            $class .= <<<PHP
                ];
            }

            public function attributeLabels(): array
            {
                return [
                    'name'=>'Название',
                    'code'=>'Код',
                    'site_id'=>'Сайт',
                    'active'=>'Активность',
                    'created_at'=>'Дата создания',
                    'updated_at'=>'Дата изменения',
                    'sort' => 'Сортировка',
PHP;
            foreach ($model->properties as $property) {
                $class .= <<<PHP
                '{$property->code}'=>'{$property->name}',
PHP;
            }
            $class .= <<<PHP
                ];
            }

PHP;

            foreach ($model->properties as $property) {
                if ((int)$property->multi === 1) {
                    $linkFunction = 'hasMany';
                    $type = ucfirst($property->code);
                    if ((int)$property->type === 4) {
                        if ($property->propertyLink->iblock_id === $property->iblock) {
                            $linkedClassName = 'self';
                        } else {
                            $iblockType = InfoblockTypes::findOne($property->propertyLink->iblock_id);
                            $iblockClass = self::byCode($iblockType->code);
                            $linkedClassName = '\\' . $iblockClass::className();
                        }

                        $class .= <<<PHP
                public function get$type()
                {
                    \$class = Infoblock::multiProperty('{$code}', '{$property->code}');

                    \$tbl = \$class::tableName();
                    \$tbl_primary = $linkedClassName::tableName();

                    \$query = $linkedClassName::find()
                        ->select([
                            \$tbl.'.id',
                            new Expression(\$tbl.'.value as code'),
                            \$tbl_primary.'.name',
                            \$tbl_primary.'.iblock_id',
                        ])
                        ->innerJoin(\$tbl, \$tbl.'.value='.\$tbl_primary.'.id')
                        ->where([\$tbl.'.content_id'=>\$this->id])
                        ;
                    \$query->multiple = true;
                    return \$query;

                }
PHP;
                    } else {
                        $class .= <<<PHP
                public function get$type()
                {
                    \$class=Infoblock::multiProperty('{$code}', '{$property->code}');
                    return \$this->$linkFunction(\$class::className(), ['content_id' => 'id']);
                }
PHP;
                    }
                } elseif ($property->propertyLink) {
                    if ((int)$property->propertyLink->type_id === 1) {
                        $linkFunction = 'hasMany';
                        $from = (int)$property->propertyLink->linkedProperty === 0 ? 'id' : $property->propertyLink->linkedProperty->code;
                        $to = $property->code;
                    } else {
                        $linkFunction = 'hasOne';
                        $to = (int)$property->propertyLink->linkedProperty === 0 ? 'id' : $property->propertyLink->linkedProperty->code;
                        $from = $property->code;
                    }

                    $type = ucfirst($property->propertyLink->type->code);
                    $class .= <<<PHP
                public function get$type()
                {
                    \$class=Infoblock::byCode('{$property->propertyLink->type->code}');
                    return \$this->$linkFunction(\$class::className(), ['$from' => '$to']);
                }
PHP;
                }
            }

            $class .= <<<PHP
        }
PHP;

            eval($class);
        }
        $clname = "CmsModule\\Infoblocks\\models\\$clname";
        return new $clname();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['type'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'created_at' => 'Дата создания',
            'name' => 'Name',
            'type' => 'Type',
            'sort' => 'Порядок сортировки',
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
     * @return ActiveQuery
     */
    public function getInfoblockType(): ActiveQuery
    {
        return $this->hasOne(InfoblockTypes::class, ['id' => 'iblock_id'])->cache();
    }

    /**
     * @param $param
     * @throws HttpException
     */
    public function setMultiParams($param): void
    {
        foreach ($param['value'] as $value) {
            $class = $this->multiProperty($this->infoblockType->code, $param['code']);

            if (isset($value['isNew'])) {
                $model = new $class();
                $model->content_id = $this->id;
            } else {
                $model = $class::findOne($value['id']);
            }

            if ($model) {
                if ((int)$param['type'] === self::TYPE_PROPERTY_IMAGE) {
                    if (strpos($value['value'], 'base64') !== false) {
                        $model->value = $value['value'];
                    }
                } else {
                    $model->value = $value['value'];
                }

                $this->multiParams[$param['code']][] = $model;
            }
        }
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $valid = parent::validate($attributeNames, $clearErrors);
        foreach ($this->multiParams as $k => &$param) {
            foreach ($param as &$p) {
                $p->validate();
                $valid = $valid && count($p->errors) === 0;
            }
        }
        return $valid;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->updated_at = (new DateTime)->format('Y-m-d H:i:s');
        if ($this->isNewRecord) {
            $this->created_at = $this->updated_at;
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws HttpException
     */
    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->multiParams as &$param) {
            foreach ($param as &$p) {
                $p->save();
            }
        }

        foreach ($this->multiDelete as $code => $ids) {
            $class = $this->multiProperty($this->infoblockType->code, $code);
            foreach ($ids as $id) {
                $model = $class::findOne($id);
                if ($model) {
                    $model->delete();
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }
}
