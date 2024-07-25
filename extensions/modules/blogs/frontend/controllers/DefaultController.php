<?php

namespace CmsModule\Blogs\frontend\controllers;

use CmsModule\Blogs\frontend\forms\Blog;
use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Reviews\frontend\forms\ReviewsForm;
use CmsModule\Shop\common\models\Products;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Session;
use yii\web\UploadedFile;

class DefaultController extends Controller
{


    public function actionIndex()
    {
        $infoblock = Infoblock::byCode(Blog::TYPE);
        $query = $infoblock::find()->where(['active' => true])->orderBy(['created_at' => SORT_DESC]);

        $query2 = clone $query;
        $totalCount = $query2->count();
        $pages = new Pagination([
            'totalCount' => $totalCount,
            'defaultPageSize' => 10,
            'pageSizeParam' => true,
            'forcePageParam' => false,
        ]);

        $blogs = $query->offset($pages->offset)->limit($pages->pageSize)->all();
        return $this->render('index', [
            'pages' => $pages,
            'blogs' => $blogs
        ]);
    }

    public function actionItem($code)
    {
        $blog_id = $code;

        return $this->render('item', ['blog_id' => $blog_id]);
    }

}
