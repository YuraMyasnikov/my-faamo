<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<?php if ($faqs) { ?>
    <?php foreach ($faqs as $faq):?>
    <div class="js-accordion-item faq-item">
        <div class="js-accordion-header faq-item-header">
            <h4><?= $faq->faq_quest?></h4>
        </div>
        <div class="js-accordion-body faq-item-body" style="display: none;">
            <?= $faq->faq_answer?>
        </div>
    </div>
    <?php endforeach;?>
<?php } ?>

