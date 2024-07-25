<?php

namespace CmsModule\Infoblocks\services;

use Yii;
use CmsModule\Infoblocks\{
    models\Infoblock,
    models\InfoblockProperties,
    repository\InfoblockContentRepository};
use moonland\phpexcel\Excel;
use yii\{base\Exception, base\InvalidConfigException, web\HttpException, web\NotFoundHttpException, web\UploadedFile};

/**
 * Class InfoblockService
 * @package CmsModule\Infoblocks\services
 */
class InfoblockService
{
    protected $infoblock, $code;

    /**
     * InfoblockService constructor.
     * @param string $codeInfoblock
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function __construct(string $codeInfoblock)
    {
        $this->infoblock = Infoblock::byCode($codeInfoblock);
        $this->code = $codeInfoblock;
    }

    /**
     * Экспорт инфоблока в Excel
     */
    public function export(array $filter): void
    {
        $class = $this->infoblock;
        $filterModel = $class::create();
        $query = InfoblockContentRepository::findByParams($filterModel, $class, $filter);
        $models = $query->all();
        $modelExample = $models[0];
        $iblockProperty = InfoblockProperties::find()->where(['iblock' => $modelExample->iblock_id])->all();
        $noShow = ['id', 'iblock_id', 'site_id', 'code', 'sort', 'created_at', 'updated_at'];
        $columns = [];
        $headers = [];
        foreach ($iblockProperty as $property) {
            if (0 === $property->multi) {
                $columns[] = $property->code;
                $noShow[] = $property->code;
            } else {
                $tmp = $property->code;
                $noShow[] = $property->code;
                $columns[] = [
                    'header' => $property->code,
                    'value' => function ($model) use ($tmp) {
                        $results = $model->multiProperty($this->code, $tmp)->find()->where('content_id=' . $model->id)->all();
                        $values = array_column($results, 'values');
                        return implode(' | ', $values);
                    },
                ];
            }
            $headers[] = [$property->name => $property->code];
        }
        foreach ($modelExample->attributes as $key => $attribute) {
            if (!in_array($key, $noShow, true)) {
                $columns[] = $key;
                $headers[] = [$key => $attribute];
            }
        }
        Excel::export([
            'models' => $models,
            'columns' => $columns,
            'headers' => $headers,
        ]);
    }

    /**
     * Импорт инфоблока из Excel
     *
     * @param UploadedFile $file
     * @return bool
     * @throws Exception
     * @throws HttpException
     */
    public function import(UploadedFile $file): bool
    {
        $model = $this->infoblock::create();
        $iblockProperty = InfoblockProperties::find()->where(['iblock' => $model->iblock_id])->all();
        $filter = [];
        foreach ($iblockProperty as $property) {
            if (1 == $property->multi) {
                $filter[] = $property->name;
            }
        }
        $rows = $this->saveFile($file);
        foreach ($rows as $key => $row) {
            $model = $this->infoblock::create();
            $this->modify($model, $row, $filter);
        }
        return true;
    }

    /**
     * Сохранение файла и импорт в массив
     * @param $file
     * @return string
     */
    private function saveFile(UploadedFile $file): string
    {
        $fileName = strtolower(uniqid($file->baseName, true) . '.' . $file->extension);
        $filePath = Yii::getAlias('@webroot/uploads/' . $fileName);
        $file->saveAs($filePath);
        return Excel::import($filePath);
    }

    /**
     * @param Infoblock $model
     * @param array $row
     * @param array $filter
     * @return bool
     * @throws Exception
     * @throws HttpException
     */
    public function modify(Infoblock $model, array $row, array $filter = []): ?bool
    {
        foreach ($model->attributeLabels() as $key => $attribute) {
            if (!in_array($key, $filter, true)) {
                $model[$key] = $row[$attribute];
            }
        }
        $model->code = Yii::$app->security->generateRandomString();
        if ($model->validate()) {
            $model->save();
            foreach ($model->attributeLabels() as $key => $attribute) {
                if (in_array($key, $filter, true)) {
                    $param = [];
                    $param['code'] = $key;
                    $param['name'] = $row[$attribute];
                    $param['multi'] = true;
                    $param['type'] = 1;
                    $mas = (object)explode(' | ', $row[$attribute]);
                    foreach ($mas as $m) {
                        $param['value'][] = (object)['isNew' => true, 'value' => $m];
                    }
                    $model->setMultiParams((object)$param);

                }
            }
            $model->save();
            return true;
        }

        ob_start();
        var_dump($model->getErrors());
        $result = ob_get_clean();
        throw new NotFoundHttpException($result);
    }
}
