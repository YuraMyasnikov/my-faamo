$('#seo-popup').click( function(event){
    event.preventDefault();
    $('.overlay-seo').fadeIn(297,	function(){
        $('.seo-fos')
            .css('display', 'block')
            .animate({opacity: 1}, 198);
    });
});

$('.seo-fos__close').click( function(){
    $('.seo-fos').animate({opacity: 0}, 198, function(){
        $(this).css('display', 'none');
        $('.overlay-seo').fadeOut(297);
    });
});

$('.overlay-seo').click( function(e){
    var popup = $('.seo-fos');
    if ( !popup.is(e.target) && popup.has(e.target).length === 0) {
      $('.seo-fos').animate({opacity: 0}, 198, function(){
        $(this).css('display', 'none');
        $('.overlay-seo').fadeOut(297);
    });
    }
  });