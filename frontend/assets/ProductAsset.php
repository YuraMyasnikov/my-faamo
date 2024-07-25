<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class ProductAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/product.css',
    ];
    public $js = [
        'js/product.js',
    ];
    public $depends = [
        AppAsset::class,
        NotifyAsset::class,
        MaskAsset::class,
        FavouriteAsset::class
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
