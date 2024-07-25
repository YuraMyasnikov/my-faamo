<?php

namespace CmsModule\Infoblocks\admin\assets;

use CmsModule\Infoblocks\admin\assets\bower\TinyMCEAsset as BowerTinyMCEAsset;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class TinyMCEAsset
 * @package cms\common\assets
 */
class TinyMCEAsset extends AssetBundle
{
    public $jsOptions = ['position' => View::POS_HEAD];

    public $sourcePath = '@bower/angular-ui-tinymce';

    public $js = [
        'src/tinymce.js',
    ];

    public $depends = [
        BowerTinyMCEAsset::class
    ];
}
