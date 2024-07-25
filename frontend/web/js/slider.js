var swiper = new Swiper('.main-banner-slider', {
	direction: 'horizontal',
	effect: 'scroll',
	slidesPerView: 1,
	slidesPerGroup: 1,
	spaceBetween: 0,
	speed: 700,
	loop: false,
	navigation: {
		nextEl: '.main-next',
		prevEl: '.main-prev',
	},
	pagination: {
		el: '.swiper-pagination',
		type: 'bullets',
		clickable: true,
	},
});

var swiper = new Swiper('.page-item-slider-wrp', {
	direction: 'horizontal',
	effect: 'scroll',
	slidesPerView: 'auto',
	slidesPerGroup: 1,
	spaceBetween: 0,
	speed: 700,
	loop: false,
	navigation: {
		nextEl: '.slider-next',
		prevEl: '.slider-prev',
	},
});

var blog = new Swiper('.blog-page-slider', {
	direction: 'horizontal',
	effect: 'scroll',
	slidesPerView: 'auto',
	slidesPerGroup: 1,
	spaceBetween: 0,
	speed: 700,
	loop: false,
	navigation: {
		nextEl: '.slider-next',
		prevEl: '.slider-prev',
	},
});

var catalogSlider = null;
var mediaQuerySize = 768;

function catalogSliderInit() {
	if (!catalogSlider) {
		catalogSlider = new Swiper('.item-page-left', {
			direction: 'horizontal',
			effect: 'scroll',
			slidesPerView: 1,
			slidesPerGroup: 1,
			spaceBetween: 0,
			speed: 700,
			loop: false,
			navigation: {
				nextEl: '.item-next',
				prevEl: '.item-prev',
			},
		});
	}
}

function catalogSliderDestroy() {
	if (catalogSlider) {
		catalogSlider.destroy();
		catalogSlider = null;
	}
}
$(window).on('load resize', function () {
	var windowWidth = $(this).innerWidth();
	if (windowWidth <= mediaQuerySize) {
		catalogSliderInit()
	} else {
		catalogSliderDestroy()
	}
});