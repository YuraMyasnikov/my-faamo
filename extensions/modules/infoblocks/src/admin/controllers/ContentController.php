<?php

namespace CmsModule\Infoblocks\admin\controllers;

use Cms;
use cms\{admin\components\Controller, helpers\DateTime, common\components\core\Model, common\Core, common\models\User};
use cms\admin\controllers\AdminController;
use cms\common\models\Images;
use CmsModule\Infoblocks\{
        models\Infoblock,
        models\InfoblockLinks,
        models\InfoblockLoad,
        models\InfoblockProperties,
        models\InfoblockTypes,
        models\InvoltaIblockAccess,
        models\InvoltaIblockFolders,
        repository\InfoblockContentRepository,
        repository\InfoblockTypeRepository,
        services\InfoblockService
};
use DateTime as GlobalDateTime;
use yii\{base\Exception,
    base\InvalidConfigException,
    data\ActiveDataProvider,
    helpers\ArrayHelper,
    web\HttpException,
    web\Request,
    web\Response,
    web\UploadedFile,
    helpers\Json
};
use RuntimeException;
use Throwable;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class ContentController
 * @package CmsModule\Infoblocks\admin\controllers
 */
class ContentController extends AdminController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        // $behaviors['access']['rules'] = [[
        //     'allow' =>
        //         Yii::$app->user->can('adminPanelAccess') &&
        //         Yii::$app->user->can('access_module_infoblocks_content')
        // ]];
        return $behaviors;
    }

    public function getTitle(): string
    {
        return 'Контент';
    }

    public function actionIndex(Request $request, int $folder = null): string
    {
        $userRole = array_key_first(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));

        $folders = InvoltaIblockFolders::find()->where(['parent_id' => $folder]);

        $folderDataProvider = new ActiveDataProvider([
            'query' => $folders,
            'pagination' => [
                'pageParam' => 'folder-page',
                'pageSizeLimit' => [20, 50, 100]
            ],
            'sort' => [
                'sortParam' => 'folder-sort'
            ]
        ]);
        $itemDataProvider = new ActiveDataProvider([
            'query' => InfoblockTypeRepository::findByParams($userRole, $request->get(null, [])),
            'pagination' => [
                'pageParam' => 'item-page',
                'pageSizeLimit' => [20, 50, 100]
            ],
            'sort' => [
                'sortParam' => 'item-sort'
            ]
        ]);

        //$this->module->setViewPath(Yii::getAlias('@CmsModule/Infoblocks/admin/views'));

        return $this->render('index', ['folderDataProvider' => $folderDataProvider, 'itemDataProvider' => $itemDataProvider]);
    }

    /**
     * @param string $code
     * @param string $back
     * @return Response
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionExport(string $code, array $filter = ['sort' => null], string $back = ''): Response
    {
        error_reporting(E_ERROR & E_WARNING & E_NOTICE);
        $infoblockService = new InfoblockService($code);
        $fileName = 'Выгрузка инфоблока ' . $code . '.xlsx';
        header('Content-Description: File Transfer');
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename=' . $fileName);
        $infoblockService->export($filter);
        return $this->redirect(urldecode($back));
    }

    /**
     * @param $code
     * @param null $viewType
     * @param array $filter
     * @return string
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionIblock($code, $viewType = null, array $filter = ['sort' => null]): string
    {
        $class = Infoblock::byCode($code);
        $filterModel = $class::create();

        $query = InfoblockContentRepository::findByParams($filterModel, $class, $filter);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC]
            ],
            'pagination' => [
                'pageSizeLimit' => [20, 50, 100]
            ],
        ]);

        if ($viewType === 'childWindow') {
            $this->layout = 'childWindow';
            $view = 'childWindow';
        } else {
            $view = 'iblock';
        }

        return $this->render('@CmsModule/Infoblocks/admin/views/content/' . $view, [
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel,
            'code' => $code,
        ]);
    }

    /**
     * @param $id
     * @param $code
     * @param $back
     * @return string
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionUpdate($id, $code, $back)
    {
        $class = Infoblock::byCode($code);
        return $this->modify($class::findOne($id), $back);
    }

    /**
     * @param ActiveRecord $model
     * @param string $back
     * @return string
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function modify(ActiveRecord $model, string $back): string
    {
        $model = $this->postProcessing($model, $back);
        $params = $this->getForView($model, $back);
        return $this->render('@CmsModule/Infoblocks/admin/views/content/_form', $params);
    }

    /**
     * @param ActiveRecord $model
     * @param string $back
     * @return ActiveRecord
     * @throws Exception
     */
    private function postProcessing(ActiveRecord $model, string $back): ActiveRecord
    {
        if ($model->load(Yii::$app->request->post())) {
            $params = Json::decode(Yii::$app->request->post('content'));
            $model->multiDelete = Json::decode(Yii::$app->request->post('multiDelete'));
            $delete = Json::decode(Yii::$app->request->post('delete'));
            foreach ($delete as $k => $v) {
                $model->$k = null;
            }
            foreach ($params as &$param) {
                if ('datetime' === InfoblockProperties::$TYPES[$param['type']]) {
                    $dateTime = new GlobalDateTime($param['value']);
                    $param['value'] = !empty($param['value']) ? $dateTime->format('Y-m-d H:i:s') : null;
                } elseif ('file' === InfoblockProperties::$TYPES[$param['type']]) {
                    $file = $_FILES[$param['code']];
                    if (!empty($file['name'])) {
                        $param['value'] = $this->uploadFile($file);
                    }
                }
                if ($param['multi']) {
                    $model->setMultiParams($param);
                } else if ($param['type'] == 3) {
                    if (!empty($param['value']) && strpos($param['value'], 'base64') !== false) {
                        $model->{$param['code']} = Images::saveBase64ToDir(sprintf('content/%s/%s/', $model::infoblockCode(), $model->id), $param['value'], false);
                    }
                } else {
                    $model->{$param['code']} = $param['value'];
                }
            }

            if ($model->validate()) {
                $model->save();
                $this->mainRedirect($model, $back);
            }
        }
        return $model;
    }

    /**
     * @param $file
     * @return string
     */
    private function uploadFile(array $file): string
    {
        if (empty($file['name'])) {
            return '';
        }

        $folder = Yii::getAlias('@webroot') . '/uploads/infoblocks';

        $fileName = ''; 

        do {
            $fileName = mb_strtolower(str_replace('.', '', uniqid('', true)) . '.' . end(explode('.', $file['name'])));
        }
        while (file_exists($folder . '/' . $fileName));

        $isVideo = strpos($file['type'], 'video') !== false;
        
        if ($isVideo) {
            $folder .= '/video-originals';
        }

        $filePath = $folder . '/' . $fileName;
        if (!file_exists($folder) && !is_dir($folder)) {
            if (!mkdir($folder, 0755, true)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $folder));
            }
        }

        move_uploaded_file($file['tmp_name'], $filePath);

        $url = '/uploads/infoblocks/';

        if ($isVideo) {
            $url .= 'videos/';
        }

        return $url . $fileName;
    }

    /**
     * @param ActiveRecord $model
     * @param string $back
     * @return array
     * @throws Throwable
     * @throws InvalidConfigException
     * @throws HttpException
     */
    private function getForView(ActiveRecord $model, string $back): array
    {
        $params = ['model' => $model, $back];

        $params['content'] = [];

        if (is_array($model->infoblockType->properties)) {
            foreach ($model->infoblockType->properties as $prop) {
                $tmp = [
                    'name' => $prop->name,
                    'code' => $prop->code,
                    'multi' => (int)$prop->multi === 1,
                    'type' => (int)$prop->type,
                    'id' => (int)$prop->id,
                ];


                if ($prop->type === 4) {
                    $linkenPropertyModel = InfoblockLinks::find()->where(['property_id' => $prop->id])->one();
                    $tmp['propertyLinkCode'] = $linkenPropertyModel->type->code;
                }

                if ((int)$prop->multi === 1) {
                    $tmp['value'] = [];

                    $multiParams = $model->multiParams[$prop->code] ?? $model->{$prop->code};

                    foreach ($multiParams as $k => $multiValue) {
                        //if (is_array($model->multiDelete->{$prop->code})) {
                        if (is_array($model->multiDelete)) {
                            if (in_array($multiValue->id, $model->multiDelete, true)) {
                                continue;
                            }
                        }

                        $_tmp = ['id' => $multiValue->id, 'errors' => $multiValue->errors['value'] ?? []];
                        if ($prop->type === 3) {
                            //$value = Yii::$app->image->get($multiValue->value)->file;
                            $value = Yii::$app->image->get($multiValue->value) ? Yii::$app->image->get($multiValue->value)->file : null;
                        } elseif ($prop->type === 4) {
                            $_tmp['title'] = $multiValue->name;
                            $value = $multiValue->code;
                        } elseif ($prop->type === 5) {
                            $value = (boolean)$multiValue->code;
                        } elseif ($prop->type === 6) {
                            $value = (new GlobalDateTime($multiValue->code))->format('Y-m-d');
                        } else {
                            $value = $multiValue->value;
                        }

                        $_tmp['value'] = $value;
                        $tmp['value'][] = $_tmp;
                    }
                } else {
                    if ($prop->type === 3) {
                        $tmp['value'] = Yii::$app->image->get($model->{$prop->code}) ? Yii::$app->image->get($model->{$prop->code})->file : null;
                    } elseif ($prop->type === 4) {
                        $tmp['title'] = Infoblock::byCode($tmp['propertyLinkCode'])->findOne($model->{$prop->code})->name;
                        $tmp['value'] = $model->{$prop->code};
                    } elseif ($prop->type === 5) {
                        $tmp['value'] = (boolean)$model->{$prop->code};
                    } elseif ($prop->type === 6) {
                        $tmp['value'] = (new GlobalDateTime($model->{$prop->code}))->format('Y-m-d');
                    } elseif ($prop->type === 7) {
                        $dateTime = new GlobalDateTime($model->{$prop->code});
                        $dateTime = $dateTime->format('Y-m-d\TH:i');
                        $tmp['value'] = $dateTime;
                    } else {
                        $tmp['value'] = $model->{$prop->code};
                    }

                    if (isset($model->errors[$prop->code]) && !empty($model->errors[$prop->code])) {
                        $tmp['errors'] = $model->errors[$prop->code];
                    }
                }
                $params['content'][] = $tmp;
            }
        }

        // if (!Yii::$app->user->can('access_module_infoblocks_types')) {
        //     $params['siteList'] = Core::getUserSites();
        // } else {
        //     $params['siteList'] = ArrayHelper::merge([null => '(не задано)'], Core::getUserSites());
        // }
        return $params;
    }

    /**
     * @param $model
     * @param bool $back
     */
    public function mainRedirect($model, $back = false)
    {
        if ($back) {
            $this->redirect(urldecode($back));
        } else {
            $this->redirect(['iblock', 'code' => $model->infoblockType->code]);
        }
    }

    /**
     * @param $id
     * @param $code
     * @param $back
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionDelete($id, $code, $back)
    {
        $class = Infoblock::byCode($code);
        $class::findOne($id)->delete();
        $this->redirect(urldecode($back));
    }

    /**
     * @param $code
     * @param $back
     * @return string
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionCreate($code, $back)
    {
        $class = Infoblock::byCode($code);
        $model = $class::create();
        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->save();
        }

        //$model->site_id = 1;

        return $this->modify($model, $back);
    }

    /**
     * @param string $code
     * @param string $back
     * @return string
     * @throws Exception
     * @throws InvalidConfigException
     * @throws HttpException
     */
    public function actionImport(string $code, $back = ''): string
    {
        $model = new InfoblockLoad();
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'loadFile');
            $infoblockService = new InfoblockService($code);
            if ($infoblockService->import($file)) {
                $this->redirect(urldecode($back));
            }
        }
        return $this->render('import.php', ['model' => $model]);
    }

    public function actionUpload(): ?string
    {
        $file = UploadedFile::getInstanceByName('file');
        $fileName = strtolower(uniqid($file->baseName, true) . '.' . $file->extension);
        $folderPath = '/uploads/infoblocks/';
        if ($code = Yii::$app->request->post('infoblock')) {
            $folderPath .= $code . '/';
        }
        if (!file_exists(Yii::getAlias('@webroot' . $folderPath))) {
            if (!mkdir($concurrentDirectory = Yii::getAlias('@webroot' . $folderPath), 0777, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        $urlPath = $folderPath . $fileName;
        $filePath = Yii::getAlias('@webroot' . $urlPath);
        if ($file->saveAs($filePath)) {
            return Json::encode(['status' => true, 'location' => $urlPath]);
        }

        return Json::encode(['status' => false, 'location' => $urlPath, 'errors' => $file->error]);
    }
}
