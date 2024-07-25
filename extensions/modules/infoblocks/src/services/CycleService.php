<?php

namespace CmsModule\Infoblocks\services;

use cms\common\Core;
use cms\common\entity\Images;
use Cycle\ORM\Promise\Reference;
use Doctrine\Common\Inflector\Inflector;
use Involta\Cycle\ClassDeclaration;
use Involta\Cycle\FileRepository;
use Involta\Cycle\Forms\Form;
use CmsModule\Infoblocks\{models\InfoblockProperties, models\InfoblockTypes};
use Involta\Cycle\ORM\EntityGenerator;
use Spiral\Migrations\Config\MigrationConfig;
use Spiral\Migrations\Migration;
use Spiral\Reactor\FileDeclaration;
use Throwable;
use Yii;
use Cms;

class CycleService
{
    public function generateEntity(int $infoblockTypeId): void
    {
        /** @var InfoblockTypes $infoblockType */
        $infoblockType = InfoblockTypes::findOne($infoblockTypeId);

        $class = new EntityGenerator($infoblockType->code, $infoblockType->getTable());
        $class->getFile()->addUse(Core::class)->addUse(InfoblockTypes::class)->addUse(Yii::class);
        $constructor = <<<PHP
\$this->siteId = Core::getCurrentSiteId();
\$this->iblockId = InfoblockTypes::findOne(['code' => "{$infoblockType->code}"])->id;
\$this->sort = 500;
\$this->code = Yii::\$app->security->generateRandomString();
\$this->createdAt = date('Y-m-d H:i:s');
\$this->updatedAt = date('Y-m-d H:i:s');
\$this->active = false;
PHP;
        $class->setConstructor($constructor);
        $class->addProperty('id', 'primary');
        $class->addProperty('iblock_id', 'integer');
        $class->addProperty('site_id', 'integer');
        $class->addProperty('active', 'boolean');
        $class->addProperty('created_at', 'datetime');
        $class->addProperty('updated_at', 'datetime');
        $class->addProperty('sort', 'integer');
        $class->addProperty('name', 'string');
        $class->addProperty('code', 'string');
        foreach ($infoblockType->properties as $property) {
            if ($property->multi) {
                $code = $infoblockType->code . '_' . $property->code;
                $this->generateMultiPropertyClass($infoblockType->code, $code,
                    $infoblockType->getTable() . '_multi_' . $property->code);
                $class->addRelation($property->code, 'HasMany', Inflector::classify($code), 'id', 'content_id');
            } else {
                if (InfoblockProperties::$TYPES[$property->type] === 'image') {
                    $class->addRelation($property->code, 'BelongsTo', Images::class, $property->code, 'id');
                    $class->getFile()->addUse(Images::class)->addUse(Reference::class);
                    $class->getClass()->method(Inflector::camelize('get_' . $property->code))->setComment('return Images|Reference');
                } else {
                    $class->addProperty($property->code, InfoblockProperties::$ATTRIBUTE_TYPES[$property->type]);
                }
            }
        }

        $class->renderFile('@frontend/entity');
    }

    private function generateMultiPropertyClass(string $parentName, string $name, string $table)
    {
        $class = new EntityGenerator($name, $table);
        $class->addProperty('id', 'primary');
        $class->addRelation('content', 'HasOne', Inflector::classify($parentName), 'content_id', 'id');
        $class->addProperty('value', 'string');
        $class->renderFile('@frontend/entity');
    }

    public function generateForm(int $infoblockTypeId): void
    {
        /** @var InfoblockTypes $infoblockType */
        $infoblockType = InfoblockTypes::findOne($infoblockTypeId);
        $className = Inflector::classify($infoblockType->code . 'Form');
        $class = new ClassDeclaration($className);
        $class->setExtends('Form');
        $properties = [];
        $labels = [];
        foreach ($infoblockType->properties as $property) {
            $propertyCode = Inflector::camelize($property->code);
            $properties[] = "'" . $propertyCode . "'";
            $labels[] = "'" . $propertyCode . "' => " . "'" . $property->name . "'";
            $class->property($propertyCode)->setPublic();
        }
        $propertiesString = implode(', ', $properties);
        $rules = <<<PHP
return [
    [[{$propertiesString}], 'required']
];
PHP;
        $class->method('rules')->setSource($rules)->setPublic()->setReturn('array');
        $labels = implode(', ' . PHP_EOL . '    ', $labels);
        $attributeLabels = <<<PHP
return [
    {$labels}
];
PHP;

        $class->method('attributeLabels')->setSource($attributeLabels)->setPublic()->setReturn('array');
        $entityPk = <<<PHP
return 'id';
PHP;

        $class->method('getEntityPk')->setPublic()->setStatic()->setSource($entityPk)->setReturn('?string');

        $file = new FileDeclaration('frontend\\forms');
        $file->addElement($class);
        $file->setDirectives('strict_types=1');
        $file->addUse(Form::class);

        $content = $file->render();
        $content = str_replace("class ${className}", "final class ${className}", $content);
        file_put_contents(Yii::getAlias('@frontend/forms/') . $className . '.php', $content);
    }

    public function generateMigration(int $infoblockTypeId): void
    {
        /** @var InfoblockTypes $infoblockType */
        $infoblockType = InfoblockTypes::findOne($infoblockTypeId);
        $name = 'create_' . $infoblockType->code . '_infoblock';

        $fileRepository = new FileRepository($this->getMigrationConfig());
        $name = $fileRepository->createFilename($name);

        $class = new ClassDeclaration(str_replace('.php', '', basename($name)), 'Migration');
        $class->method('up')
            ->setPublic()
            ->setReturn('void')
            ->setSource($this->getSourceUp($class, $infoblockType));

        $class->method('down')
            ->setPublic()
            ->setReturn('void')
            ->setComment('@throws Throwable')
            ->setSource($this->getSourceDown($infoblockType));
        $file = new FileDeclaration($this->getMigrationConfig()->getNamespace());
        $file->addUse(Migration::class);
        $file->addUse(Throwable::class);
        $file->addUse(InfoblockProperties::class);
        $file->addUse(InfoblockTypes::class);
        $file->addElement($class);

        $file->setDirectives('strict_types=1');
        $content = $file->render();
        $className = $class->getName();
        $content = str_replace("class ${className}", "final class ${className}", $content);
        file_put_contents($name, $content);
    }

    private function getSourceUp(ClassDeclaration $class, InfoblockTypes $infoblockType): string
    {
        $src = <<<PHP
\$model = new InfoblockTypes();
\$model->code = '{$infoblockType->code}';
\$model->name = '{$infoblockType->name}';
\$model->type = {$infoblockType->type};
\$model->save();


PHP;
        foreach ($infoblockType->properties as $property) {
            $propertyClassify = Inflector::classify($property->code);
            $type = $this->getPropertyTypeConstant($property);
            $isMultiValue = $property->multi ? 'true' : 'false';
            $sortConstant = $class->constant('SORT_FIELD_' . strtoupper($property->code));
            $sortConstant->setValue($property->sort);
            $src .= <<<PHP
\$prop{$propertyClassify} = new InfoblockProperties();
\$prop{$propertyClassify}->iblock = \$model->id;
\$prop{$propertyClassify}->name = '{$property->name}';
\$prop{$propertyClassify}->code = '{$property->code}';
\$prop{$propertyClassify}->type = {$type};
\$prop{$propertyClassify}->sort = self::{$sortConstant->getName()};
\$prop{$propertyClassify}->multi = {$isMultiValue};
\$prop{$propertyClassify}->save();


PHP;
        }
        return $src;
    }

    /**
     * @param InfoblockProperties $property
     * @return string|int
     */
    private function getPropertyTypeConstant(InfoblockProperties $property)
    {
        switch ($property->type) {
            case InfoblockProperties::TYPE_STRING:
                return 'InfoblockProperties::TYPE_STRING';
            case InfoblockProperties::TYPE_TEXT:
                return 'InfoblockProperties::TYPE_TEXT';
            case InfoblockProperties::TYPE_IMAGE:
                return 'InfoblockProperties::TYPE_IMAGE';
            case InfoblockProperties::TYPE_ATTACH:
                return 'InfoblockProperties::TYPE_ATTACH';
            case InfoblockProperties::TYPE_CHECKBOX:
                return 'InfoblockProperties::TYPE_CHECKBOX';
            case InfoblockProperties::TYPE_DATE:
                return 'InfoblockProperties::TYPE_DATE';
            case InfoblockProperties::TYPE_DATETIME:
                return 'InfoblockProperties::TYPE_DATETIME';
            case InfoblockProperties::TYPE_FILE:
                return 'InfoblockProperties::TYPE_FILE';
            default:
                return $property->type;
        }
    }

    private function getSourceDown(InfoblockTypes $infoblockType): string
    {
        $src = <<<PHP
/** @var InfoblockTypes \$model */
\$model = InfoblockTypes::find()->where(['code' => '{$infoblockType->code}'])->one();
foreach (\$model->properties as \$property) {
    \$property->delete();
}
\$model->delete();
PHP;
        return $src;
    }

    private function getMigrationConfig(): MigrationConfig
    {
        $migrationPath = Yii::getAlias('@frontend/migrations');

        $config = new MigrationConfig([
            'directory' => $migrationPath,
            'table' => 'migrations',
            'namespace' => 'frontend\\migrations'
        ]);

        return $config;
    }
}