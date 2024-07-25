<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class NotifyAsset extends AssetBundle
{
    public $jsOptions = ['position' => View::POS_HEAD];
    public $sourcePath = '@bower/noty/lib';
    public $js = [
        'noty.min.js',
    ];
    public $css = [
        'noty.css',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];

    public function init() {
        if(!YII_ENV_PROD) {
            $time = time();
            foreach($this->js as &$js) {
                $js = $js . '?time=' . $time;
            }
            foreach($this->css as &$css) {
                $css = $css . '?time=' . time();
            }
        }
        parent::init();
    }
}
