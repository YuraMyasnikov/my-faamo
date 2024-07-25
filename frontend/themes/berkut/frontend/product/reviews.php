<?php 

/** 
 * @var \yii\data\ActiveDataProvider $productReviewsDataProvider
 */

use CmsModule\Infoblocks\models\Infoblock;
use yii\helpers\Html;
?>

<?php foreach($productReviewsDataProvider->getModels() as $review) {?>
    <?php /** @var Infoblock $review */ ?>
    <?= $this->render('_review', ['review' => $review]) ?>
<?php }?>    

