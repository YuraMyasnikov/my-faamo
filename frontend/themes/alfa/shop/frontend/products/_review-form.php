<?php

use CmsModule\Reviews\frontend\forms\ReviewsForm;
use frontend\models\shop\Products;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var ReviewsForm $reviewForm  */
/** @var Products $product */
/** @var \frontend\models\shop\ProductReviewsForm $productReviewsForm */
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
            let myFiles = [];
            let count = 0;
            triggerDiv.click(function() {
                fileGalleryInput.click();

            });
            fileGalleryInput.change(function() {

                let files = this.files;
                myFiles.push(files);
                if (myFiles.length > 1){
                    console.log(11,myFiles)
                    myFiles.pop()
                }
                console.log(myFiles);

                for (let i = 0; i < files.length; i++) {
                    let preview = $('<img>').addClass('review-add-img-item-img');

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        let newItem = $('<div>').addClass(`review-add-img-item item-${count}`).append(preview);
                        newItem.append('<span class="review-add-img-item-del"></span>');
                        galleryContainer.prepend(newItem);
                        count ++;
                    };

                    reader.readAsDataURL(myFiles[0][0]);
                }
                if ( $('.item-1') ){
                    $('.item-0').remove();
                    myFiles.pop()
                }
                if( $('.item-0')!== 0 ){
                    count = 0;
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
                        $(this).addClass('active');
                        // $(this).attr('src', '/images/icon-star-black.svg');
                    }
                });
            }, function() {
                // При убирании курсора возвращаем исходное состояние
                $('.icon-star').each(function() {
                    let starValue = parseInt($(this).attr('id').split('-')[1]);
                    let isChecked = $(this).hasClass('checked');
                    if(!isChecked) {
                        $(this).removeClass('active');
                    }
                    // $(this).attr('src', isChecked ? '/images/icon-star-black.svg' : '/images/icon-star-gray.svg');
                });
            });

            // Обработчик события click для радиокнопок
            $(document).on('click','.icon-star',function() {
                let value = parseInt($(this).attr('id').split('-')[1]);
                $('#grade_value').val(value)
                $('.icon-star').each(function() {
                    let starValue = parseInt($(this).attr('id').split('-')[1]);
                    if (starValue <= value) {
                        $(this).addClass('active checked');
                    } else {
                        $(this).removeClass('active checked');
                    }
                });
            });
        });
    })();
</script>

    
    <?php $form = ActiveForm::begin([
        'id' => 'review-form',
        'action' => Url::to(['/product/add-review', 'code' => $product->code]),
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'popup-form'],
    ]); ?>
    <?php
            echo $form->field($productReviewsForm, 'product_id', [
                'template' => '{input}',
            ])->hiddenInput();
        ?>

    <div class="popup-cols">
        <div class="popup-cols-left">
            <?= $form
                ->field($productReviewsForm, 'fio', [
                    'options' => [
                        'class' => 'popup-form-item'
                    ],
                    'template' => '<label for="review-fio">Ваше имя: *</label>{input}{error}{hint}',
                ])
                ->textInput([
                    'class' => 'radius',
                    'id' => 'review-fio',
                    'placeholder' => 'Ваше имя...',
                    'required' => true,
                    'autocomplete' => 'off'
                ]);
            ?>

            <?= $form
                ->field($productReviewsForm, 'email', [
                    'options' => [
                        'class' => 'popup-form-item'
                    ],
                    'template' => '<label for="login-mail">Электронная почта: *</label>{input} 
                        <div class="input-icon">
                            <svg width="20" height="20">
                                <use xlink:href="#icon-input-user"></use>
                            </svg>
                        </div>
                        {error}{hint}',
                ])
                ->textInput([
                    'class' => 'radius',
                    'placeholder' => 'Ваш email...'
                ]);
            ?>
        </div>
        <div class="popup-cols-right">
            <?= $form
                ->field($productReviewsForm, 'review_text', [
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
                $_list = array_reverse([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1]);
                // $_options = [
                //     'class' => 'rating-area',
                //     'item' => function ($index, $label, $name, $checked, $value) {
                //         $id = 'star-'. $value;
                //         $starImage = $checked ? '/images/icon-star-black.svg' : '/images/icon-star-gray.svg';
                //         return Html::img($starImage, ['class' => 'icon-star', 'alt' => 'Star', 'id' => $id]);
                //     },
                // ];

                $_tmp = '';
                foreach($_list as $s) {
                        $starImage = $productReviewsForm->grade>=1 && $s >= $productReviewsForm->grade ? '/images/icon-star-black.svg' : '/images/icon-star-gray.svg';
                        $isActive  = $productReviewsForm->grade>=1 && $s >= $productReviewsForm->grade;
                        $isActiveClass = $isActive ? 'active checked' : '';
                        $_tmp .=  '<div id="star-'.$s.'" class="popup-cols-right-stars icon-star'.$isActiveClass.'"></div>';
                        // $_tmp .=  Html::img($starImage, ['class' => 'icon-star', 'alt' => 'Star', 'id' => 'star-'. $s]);
                }
                $_tmp .= '';

                echo $form
                    ->field($productReviewsForm, 'grade', [
                        'options' => [],
                        'template' => '<div class="popup-cols-right-stars-wrp"><span>Ваша оценка:</span>'.$_tmp.'{input}</div>{error}{hint}',
                    ])
                    ->hiddenInput(['id' => 'grade_value']);
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
        <?= $form->field($productReviewsForm, 'photo', [
            'options' => ['style' => 'display: none;'],
        ])->fileInput([
            'id' => 'fileGalleryInput',
            'accept' => 'image/jpg, image/jpeg, image/png, image/webp'
        ]); ?>

        <div class="popup-cols-right">
            <button type="submit" class="btn-bg black radius full">Отправить</button>
        </div>
    </div>

    <?php $form::end(); ?>

</div>