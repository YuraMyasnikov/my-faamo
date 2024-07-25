<?php

namespace frontend\assets\admin;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;


class RelatedCategoriesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/admin/related_categories.js',
    ];
    public $depends = [
    ];

    public function init() {
        if(!YII_ENV_PROD) {
            $time = time();
            foreach($this->js as &$js) {
                $js = $js . '?time=' . $time;
            }
        }
        parent::init();
    }
}
