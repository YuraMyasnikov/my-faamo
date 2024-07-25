<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class RegistrationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/registration.js',
        'js/kladr.js',
    ];
    public $depends = [
        AppAsset::class,
        MaskAsset::class
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
