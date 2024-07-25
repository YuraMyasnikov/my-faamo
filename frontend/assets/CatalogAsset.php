<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CatalogAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/catalog.js',
    ];
    public $depends = [
        AppAsset::class
    ];

    public function init() {
        if(!YII_ENV_PROD) {
            foreach($this->js as &$js) {
                $js = $js . '?time=' . time();
            }
        }
        parent::init();
    }
}
