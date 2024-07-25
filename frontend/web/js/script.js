$(function () {


   const DOC = $(document),
      BODY = $('body')

   $(popup)

   $('.js-rating').Rating()

   $('.js-element').Element()

   $('.js-collapse').Collapse()


   /**
    * Modal
    * https://micromodal.vercel.app/
    */
   MicroModal.init({
      awaitOpenAnimation: true,
      awaitCloseAnimation: true,
      disableScroll: true,
      onShow: modal => scrollbarOffsetAdd(),
      onClose: modal => scrollbarOffsetRemove(),
   });

   const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
   const modalMain = $('.modal-main')

   function scrollbarOffsetAdd() {
      BODY.css('margin-right', scrollbarWidth + 'px');
      modalMain.css('right', '');
   }

   function scrollbarOffsetRemove() {
      BODY.css('margin-right', '');
      modalMain.css('right', scrollbarWidth / 2 * -1 + 'px');
   }

   /**
    * https://splidejs.com/guides/options/
    */

   /**
    * Review slider
    */
   $('.review-slider').each(function () {
      new Splide(this, {
         perPage: 3,
         gap: 40,
         breakpoints: {
            1199: {
               perPage: 2,
            },
            767: {
               perPage: 1,
            },
         }
      }).mount();
   })

   /**
    * Gallery slider
    */
   $('.gallery-slider').each(function () {
      new Splide(this, {
         perPage: 4,
         gap: 40,
         pagination: false,
         breakpoints: {
            1199: {
               perPage: 3,
            },
            1023: {
               perPage: 2,
               gap: 20,
               pagination: true
            },
            767: {
               perPage: 1,
            },
         }
      }).mount();
   })

   /**
    * Showcase slider
    */
   const showcase = $('.showcase')
   const showcasePanel = $('.showcase-panel')

   $('.showcase-slider').each(function () {
      let showcaseSlider = new Splide(this, {
         perPage: 1,
         rewind: true
      })
      showcaseSlider.on('mounted', function () {
         $('.splide__arrows', showcase).appendTo(showcasePanel)
         $('.splide__pagination', showcase).appendTo(showcasePanel)
      })
      showcaseSlider.mount();
   })

   /**
    * Cart slider
    */
   $('.cart-image-slider').each(function () {
      let cartImageMain = new Splide(this, {
         pagination: false,
         arrows: false,
         rewind: true
      });

      let thumbnails = new Splide('.cart-thumbnail-slider', {
         rewind: true,
         isNavigation: true,
         perPage: 3,
         gap: 20,
         focus: 'center',
         pagination: false,
         mediaQuery: 'min',
         breakpoints: {
            1400: {
               direction: 'ttb',
               height: 460,
               fixedHeight: 136,
            },
         },
      });

      cartImageMain.sync(thumbnails);
      cartImageMain.mount();
      thumbnails.mount();
   })

   /**
    * Catalog popup
    */
   const catalogCode = $('[data-catalog-code]');
   const catalogPopupCode = $('[data-popup-code]');
   const catalogMenuLink = $('.js-catalog-menu');
   const catalogPopup = $('.catalog-popup');
   const catalogPopupReturn = $('.js-catalog-return');
   let event

   if (window.matchMedia('(max-width: 1200px)').matches) {
      event = 'click'
      catalogCode.add(catalogPopupCode).removeClass('active')
   } else {
      event = 'mouseenter'
   }

   catalogCode.on(event, function () {
      const _this = $(this)
      const code = _this.data('catalog-code')

      catalogCode.add(catalogPopupCode).removeClass('active')

      catalogPopup.addClass('sub')

      _this.addClass('active')
      $('[data-popup-code=' + code + ']').addClass('active')

      if (code) {
         return false
      }
   })

   catalogMenuLink.on('click', function () {
      BODY.toggleClass('overflow')
      catalogPopup.toggleClass('show-popup')
      return false
   })

   catalogPopupReturn.on('click', function () {
      catalogPopup.removeClass('sub')
      catalogCode.add(catalogPopupCode).removeClass('active')
   })

   /**
    * Filter button
    */
   $(document).on('click', '.js-filter-button', function () {
      $('.filter').toggleClass('show-popup')
      BODY.toggleClass('overflow')
   })

   /**
    * Cart
    */
   const value = $('.js-value')

   value.each(function () {
      const _this = $(this)
      const plus = $('.js-plus', _this)
      const minus = $('.js-minus', _this)
      const count = $('.js-count', _this)

      plus.on('click', function () {
         count.val(1 * count.val() + 1)
      })

      minus.on('click', function () {
         const value = 1 * count.val() - 1
         value === -1 ? count.val(0) : count.val(value)
      })
   })

});

/**
 * Popup event
 */
const popup = function () {
   const
      POPUP_SELECTOR = $('[data-popup]'),
      POPUP_SELECTOR_LINK = $('[data-popup-link]'),
      POPUP_SHOW_CLASS = 'show-popup',
      POPUP_ACTIVE_CLASS = 'active'

   // POPUP_SELECTOR_LINK.on('click', function () {
   $(document).on('click', '[data-popup-link]', function () {
      const
         _this = $(this),
         select = _this.data('popup-link'),
         popup = $('[data-popup="' + select + '"]')

      if (popup.is($('.' + POPUP_SHOW_CLASS))) {
         POPUP_SELECTOR.removeClass(POPUP_SHOW_CLASS)
         POPUP_SELECTOR_LINK.removeClass(POPUP_ACTIVE_CLASS)
      } else {
         POPUP_SELECTOR.removeClass(POPUP_SHOW_CLASS)
         POPUP_SELECTOR_LINK.removeClass(POPUP_ACTIVE_CLASS)
         popup.addClass(POPUP_SHOW_CLASS)
         _this.addClass(POPUP_ACTIVE_CLASS)
      }
      return false;
   });

   $(document).on('click', function (e) {
      if ($(e.target).closest('.' + POPUP_SHOW_CLASS).length === 0) {
         POPUP_SELECTOR.removeClass(POPUP_SHOW_CLASS)
         POPUP_SELECTOR_LINK.removeClass(POPUP_ACTIVE_CLASS)
      }
   });
}

/**
 * Element
 */
$.fn.Element = function () {
   return this.each(function () {
      const _this = $(this);
      const imageNav = $('.js-image-nav li', _this)
      const imageMain = $('.js-image-main', _this)
      const imageMainSrc = imageMain.attr('src')

      imageNav.on('mouseenter', function () {
         const _thisNav = $(this)
         _thisNav.addClass('active').siblings().removeClass('active')
         imageMain.attr('src', _thisNav.data('src'))
      })

      _this.on('mouseleave', function () {
         imageMain.attr('src', imageMainSrc)
         imageNav.eq(0).addClass('active').siblings().removeClass('active')
      })
   })
}

/**
 * Rating
 */
$.fn.Rating = function () {
   return this.each(function () {
      const _this = $(this)
      const item = $('.star', _this)
      const classFull = 'star--active'
      const classActive = 'active'
      const input = _this.next()

      item.on('mouseenter', function () {
         let index = $(this).index() + 1;
         item.slice(0, index).addClass(classFull)
      }).on('mouseleave', function () {
         item.removeClass(classFull)
      }).on('click', function () {
         let index = $(this).index() + 1
         item.removeClass(classActive).slice(0, index).addClass(classActive)
         input.val(index)
      });
   })
}

/**
 * Collapse
 */
$.fn.Collapse = function () {
   return this.each(function () {
      const _this = $(this)
      const _title = $('.js-collapse-title', _this)
      const _content = $('.js-collapse-content', _this)

      _title.on('click', function () {
         _title.add(_this).toggleClass('active')
         _content.stop().slideToggle(200)
      })

   })
}