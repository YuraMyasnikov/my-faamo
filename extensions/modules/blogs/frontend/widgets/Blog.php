<?php
namespace CmsModule\Blogs\frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use yii\base\Widget;

class Blog extends Widget
{
    const BLOG_PATH = 'blog';

    public $blogs;

    public function run()
    {

        return $this->render('blog/index', ['blogs' => $this->blogs]);
    }
}