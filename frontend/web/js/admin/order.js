
$('#add_sku_to_order_action').click(function(e) {
    let skus = $('select[name="add_sku_to_order[]"]').select2('val');
    let url  = $(this).data('url') + '&skus=' + skus.join(',');
    $.get(url, function(response) {
        window.location.reload();
    });
});

$('.input-sku-count').change(function(e) {
    let url = $(this).data('url') + '&count=' + $(this).val();
    let skuSumSelector = $(this).data('sku-sum-selector');
    
    $.get(url, function(response) {        
        if(response.msg) {
            if(response.success) {
                alert('Success' + ' ' + response.msg);
            } else {
                alert('Error' + ' ' + response.msg);
            }            
        }
        if(!response.success) {
            return;
        }
        if(response['order']) {
            ['order_skus_price_sum', 'order_delivery_price', 'order_discount', 'order_total_sum'].forEach(function(key) {
                if(response['order'][key]) {
                    $('#' + key).html(response['order'][key]);
                }
            });
        }
        
        if(response['sku'] != undefined && response['sku']['sum']) {
            $(skuSumSelector).html(response['sku']['sum']);
        }
    });
});

$('.input-sku-price').change(function(e) {
    let url  = $(this).data('url') + '&price=' + $(this).val();
    let skuSumSelector = $(this).data('sku-sum-selector');

    $.get(url, function(response) {
        if(response.msg) {
            alert(response.success === false ? 'Err' : 'Success' + ' ' + response.msg);
        }
        ['order_skus_price_sum', 'order_delivery_price', 'order_discount', 'order_total_sum'].forEach(function(key) {
            if(response['order'][key]) {
                $('#' + key).html(response['order'][key]);
            }
        });
        if(response['sku']['sum']) {
            $(skuSumSelector).html(response['sku']['sum']);
        }
    });
});

$('#union-order').submit(function(e) {
    e.preventDefault();
    let url = $(this).attr('action') + '&ids=' + $('#union-order-id').val();
    window.location.replace(url);
});

function printPdf(url) {
    var iframe = document.createElement('iframe');
    // iframe.id = 'pdfIframe'
    iframe.className='pdfIframe'
    document.body.appendChild(iframe);
    iframe.style.display = 'none';
    iframe.onload = function () {
        setTimeout(function () {
            iframe.focus();
            iframe.contentWindow.print();
            URL.revokeObjectURL(url)
            // document.body.removeChild(iframe)
        }, 1);
    };
    iframe.src = url;
    // URL.revokeObjectURL(url)
}

$('#print').click(function(e) {
    e.preventDefault();
    printPdf($(this).attr('href'));
});

$('.send-invoice-action').click(function(e) {
    // TODO сделать notify сразу после нажатия, что сообщение отправлено и после того как придет ответ, тоже чтото показать.
    e.preventDefault();
    let url = $(this).attr('href');
    $.get(url, function(response, status, xhr) {
        if(xhr.status == 200) {
            alert('Сообщение доставлено');
        } else {
            alert('Сообщение неудалось доставить');
        }
    });
});