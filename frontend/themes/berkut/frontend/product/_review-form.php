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
    .rating-area:not(:checked) > input {
        display: none;
    }
    .rating-area:not(:checked) > label {
        display: block;
        margin-left: 8px;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        background: url("data:image/svg+xml,%3Csvg width='29' height='58' viewBox='0 0 29 58' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.3634 30.3658C13.2117 28.5544 15.6845 28.5421 16.5495 30.3449L19.2564 35.9867C19.5962 36.695 20.2456 37.1866 20.997 37.3045L27.0105 38.2479C28.921 38.5476 29.6752 40.998 28.2885 42.4L24.0019 46.7338C23.4487 47.2931 23.1961 48.1009 23.3267 48.8926L24.3596 55.1492C24.6875 57.1354 22.6887 58.652 20.9756 57.7168L15.5259 54.742C14.8439 54.3697 14.0295 54.3718 13.3492 54.7476L8.03963 57.6809C6.32625 58.6275 4.31657 57.1105 4.64537 55.1188L5.67326 48.8926C5.80394 48.1009 5.55128 47.2931 4.99807 46.7338L0.711473 42.4C-0.675215 40.998 0.0790507 38.5476 1.98955 38.2479L7.98898 37.3067C8.7482 37.1876 9.40269 36.6871 9.73946 35.9681L12.3634 30.3658Z' fill='%23FBB03B'/%3E%3Cpath d='M12.3634 1.36577C13.2117 -0.445575 15.6845 -0.457906 16.5495 1.3449L19.2564 6.98673C19.5962 7.69503 20.2456 8.18664 20.997 8.30453L27.0105 9.24792C28.921 9.54764 29.6752 11.998 28.2885 13.4L24.0019 17.7338C23.4487 18.2931 23.1961 19.1009 23.3267 19.8926L24.3596 26.1492C24.6875 28.1354 22.6887 29.652 20.9756 28.7168L15.5259 25.742C14.8439 25.3697 14.0295 25.3718 13.3492 25.7476L8.03963 28.6809C6.32625 29.6275 4.31657 28.1105 4.64537 26.1188L5.67326 19.8926C5.80394 19.1009 5.55128 18.2931 4.99807 17.7338L0.711473 13.4C-0.675215 11.998 0.0790507 9.54764 1.98955 9.24792L7.98898 8.30673C8.7482 8.18762 9.40269 7.68711 9.73946 6.96807L12.3634 1.36577Z' fill='%23C5C5C5'/%3E%3C/svg%3E");
        cursor: pointer;
        background-size: 20px;
    }
    .rating-area:not(:checked) > label:before {
        content: '';
    }
    .rating-area > input:checked ~ label {
        background-position: 0 -20px;
    }
    .rating-area:not(:checked) > label:hover,
    .rating-area:not(:checked) > label:hover ~ label {
        background-position: 0 -20px;
    }
</style>

<script type="text/javascript">
(function() {
    document.addEventListener('DOMContentLoaded', function(e) {
        const fileGalleryWrapper = document.querySelector('.file-gallery__wrapper');
        const fileGalleryInput   = document.querySelector('.file-gallery__input');

        fileGalleryWrapper.addEventListener('click', function(e){
            const target = e.target.closest('.file-gallery__previews-item-remove');
            if(!target) {
                return;
            }

            let id = target.dataset.id;
            let previewItem = document.querySelector('#file-gallery__previews-item-'+id);
            if(previewItem) {
                previewItem.remove();
            }

            let _dataTransfer = new DataTransfer();
            for(let i = 0; i < fileGalleryInput.files.length; i++) {
                if(i != id) {
                    _dataTransfer.items.add(fileGalleryInput.files[i]);
                }
            }
            document.querySelector('#productreviewsform-photo').files = _dataTransfer.files;
        });

        fileGalleryInput.addEventListener('change', (event) => {
            let result   = fileGalleryWrapper.querySelector('#foo');
            $('.file-gallery__previews-item').remove();
            
            for(let i = 0; i < event.target.files.length; i++) {
                let li = document.createElement('li');
                li.id = 'file-gallery__previews-item-'+i;
                li.classList.add('file-gallery__previews-item');
                li.innerHTML = '<div class="file-gallery__close file-gallery__previews-item-remove" data-id="'+i+'"></div>' +
                    '<img src="'+ URL.createObjectURL(event.target.files[i]) +'" />';
                result.prepend(li);
            }
        });

    });    
})();
</script>

<div class="modal-title">Написать отзыв</div>
<div class="modal-close" data-micromodal-close></div>
<div class="modal-content">
    
    <?php $form = ActiveForm::begin([
        'id' => 'review-form',
        'action' => Url::to(['product/add-review', 'code' => $product->code]),
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
        <?php 
        
            echo $form->field($productReviewsForm, 'email', [
                'template' => '{input}',
            ])->hiddenInput();

            echo $form->field($productReviewsForm, 'product_id', [
                'template' => '{input}',
            ])->hiddenInput();
        ?>
        <div class="columns">
            <div class="column col-6 sm-col-12">
                
                <?= $form
                    ->field($productReviewsForm, 'fio', [
                        'options' => [
                            'class' => 'input-group'
                        ],
                        'template' => '{input} 
                            <div class="input-icon">
                                <svg width="20" height="20">
                                    <use xlink:href="#icon-input-user"></use>
                                </svg>
                            </div>
                            {error}{hint}',
                    ])
                    ->textInput([
                        'class' => 'input input--icon', 
                        'placeholder' => 'Ваше имя...'
                    ]);
                ?>
                
                <div class="input-group">   
                    <?php
                        $_list = [5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1];
                        $_options = [
                            'class' => 'rating-area',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $id = 'star-'. $value;
                                return Html::radio($name, $checked, ['value' => $value, 'id' => $id]) .
                                    Html::label('', $id, ['class' => 'icon-star']);
                            },
                        ];
                        echo $form
                            ->field($productReviewsForm, 'grade', [
                                'options' => [],
                                'template' => '<div class="star-block">{label}{input}</div>{error}{hint}',
                            ])
                            ->radioList($_list, $_options);
                    ?>
                </div>
            </div>
            <div class="column col-6 sm-col-12">
                <?= $form
                    ->field($productReviewsForm, 'review_text', [
                        'options' => [
                            'class' => 'input-group input-group--textarea'
                        ],
                        'template' => '{input}{error}{hint}'
                    ])
                    ->textarea([
                        'class'=>'input textarea', 
                        'placeholder' => "Текст сообщения. Необязательно..."
                    ]);
                ?>
            </div>
            <div class="column col-12">
                <div class="input-group file-gallery__wrapper">
                    <?php
                        echo $form
                        ->field($productReviewsForm, 'photo[]',[
                            'options' => ['class' => 'input-group'],
                            'template' => '<ul id="foo" class="file-gallery">   
                                <li>
                                    {input}
                                    <div class="file-gallery__add">
                                        <svg width="14" height="14">
                                            <use xlink:href="#icon-plus"></use>
                                        </svg> 
                                        ФОТО
                                    </div>
                                </li>
                            </ul>
                            {error}
                            {hint}'
                        ])->fileInput([
                            'multiple' => true, 
                            'class' => 'file-gallery__input',
                            'accept' => 'image/jpg, image/jpeg, image/png, image/webp'
                        ]); 
                    ?>

                    <!-- <ul class="file-gallery">
                        <li>
                            <div class="file-gallery__close"></div>
                            <img src="temp/element-1.jpg" alt="">
                        </li>
                        <li>
                            <div class="file-gallery__close"></div>
                            <img src="temp/element-2.jpg" alt="">
                        </li>
                        <li>
                            <input type="file" class="file-gallery__input">
                            <div class="file-gallery__add">
                                <svg width="14" height="14">
                                    <use xlink:href="#icon-plus"></use>
                                </svg>
                                ФОТО
                            </div>
                        </li>
                    </ul> -->
                </div>
            </div>
        </div>
        <ul class="form-footer">
            <li>
                Нажимая кнопку «Отправить», вы соглашаетесь с <a href="<?= \yii\helpers\Url::to(['site/politics'])?>">политикой
                конфиденциальности</a>
            </li>
            <li>
                <button type="submit" class="btn">Отправить отзыв</button>
            </li>
        </ul>
    <?php $form::end(); ?>

</div>