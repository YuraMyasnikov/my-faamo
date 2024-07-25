<?php

namespace CmsModule\Infoblocks\admin\assets;

use cms\admin\assets\AngularAsset as AssetsAngularAsset;
use cms\common\components\core\AssetBundle;
use yii\web\YiiAsset;
use cms\common\assets\TinyMCEAsset;
use cms\common\assets\AngularAsset;
use CmsModule\Infoblocks\admin\assets\TinyMCEAsset as AssetsTinyMCEAsset;
use yii\web\AssetBundle as WebAssetBundle;

/**
 * Class ContentAsset
 * @package CmsModule\Infoblocks\admin\assets
 */
class ContentAsset extends WebAssetBundle
{
    /** @var string */
    public $sourcePath;

    /** @var array */
    public $js = [];

    /** @var array */
    public $css = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->sourcePath = __DIR__ . '/public';

        $this->css = [
            'css/form.css'
        ];

        $this->js = [
            'js/translit.js',
            'js/IBlockContentCtrl.js'
        ];
    }

    /** @var array */
    public $depends = [
        AssetsAngularAsset::class,
        AssetsTinyMCEAsset::class,
        YiiAsset::class,
    ];
}
