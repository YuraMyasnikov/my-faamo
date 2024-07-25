<?php

namespace CmsModule\Infoblocks\admin\helpers;

use Cms;
use Yii;
use yii\helpers\Url;

class UrlHelper
{
    private static function getBack(): string
    {
        return urlencode(Yii::$app->request->absoluteUrl);
    }


    public static function importInfoblockType(): string
    {
        return Url::to(['/admin/infoblocks/types/load', 'back' => static::getBack()]);
    }

    public static function createFolder(): string
    {
        return Url::to(['/admin/infoblocks/types/create-folder', 'folder' => Yii::$app->request->get('folder')]);
    }

    public static function createInfoblockType(): string
    {
        return Url::to(['/admin/infoblocks/types/create', 'folder' => Yii::$app->request->get('folder')]);
    }

    public static function exportContent(string $code): string
    {
        $filter = Yii::$app->request->get('filter', []);
        $urlParams = ['export', 'code' => $code, 'filter' => $filter, 'back' => static::getBack()];
        return Url::to($urlParams);
    }
}