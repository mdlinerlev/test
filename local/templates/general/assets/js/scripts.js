'use strict';
var _typeof = typeof Symbol === 'function' && typeof Symbol.iterator === 'symbol' ? function (obj) {
	return typeof obj;
} : function (obj) {
	return obj && typeof Symbol === 'function' && obj.constructor === Symbol ? 'symbol' : typeof obj;
};

function _toConsumableArray(arr) {
	if (Array.isArray(arr)) {
		for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) {
			arr2[i] = arr[i];
		}
		return arr2;
	} else {
		return Array.from(arr);
	}
}

var app = {
	define: function define(element, selector, options) {
		if (element != null && selector != null) {
			if (typeof this[element] === 'undefined') {
				this[element] = {el: $(selector), selector: selector};
				this[element].obj = this[element];
				if ((typeof options === 'undefined' ? 'undefined' : _typeof(options)) === 'object') {
					for (var prop in options) {
						this[element][prop] = options[prop];
					}
					if (_typeof(options.events) === 'object') {
						for (var _prop in options.events) {
							var propArray = _prop.indexOf(' ') !== -1 ? [_prop.substr(0, _prop.indexOf(' ')), _prop.substr(_prop.indexOf(' ') + 1)] : [_prop, null];
							$(selector).on(propArray[0], propArray[1], {element: this[element], val: options.events[_prop]}, function (ev) {
								var eventVal = ev.data.val;
								var callArgs = [ev.data.element, ev];
								if (typeof eventVal !== 'function') {
									var calledEvent = eventVal;
									if (eventVal instanceof Array) {
										calledEvent = eventVal[0];
										callArgs.push.apply(callArgs, _toConsumableArray(eventVal.slice(1)));
									}
									app[element][calledEvent].apply(this, callArgs);
								} else {
									eventVal.apply(this, callArgs);
								}
							});
						}
					}
					options.init && this[element].init(this[element], this[element].el);
				}
			} else {
				console.log('app.' + element + ' is already defined!');
			}
		} else {
			console.log('pass minimum 2 arguments!');
		}
	}
};
'use strict';
$(function () {
	$.fn.inlineStyle = function (prop) {
		var styles = this.attr('style');
		var value = '';
		styles && styles.split(';').forEach(function (e) {
			var style = e.split(':');
			if ($.trim(style[0]) === prop) {
				value = style[1];
			}
		});
		return value;
	};
	$.fn.removeStyle = function (style) {
		var search = new RegExp(style + '[^;]+;?', 'g');
		return this.each(function () {
			$(this).attr('style', function (i, style) {
				return style.replace(search, '');
			});
		});
	};
	$.validator.addMethod('validFio', function (value) {
		if (value === '') {
			return true;
		}
		return (/^([а-яёa-z-]+\s)*[а-яёa-z-\s]+$/i.test(value));
	});
	$.validator.addMethod('validPhone', function (value) {
		if (value === '') {
			return true;
		}
		return (/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i.test(value));
	});
	$.validator.addMethod('validEmail', function (value) {
		if (value === '') {
			return true;
		}
		return (/^([a-z0-9]+[\._-]?)+([a-z0-9]+[_]?)*@([a-z0-9]+[-]?)+(\.[a-z0-9]+)*(\.[a-z]{2,3})$/i.test(value));
	});
	$.validator.addMethod('validEmailOrPhone', function (value) {
		if (value === '') {
			return true;
		}
		if (value.indexOf('@') === -1) {
			return (/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i.test(value));
		} else {
			return (/^([a-z0-9]+[\._-]?)+([a-z0-9]+[_]?)*@([a-z0-9]+[-]?)+(\.[a-z0-9]+)*(\.[a-z]{2,3})$/i.test(value));
		}
	});
	$.extend(jQuery.validator.messages, {
		required: 'Это обязательное поле',
		validFio: 'Некорректно введено имя',
		validEmail: 'Введите корректный e-mail',
		validPhone: 'Введите корректный номер телефона',
		minlength: 'Введите корректный номер телефона'
	});
	$.extend(true, $.magnificPopup.defaults, {
		tLoading: 'Загружается...',
		gallery: {
			arrowMarkup: '<a title="%title%" class="mfp-arrow mfp-arrow-%dir%"></a>',
			tPrev: 'Назад',
			tNext: 'Вперед',
			tCounter: '%curr% из %total%'
		},
		closeMarkup: '<a title="Закрыть (Esc)" class="mfp-close"></a>'
	});
});
'use strict';
$(function () {
	app.define('document', 'html', {
		events: {
			click: 'click',
			'click .header__menu-button': 'menuOpen',
			'click .header-fixed__menu-button': 'menuOpen',
			'click .offcanvas__shim': 'menuClose',
			'touchmove .offcanvas__shim': 'disableScroll',
			'touchend .offcanvas__shim': 'menuClose'
		},
		$body: $('body'),
		$offcanvas: $('.offcanvas'),
		$offcanvasMenu: $('.offcanvas__menu'),
		$offcanvasInner: $('.offcanvas-menu__inner'),
		disableScroll: function disableScroll(obj, event) {
			obj.menuShow = true;
			event.preventDefault();
		},
		click: function click(obj, event) {
			var $target = $(event.target);
			if ($('.header__search.hover-end').length && !$target.closest('.header__search').length) {
				$('.header__search').removeClass('hover hover-end');
			}
			if ($('.main-menu.fixed-active').length && !$target.closest('.main-menu').length && !$target.closest('.header-fixed__menu-button').length) {
				$('.main-menu').removeClass('fixed-active');
			}
			if ($('.header__catalog-menu.fixed-active').length && !$target.closest('.header__catalog-menu').length && !$target.closest('.header-fixed__button--catalog').length) {
				$('.header__catalog-menu').removeClass('fixed-active');
			}
			if ($('.header-fixed__search.active').length && !$target.closest('.header-fixed__search').length) {
				$('.header-fixed__search').removeClass('active');
			}
		},
		menuOpened: false,
		menuWidth: 270,
		menuDuration: 400,
		menuEasing: 'swing',
		menuOpen: function menuOpen(obj, event, isCatalog) {
			var pos = arguments.length <= 3 || arguments[3] === undefined ? obj.menuWidth : arguments[3];
			if (!obj.menuOpened && app.window.width() < app.header.mobileMenuBp) {
				var animateObj = pos > 0 ? {left: 0} : {right: 0};
				obj.el.addClass('menu-opened' + (pos > 0 ? '' : ' filters-opened') + (pos === '-100%' ? ' filters-opened-large' : '')).removeClass('menu-closed');
				obj.menuOpened = true;
				obj.$offcanvasInner.height(isCatalog ? $('.header-catalog__menu').height() : 'auto');
				$('.page-wrapper, .header-fixed').stop().animate({left: pos}, obj.menuDuration, obj.menuEasing);
				obj.$offcanvas.stop().animate(animateObj, obj.menuDuration, obj.menuEasing, function () {
					obj.el.addClass('menu-opened-end');
				});
			} else {
				if (app.window.width() >= app.header.mobileMenuBp) {
					$('.main-menu').toggleClass('fixed-active');
				}
			}
		},
		menuClose: function menuClose(obj, event) {
			var pos = arguments.length <= 2 || arguments[2] === undefined ? -obj.menuWidth : arguments[2];
			if (!obj.menuShow && obj.menuOpened) {
				var animateObj = obj.el.hasClass('filters-opened') ? {right: pos} : {left: pos};
				obj.el.removeClass('menu-opened-end').addClass('menu-closed');
				obj.menuOpened = false;
				$('.page-wrapper, .header-fixed').stop().animate({left: 0}, obj.menuDuration, obj.menuEasing);
				obj.$offcanvas.stop().animate(animateObj, obj.menuDuration, obj.menuEasing, function () {
					obj.el.removeClass('menu-opened filters-opened filters-opened-large');
					obj.$offcanvas.removeStyle('right');
				});
			}
			obj.menuShow = false;
			app.headerCatalog.closeAll(app.headerCatalog);
		},
		init: function init(obj) {
			if (!navigator.MOBILE) {
				obj.$body.addClass('mobile');
			}
		}
	});
});
'use strict';
$(function () {
	app.define('popupLink', document, {
		events: {'click .popup-link': 'openPopup'}, openPopup: function openPopup() {
			var popup = $(this).data('popup');
			if (popup) {
				$.magnificPopup.open({items: {src: $('.' + popup)}, type: 'inline'});
			} else {
				console.log('popup not found');
			}
		}
	});
	app.define('oneClickBuy', document, {
		events: {'click .one-click-buy': 'openPopup'}, openPopup: function openPopup() {
			var popup = $(this).data('popup');
			var productId = $(this).data('product_id');
			if (popup) {
				$.ajax({
					type: 'POST',
					url: '/bitrix/templates/general/ajax/oneclickbuy.php?productId=' + productId,
					success: function (html) {
						$('.' + popup).html(html);
						$('.phone_input').mask('+7 (999) 999-99-99');
					}
				});
				$.magnificPopup.open({items: {src: $('.' + popup)}, type: 'inline', focus: '.phone_input'});
			} else {
				console.log('popup not found');
			}
		}
	});
	app.define('quantity', document, {
		events: {
			'click .quantity__button--minus': 'minusClick',
			'click .quantity__button--plus': 'plusClick',
			'blur .quantity__input': 'testInput'
		}, minusClick: function minusClick(obj) {
			var $this = $(this);
			var $container = $this.closest('.quantity');
			if (!$this.hasClass('disabled')) {
				var newVal = parseInt($container.find('.quantity__input').val()) - 1;
				if (newVal >= 0) {
					$container.find('.quantity__input').val(newVal).trigger('change');
				}
				if (newVal < 0) {
					$container.find('.quantity__button--minus').addClass('disabled');
				}
			}
		}, plusClick: function plusClick(obj) {
			var $container = $(this).closest('.quantity');
			var newVal = parseInt($container.find('.quantity__input').val()) + 1;
			$container.find('.quantity__input').val(newVal).trigger('change');
			if (newVal > 0) {
				$container.find('.quantity__button--minus').removeClass('disabled');
			}
		}, testInput: function testInput(obj) {
			var $this = $(this);
			var $container = $this.closest('.quantity');
			var oldVal = parseInt($this.val());
			if ($this.val() === '' || oldVal < 0) {
				$this.val(0);
			}
			if (oldVal === 0 || isNaN(oldVal)) {
				$container.find('.quantity__button--minus').addClass('disabled');
			} else {
				if (oldVal > 0) {
					$container.find('.quantity__button--minus').removeClass('disabled');
				}
			}
		}, initScripts: function initScripts() {
			$('.quantity').each(function () {
				var $input = $(this).find('.quantity__input');
				if ($input.val() > 0) {
					if ($input.data('min') && $input.val() > $input.data('min') + 1 || !$input.data('min')) {
						$(this).find('.quantity__button--plus').removeClass('disabled');
					} else {
						$(this).find('.quantity__button--plus').addClass('disabled');
					}
					if ($input.data('max') && $input.val() < $input.data('max') - 1 || !$input.data('max')) {
						$(this).find('.quantity__button--minus').removeClass('disabled');
					} else {
						$(this).find('.quantity__button--plus').addClass('disabled');
					}
				}
			});
		}, init: function init(obj, el) {
			obj.initScripts();
		}
	});
	app.define('owlCarousel', '.owl-carousel', {
		controlsRecount: function controlsRecount(sliderObj) {
			setTimeout(function () {
				var navigation = sliderObj.$element.find('.owl-controls');
				if (sliderObj._items.length > sliderObj.settings.items) {
					return navigation.show();
				} else {
					return navigation.hide();
				}
			}, 0);
		}
	});
	app.define('detailItemSlider', document, {
		init: function init(obj, el) {
			obj.slidersInit();
		}, slidersInit: function slidersInit() {
			$('.js-product-gallery__slider').owlCarousel({
				loop: false,
				responsiveClass: true,
				nav: true,
				navText: [],
				navRewind: false,
				dots: false,
				responsiveRefreshRate: 0,
				responsive: {319: {items: 4, margin: 6,}, 420: {items: 5, margin: 12,}},
				onResized: function onResized() {
					app.owlCarousel.controlsRecount(this);
				},
				onInitialized: function onInitialized() {
					app.owlCarousel.controlsRecount(this);
				}
			});
		}
	});
	app.define('itemsSlider', document, {
		init: function init(obj, el) {
			obj.slidersInit();
		}, slidersInit: function slidersInit() {
			$('.items-slider').each(function () {
				!$(this).find('.owl-carousel').length && $(this).find('.items-slider__slider').owlCarousel({
					items: 1,
					loop: false,
					nav: true,
					navText: [],
					navRewind: false,
					dots: false,
					responsiveRefreshRate: 0,
					responsive: {
						395: {items: 2, dots: true,},
						640: {items: 3, dots: true,},
						840: {items: 4, dots: true,},
						1024: {items: 5},
						1200: {items: 6}
					},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			});
		}
	});
	if ($('.catalog-item').length) {
		app.define('catalogItem', '.catalog__list', {
			events: {
				'click .catalog-item-aside__links a': 'sizeChange',
				'click .catalog-item-aside__colors a': 'colorChange',
				'click .catalog-item__compare': 'compareClick'
			}, init: function init() {
				this.reinit();
			}, reinit: function reinit() {
				var maxImgHeight = 0;
				this.el.find('.catalog-item__image').each(function () {
					var heightImg = $(this).parent().parent().outerHeight();
					if (heightImg > maxImgHeight) {
						maxImgHeight = heightImg;
					}
				});
				this.el.find('.catalog-item__image-container').each(function () {
					$(this).css('min-height', maxImgHeight + 'px');
					$(this).css('height', maxImgHeight + 'px');
				});
				this.el.find('.catalog-item').each(function () {
					$(this).find('img').one('load', function () {
						var $item = $(this).closest('.catalog-item');
						$item.height($item.find('.catalog-item__top').outerHeight()).find('.catalog-item__top').css('min-height', $item.find('.catalog-item__aside').height());
					}).each(function () {
						if (this.complete) {
							$(this).load();
						}
					});
				});
				$('img,.lazy').lazyload({effect: 'fadeIn'});
			}, sizeChange: function sizeChange() {
				$(this).closest('.catalog-item').find('.catalog-item-aside__links a').removeClass('active');
				$(this).addClass('active');
			}, colorChange: function colorChange() {
				$(this).closest('.catalog-item').find('.catalog-item-aside__colors a').removeClass('active');
				$(this).addClass('active');
			}, compareClick: function compareClick() {
				$(this).toggleClass('active');
			}
		});
	}
	if ($('.sidebar__menu').length) {
		app.define('sidebarMenu', '.sidebar', {
			events: {
				'click .sidebar__button--menu': 'menuToggle',
				'click .sidebar-menu__link': 'menuSlideUp'
			}, menuToggle: function menuToggle() {
				$(this).toggleClass('active');
				$('.sidebar__menu').stop().slideToggle();
			}, menuSlideUp: function menuSlideUp() {
				if (app.window.width() < 991) {
					$('.sidebar__button--menu').toggleClass('active');
					$('.sidebar__menu').stop().slideUp();
					$('.sidebar__button--menu span').text($(this).text());
				}
			}, init: function init() {
				$('.page-title__title').addClass('reduced');
			}
		});
	}
	app.define('popupCallback', '.popup--callback', {
		init: function init() {
			$('.popup--callback form').validate({
				rules: {
					'callback-name': {required: true, validFio: true},
					'callback-phone': {required: true, validPhone: true}
				}, errorPlacement: function errorPlacement() {
					return true;
				}
			});
		}
	});
});
'use strict';
$(function () {
	app.define('header', '.header', {
		events: {
			'click .header__button--catalog-button': 'catalogMobileToggle',
			'click .header-fixed__button--catalog': 'catalogMobileToggle'
		}, mobileMenuBp: 991, fixed: false, countHeight: function countHeight() {
			$('.header').outerHeight();
		}, fixedChange: function fixedChange(fixed) {
			this.obj.fixed = fixed;
			if (fixed) {
				this.el.removeClass('not-fixed').addClass('fixed');
			} else {
				$('.main-menu, .header__catalog-menu').removeClass('fixed-active');
				this.el.removeClass('fixed').addClass('not-fixed');
			}
		}, catalogMobileToggle: function catalogMobileToggle(obj, event) {
			if (app.window.width() < obj.mobileMenuBp) {
				app.document.menuOpen(app.document, event);
				app.headerCatalog.buttonClick(app.headerCatalog, event, 0);
			} else {
				$('.header__catalog-menu').toggleClass('fixed-active');
			}
		}, fixSplitableDoors: function fixSplitableDoors() {
			var $splitableDoors = $('.header-catalog-menu__item--splitable-doors');
			if ($splitableDoors.length) {
				$splitableDoors.css('width', $splitableDoors.closest('.header-catalog-menu__list').width() - $splitableDoors.prev().position().left);
			}
		}, init: function init(obj) {
			obj.fixSplitableDoors();
		}
	});
	var timer;
	app.define('headerSearch', document, {
		events: {
			'click .header-search__button--submit': 'searchToggle',
			'keyup .header-search__input': 'searchKeyUpToggle',
			'mouseenter .header__search': 'searchResultShow',
			'mouseleave .header__search': 'searchResultHide',
			'mouseenter .title-search-result': 'searchResultHover',
			'mouseleave .title-search-result': 'searchResultLeave'
		}, $input: $('.header-search__input'), searchKeyUpToggle: function searchToggle(obj, event) {
			if (obj.$input.val() === '') {
				$('.header__search form').removeClass('active');
			} else {
				$('.header__search form').addClass('active');
			}
		}, searchToggle: function searchToggle(obj, event) {
			if (!$('.header__search').hasClass('hover')) {
				event.preventDefault();
				$('.header__search').addClass('hover');
				setTimeout(function () {
					obj.$input.focus();
					$('.header__search').addClass('hover-end');
				}, 100);
			} else {
				if (obj.$input.val() === '') {
					event.preventDefault();
					obj.$input.focus();
				}
			}
		}, searchResultShow: function searchResultShow(obj) {
			$('.header__search form').addClass('active');
			$('.title-search-result').length && $('.title-search-result').show();
			window.clearTimeout(timer);
		}, searchResultHover: function searchResultHover(obj) {
			$('.header__search').addClass('hover hover-end');
			$('.header__search form').addClass('active');
			window.clearTimeout(timer);
		}, searchResultLeave: function searchResultLeave(obj) {
			$('.header__search').removeClass('hover hover-end');
			$('.header__search form').removeClass('active');
			$(this).hide();
			window.clearTimeout(timer);
		}, searchResultHide: function searchResultHide(event) {
			window.clearTimeout(timer);
			timer = window.setTimeout(function request() {
				$('.header__search').removeClass('hover hover-end');
				$('.header__search form').removeClass('active');
				$('.title-search-result').hide();
			}, 500);
		}, init: function init() {
			$('.header__search form').validate({
				rules: {'header-search': {required: true}}, errorPlacement: function errorPlacement() {
					return true;
				}
			});
		}
	});
	app.define('headerFixedSearch', '.header-fixed__search', {
		events: {'click form': 'submitClick'},
		$input: $('.header-fixed-search .header_search__input'),
		submitClick: function submitClick(obj, event) {
			if (!obj.el.hasClass('active')) {
				event.preventDefault();
				obj.el.addClass('active');
				setTimeout(function () {
					obj.$input.focus();
				}, 100);
			} else {
				if (obj.$input.val() === '') {
					event.preventDefault();
					obj.$input.focus();
				}
			}
		},
		init: function init() {
			$('.header-fixed__search form').validate({
				rules: {'fixed-header-search': {required: true}},
				errorPlacement: function errorPlacement() {
					return true;
				}
			});
		}
	});
	app.define('offcanvasMenuSearch', '.offcanvas-menu__search', {
		init: function init() {
			$('.offcanvas-menu__search form').validate({
				rules: {'mobile-menu-search': {required: true}},
				errorPlacement: function errorPlacement() {
					return true;
				}
			});
		}
	});
	app.define('mainMenu', '.main-menu', {
		events: {
			'click .main-menu__link--has-items': 'itemClick',
			'click .main-menu__link--back': 'backLink'
		}, menuWidth: 270, menuDuration: 300, itemClick: function itemClick(obj, event) {
			var _this = this;
			if (app.window.width() < app.header.mobileMenuBp) {
				(function () {
					var $item = $(_this).closest('.main-menu__item');
					event.preventDefault();
					$item.addClass('active');
					app.document.$offcanvasInner.stop().animate({left: -obj.menuWidth}, obj.menuDuration, function () {
						$(this).addClass('inner-active').height($item.find('.main-menu__container').outerHeight() + $('.main-menu__link--back').outerHeight());
					});
				})();
			}
		}, backLink: function backLink(obj) {
			app.document.$offcanvasInner.removeClass('inner-active').height('auto').stop().animate({left: 0}, obj.menuDuration, function () {
				$('.main-menu__item').removeClass('active');
			});
		}
	});
	app.define('headerCatalog', '.header__catalog-menu', {
		events: {
			'click .header-catalog-menu__button': 'buttonClick',
			'click a.header-catalog-menu__link--has-items-mobile': 'innerItemClick',
			'click .header-catalog-menu__link--back': 'backLink'
		}, menuWidth: 270, menuDuration: 300, buttonClick: function buttonClick(obj, event) {
			var duration = arguments.length <= 2 || arguments[2] === undefined ? obj.menuDuration : arguments[2];
			if (app.window.width() < app.header.mobileMenuBp) {
				app.document.$offcanvas.addClass('mobile-catalog-active');
				app.document.$offcanvasInner.stop().animate({left: -obj.menuWidth}, duration, function () {
					$(this).addClass('inner-active').height($('.header-catalog-menu__container').height() + $('.header-catalog-menu__link--back').outerHeight());
				});
			}
		}, innerItemClick: function innerItemClick(obj, event) {
			if (app.window.width() < app.header.mobileMenuBp) {
				event.preventDefault();
				app.document.$offcanvas.addClass('mobile-catalog-active-inner');
				$(this).closest('.header-catalog-menu__item').addClass('active');
				$('.header-catalog-menu__container--level1').stop().animate({left: 0}, obj.menuDuration, function () {
					app.document.$offcanvasInner.height($('.header-catalog-menu__item--level1.active .header-catalog-menu__container--level2').height() + $('.header-catalog-menu__link--back').outerHeight());
				});
			}
		}, backLink: function backLink(obj) {
			if (app.document.$offcanvas.hasClass('mobile-catalog-active-inner')) {
				$('.header-catalog-menu__container--level1').stop().animate({left: obj.menuWidth}, obj.menuDuration, function () {
					$('.header-catalog-menu__item--has-items-mobile.active').removeClass('active');
					app.document.$offcanvas.removeClass('mobile-catalog-active-inner');
					app.document.$offcanvasInner.height($('.header-catalog-menu__container').height() + $('.header-catalog-menu__link--back').outerHeight());
				});
			} else {
				app.document.$offcanvasInner.stop().animate({left: 0}, obj.menuDuration, function () {
					app.document.$offcanvas.removeClass('mobile-catalog-active');
					$(this).removeClass('inner-active').height('auto');
				});
			}
		}, closeAll: function closeAll(obj) {
			$('.header-catalog-menu__container--level1').stop().animate({left: obj.menuWidth}, 0, function () {
				$('.header-catalog-menu__item--has-items-mobile.active').removeClass('active');
				app.document.$offcanvas.removeClass('mobile-catalog-active-inner');
				app.document.$offcanvasInner.height($('.header-catalog-menu__container').height() + $('.header-catalog-menu__link--back').outerHeight());
			});
			app.document.$offcanvasInner.stop().animate({left: 0}, 0, function () {
				app.document.$offcanvas.removeClass('mobile-catalog-active');
				$(this).removeClass('inner-active').height('auto');
			});
		}
	});
});
'use strict';
$(function () {
	if ($('.index').length) {
		if ($('.index-slider__item').length > 1) {
			app.define('indexSlider', '.index-slider', {
				init: function init() {
					$('.index-slider__slider').each(function (i, el) {
						var true_false = false;
						if ($(el).find('.index-slider__item').length <= 2) {
							true_false = false;
						} else {
							true_false = true;
						}
						$(el).owlCarousel({
							items: 1,
							lazyLoad: true,
							loop: true_false,
							nav: true,
							autoplay: true,
							autoplayTimeout: 5000,
							navText: [],
							navRewind: false,
							responsiveRefreshRate: 0,
							responsive: {1200: {items: 1}},
							onResized: function onResized() {
								app.owlCarousel.controlsRecount(this);
							},
							onInitialized: function onInitialized() {
								app.owlCarousel.controlsRecount(this);
							}
						});
					});
				}
			});
		}
	}
	if ($('.index-feedback__slider').length) {
		app.define('feedbackSlider', '.index-feedback__slider', {
			init: function init(obj, el) {
				el.owlCarousel({
					items: 1,
					dots: true,
					nav: false,
					navText: [],
					navRewind: false,
					responsiveRefreshRate: 0,
					autoHeight: true,
					responsive: {
						768: {nav: false, items: 2, margin: 20},
						991: {nav: true, items: 3, margin: 30},
						1300: {nav: true, items: 4, margin: 34}
					},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			}
		});
	}
	var top_btn = $('.move_top_arrow');
	window.addEventListener('scroll', function () {
		var scrollPos = document.documentElement.scrollTop;
		if (scrollPos > 400) {
			top_btn.fadeIn('slow', 'linear');
		} else {
			top_btn.fadeOut(500);
		}
	});
	top_btn.on('click', function () {
		$('html,body').animate({scrollTop: 0}, 600);
	});
	$('.toggled-elem').on('click', function () {
		$(this).toggleClass('toggled-elem-on').siblings('.toggled-item').fadeToggle();
	});
	if ($('.js-img-blocks-list').length) {
		app.define('projectSlider', '.js-img-blocks-list', {
			init: function init(obj, el) {
				el.owlCarousel({
					items: 1,
					dots: true,
					nav: false,
					responsiveRefreshRate: 0,
					autoHeight: true,
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			}
		});
	}
	if ($('.project__box-slider').length) {
		app.define('projectSlider', '.project__box-slider', {
			init: function init(obj, el) {
				el.owlCarousel({
					items: 1,
					dots: true,
					nav: false,
					navText: [],
					navRewind: false,
					responsiveRefreshRate: 0,
					autoHeight: true,
					responsive: {
						767: {nav: false, items: 2, margin: 20},
						991: {nav: true, items: 3, margin: 30},
						1300: {nav: true, items: 3, margin: 34}
					},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			}
		});
	}
	if ($('.related__slider').length) {
		app.define('relatedSlider', '.related__slider', {
			init: function init(obj, el) {
				el.owlCarousel({
					items: 1,
					dots: true,
					nav: true,
					navText: [],
					navRewind: false,
					responsiveRefreshRate: 0,
					margin: 5,
					dotsEach: 5,
					responsive: {
						// 390: {dots: true, nav: true, items: 5, margin: 5},
						// 460: {items: 6, margin: 5},
						// 600: {items: 3, margin: 20},
						// 768: {items: 4, margin: 20},
						// 991: {items: 5, margin: 30},
						// 1200: {items: 8, margin: 30}
						390: {dots: true, items: 1, margin: 5},
						460: {items: 2, margin: 5},
						768: {items: 3, margin: 10},
						991: {items: 4, margin: 10},
						1200: {items: 5, margin: 10}
					},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			}
		});
	}
	if ($('.index-brands__slider').length) {
		app.define('brandsSlider', '.index-brands__slider', {
			init: function init(obj, el) {
				el.owlCarousel({
					items: 2,
					lazyLoad: true,
					nav: true,
					navText: [],
					navRewind: false,
					responsiveRefreshRate: 0,
					responsive: {480: {items: 3}, 768: {items: 4}, 1024: {items: 5}, 1200: {items: 6}},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			}
		});
	}
	if ($('.index-news__slider').length) {
		app.define('newsSlider', '.index-news__slider', {
			init: function init(obj, el) {
				el.owlCarousel({
					items: 1,
					lazyLoad: true,
					nav: false,
					navRewind: false,
					dotsEach: 1,
					navText: [],
					responsiveRefreshRate: 0,
					margin: 0,
					loop: true,
					autoHeight: true,
					responsive: {
						768: {items: 2, nav: false, margin: 20},
						991: {nav: true, items: 3, margin: 30},
						1300: {nav: true, items: 3, margin: 34}
					},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
			}
		});
	}
});
'use strict';
$(function () {
	if ($('.catalog').length) {
		app.define('sidebarFilters', '.sidebar--filters', {
			events: {
				'click .filters__title': 'filterToggle',
				'change input[type="checkbox"]': 'checkboxChange',
				'click .sidebar__filters-close': 'filtersClose'
			}, filterToggle: function filterToggle() {
				if (app.window.width() < 991) {
					$(this).closest('.filters__filter').toggleClass('active').find('.filters__inner').stop().slideToggle();
				}
			}, checkboxChange: function checkboxChange() {
				var $filter = $(this).closest('.filters__filter');
				if ($filter.find(':checked').length) {
					$filter.addClass('active');
					$filter.find('.filters__inner').show();
				}
			}, filtersClose: function filtersClose(obj, event) {
				app.document.menuClose(app.document, event, '-100%');
			}, init: function init() {
				$('.filters__filter').each(function () {
					if (!$(this).hasClass('active')) {
						$(this).find('.filters__inner').slideUp(0);
					} else {
						$(this).find('.filters__inner').slideDown(0);
					}
				});
			}
		});
		app.define('catalogTopbar', '.catalog__topbar', {
			events: {'click .catalog__filter-link': 'filtersToggle'},
			filtersWidth: 270,
			filtersToggle: function filtersToggle(obj, event) {
				if (app.window.width() >= 600) {
					app.document.menuOpen(app.document, event, false, -obj.filtersWidth);
				} else {
					app.document.menuOpen(app.document, event, false, '-100%');
				}
			}
		});
		app.define('filtersColors', '.filters__colors', {
			events: {
				'click .filters-color-block__toggler': 'togglerClick',
				'click .filters-color-block__title': 'toggleBlock',
				'change .filters-color-block__checkbox input': 'checkboxChange',
				'change .js-color-checkbox input': 'checkboxColorChange'
			}, togglerClick: function togglerClick() {
				var $block = $(this).closest('.filters__color-block');
				var labels = $block.find('.js-color-checkbox');
				var inputs = $block.find('input[type="checkbox"]');
				if ($(this).hasClass('checked')) {
					$(this).removeClass('checked has-checked');
					inputs.prop('checked', false);
					labels.removeClass('has-checked');
				} else {
					$(this).addClass('checked has-checked');
					inputs.prop('checked', true);
					labels.addClass('has-checked');
				}
			}, toggleBlock: function toggleBlock() {
				$(this).toggleClass('active').closest('.filters__color-block').find('.filters-color-block__inner').stop().slideToggle();
			}, checkboxChange: function checkboxChange() {
				var $block = $(this).closest('.filters__color-block');
				if ($block.find(':checked').length) {
					$block.find('.filters-color-block__toggler').addClass('has-checked');
					if ($block.find(':checked').length === $block.find('input[type="checkbox"]').length) {
						$block.find('.filters-color-block__toggler').addClass('checked');
					} else {
						$block.find('.filters-color-block__toggler').removeClass('checked');
					}
				} else {
					$block.find('.filters-color-block__toggler').removeClass('has-checked');
				}
			}, checkboxColorChange: function checkboxColorChange() {
				var checkbox = $(this);
				var $block = checkbox.closest('.js-color-checkbox');
				if (checkbox.prop('checked')) {
					$block.addClass('has-checked');
				} else {
					$block.removeClass('has-checked');
				}
			}
		});
		app.define('sidebarRange', '.filters__range', {
			events: {'keyup .filters-range__input--from': 'inputFromChange', 'keyup .filters-range__input--to': 'inputToChange'},
			$range: $('.filters-range__slider-inner'),
			$inputFrom: $('.filters-range__input--from'),
			$inputTo: $('.filters-range__input--to'),
			inputFromChange: function inputFromChange(obj) {
				if (window.rangePrice) {
					var $this = $(this);
					var val = obj.removeSpaces($this.val());
					if (val === '') {
						obj.$range.slider('values', 0, obj.rangeMin);
					} else {
						if (val <= obj.$range.slider('values', 1)) {
							obj.$range.slider('values', [val, obj.removeSpaces(obj.$inputTo.val())]);
						}
					}
					$this.trigger('keyup');
				}
			},
			inputToChange: function inputToChange(obj) {
				if (window.rangePrice) {
					var $this = $(this);
					var val = obj.removeSpaces($this.val());
					if (val === '') {
						obj.$range.slider('values', 1, obj.rangeMax);
					} else {
						if (val >= obj.$range.slider('values', 0)) {
							obj.$range.slider('values', [obj.removeSpaces(obj.$inputFrom.val()), val]);
						}
					}
					$this.trigger('keyup');
				}
			},
			numberFormat: function numberFormat(num) {
				return num.toString();
			},
			removeSpaces: function removeSpaces(str) {
				return str.replace(/\s/g, '');
			},
			rangeMin: 0,
			rangeMax: 0,
			init: function init(obj) {
				if (window.rangePrice) {
					obj.rangeMin = parseFloat(window.rangePrice.minPrice) || obj.rangeMin;
					obj.rangeMax = parseFloat(window.rangePrice.maxPrice) || obj.rangeMax;
					var currentMin = parseFloat(window.rangePrice.curMinPrice) || obj.rangeMin;
					var currentMax = parseFloat(window.rangePrice.curMaxPrice) || obj.rangeMax;
					$().slider && obj.$range.slider({
						range: true,
						min: obj.rangeMin,
						max: obj.rangeMax,
						step: 1,
						values: [currentMin, currentMax],
						slide: function slide(event, ui) {
							if (ui.handle.nextSibling) {
								obj.$inputFrom.val(obj.numberFormat(ui.values[0] !== obj.rangeMin ? ui.values[0] : obj.rangeMin));
							} else {
								obj.$inputTo.val(obj.numberFormat(ui.values[1] !== obj.rangeMax ? ui.values[1] : obj.rangeMax));
							}
							obj.$inputFrom.trigger('keyup');
						}
					}).draggable();
				}
			}
		});
	}
	if ($('.catalog--search').length) {
		app.define('searchPage', '.catalog--search', {
			init: function init() {
				$('.catalog__search form').validate({
					rules: {'search-input': {required: true}}, errorPlacement: function errorPlacement() {
						return true;
					}
				});
			}
		});
	}
});
'use strict';
$(function () {
	app.define('popup', '.js-show-contacts', {
		events: {'click .js-show-contacts': 'showMobnavPopup'}, showMobnavPopup: function () {
			$(this).toggleClass('shown');
		}
	});
	app.define('product', '.catalog.catalog--main', {
		events: {
			'click .product-view-links__link': 'viewChange',
			'click .product-filter__type': 'typeChange',
			'click .product-filter-color__link': 'doorColorChange',
			'click .product-price__color-link': 'colorChange',
			'click .product-filter-price-tabs__tab': 'priceTabChange',
			'click .product-filter-price-tabs__configure': 'configureToggle',
			'click .product-title__toggler': 'imagesBlockToggle'
		}, configureToggleDuration: 300, viewChange: function viewChange() {
			if (!$(this).hasClass('active')) {
				$('.product-view-links__link').removeClass('active');
				$(this).addClass('active');
				$('.product-preview').css({backgroundImage: 'url(' + $(this).data('image') + ')', backgroundColor: $(this).data('color')});
			}
		}, typeChange: function typeChange() {
			if (!$(this).hasClass('active')) {
				$('.product-filter__type').removeClass('active');
				$(this).addClass('active');
			}
		}, doorColorChange: function doorColorChange() {
			if (!$(this).hasClass('active')) {
				$(this).closest('.product-filter-color__inner').find('.product-filter-color__link').removeClass('active');
				$(this).addClass('active');
			}
		}, colorChange: function colorChange() {
			if (!$(this).hasClass('active')) {
				$(this).closest('.product-price__colors').find('.product-price__color-link').removeClass('active');
				$(this).addClass('active');
			}
		}, priceTabChange: function priceTabChange(obj) {
			if (!$(this).hasClass('active')) {
				$('.product-filter-price-tabs__tab').removeClass('active');
				$(this).addClass('active');
				var detailToggler = $('.product-filter-price-tabs__configure');
				if ($(this).hasClass('detail-price-complect')) {
					detailToggler.show();
				} else {
					detailToggler.hide();
					$('.product-filter__complect').stop().slideUp(obj.configureToggleDuration);
				}
				if (typeof setDetailPrices !== 'undefined') {
					setDetailPrices();
				}
			}
		}, configureToggle: function configureToggle(obj) {
			if ($('.product-filter__complect').is(':hidden')) {
				obj.tableMaxHeight();
			}
			$('.product-filter__complect').stop().slideToggle(obj.configureToggleDuration);
		}, imagesBlockToggle: function imagesBlockToggle() {
			if (app.window.width() < 991) {
				$(this).toggleClass('inactive').closest('div').parent().find('.toggle-block').stop().slideToggle();
			}
		}, tableMaxHeight: function tableMaxHeight() {
			$('.product-filter-complect__inner').outerHeight($('.product-preview').height() - $('.product__filter').outerHeight());
		}, initScripts: function initScripts() {
			$('.product-gallery__link:not(.product-gallery__link__video)').magnificPopup({type: 'image', gallery: {enabled: true}});
			$('.product-gallery__link__video').magnificPopup({disableOn: 700, type: 'iframe'});
		}, init: function init(obj) {
			$('.product-filter__select').not('.not-auto-init').selectmenu();
			obj.initScripts();
		}
	});
	if ($('.product__gallery-large').length) {
		app.define('galleryLarge', '.product__gallery-large', {
			events: {'click .product-gallery-large-previews__item': 'previewClick', 'click .product-gallery-large__inner': 'mediumClick'},
			$bottom: $('.product-gallery-large__container--bottom'),
			$top: $('.product-gallery-large__container--top'),
			$item: $('.product-gallery-large-previews__item'),
			galleryDuration: 200,
			previewClick: function previewClick(obj, event) {
				var _this = this;
				event.preventDefault();
				if (!$(this).hasClass('active')) {
					(function () {
						var dataMedium = $(_this).data('medium');
						obj.$item.removeClass('active');
						$(_this).addClass('active');
						obj.$bottom.find('img').attr('src', dataMedium).end().stop().animate({opacity: 1}, obj.galleryDuration);
						obj.$top.stop().animate({opacity: 0}, obj.galleryDuration, function () {
							$(this).find('img').attr('src', dataMedium).end().css('opacity', 1);
							obj.$bottom.css('opacity', 0);
						});
					})();
				}
			},
			mediumClick: function mediumClick() {
				var $gallery = $(this).closest('.product__gallery-large');
				$gallery.find('.product-gallery-large__previews').magnificPopup('open', $gallery.find('.product-gallery-large-previews__item.active').index());
			},
			init: function init(obj) {
				$('.product-gallery-large__previews').magnificPopup({
					delegate: obj.$item,
					type: 'image',
					gallery: {enabled: true}
				}).unbind('click');
			}
		});
	}
});
'use strict';
$(function () {
	app.define('orderForm', '.order__form', {
		events: {'click .order__button--file': 'triggerFileButton'},
		triggerFileButton: function triggerFileButton(obj) {
			obj.el.find('.order__file-input').last().trigger('click');
		},
		init: function init() {
			if ($('.order__file-input').length) {
				var formats = 'doc|docx|odt|rar|zip|7z|pdf';
				$('.order__file-input').MultiFile({
					max: 5,
					maxfile: 20 * 1024,
					accept: formats,
					list: '.order__files-container',
					STRING: {
						file: '<span class="order__files-text" title="Нажмите крестик, чтобы отменить загрузку файла">$file</span>',
						denied: 'Вы не можете загрузить файл с расширением $ext!\r\rДоступные расширения:\r' + formats.split('|').join(', '),
						duplicate: 'Данный файл уже выбран!',
						toomany: 'Выбрано слишком много файлов (Максимум: $max)',
						toobig: '$file слишком большой (Максимальный размер $size)'
					}
				});
			}
		}
	});
});
'use strict';
$(function () {
	if ($('.compare').length) {
		app.define('compare', '.compare', {
			events: {
				'mouseenter .compare-content-slider__parameter': 'parameterMouseenter',
				'mouseleave .compare-content-slider__parameter': 'parameterMouseleave',
				'click .compare-categories__button--category': 'categoryChange',
				'click .compare-categories__button--toggler': 'categoriesToggle'
			}, controlsRecount: function controlsRecount() {
				$('.compare-content__tab').each(function () {
					var $tab = $(this);
					var itemsCount = $tab.find('.compare-content-slider__item').length;
					var activeItems = Math.floor($tab.find('.compare-content-slider__inner').width() / $tab.find('.owl-item').width());
					if (itemsCount > activeItems) {
						$tab.find('.compare-content-controls__scroller').width(100 / (itemsCount / activeItems) + '%').end().find('.compare-content-controls__slider-scroll').removeClass('hidden').end().find('.owl-controls').show();
					} else {
						$tab.find('.compare-content-controls__slider-scroll').addClass('hidden').end().find('.owl-controls').hide();
					}
				});
			}, countTopHeight: function countTopHeight($tab) {
				var $topContainer = $tab.find('.compare-content-slider__top');
				var maxHeight = 0;
				$topContainer.height('auto').each(function () {
					var newHeight = $(this).outerHeight(true);
					if (newHeight > maxHeight) {
						maxHeight = newHeight;
					}
				});
				$topContainer.outerHeight(maxHeight);
				$tab.find('.owl-nav > div').css('top', maxHeight / 2).end().find('.owl-dots').css('top', maxHeight);
			}, countHeights: function countHeights() {
				$('.compare-content-slider__parameter').height('auto');
				$('.compare-content__tab').each(function () {
					var $tab = $(this);
					var heights = [];
					for (var i = 0, length = $tab.find('.compare-content-slider__parameter--title').length; i < length; i++) {
						var $lists = $tab.find('.compare-content-slider__parameters');
						heights.push(0);
						for (var j = 0, _length = $lists.length; j < _length; j++) {
							var newHeight = $lists.eq(j).find('.compare-content-slider__parameter').eq(i).outerHeight();
							if (newHeight > heights[i]) {
								heights[i] = newHeight;
							}
						}
						$lists.find('.compare-content-slider__parameter:nth-child(' + (i + 1) + ')').outerHeight(heights[i]);
					}
				});
			}, countPositions: function countPositions() {
				$('.compare-content__tab').each(function () {
					var $tab = $(this);
					$tab.find('.compare-content-slider__parameter--title').each(function (n) {
						$(this).css('top', $tab.find('.compare-content-slider__inner .compare-content-slider__parameter').eq(n).position().top);
						$tab.find('.compare-content-slider__inner .compare-content-slider__parameter:nth-child(' + (n + 1) + ')').css('margin-top', $(this).outerHeight());
					});
				});
			}, parameterMouseenter: function parameterMouseenter() {
				var _this = this;
				if (!navigator.MOBILE) {
					(function () {
						var $this = $(_this);
						var $tab = $('.compare-content__tab.active');
						var index = $this.index();
						$tab.find('.compare-content-slider__parameters').each(function () {
							$(this).find('.compare-content-slider__parameter').eq(index).addClass('hover');
							$('.compare-content-slider__parameter--title').eq(index).addClass('hover');
						});
					})();
				}
			}, parameterMouseleave: function parameterMouseleave() {
				$('.compare-content-slider__parameter--title, .compare-content-slider__parameter').removeClass('hover');
			}, categoryChange: function categoryChange() {
				var $this = $(this);
				if (!$this.hasClass('active')) {
					var $toggler = $('.compare-categories__button--toggler');
					$('.compare-categories__button--category').removeClass('active');
					$this.addClass('active');
					$('.compare-content__tab').removeClass('active').eq($this.index()).addClass('active');
					$toggler.find('span').text($this.find('span').text());
					if (app.window.width() < 600) {
						$toggler.toggleClass('active');
						$('.compare-categories__menu').stop().slideToggle();
					}
				}
			}, categoriesToggle: function categoriesToggle() {
				$(this).toggleClass('active');
				$('.compare-categories__menu').stop().slideToggle();
			}, init: function init(obj) {
				$('.compare-content__tab').each(function () {
					var $tab = $(this);
					var items = $tab.find('.compare-content-slider__item').length === 1 ? 1 : 2;
					$tab.find('.compare-content-slider__inner').owlCarousel({
						items: items,
						nav: true,
						dots: true,
						dotsEach: 1,
						navText: [],
						navRewind: false,
						margin: -1,
						responsiveRefreshRate: 0,
						responsive: {
							500: {items: items},
							640: {items: 3},
							840: {items: 3, stagePadding: 25},
							1024: {items: 4, stagePadding: 25}
						},
						onTranslate: function onTranslate() {
							if (!obj.scrollInterval) {
								obj.scrollInterval = setInterval(function () {
									var itemWidth = $tab.find('.owl-item').width() - 1;
									var itemsCount = $tab.find('.compare-content-slider__item').length;
									var sliderTransformX = parseInt($tab.find('.owl-stage').css('transform').split(',')[4]);
									if (sliderTransformX < 0) {
										$tab.find('.compare-content-controls__scroller').css('left', Math.abs(sliderTransformX) / (itemWidth * itemsCount / 100) + '%');
									} else {
										$tab.find('.compare-content-controls__scroller').css('left', 0);
									}
								}, 1);
							}
						},
						onTranslated: function onTranslated() {
							clearInterval(obj.scrollInterval);
							obj.scrollInterval = null;
						},
						onChanged: function onChanged() {
							obj.controlsRecount();
						}
					});
					obj.el.imagesLoaded(function () {
						obj.countTopHeight($tab);
						obj.countHeights();
						obj.countPositions();
					});
				});
				obj.controlsRecount();
			}
		});
	}
});
'use strict';
$(function () {
	if ($('.text-content').length) {
		app.define('textContent', '.text-content', {
			init: function init(obj, el) {
				var $slider = el.find('.slider');
				el.find('table').wrap('<div class="table-wrapper"></div>');
				$slider.owlCarousel({
					items: 1,
					lazyLoad: false,
					nav: true,
					navText: [],
					responsiveRefreshRate: 0,
					margin: 20,
					responsive: {320: {items: 1}, 710: {items: 2}},
					onResized: function onResized() {
						app.owlCarousel.controlsRecount(this);
					},
					onInitialized: function onInitialized() {
						app.owlCarousel.controlsRecount(this);
					}
				});
				if ($slider.find('a').length) {
					$slider.find('a').magnificPopup({type: 'image', gallery: {enabled: true}});
				}
			}
		});
	}
	if ($('.contacts__form').length) {
		app.define('contactsForm', '.contacts', {
			init: function init() {
			}
		});
	}
	if ($('.splitable-doors__tabs').length) {
		app.define('splitableDoorsTabs', '.splitable-doors__tabs', {
			events: {
				'click .splitable-doors-tabs__button--menu': 'menuToggle',
				'click .splitable-doors-tabs-menu__item': 'menuSlideUp'
			}, menuToggle: function menuToggle() {
				$(this).toggleClass('active');
				$('.splitable-doors-tabs__menu').stop().slideToggle();
			}, menuSlideUp: function menuSlideUp() {
				if (app.window.width() < 768 && !$(this).hasClass('active')) {
					$('.splitable-doors-tabs__button--menu').toggleClass('active');
					$('.splitable-doors-tabs__menu').stop().slideUp();
					$('.splitable-doors-tabs__button--menu span').text($(this).text());
				}
			}, init: function init() {
				$('.page-title__title').addClass('reduced');
			}
		});
	}
	if ($('.feedback').length) {
		app.define('feedback', '.feedback', {
			events: {'click .feedback__button--show-form': 'formToggle'},
			formToggle: function formToggle() {
				$('.feedback__form').slideToggle();
			},
			init: function init() {
			}
		});
	}
	if ($('.stores').length) {
		app.define('stores', '.stores', {
			init: function init(obj) {
				$('.stores__gallery').each(function () {
					$(this).find('.stores__image-link').magnificPopup({type: 'image', gallery: {enabled: true}});
				});
			}
		});
	}
});
'use strict';
$(function () {
	app.define('window', window, {
		events: {scroll: 'scroll', resize: 'resize', touchstart: 'touchstartEvent', touchmove: 'touchmoveEvent'},
		depositInit: function depositInit() {
			this.obj.scroll(this.obj);
			this.obj.resize(this.obj);
		},
		scrollY: function scrollY() {
			return this.el.scrollTop();
		},
		scroll: function scroll(obj) {
			var isFixed = obj.scrollY() > $('.header').outerHeight();
			app.header.fixedChange(isFixed);
		},
		touchStart: 0,
		touchEnd: 0,
		touchstartEvent: function touchstartEvent(obj, event) {
			if (app.document.menuOpened) {
				obj.touchStart = event.originalEvent.touches[0].clientY;
			}
		},
		touchmoveEvent: function touchmoveEvent(obj, event) {
			if (app.document.menuOpened) {
				var menuHeight = app.document.el.hasClass('filters-opened') ? app.sidebarFilters.el.outerHeight() : app.document.$offcanvasInner.outerHeight();
				obj.touchEnd = event.originalEvent.touches[0].clientY;
				if (app.document.$offcanvasMenu.scrollTop() + obj.el.height() >= menuHeight && obj.touchEnd < obj.touchStart || app.document.$offcanvasMenu.scrollTop() === 0 && obj.touchEnd > obj.touchStart) {
					event.preventDefault();
				}
			}
		},
		breakpoints: [[991, false], [768, false], [600, false], [0, false]],
		width: function width() {
			return window.innerWidth;
		},
		resize: function resize(obj) {
			var prevBp = '';
			var breakpointChange = function breakpointChange(currentBp) {
				for (var i = 0, length = obj.breakpoints.length; i < length; i++) {
					var breakpoint = obj.breakpoints[i];
					if (breakpoint[1]) {
						prevBp = breakpoint[0];
						breakpoint[1] = false;
					}
					if (breakpoint[0] === currentBp) {
						breakpoint[1] = true;
					}
				}
				var $sidebarFilters = $('.sidebar--filters');
				if (currentBp < app.header.mobileMenuBp) {
					$('.main-menu').removeClass('fixed-active').insertAfter('.offcanvas-menu__shop-links');
					$('.header__catalog-menu').removeClass('fixed-active').insertAfter('.offcanvas-menu__search');
					if ($sidebarFilters.length) {
						$sidebarFilters.insertAfter('.offcanvas-menu__inner').css({display: 'block'});
					}
				} else {
					if (prevBp != null) {
						$('.main-menu').insertAfter('.header__menu-button');
						$('.header__catalog-menu').insertAfter('.header__button--catalog-button');
						if ($sidebarFilters.length) {
							$sidebarFilters.insertBefore('.catalog__content');
						}
					}
				}
				if (currentBp >= app.header.mobileMenuBp && prevBp != null && prevBp < app.header.mobileMenuBp) {
					app.document.$offcanvas.removeClass('mobile-catalog-active mobile-catalog-active-inner');
					if (app.document.menuOpened) {
						$('.offcanvas__shim').trigger('click');
					}
				}
			};
			for (var i = 0, length = obj.breakpoints.length; i < length; i++) {
				var breakpoint = obj.breakpoints[i];
				if (i === 0) {
					if (obj.width() >= breakpoint[0] && !breakpoint[1]) {
						breakpointChange(breakpoint[0]);
					}
				} else {
					if (i < length - 1) {
						if (obj.width() >= breakpoint[0] && obj.width() < obj.breakpoints[i - 1][0] && !breakpoint[1]) {
							breakpointChange(breakpoint[0]);
						}
					} else {
						if (obj.width() < obj.breakpoints[i - 1][0] && !breakpoint[1]) {
							breakpointChange(breakpoint[0]);
						}
					}
				}
			}
			app.header.fixSplitableDoors();
			if ($('.compare').length) {
				$('.compare-content__tab').each(function () {
					app.compare.countTopHeight($(this));
				});
				app.compare.countHeights();
				app.compare.controlsRecount();
				if (obj.width() < 840) {
					app.compare.countPositions();
				}
			}
			if ($('.catalog--main').length) {
				if (obj.width() >= 991) {
					$('.catalog__list .catalog-item').removeClass('right-item').each(function () {
						if ($(this).closest('.catalog__list').width() - $(this).offset().left < 150) {
							$(this).addClass('right-item');
						}
					});
				} else {
					$('.catalog__list .catalog-item').removeClass('right-item');
				}
			}
		},
		init: function init(obj, el) {
			if ($('.product-preview').length) {
				el.load(function () {
					$('.product-view-links__link').each(function () {
						$(this).append('<img src="' + $(this).data('image') + '" class="product-view-links__hidden">');
					});
				});
			}
		}
	});
});
'use strict';
$(function () {
	app.window.depositInit();
});
$.fn.setMaxHeights = function () {
	var maxHeight = this.map(function (i, e) {
		return $(e).height();
	}).get();
	return this.height(Math.max.apply(this, maxHeight));
};
$(document).ready(function () {
	BX.addCustomEvent('onAjaxSuccess', function () {
		$('img,.lazy').lazyload({effect: 'fadeIn'});
		app.detailItemSlider.init(app.detailItemSlider);
	});
	var body = document.body;
	navigator.BROWSER = (function () {
		var ua = navigator.userAgent;
		var tem;
		var M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
		if (/trident/i.test(M[1])) {
			tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
			return 'IE ' + (tem[1] || '');
		}
		if (M[1] === 'Chrome') {
			tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
			if (tem != null) {
				return tem.slice(1).join(' ').replace('OPR', 'Opera');
			}
		}
		M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
		if ((tem = ua.match(/version\/(\d+)/i)) != null) {
			M.splice(1, 1, tem[1]);
		}
		return M.join(' ');
	})();
	navigator.OS = (function () {
		if (navigator.appVersion.indexOf('Win') != -1) {
			return 'Windows';
		}
		if (navigator.appVersion.indexOf('Mac') != -1) {
			return 'MacOS';
		}
		if (navigator.appVersion.indexOf('X11') != -1) {
			return 'UNIX';
		}
		if (navigator.appVersion.indexOf('Linux') != -1) {
			return 'Linux';
		}
	})();
	navigator.MOBILE = '';
	(function (a) {
		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) {
			navigator.MOBILE = 'mobile';
		}
	})(navigator.userAgent || navigator.vendor || window.opera);
	navigator.SCROLLBAR = window.outerWidth - body.clientWidth;
	if (body.className !== '') {
		body.className += ' ';
	}
	body.className += 'browser-' + navigator.BROWSER.toLowerCase().split(' ')[0];
	body.className += ' os-' + navigator.OS.toLowerCase();
	if (navigator.MOBILE) {
		body.className += ' ' + navigator.MOBILE;
	}
	if (navigator.SCROLLBAR > 0) {
		body.className += ' has-scrollbar';
	}
	$('img,.lazy').lazyload({effect: 'fadeIn'});
	$('html,body').on('scroll', function () {
		$('img,.lazy').lazyload({effect: 'fadeIn'});
	});
	$('body').on('click', '.msgSent', function () {
		$.magnificPopup.close();
	});
	fixedBlockWhenScrolling();
	setBannerHeight();
	flexMenu();
	toggleClassBlock();
	toggleAccordion();
	toggleCheckboxBlock();
	toggleFade();
	scrollToBlock();
	initMaskInput();
	openChatBtn();
	hideCharacteristicTabInMobile();
	validateSearchForm();
	$(window).on('resize', setBannerHeight);
	/*var scrollMain = false;
	$(window).on('scroll', function () {
		if (scrollMain) {
			return true;
		}
		scrollMain = true;
		var main_container = $('.js_main_container');
		if (main_container.length > 0) {
			$.ajax({
				type: 'POST', url: '/include/main_container.php', success: function (html) {
					$('.js_main_container').html(html);
					$('.index-feedback__slider').owlCarousel({
						items: 1,
						dots: true,
						nav: false,
						navText: [],
						navRewind: false,
						responsiveRefreshRate: 0,
						autoHeight: true,
						responsive: {
							768: {nav: false, items: 2, margin: 20},
							991: {nav: true, items: 3, margin: 30},
							1300: {nav: true, items: 4, margin: 34}
						},
						onResized: function onResized() {
							app.owlCarousel.controlsRecount(this);
						},
						onInitialized: function onInitialized() {
							app.owlCarousel.controlsRecount(this);
						}
					});
					$('.project__box-slider, .index-news__slider').owlCarousel({
						items: 1,
						dots: true,
						nav: false,
						navText: [],
						navRewind: false,
						responsiveRefreshRate: 0,
						autoHeight: true,
						responsive: {
							768: {nav: false, items: 2, margin: 20},
							991: {nav: true, items: 3, margin: 30},
							1300: {nav: true, items: 3, margin: 34}
						},
						onResized: function onResized() {
							app.owlCarousel.controlsRecount(this);
						},
						onInitialized: function onInitialized() {
							app.owlCarousel.controlsRecount(this);
						}
					});
					$('img,.lazy').lazyload({effect: 'fadeIn'});
				}
			});
		}
	});*/
});

function setBannerHeight() {
	var width = $('.section-image__container').width();
	$('.section-image__block').css('height', width * 0.2 + 'px');
}

function removeElementsByClass(className) {
	var elements = document.getElementsByClassName(className);
	while (elements.length > 0) {
		elements[0].parentNode.removeChild(elements[0]);
	}
}

removeElementsByClass('imageobject');

function hideCharacteristicTabInMobile() {
	var win = $(window);
	var startWidth = win.width();
	var endWidth = 0;
	var init = false;
	var labels = $('.js-tab-label');
	win.off('resize.uncheckTabProductPage').on('resize.uncheckTabProductPage', function () {
		endWidth = win.width();
		if (endWidth <= 600 && !init || endWidth <= 600 && startWidth != endWidth) {
			$('#tab2').removeAttr('checked');
			init = true;
			startWidth = endWidth;
			labels.click(function () {
				var label = $(this);
				var input = $('#' + label.attr('for'));
				if (input.prop('checked')) {
					setTimeout(function () {
						input.prop('checked', false);
					}, 10);
				}
			});
		}
	}).trigger('resize.uncheckTabProductPage');
}

function fixedBlockWhenScrolling() {
	$('.js-fixed').each(function (index) {
		var block = $(this);
		var blockHeight = block.outerHeight();
		var blockStart = block.offset().top;
		var doc = $(document);

		function fixedBlockWhenScrolling() {
			var docScroll = doc.scrollTop();
			if (blockStart <= docScroll) {
				block.addClass('active-fixed').css('padding-top', blockHeight);
			} else {
				if (blockStart >= docScroll) {
					block.removeClass('active-fixed').css('padding-top', 0);
				}
			}
		}

		doc.off('scroll.fixedBlockWhenScrolling' + index).on('scroll.fixedBlockWhenScrolling' + index, function () {
			if (requestAnimationFrame) {
				requestAnimationFrame(fixedBlockWhenScrolling);
			} else {
				fixedBlockWhenScrolling();
			}
		}).trigger('scroll.fixedBlockWhenScrolling' + index);
	});
}

function flexMenu() {
	if ($.fn.flexMenu) {
		var parents = $('.js-queries-list');
		parents.each(function () {
			$(this).flexMenu({
				linkText: 'Еще...',
				linkTitle: 'Показать еще',
				linkTextAll: 'Список запросов',
				linkTitleAll: 'Показать список',
				cutoff: 1,
				showOnHover: false,
				shouldApply: function () {
					return $(window).width() > 600;
				}
			});
		});
		var toggler = parents.find('.flexMenu-viewMore');
		var content = parents.find('.flexMenu-popup');
		parents.off('click.flexMenu').on('click.flexMenu', function (event) {
			event.stopPropagation();
		});
		$(document).off('click.flexMenu').on('click.flexMenu', function () {
			toggler.removeClass('active');
			content.hide();
		});
	}
}

function toggleClassBlock() {
	var allParents = $('.js-class');
	allParents.each(function () {
		var parent = $(this);
		var activeClass = parent.data('class') || 'active-class';
		parent.find('.js-class__toggler').off('click.toggleClass').on('click.toggleClass', function (event) {
			event.stopPropagation();
			event.preventDefault();
			if (!parent.hasClass(activeClass)) {
				parent.addClass(activeClass);
			} else {
				parent.removeClass(activeClass);
			}
		});
	});
}

function toggleAccordion() {
	$('.js-accordion').each(function () {
		var parent = $(this);
		var content = parent.find('.js-accordion__content');
		if (!parent.hasClass('open-accordion')) {
			content.slideUp(0);
		}
		parent.addClass('init-accordion');
		parent.find('.js-accordion__toggler').off('click.toggleAccordion').on('click.toggleAccordion', function (event) {
			event.stopPropagation();
			event.preventDefault();
			parent.toggleClass('open-accordion');
			content.slideToggle(300);
		});
	});
}

function toggleCheckboxBlock() {
	$('.js-checkbox-block').each(function () {
		var parent = $(this);

		function toggleCheckboxBlock() {
			var content = $('.js-checkbox-block__content');
			var toggler = $('.js-checkbox-block__input');

			if (toggler.prop('checked')) {
				content.removeClass('hidden').fadeIn(300);
			} else {
				content.addClass('hidden').fadeOut(300);
			}
		}

		toggleCheckboxBlock();
		$(document).off('change.toggleCheckboxBlock').on('change.toggleCheckboxBlock', '.js-checkbox-block__input', toggleCheckboxBlock);
	});
}

function toggleFade() {
	var allParents = $('.js-fade');
	var allContents = allParents.find('.js-fade__content');
	allParents.each(function () {
		var parent = $(this);
		var content = parent.find('.js-fade__content');
		content.click(function (event) {
			event.stopPropagation();
		});
		parent.find('.js-fade__toggler').off('click.toggleFade').on('click.toggleFade', function (event) {
			event.preventDefault();
			event.stopPropagation();
			allParents.not(parent).removeClass('open-fade');
			allContents.not(content).fadeOut(300);
			if (!parent.hasClass('open-fade')) {
				openDropdown();
			} else {
				closeDropdown();
			}
		});

		function openDropdown() {
			parent.addClass('open-fade');
			content.fadeIn(300, function () {
				initSliderProductComplectPopup(content)
			});
		}

		function closeDropdown() {
			parent.removeClass('open-fade');
			content.fadeOut(300);
		}
	});
	toggleFade.closeAllFade = function () {
		allParents.removeClass('open-fade');
		allContents.fadeOut(300);
	};
	$(window).click(toggleFade.closeAllFade);
}

function scrollToBlock() {
	$('.js-scroll-to').each(function () {
		var _this = $(this);
		_this.off('click.scrollToBlock').on('click.scrollToBlock', function (event) {
			event.preventDefault();
			var offset = $(_this).data('offset') || 0;
			var block = $(_this.attr('href'));
			if (block.length) {
				var positionBlock = block.offset().top - offset;
				$([document.documentElement, document.body]).animate({scrollTop: positionBlock}, 400);
			}
		});
	});
}

function initMaskInput() {
	if ($.fn.mask) {
		$('input[name="PROP[PHONE]"]').mask('+7 (999) 999-99-99');
	}
}

function openChatBtn() {
	$('.js-open-chat').click(function () {
		if (window.jivo_api) {
			jivo_api.open();
		}
	});
}

function validateSearchForm() {
	$('.js-search-form').each(function () {
		var form = $(this);
		form.submit(function (event) {
			if (!form.find('input[type="text"]').val().length) {
				event.preventDefault();
			}
		});
	});
}
// карусель в скрытых попапах для комплектов на странице продукта
function initSliderProductComplectPopup(scope) {
	if ($.fn.owlCarousel) {
		$(scope).find('.js-popup-slider:not(.owl-loaded)').each(function () {
			var slider = $(this)

			slider.owlCarousel({
				items: 1,
				dots: true,
				nav: false,
				responsiveRefreshRate: 0,
				autoHeight: true
			})
		})
	}
}

// отсчет таймера
function coundownTimer() {
	if ($.fn.countdown) {
		// установка языка
		$.countdown.regionalOptions.ru = {
			labels: ['Лет', 'Месяцев', 'Недель', 'Дней', 'Часов', 'Минут', 'Секунд'],
			labels1: ['Год', 'Месяц', 'Неделя', 'День', 'Час', 'Минута', 'Секунда'],
			labels2: ['Года', 'Месяца', 'Недели', 'Дня', 'Часа', 'Минуты', 'Секунды'],
			compactLabels: ['л', 'м', 'н', 'д'],
			compactLabels1: ['г', 'м', 'н', 'д'],
			whichLabels: function (amount) {
				var units = amount % 10;
				var tens = Math.floor((amount % 100) / 10);
				return (amount === 1 ? 1 : (units >= 2 && units <= 4 && tens !== 1 ? 2 :
					(units === 1 && tens !== 1 ? 1 : 0)));
			},
			digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
			timeSeparator: ':',
			isRTL: false
		};
		$.countdown.setDefaults($.countdown.regionalOptions.ru);

		// инициализация
		$('.js-countdown-simple').each(function () {
			var parent = $(this)
			var dateEnd = new Date(parent.data('date'))

			parent.countdown({
				// format: 'YOWDHMS',
				format: 'DHMS',
				until: dateEnd
			});

			// запуск / пауза
			// parent.countdown('toggle');
		})
	}
}

BX.ready(function () {
	var upButton = document.querySelector('[data-role="eshopUpButton"]');
	BX.bind(upButton, 'click', function () {
		var windowScroll = BX.GetWindowScrollPos();
		(new BX.easing({
			duration: 500,
			start: {scroll: windowScroll.scrollTop},
			finish: {scroll: 0},
			transition: BX.easing.makeEaseOut(BX.easing.transitions.quart),
			step: function (state) {
				window.scrollTo(0, state.scroll);
			},
			complete: function () {
			}
		})).animate();
	});
});


$( document ).ready(function() {
	$(".popup-form__form-group--phone .inputtext").mask("+7 (999) 999-99-99");
	$(".contacts-form_phone").mask("+7 (999) 999-99-99");
});


function clickPhone() {
	(function () {
		var key = '__rtbhouse.lid';
		var lid = window.localStorage.getItem(key);
		if (!lid) {
			lid = '';
			var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
			window.localStorage.setItem(key, lid);
		}
		var body = document.getElementsByTagName("body")[0];
		var ifr = document.createElement("iframe");
		var siteReferrer = document.referrer ? document.referrer : '';
		var siteUrl = document.location.href ? document.location.href : '';
		var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
		var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
		var timestamp = "" + Date.now();
		var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_1_call-call_ru-call&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
		ifr.setAttribute("src", source);
		ifr.setAttribute("width", "1");
		ifr.setAttribute("height", "1");
		ifr.setAttribute("scrolling", "no");
		ifr.setAttribute("frameBorder", "0");
		ifr.setAttribute("style", "display:none");
		ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
		body.appendChild(ifr);
	}());
}

$( document ).ready(function() {
	$( ".header-fixed-phones__number" ).on( "click", function() { clickPhone(); });
	$( ".phone-list" ).on( "click", function() { clickPhone(); });
	$( ".header-text-item__phone" ).on( "click", function() { clickPhone(); });
	$( ".mobnav_popup_line" ).find("a").on( "click", function() { clickPhone(); });
	$( ".footer-section-contacts__title" ).on( "click", function() { clickPhone(); });
	$( ".footer-section__contacts" ).find("a").on( "click", function() { clickPhone(); });
	coundownTimer()
	toggleBgBreakpoint()
	toggleTabs()
});

function jivo_onMessageSent(res) {
	console.log("jivo");
	(function () {
		var key = '__rtbhouse.lid';
		var lid = window.localStorage.getItem(key);
		if (!lid) {
			lid = '';
			var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
			window.localStorage.setItem(key, lid);
		}
		var body = document.getElementsByTagName("body")[0];
		var ifr = document.createElement("iframe");
		var siteReferrer = document.referrer ? document.referrer : '';
		var siteUrl = document.location.href ? document.location.href : '';
		var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
		var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
		var timestamp = "" + Date.now();
		var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_2_chat-chat_ru-chat&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
		ifr.setAttribute("src", source);
		ifr.setAttribute("width", "1");
		ifr.setAttribute("height", "1");
		ifr.setAttribute("scrolling", "no");
		ifr.setAttribute("frameBorder", "0");
		ifr.setAttribute("style", "display:none");
		ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
		body.appendChild(ifr);
	}());
}

// переключение фона элемента по брейкпойнту
function toggleBgBreakpoint() {
	var win = $(window)

	$('.js-toggle-bg-breakpoint').each(function (ind) {
		var oldWinWidth = win.width() - 1
		var newWinWidth = null
		var elem = $(this)
		var timeout = null
		var breakpoint = elem.data('breakpoint')
		var minBg = elem.data('minBg')
		var maxBg = elem.data('maxBg')

		win.off('resize.toggleBgBreakpoint' + ind).on('resize.toggleBgBreakpoint' + ind, function () {
			clearTimeout(timeout)
			timeout = setTimeout(function () {
				newWinWidth = win.width()

				if (oldWinWidth != newWinWidth) {
					oldWinWidth = newWinWidth

					if (newWinWidth > breakpoint) {
						elem.css({backgroundImage: 'url(' + maxBg + ')'})
					} else {
						elem.css({backgroundImage: 'url(' + minBg + ')'})
					}
				}
			}, 300)
		}).trigger('resize.toggleBgBreakpoint' + ind)
	})
}

// отправка rtb запросов на новых б24 формах
var typeRTB = 0;
var IDproduct = 0;

function setCallback() {
	typeRTB = 'callback'
}
function setMeasure() {
	typeRTB = 'measure'
}
function setOneBuyClick(el) {
	typeRTB = '1click'
	IDproduct = $(el).attr('data-product_id')
}

$('.header__text-item--phone').on( "click", ".popup-link", function() {
	setCallback()
});
$('.phone-list').find('.popup-link').on( "click", function() {
	setCallback()
});
$('.mobnav_popup_line').find('.popup-link').on( "click", function() {
	setCallback()
});

$('.main-menu__item--level1').find("script[data-b24-form='click/9/5qnm9v']").parent().on( "click", function() {
	setMeasure()
});
$('.measure-form').on( "click", function() {
	setMeasure()
});
$('.one-click-buy').on( "click", function() {
	setOneBuyClick($(this))
});

function callbackRTB() {
	(function () {
		var key = '__rtbhouse.lid';
		var lid = window.localStorage.getItem(key);
		if (!lid) {
			lid = '';
			var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
			window.localStorage.setItem(key, lid);
		}
		var body = document.getElementsByTagName("body")[0];
		var ifr = document.createElement("iframe");
		var siteReferrer = document.referrer ? document.referrer : '';
		var siteUrl = document.location.href ? document.location.href : '';
		var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
		var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
		var timestamp = "" + Date.now();
		var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_2_callback-callback_ru-callback&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
		ifr.setAttribute("src", source);
		ifr.setAttribute("width", "1");
		ifr.setAttribute("height", "1");
		ifr.setAttribute("scrolling", "no");
		ifr.setAttribute("frameBorder", "0");
		ifr.setAttribute("style", "display:none");
		ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
		body.appendChild(ifr);
	}());
}
function measureRTB() {
	(function () {
		var key = '__rtbhouse.lid';
		var lid = window.localStorage.getItem(key);
		if (!lid) {
			lid = '';
			var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
			window.localStorage.setItem(key, lid);
		}
		var body = document.getElementsByTagName("body")[0];
		var ifr = document.createElement("iframe");
		var siteReferrer = document.referrer ? document.referrer : '';
		var siteUrl = document.location.href ? document.location.href : '';
		var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
		var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
		var timestamp = "" + Date.now();
		var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_3_measure-measure_ru-measure&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
		ifr.setAttribute("src", source);
		ifr.setAttribute("width", "1");
		ifr.setAttribute("height", "1");
		ifr.setAttribute("scrolling", "no");
		ifr.setAttribute("frameBorder", "0");
		ifr.setAttribute("style", "display:none");
		ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
		body.appendChild(ifr);
	}());
}
function oneclickbuyRTB() {
	if (IDproduct > 0) {
		(function () {
			var key = '__rtbhouse.lid';
			var lid = window.localStorage.getItem(key);
			if (!lid) {
				lid = '';
				var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
				window.localStorage.setItem(key, lid);
			}
			var body = document.getElementsByTagName("body")[0];
			var ifr = document.createElement("iframe");
			var siteReferrer = document.referrer ? document.referrer : '';
			var siteUrl = document.location.href ? document.location.href : '';
			var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
			var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
			var timestamp = "" + Date.now();
			var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_3_1click-1click_ru-"+ IDproduct +"&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
			ifr.setAttribute("src", source);
			ifr.setAttribute("width", "1");
			ifr.setAttribute("height", "1");
			ifr.setAttribute("scrolling", "no");
			ifr.setAttribute("frameBorder", "0");
			ifr.setAttribute("style", "display:none");
			ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
			body.appendChild(ifr);
		}());
	}
}

function sendRTB(type) {
	switch (type) {
		case "callback":
			callbackRTB()
			break;
		case "measure":
			measureRTB()
			break;
		case "1click":
			oneclickbuyRTB()
			break;
		default:
			console.log( "Нет таких значений" );
	}

}
$('body').on('click', '.b24-form-btn', function() {
	sendRTB(typeRTB)
	typeRTB = 0
	IDproduct = 0
});

// отправка rtb запросов на новых б24 формах
var typeRTB = 0;
var IDproduct = 0;

function setCallback() {
	typeRTB = 'callback'
}
function setMeasure() {
	typeRTB = 'measure'
}
function setOneBuyClick(el) {
	typeRTB = '1click'
	IDproduct = $(el).attr('data-product_id')
}

$('.header__text-item--phone').on( "click", ".popup-link", function() {
	setCallback()
});
$('.phone-list').find('.popup-link').on( "click", function() {
	setCallback()
});
$('.mobnav_popup_line').find('.popup-link').on( "click", function() {
	setCallback()
});

$('.main-menu__item--level1').find("script[data-b24-form='click/9/5qnm9v']").parent().on( "click", function() {
	setMeasure()
});
$('.measure-form').on( "click", function() {
	setMeasure()
});
$('.one-click-buy').on( "click", function() {
	setOneBuyClick($(this))
});

function callbackRTB() {
	(function () {
		var key = '__rtbhouse.lid';
		var lid = window.localStorage.getItem(key);
		if (!lid) {
			lid = '';
			var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
			window.localStorage.setItem(key, lid);
		}
		var body = document.getElementsByTagName("body")[0];
		var ifr = document.createElement("iframe");
		var siteReferrer = document.referrer ? document.referrer : '';
		var siteUrl = document.location.href ? document.location.href : '';
		var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
		var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
		var timestamp = "" + Date.now();
		var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_2_callback-callback_ru-callback&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
		ifr.setAttribute("src", source);
		ifr.setAttribute("width", "1");
		ifr.setAttribute("height", "1");
		ifr.setAttribute("scrolling", "no");
		ifr.setAttribute("frameBorder", "0");
		ifr.setAttribute("style", "display:none");
		ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
		body.appendChild(ifr);
	}());
}
function measureRTB() {
	(function () {
		var key = '__rtbhouse.lid';
		var lid = window.localStorage.getItem(key);
		if (!lid) {
			lid = '';
			var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
			window.localStorage.setItem(key, lid);
		}
		var body = document.getElementsByTagName("body")[0];
		var ifr = document.createElement("iframe");
		var siteReferrer = document.referrer ? document.referrer : '';
		var siteUrl = document.location.href ? document.location.href : '';
		var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
		var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
		var timestamp = "" + Date.now();
		var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_3_measure-measure_ru-measure&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
		ifr.setAttribute("src", source);
		ifr.setAttribute("width", "1");
		ifr.setAttribute("height", "1");
		ifr.setAttribute("scrolling", "no");
		ifr.setAttribute("frameBorder", "0");
		ifr.setAttribute("style", "display:none");
		ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
		body.appendChild(ifr);
	}());
}
function oneclickbuyRTB() {
	if (IDproduct > 0) {
		(function () {
			var key = '__rtbhouse.lid';
			var lid = window.localStorage.getItem(key);
			if (!lid) {
				lid = '';
				var pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				for (var i = 0; i < 20; i++) lid += pool.charAt(Math.floor(Math.random() * pool.length));
				window.localStorage.setItem(key, lid);
			}
			var body = document.getElementsByTagName("body")[0];
			var ifr = document.createElement("iframe");
			var siteReferrer = document.referrer ? document.referrer : '';
			var siteUrl = document.location.href ? document.location.href : '';
			var querySeparator = siteUrl.indexOf('?') > -1 ? '&' : '?';
			var finalUrl = siteUrl + querySeparator + 'sr=' + encodeURIComponent(siteReferrer);
			var timestamp = "" + Date.now();
			var source = "https://creativecdn.com/tags?id=pr_B6MjHKf0gPbC1LVhT75b_orderstatus2_3_1click-1click_ru-"+ IDproduct +"&cd=default&id=pr_B6MjHKf0gPbC1LVhT75b_lid_" + encodeURIComponent(lid) + "&su=" + encodeURIComponent(finalUrl) + "&ts=" + timestamp;
			ifr.setAttribute("src", source);
			ifr.setAttribute("width", "1");
			ifr.setAttribute("height", "1");
			ifr.setAttribute("scrolling", "no");
			ifr.setAttribute("frameBorder", "0");
			ifr.setAttribute("style", "display:none");
			ifr.setAttribute("referrerpolicy", "no-referrer-when-downgrade");
			body.appendChild(ifr);
		}());
	}
}

function sendRTB(type) {
	switch (type) {
		case "callback":
			callbackRTB()
			break;
		case "measure":
			measureRTB()
			break;
		case "1click":
			oneclickbuyRTB()
			break;
		default:
			console.log( "Нет таких значений" );
	}

}
$('body').on('click', '.b24-form-btn', function() {
	sendRTB(typeRTB)
	typeRTB = 0
	IDproduct = 0
});

// переключение контента по табам
function toggleTabs() {
	$('.js-tabs').each(function () {
		var parent = $(this)
		var togglers = parent.find('.js-tabs__toggle')
		var tabs = parent.find('.js-tabs__tab')

		// инициализация и закрытие всех табов при загрузке, кроме того что с классом active-tab
		tabs.not('.active-tab').fadeOut(0)
		parent.addClass('init-tabs')

		togglers.each(function (index) {
			var toggler = $(this)

			// обработка переключения таба
			toggler.off('click.toggleTabs').on('click.toggleTabs', function (event) {
				event.preventDefault()

				// индекс нажатого таба
				var indexTab = toggler.addClass('active-tab').data('tabsItem')

				if (indexTab == 2) {
					//BX.ajax.insertToNode('https://belwooddoors.by/bitrix/templates/general/ajax/similar_products.php',"similar_ajax");
					$.ajax({
						url: "/bitrix/templates/general/ajax/similar_products.php",
						method: 'post',
						dataType: 'html',
						data: {id: $(".detail-product").data("product-id"), collections: $(".detail-product").data("collections")},
						success: function(data){
							$('.similar_ajax_loader').remove();
							$("#similar_ajax").html(data);
							$(".similar .tooltip1 img").click();
							$(".right-tut").addClass("right-item").removeClass("right-tut");
							$(".similar").height($(".similar").find('.catalog-item__top').outerHeight()).find('.catalog-item__top').css('min-height', $(".similar").find('.catalog-item__aside').height());
						}
					});



				}
				togglers.not(toggler).removeClass('active-tab')

				tabs.each(function () {
					var tab = $(this)

					// открытие таба при совпадении с индексом переключателя
					if (tab.data('tabsItem') != indexTab) {
						tab.fadeOut(0).removeClass('active-tab')
					} else {
						tab.fadeIn(300).addClass('active-tab')

						// обновление lazyload картинок внутри таба
						// lazyLoad.instance.update()
					}
				})
			})
		})
	})
}