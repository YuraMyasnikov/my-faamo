<?php

namespace CmsModule\Infoblocks\admin\assets\bower;

use yii\web\AssetBundle;
use yii\web\View;

class TinyMCEAsset extends AssetBundle
{
    public $jsOptions = ['position' => View::POS_HEAD];
    public $sourcePath = '@bower/tinymce-dist';
    public $js = [
        'tinymce.min.js',
    ];
    public $css = [
        'skins/lightgray/skin.min.css',
    ];
}
