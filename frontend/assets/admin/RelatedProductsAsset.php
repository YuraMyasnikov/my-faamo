<?php

namespace frontend\assets\admin;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;


class RelatedProductsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/admin/related_products.js',
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
