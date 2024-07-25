(function() {
    
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

        const kladr = {
            number: kladrValue,
            zip: zip,
            city: city,
            street: street,
            address: address,
            unrestrictedValue: unrestrictedValue 
        };
        if(!street || street.lenght == 0) {
            // alert('Укажите полный адрес. Город и улица');
            new Noty({
                type: 'error',
                layout: 'topRight',
                text: 'Укажите полный адрес. Индекс, город и улица',
                timeout: 3000,
            }).show();
            return false;
        }
        $('#address-suggestions').hide();

        $(document).trigger('kladr-selected', [kladr]);
    });

    $(document).on('kladr-selected', function(e, kladr) {
        $('.val-zip').val(kladr.zip);
        $('.val-city').val(kladr.city);
        $('.val-full_address').val(kladr.unrestrictedValue);
        $('.val-kladr').val(kladr.number);

        $('#address-block__kladr')
            .html(
                '<div class="column col-12">'+ 
                    '<p>' + kladr.unrestrictedValue + '</p>' +
                    '<a href="#" id="to-change-kladr">Изменить</a>' +
                '</div>'
            )
            .show();

        $('#address-block__form').hide();
    });

    $(document).on('click', '#to-change-kladr', function(e) {
        e.preventDefault();

        $('#address-block__form').show();
        
        $('.val-zip').val('');
        $('.val-city').val('');
        $('.val-full_address').val('');
        $('.val-address').val('');
        $('.val-kladr').val('');

        $('#address-suggestions').hide();
        $('#address-block__kladr').html('').hide();

    });

    $('.input-kladr').on('input', function(e) {
        let address = $(this).val();
        

        $.ajax({
            url: "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address",
            type: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Token a2ce91a09f94639bce71f29996a288c79cf89de9",
            },
            data: JSON.stringify({query: address}),
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

    });
})();