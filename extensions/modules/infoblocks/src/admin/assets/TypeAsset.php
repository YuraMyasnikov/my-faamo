<?php

namespace CmsModule\Infoblocks\admin\assets;

use cms\assets\AngularAsset;
use cms\common\components\core\AssetBundle;
use yii\web\AssetBundle as WebAssetBundle;

/**
 * Class TypeAsset
 * @package CmsModule\Infoblocks\admin\assets
 */
class TypeAsset extends WebAssetBundle
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
            'js/IBlockTypeCtrl.js'
        ];
    }
}
