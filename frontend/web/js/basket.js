(function() {
    const priceFormat = (price) => {
        var parts = price.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        return parts.join(".");
    };

    function CartItem(node, skuId, price, count, remnants) {
        this.node = node;
        this.skuId = skuId;
        this.price = price;
        this.priceHolder = $('.basket-item__price-count', node);
        this.count = count;
        this.countInput = $('.js-count', node);
        this.sumHolder = $('.basket-item__total', node);
        this.remnants = remnants;
        this.incrementUrl = '/api/shop/wholesale-basket/increment';
        this.decrementUrl = '/api/shop/wholesale-basket/decrement';
        this.deleteUrl    = '/api/shop/wholesale-basket/delete';
        this.isDeleted = false;

        this.bind();
    }

    CartItem.prototype.bind = function() {
        let self = this;
        $(this.node).on('click', '.js-inc', function(e) {
            self.plus();
        });
        $(this.node).on('click', '.js-dec', function(e) {
            self.minus();
        });
        $('.basket-item__del', this.node).click(function(e) {
            e.preventDefault();
            $.post(self.deleteUrl, {sku: self.skuId}, function(response) {
                self.isDeleted = true;    
                $(self.node).hide();
                $(document).trigger('recalculated', [JSON.parse(response)] );
            }); 
        
        });
    };

    CartItem.prototype.plus = function() {
        if(this.remnants - this.count === 0) {
            return;
        }

        this.count++;
        $.post(this.incrementUrl, {sku: this.skuId}, function(response) {
            $(document).trigger('recalculated', [JSON.parse(response)] );
        });        
        $('.total-sum').html('<span class="loader"></span>');
        $('.total-sum-final').html('<span class="loader"></span>');
        $('.total-sum-without-discount').html('<span class="loader"></span>');
        $('.discount-price').html('<span class="loader"></span>');

        this.render();
    };

    CartItem.prototype.minus = function() {
        if(this.count === 1) {
            return;
        }

        this.count--;
        $.post(this.decrementUrl, {sku: this.skuId}, function(response) {
            $(document).trigger('recalculated', [JSON.parse(response)] );
        });
        $('.total-sum').html('<span class="loader"></span>');
        $('.total-sum-final').html('<span class="loader"></span>');
        $('.total-sum-without-discount').html('<span class="loader"></span>');
        $('.discount-price').html('<span class="loader"></span>');

        this.render();
    };

    CartItem.prototype.render = function() {
        this.countInput
            .prop('readonly', false)
            .val(this.count)
            .prop('readonly', true);
        this.priceHolder.text(priceFormat(this.price));
        this.sumHolder.text(priceFormat(this.count * this.price));
    };

    CartItem.prototype.update = function(price, count) {
        this.price = price;
        this.count = count;
        this.render();
    };


    let cartItemsMap = {};
    let totalSumHolder = $('.total-sum');
    let counter = $('.basket-counter');

    $('.basket-item').each(function(i, el) {
        let data = $(el).data();
        cartItemsMap[data['skuId']] = new CartItem(el, data['skuId'], data['price'], data['count'], data['skuRemnants']);
    });

    $(document).on('recalculated', function(e, data) {
        const priceFormat = (price) => {
            var parts = price.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            if(parts.length > 1) {
                parts[1] *= 10;
            }
            
            return parts.join(".");
        };

        let count = 0;
        Object.keys(data.skus).forEach(skuId => {
            count += data.skus[skuId]['count'];
            cartItemsMap[skuId].update(data.skus[skuId]['price'], data.skus[skuId]['count']);
        });
        
        counter.text(count);
        totalSumHolder.html(data.sum);
        $('.order-price-type-title').html(priceFormat(data.pricesTypeTitle));
        $('.total-sum').html(priceFormat(data.sum));
        $('.total-sum-final').html(priceFormat(data.totalPriceWithDiscount));
        $('.total-sum-without-discount').html(priceFormat(data.totalPriceWithoutDiscount));
        $('.discount-price').html(priceFormat(data.discountPrice));
    });

    $(document).on('click', '.js-clear', function(e) {
        $.post('/api/shop/wholesale-basket/clear', {}, function(e) {
            location.reload(true);
        });
    });
})();