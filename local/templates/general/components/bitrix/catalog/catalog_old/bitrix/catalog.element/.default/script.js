var twoLeaf = false,
	secondOfferPrice = 0,
	secondOffer = false;
	countClickColor = 0;
// открываем запрошенное тп первым
window.selectCurrentOffer = function() {
	var currentOfferProps = false,
		$materialSelect = $('#product-size'),
		jsCurrentOffer = location.hash.replace('#offer', ''),
		i, j, propId;
	//console.log(jsCurrentOffer);
	if(jsCurrentOffer && window.jsOffers) {
		for(i in jsOffers) {
			if(!jsOffers.hasOwnProperty(i)) {
				continue;
			}
			if(jsOffers[i].hasOwnProperty(jsCurrentOffer)) {
				currentOfferProps = jsOffers[i][jsCurrentOffer];
			}
		}
	}
	//console.log(currentOfferProps);
	if(currentOfferProps) {
		// меняем, если надо, размер
		/*for(j = 0; j < currentOfferProps.length; j++) {
			propId = currentOfferProps[j];
			if($materialSelect.val() != propId && $materialSelect.find('option[value="'+propId+'"]').length) {
				$materialSelect.val(propId).trigger('change').selectmenu('refresh');
				break;
			}
		}*/
		// ищем нужный цвет
		for(j = 0; j < currentOfferProps.length; j++) {
			propId = currentOfferProps[j];
			var $citem = $('.sku-value[data-id="'+propId+'"]').eq(0);
			if($citem.length) {
				$citem.trigger('click');
			}
		}
	}
};

// тп
(function() {
	// constructor
	var BitrixOffers = function($item, options) {
		var defaults = {
				activeClass: 'current',
				hiddenClass: 'hidden',
				propWrapperSelector: '.select-container',
				propValueSelector: '.sku-container li'
			},
			that = this;
		this.$item = $item;
		this.$hover = $item.find('.catalog-item-hover');
		this.options = $.extend(defaults, options || {});

		$item.find(this.options.propValueSelector).on('click', function() {
			$(this).closest(that.options.propWrapperSelector).find(that.options.propValueSelector).removeClass(that.options.activeClass);

			$(this).addClass(that.options.activeClass);
			that.changeOfferPoints();
			//task1255
			var purl = window.location.href;		
			var exp  = /vkhodnye_dveri/;		
			var regex = new RegExp(exp);		
				if (purl.match(regex)) {		
			if($(this).hasClass("product-filter-color__link") && countClickColor > 1){		
				if($(this).parent().parent().parent().hasClass("product-filter__color--second") && !$(this).parent().parent().parent().hasClass("filter-glass-color")){		
					if($('.product-view-links__inner').children().eq(0).hasClass('active')){		
						$('.product-view-links__inner').children().eq(1).trigger('click');		
					}else{		
						$('.product-view-links__inner').children().eq(3).trigger('click');		
					}		
				}else if($(this).hasClass("product-filter-color__link") && !$(this).parent().parent().parent().hasClass("product-filter__color--second") && !$(this).parent().parent().parent().hasClass("filter-glass-color")){		
					if($('.product-view-links__inner').children().eq(1).hasClass('active')){		
						$('.product-view-links__inner').children().eq(0).trigger('click');		
					}else{		
						$('.product-view-links__inner').children().eq(2).trigger('click');		
					}		
				}		
			}else if($(this).hasClass("product-filter-color__link")){		
				countClickColor++;		
			}		
}
			//task1255
		});

		this.changeOfferPoints(true);
		BX.addCustomEvent('onFrameDataReceived', function() {
			that.changeOfferPoints(true);
		});

		$('.second-base select').on('selectmenuchange', $.proxy(this.getSecondOfferPrice, this));
	};

	BitrixOffers.prototype = {
		checkOfferExists: function(props) {
			var productId = this.$item.data('productId'),
				exists, offerId, i;
			for(offerId in jsOffers[productId]) {
				exists = true;
				for(i=0; i<props.length; i++) {
					if(jsOffers[productId][offerId].indexOf(props[i]) == -1) {
						exists = false;
						break;
					}
				}
				if(exists) {
					return offerId;
				}
			}
			return false;
		},
		changeOfferPoints: function(first) {
			var productId = this.$item.data('productId');
			var currentProps = [],
				propLevel = 0,
				that = this,
				$availItem;
			first = (typeof first !== 'undefined') && first;
			this.$item.add(this.$hover).find(this.options.propValueSelector).removeClass(this.options.hiddenClass);

			this.$item.add(this.$hover).find(this.options.propWrapperSelector).each(function() {
				var $points = $(this).find(that.options.propValueSelector),
					$activePoint,
					$visiblePoint,
					$firstPoint;
				propLevel++;

				if(propLevel == 1) {
					$activePoint = $points.filter('.'+that.options.activeClass).eq(0);
					$firstPoint = $activePoint.length ? $activePoint : $points.eq(0);
					$firstPoint.addClass(that.options.activeClass);
					currentProps.push($firstPoint.data('id'));
				}
				else {
					$points.each(function() {
						currentProps.push($(this).data('id'));
						if(!that.checkOfferExists(currentProps)) {
							$(this).addClass(that.options.hiddenClass).removeClass(that.options.activeClass);
						}
						currentProps.pop();
					});
					$activePoint = $points.filter('.'+that.options.activeClass).eq(0);

					$visiblePoint = $activePoint.length ? $activePoint : $points.not('.'+that.options.hiddenClass).eq(0);
					$visiblePoint.addClass(that.options.activeClass);
					currentProps.push($visiblePoint.data('id'));
				}
			});
			if(currentProps.length) {
				var offerId = this.checkOfferExists(currentProps),
					$offerBlocks = this.$item.add(this.$hover).find('[data-offer-id]');
				$offerBlocks.hide();
				$offerBlocks.filter('[data-offer-id="'+offerId+'"]').css('visibility', 'visible').show();
				//console.log(offerId);

				if(first/* && productId == 35419*/) {
					$availItem = $offerBlocks.filter('[data-available="1"]').eq(0);
					if($availItem.length) {
						var availOfferId = $availItem.data('offerId');
						for(offerId in jsOffers[productId]) {
							if(jsOffers[productId].hasOwnProperty(offerId) &&  offerId == availOfferId) {
								var availProps = jsOffers[productId][offerId];
								for(var i=0; i<availProps.length; i++) {
									// RU 29092016 todo временно закомментированно
									//this.$item.find(this.options.propValueSelector).filter('[data-id="'+availProps[i]+'"]').trigger('click');
								}
								break;
							}
						}
					}
				}
				else {
					$(document).trigger('bitrix:offers:change', [+offerId]);
				}
			}

			// цена для второго полотна
			secondOfferPrice = 0;
			if(twoLeaf) {
				var $secondSelect = $('.second-base select');
				var secondBaseVal = $secondSelect.val();
				var options = $('.first-base select').html();
				$secondSelect.html(options);
				if( secondBaseVal && $secondSelect.find('option[value="'+secondBaseVal+'"]').length ) {
					$secondSelect.val(secondBaseVal);
				}
				else {
					$secondSelect.val( $secondSelect.find('option').eq(0).attr('value') );
				}
				$secondSelect.selectmenu('refresh');

				this.getSecondOfferPrice();
			}
		},
		getSecondOfferPrice: function() {
			secondOfferPrice = 0;
			var propIds = [],
				colorId,
				$secondSelect = $('.second-base select');
			propIds.push(parseInt($secondSelect.val()));

			$('.product-filter-color__inner').each(function() {
				colorId = $(this).find('a.active').data('id');
				if(colorId) {
					propIds.push(parseInt(colorId));
				}
			});
			//console.log(propIds);
			var secondOfferId = this.checkOfferExists(propIds);
			if(secondOfferId) {
				secondOfferPrice = jsPrices[secondOfferId];
				secondOffer = secondOfferId;
			}
			//console.log(secondOfferPrice);
			setDetailPrices();
		}
	};

	// plugin
	$.fn.extend({
		bitrixOffers: function(options) {
			$(this).each(function() {
				var obj = new BitrixOffers( $(this), options );
				$(this).data('bitrixOffers', obj);
			});
		}
	});
})();

var numberFormat = function (number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + (Math.round(n * k) / k)
				.toFixed(prec);
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
	.split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '')
			.length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1)
		.join('0');
	}
	return s.join(dec);
};

if(window.localStorage) {
	localStorage.removeItem('detailState');
}

var saveCurrentState = function() {
	var state = {};
	state.color = $('.filter-main-color .sku-value.active').data('id');
	state.glassColor = $('.filter-glass-color .sku-value.active').data('id');
	state.size = $('#product-size').val();
	state.qty = $('#total-quantity-input').val();

	if(window.localStorage) {
		localStorage.setItem('detailState', JSON.stringify(state));
	}
};

var setDetailState = function() {
	if(window.localStorage) {
		var state = localStorage.getItem('detailState');
		state = JSON.parse(state);
		if(!state) {
			return;
		}
		if(state.color) {
			$('.filter-main-color .sku-value[data-id="'+state.color+'"]').trigger('click');
		}
		if(state.glassColor) {
			$('.filter-glass-color .sku-value[data-id="'+state.glassColor+'"]').trigger('click');
		}
		if(state.size) {
			$('#product-size').val(state.size).selectmenu('refresh');
		}
		if(state.qty) {
			$('#total-quantity-input').val(state.qty).trigger('change');
		}
	}
};

$(document).on('bitrix:offers:change', saveCurrentState);

var setDetailPrices = function() {
    if(twoLeaf) {
	var totalCount = parseInt($('#total-quantity-input').val())*2,
                isComplect = $('.detail-price-complect').is('.active');
            }else{
                	var totalCount = parseInt($('#total-quantity-input').val()),
                isComplect = $('.detail-price-complect').is('.active');
            }
		//cart = [];

	$('.detail-to-cart').data('cart', false);
	//console.log('clear cart');

	if(twoLeaf) {
		$('.simple-quantity').text('2');
	}
	else {
		$('.simple-quantity').text('1');
	}
	
	$('.left-base-price').each(function() {
		var price = parseFloat($(this).data('basePrice').toString().replace(' ', ''));
		console.log(secondOfferPrice);
		if(secondOfferPrice) {
			//price += secondOfferPrice;
		}
		if(!isNaN(price)) {
                    	if(twoLeaf) {
			$(this).text(numberFormat(price*2, 2, '.', ' ') + ' руб.');
                    }else{
                        $(this).text(numberFormat(price, 2, '.', ' ') + ' руб.');
                    }
		}
	});

	/*$('.complect-count-input').each(function() {
		var price = parseInt($(this).data('price')),
			count = parseInt($(this).val()),
			id = parseInt($(this).data('id'));
		complectPrice += price*count;

		if(isComplect) {
			cart.push([id, count]);
		}
	});*/

	$('.complect-full-price').each(function() {
		var complectPrice = 0,
			cart = [],
			basePrice = $(this).data('basePrice'),
			offerId = $(this).closest('[data-offer-id]').data('offerId'),
			$totalPrice = $('[data-offer-id="'+offerId+'"] .total-price');
		if(!$totalPrice.length) {
			$totalPrice = $('.total-price');
		}

		$('[data-offer-id="'+offerId+'"] .complect-count-input').each(function() {
			var price = parseFloat($(this).data('price')),
				count = parseInt($(this).val()),
				id = parseInt($(this).data('id'));
			complectPrice += price*count;

			if(isComplect) {
				cart.push([id, count]);
			}
		});

        /*console.log(basePrice*totalCount);
        console.log(secondOfferPrice*totalCount);
        console.log(complectPrice);
        console.log('#################');*/


		if(isComplect) {
			$totalPrice.text(numberFormat(basePrice*totalCount + secondOfferPrice*totalCount + complectPrice, 2, '.', ' ')+ ' руб.');
		}
		else {
			$totalPrice.text(numberFormat(basePrice*totalCount + secondOfferPrice*totalCount, 2, '.', ' ')+ ' руб.');
		}

		$(this).text(numberFormat(basePrice*totalCount + secondOfferPrice*totalCount + complectPrice, 2, '.', ' ')+ ' руб.');
		$('.detail-to-cart[data-offer-id="'+offerId+'"]').data('cart', cart);
	});
        
        
//        $('.complect-full-price_old').each(function() {
//		var complectPrice = 0,
//			cart = [],
//			basePrice = $(this).data('basePrice'),
//			offerId = $(this).closest('[data-offer-id]').data('offerId'),
//			$totalPrice = $('[data-offer-id="'+offerId+'"] .total-price');
//		if(!$totalPrice.length) {
//			$totalPrice = $('.product-filter-submit__price--new');
//                }
//                $totalPriceOld = $('[data-offer-id="'+offerId+'"] .total-price_old');
//                if(!$totalPriceOld.length){
//                $totalPriceOld = $('.product-filter-submit__price--old'); 
//		}
//		$('[data-offer-id="'+offerId+'"] .complect-count-input').each(function() {
//			var price = parseFloat($(this).data('price')),
//				count = parseInt($(this).val()),
//				id = parseInt($(this).data('id'));
//			complectPrice += price*count;
//
//			if(isComplect) {
//				cart.push([id, count]);
//			}
//		});
//
//		if(isComplect) {
//			$totalPrice.text(numberFormat(basePrice*totalCount + secondOfferPrice*totalCount + complectPrice, 2, '.', ' '));
//                        $totalPriceOld.text(numberFormat(basePrice*10000*totalCount + secondOfferPrice*10000*totalCount + complectPrice*10000, 0, '.', ' '));
//		//console.log('1');
//                }
//		else {
//			$totalPrice.text(numberFormat(basePrice*totalCount + secondOfferPrice*totalCount, 2, '.', ' '));
//                        $totalPriceOld.text(numberFormat(basePrice*10000*totalCount + secondOfferPrice*10000*totalCount, 0, '.', ' '));
//		//console.log('2');
//                }
//
//		$(this).text('('+(numberFormat(basePrice*10000*totalCount + complectPrice*10000, 0, '.', ' ')) +' б.р.)');
//		$('.detail-to-cart[data-offer-id="'+offerId+'"]').data('cart', cart);
//	});

	// проставляем товары для корзины
	$('.detail-to-cart').each(function() {
		var baseId = parseInt($(this).data('id')),
			//thisCart = cart.slice(0),
			thisCart = $(this).data('cart') || [],
			localCart = [baseId, totalCount];
		/*if( $('.side-type').length ) {
			var side = $('.side-type.product-filter__type--right').is('.active') ? 'Правая' : 'Левая';
			localCart.push({
				NAME: 'Сторона открывания',
				CODE: 'SIDE',
				VALUE: side,
				SORT: 100
			});
		}*/
		thisCart.push(localCart);

		if(secondOffer && secondOfferPrice) {
			thisCart.push([secondOffer, totalCount]);
		}
		$(this).data('cart', thisCart);
	});

	//saveCurrentState();
};

var initDetailProduct = function() {
	var stop = false;

	$('.detail-product .product-filter__select--size').selectmenu();

	app.quantity.initScripts();
	app.product.initScripts();
	app.itemsSlider.slidersInit();

	/*$('.side-type').on('click', function() {
		setTimeout(setDetailPrices, 200);
	});*/

	$('.total-square').on('change', function() {
		var val = $(this).val(),
			defaultVal = parseFloat($(this).data('square')),
			boxes = 1,
			offerId = $(this).closest('[data-offer-id]').data('offerId');

		if(defaultVal > 0 && val > 0) {
			val = parseFloat(val.replace(/\s/g, '').replace(',', '.'));
			boxes = Math.ceil(val/defaultVal);
		}

		$('#total-quantity-input').val(boxes).trigger('change');
	});

	$('.product-view-links__link').on('click', function() {
		var inner = $(this).data('inner'),
			$innerImage = $('.inner-image');
		if(inner && $innerImage.length) {
			$('.one-leaf-image, .two-leaf-image').addClass('hidden');
			$innerImage.removeClass('hidden');
		}
		else {
			if(twoLeaf) {
				$('.two-leaf-image').removeClass('hidden');
			}
			else {
				$('.one-leaf-image').removeClass('hidden');
			}
			$innerImage.addClass('hidden');
		}
	});

	$('.product-top__compare').on('click', function() {
		if( !$(this).is('.active') ) {
			var that = this,
				href = $(this).attr('href');
			$.post(href, {}, function() {
				$(that)
					.attr('href', '/catalog/compare/')
					.addClass('active');
				$(that).find('span')
					.removeClass('product-top-compare__text--default')
					.addClass('product-top-compare__text--active')
					.text('В сравнении');
			});
			return false;
		}
	});
        $('.catalog-item-compare').on('click', function() {
  if( $(this).is('.active') ) {
   var that = this,
    href = $(this).attr('href'); 
   $.post(href, {}, function() {
    $(that)
     .attr('href', '/catalog/compare/')
     .addClass('active');
    $(that).find('a')
     .removeClass('catalog-item-compare__text--default')
     .addClass('catalog-item-compare__text--active')
     .text('В сравнении');
   });
   return false;
  }
 });

	$('.select-sku-value').on('click', function() {
		if(stop) {
			stop = false;
			return;
		}
		var $select = $(this).closest('.sku-wrapper').find('select'),
			$options = $select.find('option'),
			id = $(this).data('id'),
			that = this;

		setTimeout(function() {
			$(that).closest('.sku-wrapper').find('.select-sku-value').each(function() {
				if($(this).is('.hidden')) {
					$options.filter('[value="'+$(this).data('id')+'"]').attr('disabled', true);
				}
			});
			$select.val(id).selectmenu('refresh');
		}, 300);
	});

	$('.sku-wrapper select').on('selectmenuchange', function() {
		var ssize = $('#product-size').val();
		$('.select-sku-value').data('id',ssize);
		stop = true;
		$(this).closest('.sku-wrapper').find('.select-sku-value').filter('[data-id="'+$(this).val()+'"]').trigger('click');
	});

	setDetailState();

	$('.detail-product').bitrixOffers({
		activeClass: 'active',
		propWrapperSelector: '.sku-wrapper',
		propValueSelector: '.sku-value'
	});

	$('.glass-type').not('.active').on('click', function() {
		var ajaxId = $(this).closest('[id^="comp_"]').attr('id'),
			href = $(this).attr('href');
		BX.ajax.insertToNode(href + '?bxajaxid=' + ajaxId.replace('comp_', ''), ajaxId);

		setTimeout(function() {
			var ssize = $('#product-size').val();
			$('.select-sku-value').data('id',ssize);
			$('.detail-product').data('bitrixOffers').changeOfferPoints();
		}, 3000);

		return false;
	});

	$('.product-top__button--type').on('click', function() {
		if( $(this).is('.active') ) {
			return;
		}
		$('.product-top__button--type').removeClass('active');
		$(this).addClass('active');
		twoLeaf = !$(this).is('.product-top__button--single');

		if(twoLeaf) {
			$('.two-leaf-image, .second-base, .two-leaf').removeClass('hidden');
			$('.one-leaf-image, .one-leaf').addClass('hidden');
			$('.leaf-switch-text').each(function() {
				$(this).text($(this).data('twoleafText'));
			});
		}
		else {
			$('.two-leaf-image, .second-base, .two-leaf').addClass('hidden');
			$('.one-leaf-image, .one-leaf').removeClass('hidden');
			$('.leaf-switch-text').each(function() {
				$(this).text($(this).data('oneleafText'));
			});
		}
		$('.detail-product').data('bitrixOffers').changeOfferPoints();
		$('#total-quantity-input').trigger('change');
		return false;
	});



	$('.complect-count-input').on('change', function() {
		setDetailPrices();
	});

	$('#total-quantity-input').on('change', function() {
		saveCurrentState();
		var val = parseInt($(this).val()),
			//map = twoLeaf ? window.complectMapTwoLeaf : window.complectMap;
			map = twoLeaf ? window.getComplectMapTwoLeaf : window.getComplectMap;
		if(map) {
			//map = map[val];
			map = map(val);
			for(var key in map) {
				if(map.hasOwnProperty(key)) {
					$('input[data-code="'+key+'"]').val(map[key] === false ? 0 : map[key]);
					if(map[key] === false) {
						$('input[data-code="'+key+'"]').closest('.product-filter-complect__row').addClass('hidden');
					}
					else {
						$('input[data-code="'+key+'"]').closest('.product-filter-complect__row').removeClass('hidden');
					}
				}
			}
		}
		setDetailPrices();

		var $ts = $('.total-square');
		if($ts.length) {
			$ts.val( (val*parseFloat($ts.data('square'))).toFixed(2).replace('.', ',') );
		}
	});
	setDetailPrices();

	$('.detail-to-cart').on('click', function() {
		if(!$(this).is('.detail-to-cart')) {
			return true;
		}
		var cart = $(this).data('cart'),
			that = this;
		if(!cart.length) {
			return false;
		}
		$.post('/bitrix/templates/general/ajax/connector.php?act=cart', {
			cart: JSON.stringify(cart)
		}, function(res) {
			if(res.success) {
				BX.onCustomEvent('OnBasketChange');

				if(!$(that).is('.door-to-cart')) {
					$(that)
						.addClass('button--secondary')
						.removeClass('detail-to-cart')
						.text('В корзине');
				}
			}
		}, 'json');
// task1090
		popupMarketing('AddCart');
		return false;
	});

	$('#total-quantity-input').trigger('change');

	selectCurrentOffer();
};