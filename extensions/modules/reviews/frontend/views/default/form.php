<?php

use CmsModule\Reviews\frontend\forms\ReviewsForm;
use frontend\models\shop\Products;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var ReviewsForm $modal
 */

$fio = '';
$email = '';
if (!Yii::$app->user->isGuest){
    $p = \cms\common\models\Profile::find(Yii::$app->user->identity->id)->one();
    $fio = $p->surname . ' ' . $p->name;
    $email = Yii::$app->user->identity->email;
}

?>

<style>
    .rating-area {
        display: flex;
        flex-direction: row-reverse;
    }

    .icon-star {
        margin-right: 0.5rem;
        cursor: pointer;
        width: 1rem;
        height: 1rem;
    }

    .star-block{
        display: flex;
        justify-content: left;
        padding-bottom: 1rem;
        height: 5rem;
        align-items: center;
    }
    .rating-area {
        display: flex;
        flex-direction: row-reverse;
        margin-left: 1rem;
    }

</style>


<script type="text/javascript">
    (function() {
        document.addEventListener('DOMContentLoaded', function(e) {

            let fileGalleryInput = $('#fileGalleryInput');
            let galleryContainer = $('.popup-cols-left.popup-cols-gallary');
            let triggerDiv = $('.review-add-img-item-add');
            triggerDiv.click(function() {
                fileGalleryInput.click();
            });
            fileGalleryInput.change(function() {
                let files = this.files;

                for (let i = 0; i < files.length; i++) {
                    let preview = $('<img>').addClass('review-add-img-item-img');

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        let newItem = $('<div>').addClass('review-add-img-item').append(preview);
                        newItem.append('<span class="review-add-img-item-del"></span>');
                        galleryContainer.prepend(newItem);
                    };
                    reader.readAsDataURL(files[i]);
                }
            });
            galleryContainer.on('click', '.review-add-img-item-del', function() {
                $(this).parent().remove();
            });

            $('.icon-star').hover(function() {
                let starValue = parseInt($(this).attr('id').split('-')[1]);
                $('.icon-star').each(function() {
                    let currentStarValue = parseInt($(this).attr('id').split('-')[1]);
                    if (currentStarValue <= starValue) {
                        $(this).attr('src', '/images/icon-star-black.svg');
                    }
                });
            }, function() {
                // При убирании курсора возвращаем исходное состояние
                $('.icon-star').each(function() {
                    let starValue = parseInt($(this).attr('id').split('-')[1]);
                    let isChecked = $(this).hasClass('checked');
                    $(this).attr('src', isChecked ? '/images/icon-star-black.svg' : '/images/icon-star-gray.svg');
                });
            });

            // Обработчик события click для радиокнопок
            $(document).on('click','.icon-star',function() {
                let value = parseInt($(this).attr('id').split('-')[1]);
                $('input[name="ReviewsForm[grade]"]').val(value)
                $('.icon-star').each(function() {
                    let starValue = parseInt($(this).attr('id').split('-')[1]);
                    if (starValue <= value) {
                        $(this).attr('src', '/images/icon-star-black.svg').addClass('checked');
                    } else {
                        $(this).attr('src', '/images/icon-star-gray.svg').removeClass('checked');
                    }
                });
            });
        });
    })();
</script>

<?php $form = ActiveForm::begin([
    'id' => 'form',
    'action' => Url::to(['/reviews/frontend/default/create']),
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'popup-form'],
]); ?>
<?php
/*            echo $form->field($productReviewsForm, 'product_id', [
                'template' => '{input}',
            ])->hiddenInput();
        */?>
<div class="popup-cols">
    <div class="popup-cols-left">
        <?= $form
            ->field($modal, 'fio', [
                'options' => [
                    'class' => 'popup-form-item'
                ],
                'template' => '<label for="review-fio">Ваше имя: *</label>{input}{error}{hint}',
            ])
            ->textInput([
                'class' => 'radius',
                'value' => $fio,
                'id' => 'review-fio',
                'placeholder' => 'Ваше имя...',
                'required' => true,
                'autocomplete' => 'off'
            ]);
        ?>

        <?= $form
            ->field($modal, 'email', [
                'options' => [
                    'class' => 'popup-form-item'
                ],
                'template' => '<label for="review-email">Электронная почта: *</label>{input}{error}{hint}',
            ])
            ->textInput([
                'class' => 'radius',
                'value' => $email,
                'id' => 'review-email',
                'placeholder' => 'Ваш email...',
                'required' => true,
                'autocomplete' => 'off'
            ]);
        ?>

    </div>
    <div class="popup-cols-right">
        <?= $form
            ->field($modal, 'review_text', [
                'options' => [
                    'class' => 'popup-form-item'
                ],
                'template' => '<label for="review-text">Текст озыва: *</label>{input}{error}{hint}'
            ])
            ->textarea([
                'class'=>'radius',
                'id' => 'review-text',
                'required' => true,
            ]);
        ?>

        <div class="input-group">
            <?php
            $_list = [5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1];
            $_options = [
                'class' => 'rating-area',
                'item' => function ($index, $label, $name, $checked, $value) {
                    $id = 'star-'. $value;
                    $starImage = $checked ? '/images/icon-star-black.svg' : '/images/icon-star-gray.svg';
                    return Html::img($starImage, ['class' => 'icon-star', 'alt' => 'Star', 'id' => $id]);
                },
            ];
            echo $form
                ->field($modal, 'grade', [
                    'options' => [],
                    'template' => '<div class="star-block">{label}{input}</div>{error}{hint}',
                ])
                ->radioList($_list, $_options);
            ?>
        </div>


    </div>
</div>
<div class="popup-cols">
    <div class="popup-cols-left popup-cols-gallary" style="display:flex">

        <div class="review-add-img-item">
            <div class="review-add-img-item-add"><span>+</span>Фото</div>
        </div>
    </div>
    <?= $form->field($modal, 'photo[]', [
        'options' => ['style' => 'display: none;'],
    ])->fileInput([
        'multiple' => true,
        'id' => 'fileGalleryInput',
        'accept' => 'image/jpg, image/jpeg, image/png, image/webp'
    ]); ?>
    <div class="popup-cols-right">
        <button type="submit" class="btn-bg black radius full">Отправить</button>
    </div>
</div>
<?php $form::end(); ?>

</div>









