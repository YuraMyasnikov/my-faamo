<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AlfaProductAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/alfaProduct.js',
    ];
    public $depends = [
        AppAsset::class,
        MaskAsset::class,
        FavouriteAsset::class
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
