$(function () {
	$('.related-product-checkbox').change(function () {
		console.log($(this).is(':checked'));
        let url = null;
        if($(this).is(':checked')) {
            url = $(this).data('addUrl');
        } else {
            url = $(this).data('deleteUrl');
        }
        $.post(url, {}, function(response) {
            console.log(response);           
        });
        console.log(url);
	});
});