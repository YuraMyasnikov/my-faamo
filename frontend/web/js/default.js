$(function () {
	$('.selected-city').click(function () {
		city();
	});
	$('.choice-bg').click(function () {
		city();
	});
	$('.select-city-close').click(function () {
		city();
	});
	
	$('.city').click(function () {
		city();
	});
	$('.header-mobile-item.menu').click(function () {
		mobmenu();
	});
	$('.menu-bg').click(function () {
		mobmenu();
	});
	$('.nav-close').click(function () {
		mobmenu();
	});
	$('.sub-menu').click(function () {
		submenu();
	});
	$('.header-search').click(function () {
		searchform();
	});
	$('.header-search-form-close').click(function () {
		searchform();
	});
	$('.header-mobile-item.search').click(function () {
		searchform();
	});
});
function searchform() {
	$('.header-search-form').toggleClass('active');
	$('.header-icons').toggleClass('active');
}

function city() {
	$('.select-city-choice').toggleClass('active');
	$('.choice-bg').toggleClass('active');
}
function submenu() {
	$('.sub-menu-bx').toggleClass('active');
}
function mobmenu() {
	$('nav').toggleClass('active');
	$('.menu-bg').toggleClass('active');
}

jQuery(document).ready(function ($) {
	$('.razmer').on('click', function (event) {
		event.preventDefault();
		$('.razmer-popup').addClass('active');
	});
	$('.razmer-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.razmer-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.nalichie').on('click', function (event) {
		event.preventDefault();
		$('.nalichie-popup').addClass('active');
	});
	$('.nalichie-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.nalichie-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.obmeri').on('click', function (event) {
		event.preventDefault();
		$('.obmeri-popup').addClass('active');
	});
	$('.obmeri-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.obmeri-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.pillinguemost').on('click', function (event) {
		event.preventDefault();
		$('.pillinguemost-popup').addClass('active');
	});
	$('.pillinguemost-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.pillinguemost-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.sostav').on('click', function (event) {
		event.preventDefault();
		$('.sostav-popup').addClass('active');
	});
	$('.sostav-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.sostav-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.reviews-item-page').on('click', function (event) {
		event.preventDefault();
		$('.reviews-popup').addClass('active');
	});
	$('.reviews-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.reviews-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.send-review-button').on('click', function (event) {
		event.preventDefault();
		$('.send-review-button-popup').addClass('active');
	});
	$('.send-review-button-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.send-review-button-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.appointment-button').on('click', function (event) {
		event.preventDefault();
		$('.appointment-popup').addClass('active');
	});
	$('.appointment-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.appointment-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.get-call').on('click', function (event) {
		event.preventDefault();
		$('.getcall-popup').addClass('active');
	});
	$('.getcall-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.getcall-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$(document).on('click', '.filters-left-all', function (event) {
		event.preventDefault();
		$('.filter-popup').addClass('active');
	});
	$('.filter-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.filter-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});

	$('.seo-link').on('click', function (event) {
		event.preventDefault();
		$('.seo-popup').addClass('active');
	});
	$('.seo-popup').on('click', function (event) {
		if ($(event.target).is('.popup-close') || $(event.target).is('.seo-popup')) {
			event.preventDefault();
			$(this).removeClass('active');
		}
	});
});

$(function() {
	$(".item-page-right-wrp-colors-item.catalog").click(function() {
	   $(this).toggleClass("selected");
	});
 });

 $(function() {
	$(".item-page-right-wrp-colors-item.item").click(function() {
	   $(".item-page-right-wrp-colors-item.item").removeClass("selected");
	   $(this).addClass("selected");
	});
 });

/*$(function() {
	$(".item-page-right-wrp-buttons-favorite").on("click", function() {
		$(this).toggleClass("active");
	});
});*/

$(document).on('click', '.dropdown', function () {
	$(this).attr('tabindex', 1).focus();
	$(this).toggleClass('active');
	$(this).find('.dropdown-menu').slideToggle(0);
});
$('.dropdown').focusout(function () {
	$(this).removeClass('active');
	$(this).find('.dropdown-menu').slideUp(0);
});
$(function() {
	$(document).on('click', ".dropdown.select .dropdown-menu li", function() {
		$(document)
	   		.find(".dropdown.select .dropdown-menu li")
			.removeClass("active");

	   $(this).addClass("active");
	});
 });
$(document).on('click', '.dropdown .dropdown-menu li', function () {
	$(this).parents('.dropdown').find('span').text($(this).text());
	$(this).parents('.dropdown').find('input').attr('value', $(this).attr('id'));
});

