<?php

namespace CmsModule\Infoblocks\admin\controllers;

use Cms;
use cms\{admin\components\Controller, common\models\InvoltaSites, common\models\User};
use cms\admin\controllers\AdminController;
use Cycle\ORM\ORMInterface;
use DirectoryIterator;
use CmsModule\Infoblocks\{models\Infoblock,
    models\InfoblockLinks,
    models\InfoblockLoad,
    models\InfoblockProperties,
    models\InfoblockTypes,
    models\InfoblockValidators,
    models\InvoltaIblockAccess,
    models\InvoltaIblockFolders,
    models\InvoltaIblockSites,
    services\CycleService,
    modules\shop\traits\Translit};
use yii\{base\Exception,
    base\InvalidConfigException,
    data\ActiveDataProvider,
    db\ActiveRecord,
    db\StaleObjectException,
    gii\CodeFile,
    helpers\Url,
    web\HttpException,
    web\Response,
    web\UploadedFile,
    filters\VerbFilter};
use Throwable;
use Yii;

/**
 * Class TypesController
 * @package CmsModule\Infoblocks\admin\controllers
 */
class TypesController extends AdminController
{
    /**
     * @return null|string
     */
    public function getTitle(): string
    {
        return 'Типы инфоблоков';
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        // $behaviors['access']['rules'] = [[
        //     'allow' =>
        //         Yii::$app->user->can('adminPanelAccess') &&
        //         Yii::$app->user->can('access_module_infoblocks_types')
        // ]];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete-folder' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @param null $folder
     * @return string
     */
    public function actionIndex($folder = null): string
    {
        //$userRole = User::getRole(Yii::$app->user->id);
        $userRole = array_key_first(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));

        $folders = InvoltaIblockFolders::find()->where(['parent_id' => $folder]);
        if ($userRole === 'admin') {
            $items = InfoblockTypes::find()->where(['type' => 1, 'folder_id' => $folder]);
        } else {
            $items = InfoblockTypes::find()->alias('it')
                ->innerJoin(InvoltaIblockAccess::tableName() . ' ia', 'ia.iblock_id=it.id')
                ->where(['it.type' => 1, 'it.folder_id' => $folder, 'ia.role_name' => $userRole]);
        }
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
            'query' => $items,
            'pagination' => [
                'pageParam' => 'item-page',
                'pageSizeLimit' => [20, 50, 100]
            ],
            'sort' => [
                'sortParam' => 'item-sort'
            ]
        ]);
        return $this->render('index', ['folderDataProvider' => $folderDataProvider, 'itemDataProvider' => $itemDataProvider]);
    }

    /**
     * @throws HttpException
     */
    public function actionMigrate(int $id): string
    {
        $model = $this->loadModel($id);
        return $this->render('migrate', ['model' => $model]);
    }

    public function actionForm(int $id): Response
    {
        $service = new CycleService();
        $service->generateForm($id);
        return $this->redirect(Url::to(['migrate', 'id' => $id]));
    }

    public function actionEntity(int $id): Response
    {
        $service = new CycleService();
        $service->generateEntity($id);
        return $this->redirect(Url::to(['migrate', 'id' => $id]));
    }

    public function actionMigration(int $id): Response
    {
        $service = new CycleService();
        $service->generateMigration($id);
        return $this->redirect(Url::to(['migrate', 'id' => $id]));
    }


    /**
     * @param $id
     * @return array|null|ActiveRecord|InfoblockTypes
     * @throws HttpException
     */
    public function loadModel($id)
    {
        $model = InfoblockTypes::find()->where([
            'id' => $id,
            'type' => 1
        ])->one();

        if (!$model) {
            throw new HttpException(404, 'Type not found');
        }
        return $model;
    }

    /**
     * @param null $folder
     * @return string|Response
     */
    public function actionCreateFolder($folder = null)
    {
        $model = new InvoltaIblockFolders([
            'parent_id' => $folder
        ]);
        return $this->folderModify($model);
    }

    /**
     * @param $model
     * @return string|Response
     */
    public function folderModify($model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect(['index', 'folder' => $model->parent_id]);
        }

        return $this->render('folderForm', ['model' => $model]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws HttpException
     */
    public function actionUpdateFolder($id)
    {
        if (!$model = InvoltaIblockFolders::findOne($id)) {
            throw new HttpException(404, 'Папка не найдена');
        }

        return $this->folderModify($model);
    }

    /**
     * Delete folder
     *
     * @param $id
     * @return Response
     * @throws HttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteFolder($id): Response
    {
        if (($model = InvoltaIblockFolders::findOne(['id' => $id])) && $model !== null) {
            $model->delete();
            return $this->redirect(['index']);
        }
        throw new HttpException(404, 'Папка не найдена');
    }

    /**
     * @return string
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionCreate(): string
    {
        $model = new InfoblockTypes;
        return $this->render('form', $this->modify($model));
    }

    /**
     * @param $model
     * @param bool $import
     * @param null $infoblockLoad
     * @return array
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function modify($model, $import = false, $infoblockLoad = null): array
    {
        $modelSitesData = InvoltaIblockSites::find()->where(['iblock_id' => $model->id])->asArray()->all();
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $modelRolesData = InvoltaIblockAccess::find()->where(['iblock_id' => $model->id])->asArray()->all();
        unset($roles['guest']);

        $modelSites = [];
        foreach ($modelSitesData as $md) {
            $modelSites[$md['site_id']] = $md;
        }

        $modelRoles = [];
        if ($model->isNewRecord) {
            foreach ($roles as $role) {
                $modelRoles[$role->name] = true;
            }
        } else {
            foreach ($modelRolesData as $rd) {
                $modelRoles[$rd['role_name']] = $rd;
            }
        }

        $postRoles = Yii::$app->request->post('roles', []);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // InvoltaIblockSites::deleteAll(['iblock_id' => $model->id]);
            // foreach (array_keys($postSites) as $siteId) {
            //     (new InvoltaIblockSites([
            //         'iblock_id' => $model->id,
            //         'site_id' => $siteId
            //     ]))->save();
            // }
            InvoltaIblockAccess::deleteAll(['iblock_id' => $model->id]);
            foreach (array_keys($postRoles) as $role) {
                (new InvoltaIblockAccess([
                    'iblock_id' => $model->id,
                    'role_name' => $role
                ]))->save();
            }
            $deleteProps = json_decode(Yii::$app->request->post('deleteProps', '{}'), true, 512, JSON_THROW_ON_ERROR);

            foreach ($deleteProps as $prop) {
                $dm = InfoblockProperties::find()->where(['code' => $prop, 'iblock' => $model->id])->one();
                if ($dm) {
                    $dm->delete();
                }
            }
            /*Если у нас импорт, то вытянем свойства из таблицы*/
            $properties = [];
            $rows = [];
            if ($import) {
                $file = UploadedFile::getInstance($infoblockLoad, 'loadFile');
                $rows = $infoblockLoad->saveFile($file);

                $row = $rows[0];
                foreach ($row as $key => $value) {
                    $code = Translit::encodestring($key);
                    $properties[$code]['code'] = $code;
                    $properties[$code]['name'] = $key;
                    $properties[$code]['type'] = '1'; //TODO исправить
                }

            }

            if ($import || $properties = Yii::$app->request->post('Properties', false)) {

                foreach ($properties as $code => $data) {
                    if (in_array($code, $deleteProps, true)) {
                        continue;
                    }
                    $prop = InfoblockProperties::find()->where([
                        'code' => $code,
                        'iblock' => $model->id
                    ])->one();
                    if (!$prop) {
                        $prop = new InfoblockProperties;
                        $prop->iblock = $model->id;
                    }

                    $prop->name = $data['name'];
                    $prop->code = $data['code'];
                    $prop->type = $data['type'];
                    $prop->multi = isset($data['multi']) && $data['multi'] === 'on' ? 1 : 0;
                    $prop->sort = 0;
                    $prop->save();

                    if ($prop->type == 4) {
                        $relation = json_decode($data['relation'], false, 512, JSON_THROW_ON_ERROR);

                        $link = InfoblockLinks::find()->where([
                            'property_id' => $prop->id
                        ])->one();

                        if (!$link) {
                            $link = new InfoblockLinks();
                            $link->property_id = $prop->id;
                        }

                        $link->iblock_id = (int)$relation->iblock;
                        $link->linked_id = (int)$relation->linked ?: 0;
                        $link->type_id = (int)$relation->type;
                        $link->save();
                    }

                    if ($data['validators']) {
                        $validators = json_decode($data['validators'], true, 512, JSON_THROW_ON_ERROR);

                        foreach ($validators as $vkey => $validator) {
                            $v = InfoblockValidators::find()->where([
                                'name' => $vkey,
                                'property_id' => $prop->id
                            ])->one();

                            if (!$v && !$validator['enabled']) {
                                continue;
                            }
                            if ($v && !$validator['enabled']) {
                                $v->delete();
                                continue;
                            }

                            if (!$v) {
                                $v = new InfoblockValidators;
                                $v->name = $vkey;
                                $v->property_id = $prop->id;
                            }

                            $v->enabled = $validator->enabled ? 1 : 0;
                            $v->param1 = $validator->param1 ?: null;
                            $v->param2 = $validator->param2 ?: null;
                            $v->param3 = $validator->param3 ?: null;
                            $v->param4 = $validator->param4 ?: null;
                            $v->param5 = $validator->param5 ?: null;
                            $v->save();
                        }
                    }
                }
                if ($import) {
                    $infoblockLoad->import($model->code, null, $rows);
                }
            }

            $this->redirect(['index']);
        }
        $params = ['model' => $model];

        $properties = [];

        foreach ($model->properties as $property) {
            $tmp = $property->attributes;
            $tmp['new_code'] = $tmp['code'];
            $tmp['type'] = (int)$tmp['type'];
            $tmp['multi'] = (int)$tmp['multi'] === 1;
            $tmp['iblock'] = (int)$tmp['iblock'];
            $tmp['id'] = (int)$tmp['id'];
            $tmp['sort'] = (int)$tmp['sort'];
            $tmp['validators'] = [];
            if ($tmp['type'] == 4) {

                $link = $property->propertyLink;
                $tmp['relation'] = [
                    'iblock' => (int)$link->iblock_id,
                    'type' => (int)$link->type_id
                ];

                if ($link->linked_id) {
                    $tmp['relation']['linked'] = (int)$link->linked_id;
                }

            }
            foreach ($property->propertyValidators as $validator) {
                $tmp['validators'][$validator->name] = [
                    'enabled' => (int)$validator->enabled === 1,
                    'param1' => $validator->param1,
                    'param2' => $validator->param2,
                    'param3' => $validator->param3,
                    'param4' => $validator->param4,
                    'param5' => $validator->param5,
                    'param6' => $validator->param6
                ];
            }

            if (count($tmp['validators']) === 0) {
                $tmp['validators']['default']['enabled'] = false;
            }

            if (isset($tmp['validators']['required'])) {
                $tmp['validators']['required']['param1'] = (int)$tmp['validators']['required']['param1'] === 1;
            }

            if (isset($tmp['validators']['unique'])) {
                $tmp['validators']['unique']['param1'] = (int)$tmp['validators']['unique']['param1'] === 1;
            }

            $properties[] = $tmp;
        }

        $params['properties'] = $properties;
        $params['modelSites'] = $modelSites;
        $params['modelRoles'] = $modelRoles;
        $params['roles'] = $roles;

        return $params;
    }

    /**
     * @param mixed $code
     * @return bool
     * @throws HttpException
     * @throws InvalidConfigException
     */
    public function generateForm($code): bool
    {
        $class = Infoblock::byCode($code);
        $model = $class::create();
        $newClass = InfoblockTypes::findOne(['code' => $code]);
        $properties = [];
        /** @var InfoblockTypes $newClass */
        foreach ($newClass->properties as $property) {
            $properties[$property->code] = InfoblockProperties::$TYPES[$property->type];
        }
        //var_export($properties);
        $dir = Yii::getAlias('@frontend') . '/generated/';
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }
        $file = new CodeFile(
            $dir . $code . '.php',
            $this->renderPartial('/generator/form.php', compact('model', 'properties'))
        );
        file_put_contents($file->path, $file->content);
        return true;
    }

    /**
     * @param int $id
     * @return string
     * @throws HttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionUpdate($id): string
    {
        $model = $this->loadModel($id);
        return $this->render('form', $this->modify($model));
    }

    /**
     * @param int $id
     * @throws HttpException
     * @throws InvalidConfigException
     */
    public function actionGenerate($id): void
    {
        $model = $this->loadModel($id);
        $this->generateForm($model->code);
    }

    /**
     * @param int $id
     * @throws HttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): void
    {
        $model = $this->loadModel($id);
        $model->delete();
        $this->redirect(['index']);
    }

    /**
     * @return string
     */
    public function actionImport(): string
    {
        $model = new InfoblockLoad();
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'loadFile');
            if ($model->import(null, $file)) {
                $this->redirect(['index']);
            }
        }
        return $this->render('content/import.php', ['model' => $model]);
    }

    /**
     * @return string
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionLoad(): string
    {
        $model = new InfoblockTypes();
        $infoblockLoad = new InfoblockLoad();
        $params = $this->modify($model, true, $infoblockLoad);
        $params['infoblockLoad'] = $infoblockLoad;
        return $this->render('load', $params);
    }
}
