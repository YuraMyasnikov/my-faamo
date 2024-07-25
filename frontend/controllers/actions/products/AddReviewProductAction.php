<?php 

namespace frontend\controllers\actions\product;

use frontend\models\shop\Products;
use yii\base\Action;
use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;

class AddReviewProductAction extends Action 
{
    public function run($code): void
    {
        $reviewForm = new \frontend\models\shop\ProductReviewsForm();
        if($reviewForm->load(\Yii::$app->request->post()) && $reviewForm->validate()) {
            
            $product = Products::findOne($reviewForm->product_id);
            $reviewForm->photo = UploadedFile::getInstances($reviewForm, 'photo');

            $model = $this->infoblock();
            $model->name = 'Новый отзыв от ' . $reviewForm->fio;
            $model->code = Yii::$app->security->generateRandomString(16);
            $model->client_name = $reviewForm->fio;
            $model->email = $reviewForm->email;
            $model->product_id = $reviewForm->product_id;
            if($product) {
                $model->product_title = $product->name;
            }
            $model->product_link = Url::to(['admin/shop/products/update', 'id' => $reviewForm->product_id], true);
            $model->text = $reviewForm->review_text;
            $model->grade = $reviewForm->grade;
            $model->active = false;
            
            if ($model->save() && $reviewForm->uploadPhoto($model)) {
                Yii::$app->session->setFlash('success', 'Ваш отзыв будет опубликован после модерации');
            } else {
                Yii::error($model->getErrors());
            }

            Yii::$app->response->redirect(Url::to(['product/view', 'code' => $code]));
        } else {
            // TODO: Has errors
            Yii::error($reviewForm->getErrors());
            Yii::$app->response->redirect(Url::to(['product/view', 'code' => $code]));
        }
    }

    protected function infoblock(): Infoblock|null 
    {
        $infoblock = Infoblock::byCode('product_reviews');
        if(is_callable([$infoblock::class, 'create'])) {
            return call_user_func([$infoblock::class, 'create']);
        }
        return null;
    }
}