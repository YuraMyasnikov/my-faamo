<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class OrderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/order.css',
    ];
    public $js = [
        'js/order.js',
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
            foreach($this->css as &$css) {
                $css = $css . '?time=' . $time;
            }
        }
        parent::init();
    }
}
