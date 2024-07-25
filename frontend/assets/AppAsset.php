<?php

namespace frontend\assets;

use cms\assets\AngularAsset;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/style.css',
        '/css/seo-fos.css',
        '/css/jquery.fancybox.css',
        '/css/example.css',
    ];
    public $js = [
        "/js/swipper.min.js",
        "/js/splide.js",
        "/js/slider.js",
        "/js/fancybox.js",
        "/js/micromodal.js",
        "/js/script.js",
        "/js/admin/seo-fos.js",
        "/js/default.js",
        "/js/accordion.js",

    ];
    public $depends = [
        'yii\web\YiiAsset',
        NotifyAsset::class,
    ];

    public function init() {
        if(!YII_ENV_PROD) {
            foreach($this->js as &$js) {
                $js = $js . '?time=' . time();
            }
            foreach($this->css as &$css) {
                $css = $css . '?time=' . time();
            }
        }
        parent::init();
    }
}
