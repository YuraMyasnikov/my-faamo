$(document).ready(function() {
    const skus    = $('#data').data('skus');    
    const options = $('#data').data('options');    
    const findDefaultSku = (skus) => {
        for(const key in skus) {
            if(skus[key]['is_filled']) {
                return skus[key];
            }
        }
        return null;
    };
    const findAllSizesForCurrentColor = (currentColor, skus) => {
        let sizes = [];
        for(const i in skus) {
            if(!skus[i]?.options?.color || !skus[i]?.options?.size) {
                continue;
            }
            if(!Array.isArray(skus[i].options.color) || !skus[i].options.color.includes(currentColor)) {
                continue;
            }
            for(const j in skus[i].options.size) {
                sizes.push({skuId: i, sizeCode: skus[i].options.size[j]});
            }
        }
        return sizes.filter(function (v, i, self) {
            return i == self.indexOf(v);
        });
    };
    const resetSizes = () => {
        let sizeTitle = $('.dropdown .dropdown-menu li')
            .parents('.dropdown')
            .find('span');

        $(sizeTitle).text($(sizeTitle).data('defaultTitle'));

        $('.dropdown .dropdown-menu li')
            .parents('.dropdown')
            .find('input')
            .attr('value', null);
    };
    const renderSizes = (colorCode) => {
        let allSizesForCurrentColor = findAllSizesForCurrentColor(colorCode, skus);
        if(!Array.isArray(allSizesForCurrentColor)) {
            $('.select.size>ul').html('');    
        }
        let lis = [];
        for(const key in options?.size?.options) {
            const code = options.size.options[key]?.code;
            const name = options.size.options[key]?.name;
            const isAllSizesForCurrentColorContainColorCode = allSizesForCurrentColor.filter((obj) => obj?.sizeCode == code);
            if(!isAllSizesForCurrentColorContainColorCode.length) {
                continue;
            }
            lis.push('<li id="'+isAllSizesForCurrentColorContainColorCode[0].skuId+'" data-code="'+isAllSizesForCurrentColorContainColorCode[0].sizeCode+'">' + name + '</li>');
        }
        $('.select.size>ul').html(lis.join(''));
    };
    let sku = findDefaultSku(skus);
    let currentColorCode = sku['options']['color'][0] || null;
    if(currentColorCode) {
        resetSizes();
        renderSizes(currentColorCode);
    }
    $('.js-color').click((e) => {
        $(document).trigger('color-changed', [$(e.target).data('colorCode')]);
    });
    $(document).on('color-changed', function(e, colorCode) {
        resetSizes();
        renderSizes(colorCode);
    });
    // console.log(skus, options, sku, currentColorCode);
});

$(document).ready(function() {
    $(".js-btn-add-basket").on('click', function() {
        let skuId = $('.dropdown .dropdown-menu li').parents('.dropdown').find('input').attr('value');
        if(!skuId) {
            return new Noty({
                type: 'error',
                layout: 'topRight',
                text: 'Выбирите нужный размер',
                timeout: 3000,
            }).show();
        }
        const data = {
            'sku_id': skuId,
            'count': 1
        };
        $.get('/api/shop/basket/add-product', data, function(response) {
            new Noty({
                type: 'success',
                layout: 'topRight',
                text: 'Товар добавлен',
                timeout: 3000,
            }).show();
            let val = $('.header-num').text() * 1;
            $('.header-num').text(val+1);
            $('.mobile-item-cart-num').text(val+1);
        });
    });
})

//-----------------------
// function Sku(skuData, buttonIncrement, buttonDecrement, countInput, symWrapper) {
//     this.skuId = skuData.skuId;
//     this.skuName = skuData.skuName;
//     this.skuPrice = skuData.skuPrice;
//     this.skuCount = skuData.skuCount;
//     this.skuSum = skuData.skuSum;
//     this.skuRemnants = skuData.skuRemnants;
//     this.buttonIncrement = buttonIncrement;
//     this.buttonDecrement = buttonDecrement;
//     this.countInput = countInput;
//     this.sumWrapper = symWrapper;

//     let self = this;
//     $(buttonIncrement).click(function() {
//         self.incrementCount();
//     });
//     $(buttonDecrement).click(function() {
//         self.decrementCount();
//     });
// }

// Sku.prototype.incrementCount = function(trigger = true) {
//     if(this.skuCount < this.skuRemnants) {
//         this.skuCount += 1;
//         this.setCount(this.skuCount, trigger);
//     }
// };

// Sku.prototype.decrementCount = function(trigger = true) {
//     if(this.skuCount > 0) {
//         this.skuCount -= 1;
//         this.setCount(this.skuCount, trigger);
//     }
// };

// Sku.prototype.setCount = function(count, trigger = true) {
//     $(this.countInput)
//         .prop('readonly', false)
//         .val(count)
//         .prop('readonly', true);
    
//     if(trigger) {
//         $(document).trigger('count.changed');
//     }
// };

// Sku.prototype.setSum = function(sum) {
//     $(this.sumWrapper).html(new Intl.NumberFormat("ru-RU").format(sum));
// };

// Sku.prototype.getSum = function() {
//     if(!this.skuCount) {
//         return 0;
//     }
//     if(!this.skuPrice) {
//         return 0;
//     }
//     return this.skuCount * this.skuPrice;
// };

// Sku.prototype.getCandidateSchema = function() {
//     return {
//         sku_id: this.skuId,
//         count: this.skuCount
//     };
// };

// Sku.prototype.updatePrice = function(price) {
//     this.skuPrice = price;
//     if(this.skuCount) {
//         this.skuSum = this.skuCount * this.skuPrice;
//     } else {
//         this.skuSum = 0;
//     }
//     this.setSum(this.skuSum);
// };

// (function() {

//     let skuMapName = function(skuId) {
//         return 'sku' + skuId;
//     };
//     let map = {};
//     let cartThresholdsMap = {};
//     let totalSumNode = $('.js-cart-total');

//     $(document).on("skus.add-to-cart", function(e) {
//         // alert("Товар добавлен в корзину");
//         let basketCounter = $('.basket-counter').text() * 1;
//         $('.basket-counter').text(basketCounter + 1);
        
//         new Noty({
//             type: 'success',
//             layout: 'topRight',
//             text: 'Товар добавлен в корзину',
//             timeout: 3000,
//         }).show();
//     });

//     $(document).on('recalculated', function(e, activePriceType) {
//         Object.keys(cartThresholdsMap).forEach(priceType => {
//             if(priceType !== activePriceType) {
//                 $(cartThresholdsMap[priceType]).removeClass('active');
//             } else {
//                 $(cartThresholdsMap[priceType]).addClass('active');
//             }
//         });

//         let totalSum = 0;
//         Object.keys(map).forEach(skuId => {
//             totalSum += map[skuId].getSum();
//         });
//         $(totalSumNode).text(new Intl.NumberFormat("ru-RU").format(totalSum));
//     });

//     $(document).on('count.changed', function(e) {
//         let skuCandidates = [];
//         Object.keys(map).forEach(skuId => {
//             skuCandidates.push(map[skuId].getCandidateSchema());
//         });
//         $.post('/api/shop/basket/calculate', { skus: skuCandidates }, function(response) {
//             response = JSON.parse(response);
//             let priceType = response.price_type;

//             Object.keys(response.skus).forEach(skuId => {
//                 if(map[skuMapName(skuId)]) {

//                     map[skuMapName(skuId)].updatePrice(response.skus[skuId]['price']);
//                 }
//             }); 

//             $(document).trigger('recalculated', [priceType]);
//         });
//     });

//     $('.js-cart-item').each(function(i, el) {
//         let skuData = $(el).data(); 
//         let 
//             buttonIncrement = $('.js-item-plus', el),
//             buttonDecrement = $('.js-item-minus', el),
//             countInput      = $('.js-item-count', el),
//             sumWrapper      = $('.js-item-price', el);

//         map[skuMapName(skuData.skuId)] = new Sku($(el).data(), buttonIncrement, buttonDecrement, countInput, sumWrapper);
//     });
    
//     $('.cart-thresholds__item').each(function(i, el) {
//         cartThresholdsMap[$(el).data('type')] = el;
//     });
    
//     $('.js-cart-all-minus').click(function() {
//         Object.keys(map).forEach(skuId => {
//             map[skuId].decrementCount(false);
//         });
//         $(document).trigger('count.changed');
//     });
    
//     $('.js-cart-all-plus').click(function() {
//         Object.keys(map).forEach(skuId => {
//             map[skuId].incrementCount(false);
//         });
//         $(document).trigger('count.changed');
//     });

//     /** 
//      * Reviews
//      */
//     let nextReviewsPageNumber = 2;
//     let nextReviewsButton = $('.js-load-more-reviews');
//     let nextReviewsButtonData = $(nextReviewsButton).data();
//     $(nextReviewsButton).click(function(e) {
//         $(nextReviewsButton).text(nextReviewsButtonData.awaitText);
//         let fetchUrl = nextReviewsButtonData.fetchUrl + '?page='+nextReviewsPageNumber++;

//         $.get(fetchUrl, function(data, status) {
//             $(nextReviewsButton).text(nextReviewsButtonData.text);

//             if(!data.isHasNextPage) {
//                 $(nextReviewsButton).hide();
//                 return;
//             }
//             $('.comments-list').append(data.reviews);
//         });
//     });

//     /**
//      * Cart
//      */
//     $('#add-to-cart').click(function(e) {
//         let url = $(this).data('url');
//         let skuCandidates = [];
//         Object.keys(map).forEach(skuId => {
//             let sku = map[skuId].getCandidateSchema();
//             if(sku['count'] > 0) {
//                 skuCandidates.push(sku);
//             } 
//         });
//         if(!skuCandidates.length) {
//             new Noty({
//                 type: 'error',
//                 layout: 'topRight',
//                 text: 'Добавте хотябы один товар',
//                 timeout: 3000,
//             }).show();

//             return;
//         }

//         $.post(url, { skus: skuCandidates }, function(response) {
//             response = JSON.parse(response);
//             let event = jQuery.Event("skus.add-to-cart");
//             $(document).trigger( event );
//         });
//     });

// })();

// (function() {
//     let skuId = 0;
//     let remnants = $('.one-click .value-count').data('remnants');

//     $('#oneclickorderform-phone').mask('+7 000-000-00-00', {
//         placeholder: "+7 ___-___-__-__",
//     });

//     $('.one-click select').change(function() {
//         let option = $("option:selected", this);
//         skuId = $(option).val();
//         remnants = $(option).data('sku-remnants') || 0;

//         $(document).trigger('selected-sku');
//     });

//     $(document).on('selected-sku', function() {
//         let valueinput = $('.one-click .value-count');
//         if($(valueinput).val() > remnants) {
//             $(valueinput).val(remnants);
//         }
//     });

//     $(document).on('click', '.js-plus-count', function() {
//         let val = $('.one-click .value-count').val() * 1;
//         if(val < remnants) {
//             $('.one-click .value-count').val(val+1)
//         }
//     });

//     $(document).on('click', '.js-minus-count', function() {
//         let val = $('.one-click .value-count').val() * 1;
//         if(val > 1) {
//             $('.one-click .value-count').val(val-1)
//         }
//     });
// })();