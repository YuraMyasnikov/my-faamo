(function() {

$('.a-history-item-header-top'),click(function (){
    $('.a-history-item-header-top-name').toggle(function () {
            $('.a-history-item-body').css('display:block')
        }, function () {
            $('.a-history-item-body').css('display:none')
        }
    );
});


    let orderForm = $('#create-order-form');
    let orderFormErrors = [];
    let transportCompanies = orderForm.data('transport-companies');
    let transportCompanyActive = null;
    let paymentTypes = orderForm.data('payment-types');
    let deliveryPrice = orderForm.data('delivery-price');
    let totalPriceWithDiscount = orderForm.data('total-price-with-discount');
    let paymentTypeActive = null;
    let kladr = null;
    let calculatorResponses = {};
    let userAgreement = false;
    const addressSearchQuery = (zip, city, address) => {
        let query = '';
        query += city.length ? 'г. ' + city : ''
        query += address.length ? ' ' + address : '';
        query = zip.length ? zip + ', ' + query : query;

        return query;
    };
    const urlToCalculator = (transportCompanyId, kladrNumber, zip, city) => {
        let url = '/delivery/calculate?'
            +'id=' + transportCompanyId
            +'&address=' + kladrNumber
            +'&zip=' + zip
            +'&fiasId=' + city;

        return url;
    };
    const priceFormat = (price) => {
        var parts = price.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        if(parts.length > 1) {
            parts[1] *= 10;
        }
        return parts.join(".");
    };
    transportCompanyActive = $( 'input[name="transport-company"]:checked' ).val();
    $('input[name="transport-company"]').on('change', function() {
        transportCompanyActive = $(this).val();
        $('#userordercreateform-delivery_id').val(transportCompanyActive);
        $('#organizationordercreateform-delivery_id').val(transportCompanyActive);
        $('#address-block').show();
        if(kladr) {
            kladr.tkId = transportCompanyActive;
            $(document).trigger('kladr-selected', [kladr]);
        } 
    });
    $('input[name="payment-type"]').change(function() {
        paymentTypeActive = $(this).val();
        $('#userordercreateform-payment_id').val(paymentTypeActive);
        $('#organizationordercreateform-payment_id').val(paymentTypeActive);
    });
    const requestToDadata = (query) => {
        $.ajax({
            url: "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address",
            type: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Token a2ce91a09f94639bce71f29996a288c79cf89de9",
            },
            data: JSON.stringify({query: query}),
            success: function(data) { 

                let suggestionsBlock = $('#address-suggestions');
                let suggestions = $('.suggestions', suggestionsBlock);
                $(suggestions).html('');

                if(!data.suggestions.length) {
                    return;
                }
                console.log(data);
                data.suggestions.forEach(function(val, key) {
                    let html = ''+
                    '<li class="suggestion column col-12">' +
                        '<span>' + val.unrestricted_value + '</span>' + 
                    '</li>';
                    let suggest = $(html)
                        .attr({
                            'data-unrestricted_value': val.unrestricted_value,
                            'data-zip': val.data['postal_code'],
                            'data-city': val.data['city'],
                            'data-street': val.data['street'],
                            'data-kladr': val.data['street_kladr_id'] || null,
                            'data-street_with_type': val.data['street_with_type'] || null,
                        });

                    suggestions.append( suggest );
                });
                $(document).trigger('suggestion-rendered');
            },
            error: function(err) { console.error(err); }
        });
    };
    $('#organizationordercreateform-city, #organizationordercreateform-zip, #organizationordercreateform-address').on('input', function(e) {
        let zip = $('#organizationordercreateform-zip').val();
        let city = $('#organizationordercreateform-city').val();
        let address = $('#organizationordercreateform-address').val();
        let query = addressSearchQuery(zip, city, address);

        requestToDadata(query);

    });
    $('#userordercreateform-city, #userordercreateform-zip, #userordercreateform-address').on('input', function(e) {
        let zip = $('#userordercreateform-zip').val();
        let city = $('#userordercreateform-city').val();
        let address = $('#userordercreateform-address').val();
        let query = addressSearchQuery(zip, city, address);
        
        requestToDadata(query);
    });
    $(document).on('suggestion-rendered', function() {
        $('#address-suggestions').show();
    });
    $(document).on('click', '.suggestion', function(event) {
        let unrestrictedValue = $(this).data('unrestricted_value');
        let zip = $(this).data('zip');
        let city = $(this).data('city');
        let street = $(this).data('street');
        let kladrValue = $(this).data('kladr');
        let streetWithType = $(this).data('street_with_type');
        let address = unrestrictedValue.replace(streetWithType, '|' + streetWithType).split('|')[1];

        kladr = {
            number: kladrValue,
            zip: zip,
            city: city,
            street: street,
            address: address,
            tkId: transportCompanyActive,
            unrestrictedValue: unrestrictedValue 
        };
        if(!street || street.lenght == 0) {
            // alert('Укажите полный адрес. Город и улица');
            new Noty({
                type: 'error',
                layout: 'topRight',
                text: 'Укажите полный адрес. Город и улица',
                timeout: 3000,
            }).show();
            return false;
        }
        $('#userordercreateform-city').val(city);
        $('#userordercreateform-zip').val(zip);
        $('#userordercreateform-address').val(address); 
        
        $('#organizationordercreateform-city').val(city);        
        $('#organizationordercreateform-zip').val(zip);
        $('#organizationordercreateform-address').val(address); 

        $('#address-suggestions').hide();

        $(document).trigger('kladr-selected', [kladr]);
    });
    $(document).on('kladr-selected-default', function(e, defaultKladr) {        
        kladr = {
            number: defaultKladr.number,
            zip: defaultKladr.zip,
            city: defaultKladr.city,
            street: defaultKladr.address,            
            tkId: transportCompanyActive,
            unrestrictedValue: defaultKladr.full_address 
        };
        let url = urlToCalculator(defaultKladr.tkId, defaultKladr.number, defaultKladr.zip, defaultKladr.city);
        $('#kladr_tkId').val(kladr.tkId);
        $('#kladr_number').val(kladr.number);
        $('#kladr_zip').val(kladr.zip);
        $('#kladr_city').val(kladr.city);

        $.get(url, function(response) {
            let data = {
                code: response[0]['code'],
                cost: response[0]['cost'],
                days: response[0]['days'],
                title: response[0]['title'],
                volume: response[0]['volume'],
                weight: response[0]['volume'],
            };
            $(document).trigger('delivery-calculated', [data]);
        });
        $('#delivery-cost').html('<span class="loader"></span>');
        $('#delivery-days').html('<span class="loader"></span>');
        $('#total-price').html('<span class="loader"></span>');
    });
    $(document).on('kladr-selected', function(e, kladr) {
        let url = urlToCalculator(kladr.tkId, kladr.number, kladr.zip, kladr.city);
        $('#kladr_tkId').val(kladr.tkId);
        $('#kladr_number').val(kladr.number);
        $('#kladr_zip').val(kladr.zip);
        $('#kladr_city').val(kladr.city);
        $('#address-block__kladr')
            .html(
                '<div class="column col-12">'+ 
                    '<p>' + kladr.unrestrictedValue + '</p>' +
                    '<a href="#" id="to-change-kladr">Изменить</a>' +
                '</div>'
            )
            .show();
        $('#address-block__form').hide();
        
        $.get(url, function(response) {
            let data = {
                code: response[0]['code'],
                cost: response[0]['cost'],
                days: response[0]['days'],
                title: response[0]['title'],
                volume: response[0]['volume'],
                weight: response[0]['volume'],
            };
            $(document).trigger('delivery-calculated', [data]);
        });
        $('#delivery-cost').html('<span class="loader"></span>');
        $('#delivery-days').html('<span class="loader"></span>');
        $('#total-price').html('<span class="loader"></span>');
    });
    let defaultKladr = $('.default-kladr');
    if(defaultKladr.length) {
        defaultKladr = defaultKladr[0];

        $(document).trigger('kladr-selected-default', [{
            tkId: $(defaultKladr).data('delivery_id'),
            number: $(defaultKladr).data('kladr'),
            zip: $(defaultKladr).data('zip'),
            city: $(defaultKladr).data('city'),
            address: $(defaultKladr).data('address'),
            full_address: $(defaultKladr).data('full_address'),
        }]);
    }
    $(document).on('click', '#to-change-kladr', function(e) {
        e.preventDefault();
        kladr = null;

        $('#address-block__form').show();
        
        $('#userordercreateform-city').val('');
        $('#userordercreateform-zip').val('');
        $('#userordercreateform-address').val('');
        
        $('#organizationordercreateform-city').val('');
        $('#organizationordercreateform-zip').val('');
        $('#organizationordercreateform-address').val('');

        $('#address-suggestions').hide();
        $('#address-block__kladr').html('').hide();

    });
    $(document).on('delivery-calculated', function(e, deliveryData) {
        deliveryPrice = deliveryData.cost;
        let totalPrice = deliveryPrice * 1 + totalPriceWithDiscount
        $('#total-price').text(priceFormat(totalPrice));
        
        $('#delivery-cost').html(deliveryData.cost + ' ₽');
        $('#delivery-days').html(deliveryData.days + ' дней');
    });
    orderForm.submit(function(e) {
        let isCanContinue = true;
        if(!kladr) {
            isCanContinue = false;
            // alert('Укажите корректный адрес.');
            new Noty({
                type: 'error',
                layout: 'topRight',
                text: 'Укажите корректный адрес.',
                timeout: 3000,
            }).show();
        }   
        let isUserAgreement = $('#user_agreement').prop('checked');
        if(!isUserAgreement) {
            isCanContinue = false;
            // alert('Пожалуйста подтвердите согласие на обработку персональных данных');
            new Noty({
                type: 'error',
                layout: 'topRight',
                text: 'Пожалуйста подтвердите согласие на обработку персональных данных',
                timeout: 3000,
            }).show();
        }
        if(!isCanContinue) {
            e.preventDefault();
        }
        // new Noty({
        //     type: 'success',
        //     layout: 'topRight',
        //     text: 'Оформляем заказ...',
        //     timeout: 3000,
        // }).show();

        // $('.btn', this).attr('disabled', true).html('<span class="loader"></span>');
    });
})();