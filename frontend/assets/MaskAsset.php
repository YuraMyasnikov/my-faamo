<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class MaskAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/jquery.mask.min.js',
        'js/mask.js',
    ];
    public $depends = [
        AppAsset::class,

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
