(function() {
    /**
     * Favourite
     */
    $(document).on('click', '.item-page-right-wrp-buttons-favorite', function() {
        let isActive = $(this).hasClass('active');
        let url = !isActive ? $(this).data('url-add'): $(this).data('url-remove');
        let eventIsSuccess = !isActive ? 'favorite.add' : 'favorite.remove';

        if(isActive) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }

        $.get(url, function(response, status) {
            const data = JSON.parse(response);
            let event = jQuery.Event(eventIsSuccess);
            event.status  = data.status;
            event.message = data.message;

            $(document).trigger( event );
        });
    });

    $(document).on("favorite.add", function(e) {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: 'Добавили в закладки',
            timeout: 3000,
        }).show();
        let val = $('.mobile-item-favorite-num').text() * 1;
        $('.mobile-item-favorite-num').text(val+1);
    });
    $(document).on("favorite.remove", function(e) {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: 'Убрали из закладок',
            timeout: 3000,
        }).show();

        let val = $('.mobile-item-favorite-num').text() * 1;
        val -= 1;
        if(val < 0) {
            val = 0;
        }
        $('.mobile-item-favorite-num').text(val);
    });
})();
