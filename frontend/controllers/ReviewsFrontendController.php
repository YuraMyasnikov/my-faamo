<?php

namespace frontend\controllers;

use CmsModule\Infoblocks\models\Infoblock;
use CmsModule\Reviews\frontend\forms\ReviewsForm;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\web\UploadedFile;


class ReviewsFrontendController extends Controller
{
    private int $countOnPage = 10;

    protected function statisticsByStars(): array
    {
        $sql = 'SELECT `grade`, COUNT(`grade`) as `count` 
                FROM __iblock_content_reviews 
                WHERE 
                    (`active` = 1) AND 
                    (`grade` >= 1 AND `grade` <= 5) 
                GROUP BY `grade` 
                ORDER BY `grade` DESC';
        $result = \Yii::$app->db->pdo->query($sql)->fetchAll(\PDO::FETCH_KEY_PAIR);
        $totalSum = 0;
        $totalCount = 0;
        foreach ($result as $grade => $count) {
            $totalSum += ($grade * $count);
            $totalCount += $count;
        }
        $avg = $totalCount > 0 ? $totalSum / $totalCount : 0;

        return [
            $avg,
            $result[5] ?? 0,
            $result[4] ?? 0,
            $result[3] ?? 0,
            $result[2] ?? 0,
            $result[1] ?? 0
        ];
    }

    protected function getReviewsListByPage(int $page = 1): array
    {
        $offset = ($page * $this->countOnPage) - $this->countOnPage;

        /** @var ActiveRecord $infoBlock */
        $infoBlock = Infoblock::byCode(ReviewsForm::TYPE);
        $reviews = $infoBlock::find()
            ->where(['active' => true])
            ->andWhere(['<', 'grade', 6])
            ->limit($this->countOnPage)
            ->offset($offset)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        return $reviews;
    }

    protected function getReviewsTotalCount(): int
    {
        $sql = 'SELECT COUNT(`id`) as `count` 
                FROM __iblock_content_reviews 
                WHERE 
                    (`active` = 1) AND 
                    (`grade` >= 1 AND `grade` <= 5)';
        $result = Yii::$app->db->pdo->query($sql)->fetch(\PDO::FETCH_ASSOC);
        return (int) $result['count'] ?? 0;
    }

    public function actionList(): Response|string|null
    {
        $page = (int) Yii::$app->request->get('page', 1);
        if($page < 1) {
            return '';
        }

        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $totalCount = $this->getReviewsTotalCount();
            $countPages = round($totalCount / $this->countOnPage);

            return $this->asJson([
                'total_count' => $totalCount,
                'page' => $page,
                'countPages' => $countPages,
                'is_has_next_page' => $countPages > $page,
                'reviews' => $this->renderPartial('reviews', [
                    'reviews' => $this->getReviewsListByPage($page)
                ])
            ]);
        }

        $reviews = $this->getReviewsListByPage($page);
        return $this->renderPartial('reviews', [
            'reviews' => $reviews,
        ]);
    }

    public function actionIndex(): string
    {
        $reviewForm = ReviewsForm::buildForm();
        $reviews = $this->getReviewsListByPage();
        [$avgStarsCount, $fiveStarsCount, $fourStarsCount, $threeStarsCount, $twoStarsCount, $oneStarsCount] = $this->statisticsByStars();
        
        $page = 1;
        $totalCount = $this->getReviewsTotalCount();
        $countPages = round($totalCount / $this->countOnPage);

        return $this->render('index', [
            'isHasNextPage' => $countPages > $page,
            'reviewForm' => $reviewForm,
            'reviews' => $reviews,
            'reviewsCount' => $totalCount, 
            'avgStarsCount' => $avgStarsCount,
            'oneStarsCount' => $oneStarsCount,
            'twoStarsCount' => $twoStarsCount,
            'threeStarsCount' => $threeStarsCount,
            'fourStarsCount' => $fourStarsCount,
            'fiveStarsCount' => $fiveStarsCount,
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
