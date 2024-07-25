<?php

namespace CmsModule\Reviews\frontend\controllers;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Reviews\frontend\forms\ReviewsForm;
use CmsModule\Reviews\model\Reviews;
use Yii;
Use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Session;
use yii\web\UploadedFile;

class DefaultController extends Controller
{
   /* public function actionIndex()
    {
        $reviewForm = ReviewsForm::buildForm();
        $infoblock = Infoblock::byCode(ReviewsForm::TYPE);
        $reviews = $infoblock::find()->where(['active' => true])->andWhere(['<', 'grade', 6])->orderBy(['created_at' => SORT_DESC])->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $infoblock::find()->where(['active' => true])->andWhere(['<', 'grade', 6])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $oneStarsCount = $infoblock::find()->where(['active' => true])->andWhere(['grade' => 1])->count();
        $twoStarsCount = $infoblock::find()->where(['active' => true])->andWhere(['grade' => 2])->count();
        $threeStarsCount = $infoblock::find()->where(['active' => true])->andWhere(['grade' => 3])->count();
        $fourStarsCount = $infoblock::find()->where(['active' => true])->andWhere(['grade' => 4])->count();
        $fiveStarsCount = $infoblock::find()->where(['active' => true])->andWhere(['grade' => 5])->count();
        $avgStarsCount = $infoblock::find()->where(['active' => true])->average('grade') ?? 0;

        return $this->render('index', [
            'reviewForm' => $reviewForm,
            'reviews' => $reviews,
            'avgStarsCount' => $avgStarsCount,
            'oneStarsCount' => $oneStarsCount,
            'twoStarsCount' => $twoStarsCount,
            'threeStarsCount' => $threeStarsCount,
            'fourStarsCount' => $fourStarsCount,
            'fiveStarsCount' => $fiveStarsCount,
            'dataProvider' => $dataProvider
        ]);
    }*/

    public function actionIndex()
    {
        $modal = Yii::$container->get(ReviewsForm::class);
        $infoblock = Infoblock::byCode(ReviewsForm::TYPE);
        $query = $infoblock::find()->where(['active' => true])->andWhere(['<', 'grade', 6])->orderBy(['created_at' => SORT_DESC]);

        $query2 = clone $query;
        $totalCount = $query2->count();
        $pages = new Pagination([
            'totalCount' => $totalCount,
            'defaultPageSize' => 10,
            'pageSizeParam' => true,
            'forcePageParam' => false,
        ]);

        $countGrades = Yii::$app->db->createCommand("
            SELECT ROUND(AVG(grade),0) as avg, COUNT(id) as count
            FROM __iblock_content_reviews 
            WHERE `active` = 1")->queryAll();

        $count = Yii::$app->db->createCommand("
            SELECT COUNT(`grade`) AS count, grade
            FROM __iblock_content_reviews 
            WHERE `active` = 1
            GROUP BY `grade`
            ORDER BY `grade` ASC")->queryAll();

        $reviews = $query->offset($pages->offset)->limit($pages->pageSize)->all();
        return $this->render('index', [
            'modal' => $modal,
            'reviews' => $reviews,
            'totalCount' => $totalCount,
            'pages' =>$pages,
            'countGrades' => $countGrades,
            'count' => $count
        ]);
    }

    public function actionCreate(Session $session)
    {

        $reviewForm = Yii::$container->get(ReviewsForm::class);
        
        if ($reviewForm->load(Yii::$app->request->post()) && $reviewForm->validate()) {
            $reviewForm->photo = UploadedFile::getInstances($reviewForm, 'photo');
            if ($reviewForm->save()) {
                $session->setFlash('success', 'Отзыв успешно оставлен');
            }
        } else {
            $session->setFlash('error', 'Произошла ошибка');
        }

        $this->redirect('index');
    }
}
