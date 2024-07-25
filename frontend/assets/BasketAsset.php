<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BasketAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/basket.js',
    ];
    public $depends = [
        AppAsset::class
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
