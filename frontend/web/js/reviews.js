(function() {
    let
        page = 2,
        reviewsCommentsListWrapper = $('#comments--list'),
        reviewsPullButton = $('#comments--pull');

    $(reviewsPullButton).on('click', function(e) {
        let textGo = $(this).data('text-go');
        let textAwait = $(this).data('text-await');

        $(reviewsPullButton).text(textAwait);
        $.get($(this).data('fetch-url') + '?page=' + page++, function(response) {
            let isHasNextPage = response.is_has_next_page || false;
            if (!isHasNextPage) {
                $(reviewsPullButton).hide();
            }
            $(reviewsPullButton).text(textGo);
            if(!response.reviews.lenght) {
                $(reviewsCommentsListWrapper).append($('<div class="comments">'+response.reviews+'</div>'));
            } 
        });
    });
})();