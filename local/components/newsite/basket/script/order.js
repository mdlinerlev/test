function addOneBox(event, obj) {


    $(".js-add-one-box", obj).off('click.addOneBox  blur.addOneBox');
    $('.js-btn-add-one-box', obj).off('click.addOneBox mouseover.addOneBox mouseleave.addOneBox');
    $('.js-add-one-box-input', obj).off('keyup.addOneBox change.addOneBox keydown.forceNumericOnly focus.addOneBox');

    $('.js-btn-add-one-box', obj).on('click.addOneBox', function () {
        var addOneBoxBtn = $(this),
		addOneBox = addOneBoxBtn.closest('.js-add-one-box'),
		addOneBoxInput = $('.js-add-one-box-input', addOneBox),
		addOneBoxInputVal = parseFloat(addOneBoxInput.val()),
		addOneBoxInputMinVal = parseFloat(addOneBoxInput.data('min-val')),
		addOneBoxInputMaxVal = parseFloat(addOneBoxInput.data('max-val')),
		incrementValue = parseFloat(addOneBoxInput.data('inc-val')) || 1;

	if (addOneBoxBtn.is('.js-btn-add-one-box-pl') && (addOneBoxInputVal < addOneBoxInputMaxVal)) {
	    addOneBoxInput.val(addOneBoxInputVal + incrementValue);
	} else if (addOneBoxBtn.is('.js-btn-add-one-box-mn') && (addOneBoxInputVal > addOneBoxInputMinVal)) {
	    addOneBoxInput.val(addOneBoxInputVal - incrementValue);
	}
	addOneBoxInput.triggerHandler("change.addOneBox");
    });


    $('.js-add-one-box-input', obj).on("keydown.forceNumericOnly", function (e) {
	var key = e.charCode || e.keyCode || 0;

	return (
		key === 8 ||
		key === 9 ||
		key === 46 ||
		key === 188 ||
		key === 190 ||
		(key >= 37 && key <= 40) ||
		(key >= 48 && key <= 57) ||
		(key >= 96 && key <= 105));
    });


    $(".js-add-one-box-input", obj).data("timer", 0);


    $(".js-add-one-box", obj).data("timer", 0);


    $('.js-add-one-box-input', obj).on('keyup.addOneBox change.addOneBox', function (e) {
	var addOneBoxInput = $(this),
		addOneBoxInputVal = parseFloat(addOneBoxInput.val()),
		addOneBoxInputMinVal = parseFloat(addOneBoxInput.data('min-val')),
		addOneBoxInputMaxVal = parseFloat(addOneBoxInput.data('max-val')),
		contentBasketContainer = $(this).closest(".js_cat_list_item"),
		curValue = parseFloat(addOneBoxInput.data('cur-count'));


	clearTimeout(addOneBoxInput.data("timer"));

	if (addOneBoxInput.val() == "") {
	    return;
	}

	addOneBoxInput.removeAttr("disabled");

	if (contentBasketContainer.is(".js_increment_logic")) {
	    addOneBoxInputMaxVal -= curValue;

	    if (addOneBoxInputMaxVal <= 0) {
		addOneBoxInput.attr("disabled", "disabled");
	    }
	}


	if (addOneBoxInputVal > addOneBoxInputMaxVal) {
	    addOneBoxInput.val(addOneBoxInputMaxVal);
	} else if ((addOneBoxInputVal < addOneBoxInputMinVal) || (addOneBoxInputVal === '' || !addOneBoxInputVal)) {
	    addOneBoxInput.val(addOneBoxInputMinVal);
	    if (addOneBoxInputMinVal == 0) {
		addOneBoxInput.triggerHandler("blur.addOneBox");
		$(".js_delFromOrder", contentBasketContainer).triggerHandler("click");
		return;
	    }
	}

	if (contentBasketContainer.is(".js_update_after_change")) {
	    addOneBoxInput.data("timer", setTimeout(function () {
		addOneBoxInput.triggerHandler("blur.addOneBox");


		contentBasketContainer.one("updateBasket", BasketChangeAction).triggerHandler("updateBasket");
	    }, 500));
	}

	var key = e.charCode || e.keyCode || 0;

	if (key == 13) {
	    addOneBoxInput.triggerHandler("blur.addOneBox");
	    contentBasketContainer.one("updateBasket", BasketChangeAction).triggerHandler("updateBasket");
	}

    });
}

$(document).ready(function () {
    $("body").on("onAjaxReload.addOneBox", addOneBox).triggerHandler("onAjaxReload.addOneBox", [$("body")]);
    $("body").on("onAjaxReload.BuyActionInit", BuyActionInit).triggerHandler("onAjaxReload.BuyActionInit", [$("body")]);
})

function BuyActionInit(event, bxObj) {
    var ajaxIsRun = false;

    $(".js_add_and_go_cart", bxObj).off("click.GoCartActionInit").on("click.GoCartActionInit", function () {

	var currentContainer = $(this).closest(".js_cat_list_item");

	if (ajaxIsRun) {
	    setTimeout(function () {
		$(this).triggerHandler("click.GoCartActionInit")
	    }, 200);
	    if (currentContainer.length) {
		loader_Custom_ajax_shadow(currentContainer);
	    }
	    return;
	}


	var currentDiv = $(this);
	if (currentDiv.is(".clicked"))
	    return;

	currentDiv.addClass("clicked");


	$("body").off("onAjaxReloadBefore.GoCartActionInit", function () {
	    ajaxIsRun = true;
	    loader_Custom_ajax_remove();
	    if (currentContainer.length) {
		loader_Custom_ajax_shadow(currentContainer);
	    }
	});

	// $("body").one("onAjaxReload.GoCartActionInit", function () {
	//     window.location = "/order/";
	// });

	$(this).one("click.BasketChangeAction", BasketChangeAction).trigger("click.BasketChangeAction");
    });

    $(".js_orderButton", bxObj).off("click.BuyActionInit").on("click.BuyActionInit", function () {

	var currentContainer = $(this).closest(".js_cat_list_item");

	if (ajaxIsRun) {
	    setTimeout(function () {
		$(this).triggerHandler("click.BuyActionInit")
	    }, 200);
	    loader_Custom_ajax_shadow(currentContainer);
	    return;
	}


	var currentDiv = $(this);
	if (currentDiv.is(".clicked"))
	    return;

	currentDiv.addClass("clicked");


	$("body").off("onAjaxReloadBefore.catalogAdd", function () {
	    ajaxIsRun = true;
	    loader_Custom_ajax_remove();
	    loader_Custom_ajax_shadow(currentContainer);
	});

	$("body").one("onAjaxReload.BuyActionInitReset", function () {
	    ajaxIsRun = false;
	    currentDiv.removeClass("clicked");
	    $(".showNotInOrder", currentContainer).addClass("hide");
	    $(".showInOrder", currentContainer).removeClass("hide");
	});

	currentContainer.one("click.BasketChangeAction", BasketChangeAction).trigger("click.BasketChangeAction");

    });


    var badgeHtml = $('.js-basket__item__badge').html();
    var textHtml =  $('.js-basket__item__text').html();

    if(badgeHtml.length <= 0) {
        $('.js-header-shop-links__badge').html(0);
        $('.js-header-shop-links__text').html('Корзина');
        $('.js-header-shop-links__cart').removeClass('active');
    } else {
        $('.js-header-shop-links__badge').html(badgeHtml);
        $('.js-header-shop-links__text').html(textHtml);
    }

}



/**
 * Обработка изменений в корзине
 * @returns {Boolean}
 */
function BasketChangeAction() {


    var rel = $(this).data("rel");
    var contentBasketContainer = $(this).closest(".js_cat_list_item");

    if ($(".js-add-one-box-input:disabled", contentBasketContainer).length && !$(this).is(".js_delFromOrder")) {
	    return;
    }

    if (!rel)
	    return;

    $("body").off("onAjaxReloadBefore.BasketChangeAction").one("onAjaxReloadBefore.BasketChangeAction", function () {
	loader_Custom_ajax_remove();
	loader_Custom_ajax_shadow(contentBasketContainer);
    });


    var basketPostForm = $("#basketSubmitAction");
    var GetUrl = new Url(rel);
    var dataSet = false;

    if ($("input[name=PROPS_COMMENT]", $(this)).length) {
	GetUrl.query.PROPS_COMMENT = $("input[name=PROPS_COMMENT]", $(this)).val();
    }

    if ($("input[name=QUANTITY]", $(this)).length) {
	GetUrl.query.COUNT = $("input[name=QUANTITY]", $(this)).val();



	if (contentBasketContainer.is(".js_increment_logic") && $(".js_orderButton_quantity:not(.js-add-one-box-input)", contentBasketContainer).length && $(".js_orderButton_quantity:not(.js-add-one-box-input)", contentBasketContainer).html().length) {
	    GetUrl.query.COUNT = parseFloat(GetUrl.query.COUNT) + parseFloat($(".js_orderButton_quantity:not(.js-add-one-box-input)", contentBasketContainer).html());
	}


    }



    if ($("input[name=ADD_COUPON]", $(this).parent()).length) {
        GetUrl.query.ADD_COUPON = $("input[name=ADD_COUPON]",  $(this).parent()).val();
    }

    if ($("button[name=CLEAR_COUPON]", $(this).parent()).length) {
        GetUrl.query.CLEAR_COUPON = true;
    }


    $(".setBasketValues", basketPostForm).each(function () {
	var name = $(this).attr("name");
	if (GetUrl.query[name]) {
	    $(this).val(GetUrl.query[name]);
	    dataSet = true;
	}
    });


    if (dataSet) {

	$("body").off("onAjaxReload.addOneBox").on("onAjaxReload.addOneBox", addOneBox);
	$("body").off("onAjaxReload.BuyActionInit").on("onAjaxReload.BuyActionInit", BuyActionInit);


	basketPostForm.trigger("submit");
    }

}
