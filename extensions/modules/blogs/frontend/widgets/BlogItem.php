<?php
namespace CmsModule\Blogs\frontend\widgets;

use CmsModule\Infoblocks\models\Infoblock;
use frontend\models\shop\Products;
use yii\base\Widget;
use yii\db\Expression;

class BlogItem extends Widget
{
       const BLOG_CODE = 'blog';
       public $blog_id;


    public function run()
    {
        $blogsInfoblock = Infoblock::byCode(self::BLOG_CODE);

        $blog = $blogsInfoblock::find()->where(['active' => true, 'code' => $this->blog_id])->one();

        $products = Products::find()->where(['active' => true])->andWhere(['>', 'image_id', 0])->limit(3)->orderBy(new Expression('rand()'))->all();

        return $this->render('blog-item/index', ['blog' => $blog, 'products' => $products]);
    }
}