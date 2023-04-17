//************* ajax*****************/
/* submit */
function addSubmitParams()
{
    if ($(this).is(':disabled') || $(this).is(".disabled"))
	return false;

    $('body').trigger('onAjaxReloadBeforeSubmitClick');

    var inpName = $(this).attr('name');
    var inpValue = $(this).val();
    if (typeof (inpName) !== "undefined") {
	$(this).closest("form").append('<input type="hidden" name="' + inpName + '" " value="' + inpValue + '"/>');
    }
}

function bitrixFormAjaxReplaseInit(data)
{

    $(data).off("submit.bitrixFormAjaxReplaseInit");

    $(data).attr('action', $('input[name=FORM_ACTION]', data).val());

    $('input[name=bxajaxidjQuery]', data).attr('name', 'bxajaxid');

    $('input[type=submit],button[type=submit]', data).on("click", addSubmitParams);

    $(".thisFormSubmit", data).off("click.ajaxSubmit").on("click.ajaxSubmit", function () {
	$(data).trigger("submit");
    });


    $(data).off("submit.bitrixFormAjaxReplaseInit").on("submit.bitrixFormAjaxReplaseInit", bitrixFormAjaxReplase);
}


function checkForAjaxForms()
{
    if (!$("#frameSendAjax").length) {
	$("<iframe>").attr({id: "frameSendAjax", name: "frameSendAjax", src: "/local/fakeajaxsubmit.php"}).hide().appendTo("body");
    }

    $("form.replaceFormBitixAjax").each(function () {
	bitrixFormAjaxReplaseInit(this);
    });
}

function progressHandlingFunction(e) {
    if (e.lengthComputable) {
	$('progress').attr({value: e.loaded, max: e.total});
    }
}


function bitrixFormAjaxReplase()
{

    var bxajaxObj = $("#comp_" + $('input[name=bxajaxid]', this).val());
    $(this).trigger("BeforeFormAjaxReload", [bxajaxObj]);

    loader_Custom_ajax_shadow(bxajaxObj);
    var currentForm = $(this);


    var formData = $(this).attr("method").toUpperCase() == "POST" ? new FormData($(this)[0]) : $(this).serialize();

    var options = {
	url: currentForm.attr("action"),
	dataType: "html",
	type: currentForm.attr("method"),
	cache: false,
	contentType: false,
	processData: false,
	data: formData
    };


    if (currentForm.attr("method").toLowerCase() == "post" && $('progress', bxajaxObj).length) {

	function progressHandlingFunction(e) {
	    if (e.lengthComputable) {
		$('progress', bxajaxObj).attr({value: e.loaded, max: e.total});
	    }
	}
	options.xhr = function () {  // Custom XMLHttpRequest
	    var myXhr = $.ajaxSettings.xhr();
	    if (myXhr.upload) {
		myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
	    }
	    return myXhr;
	};
    }

    //для сохранения autocompleate
    currentForm.attr("action", "/local/fakeajaxsubmit.php");
    currentForm.attr("target", "frameSendAjax");
    currentForm.off();
    currentForm.submit();

    $.ajax(options).done(function (data)
    {

	var result = {"obj": bxajaxObj, "data": data, insertNode: true};

	//восстанавливаю событие
	if (currentForm.length) {
	    currentForm.attr("action", options.url);
	    currentForm.attr("target", "");
	    currentForm.on("submit.bitrixFormAjaxReplaseInit", bitrixFormAjaxReplase);
	}

	BeforeAjaxInsert(result);

	loader_Custom_ajax_remove();
	if (result.obj === false) {
	    return;
	}
	$('body').trigger('onAjaxReload', [result.obj]);
    });
    return false;
}


function BeforeAjaxInsert(result) {
    $('body').trigger('onAjaxBeforeInsert', [result]);


    if (result.insertNode) {
	$(result.obj).html(result.data);
    }

    loader_Custom_ajax_remove();
}
if (BX.ajax)
{
    BX.originalAjaxState = [];
    BX.originalAjaxStateNum = 0;


    BX._showWait = BX.showWait;
    BX.showWait = function (node, msg)
    {
	loader_Custom_ajax_shadow(node);
	BX._showWait(node, msg);
	return;
    }

    BX.ajax._UpdatePageData = BX.ajax.UpdatePageData;


    BX.ajax.UpdatePageData = function (arAjaxPageData) {

	$('body').trigger('onAjaxBeforeUpdatePageData', [arAjaxPageData]);

	if (!arAjaxPageData) {
	    return;
	}

	BX.ajax._UpdatePageData(arAjaxPageData);
	return;
    }

    BX._closeWait = BX.closeWait;
    BX.closeWait = function (node, obMsg)
    {
	loader_Custom_ajax_remove();
	BX._closeWait(node, obMsg);
    }

    BX.ajax._submitComponentForm = BX.ajax.submitComponentForm;
    BX.ajax.submitComponentForm = function (obForm, container)
    {
	var bxajaxObj = $("#" + container)
	loader_Custom_ajax_shadow(bxajaxObj);
	BX.ajax._submitComponentForm(obForm, container);
	BX.addCustomEvent('onAjaxSuccess', FormAjaxDone);

	function FormAjaxDone()
	{
	    loader_Custom_ajax_remove();
	    $('body').trigger('onAjaxReload', [bxajaxObj]);
	}

    }

    BX.ajax.history._put = BX.ajax.history.put;
    BX.ajax.history.put = function (state, new_hash, new_hash1, bStartState) {

	var curState = {
	    state: state,
	    new_hash: new_hash,
	    new_hash1: new_hash1,
	    bStartState: bStartState,
	    putState: true

	}

	if ($("#contentForNewHash").length) {
	    state.data = $("#contentForNewHash").html();
	}

	$('body').trigger('onAjaxReloadHistoryPut', [curState]);

	if (curState.putState) {
	    BX.ajax.history._put(curState.state, curState.new_hash, curState.new_hash1, curState.bStartState);

	}

    }


    BX.ajax.component.prototype._setState = BX.ajax.component.prototype.setState;
    BX.ajax.component.prototype.setState = function (state) {
	//state.node - объект
	//state.data - объект
	//state.title - Заголовок
	//state.nav_chain - цепочка

	state.obj = $("#" + state.node);
	state.insertNode = true;


	$('body').trigger('onAjaxBeforeSetStateInsert', [state]);

	if (state.insertNode) {
	    BX.ajax.component.prototype._setState(state);
	}

	BeforeAjaxInsert(state);

	$('body').trigger('onAjaxReload', [state.obj]);
    };

    BX.ajax._insertToNode = BX.ajax.insertToNode;
    BX.ajax.insertToNode = function (url, container)
    {
	var bxajaxObj = $("#" + container);
	loader_Custom_ajax_shadow(bxajaxObj);
	var options = {
	    url: url,
	    dataType: "html",
	    type: "GET"
	};

	$.ajax(options).done(function (data)
	{
	    var result = {"obj": bxajaxObj, "data": data, insertNode: true};
	    BeforeAjaxInsert(result);

	    $('body').trigger('onAjaxReload', [result.obj]);
	});
    }
}


/*jquery ajaxa  */
function  loader_Custom_ajax_remove()
{

    $(".js_ajax_opacity").fadeTo(0, 1).removeClass("js_ajax_opacity");

    $(".waitwindowlocalshadow").remove();
}

/*jquery ajaxa  */
function loader_Custom_ajax_shadow(cont)
{
    var bxajaxObj = $(cont);

    var animatedForm = $('form.form-anim', bxajaxObj);

    if (animatedForm.length > 0) {
	animatedForm.addClass('is-send');
	return;
    }

    if (!bxajaxObj.length)
	bxajaxObj = $(".main").length == 1 ? $(".main") : $("body");

    bxajaxObj.fadeTo(0, 0.6, function () {
	$(this).addClass("js_ajax_opacity");
    });


    var waitwindowlocalshadow;
    waitwindowlocalshadow = (bxajaxObj.is('div')) ? $('<div class="waitwindowlocalshadow"/>').prependTo(bxajaxObj) : $('<div class="waitwindowlocalshadow"/>').prependTo("body");

    var waitwindowlocal = $('<div class="waitwindowlocal"/>').appendTo(waitwindowlocalshadow);


    var waitwindowlocalshadowWidth = bxajaxObj.width();
    var waitwindowlocalshadowHeight = bxajaxObj.height();
    if (bxajaxObj.is('div'))
    {
	waitwindowlocalshadow.css({
	    width: waitwindowlocalshadowWidth + "px",
	    height: waitwindowlocalshadowHeight + "px"
	})
    } else
    {
	var offsetObj = bxajaxObj.offset();
	waitwindowlocalshadow.css({
	    width: waitwindowlocalshadowWidth + parseInt(bxajaxObj.css("padding-left")) + parseInt(bxajaxObj.css("padding-right")) + "px",
	    height: waitwindowlocalshadowHeight + parseInt(bxajaxObj.css("padding-top")) + parseInt(bxajaxObj.css("padding-bottom")) + "px",
	    left: offsetObj.left - parseInt(bxajaxObj.css("padding-left")),
	    top: offsetObj.top - parseInt(bxajaxObj.css("padding-top"))
	});
    }

    waitwindowlocal.css({
	width: waitwindowlocalshadowWidth + "px",
	height: waitwindowlocalshadowHeight + "px"
    });

    if (waitwindowlocalshadowWidth >= 70 && waitwindowlocalshadowHeight >= 70)
	waitwindowlocal.addClass('small');
    else
	waitwindowlocal.addClass('very_small');

    $('body').trigger('onAjaxReloadBefore', [bxajaxObj]);
//    NProgress.start();
}

//
function windowHeight() {
    var de = document.documentElement;

    return self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
}

//
function windowWidth() {
    var de = document.documentElement;
    return self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
}




function strTrim(str) {
    return (typeof (str) == "undefined") ? "" : str.replace(/^\s+|\s+$/gm, '');
}


function LoadAjaxFancy(e, scope) {
    $(".js_openpopup", scope).on("click.PopupOpen", function () {

	if (!$(this).data("href") && !$(this).attr("href")) {
	    return
	}


	var currentPopup = $(this);
	var curpage = new Url();
	var pageUrl = new Url(currentPopup.data("href") ? currentPopup.data("href") : currentPopup.attr("href"));
	pageUrl.query.SHOWAJAXCART = "Y";
	pageUrl.query.OPENURL = curpage.path + "?" + curpage.query;


	var objOpen = {
	    //type: 'ajax',
	    // not working
        closeClickOutside: true,
	    type: currentPopup.data('type') ? currentPopup.data('type') : "ajax",

	    ajax: {
		settings: {
		    url: pageUrl.path + "?" + pageUrl.query,
		    type: 'GET',
		    dataType: "html"
		}
	    },
	    onComplete: function (param1, paaram2) {

		if (typeof currentPopup.data('width') !== 'undefined') {
		    $(".fancybox-slide > *").css("max-width", currentPopup.data('width'));
		}

		loader_Custom_ajax_remove();
		var fancyboxContainer = $(".fancybox-container");
		$("body").triggerHandler("onAjaxReload", [fancyboxContainer]);
		$("body").triggerHandler("onAjaxPopupOpen", [fancyboxContainer]);
		if (fancyboxContainer.find('.popup').length === 0) {
		    $(".fancybox-slide:not(.fancybox-slide--image) > div").addClass("popup popup--fallback-wrap");
		}
		$(".js_fancy_close", $(".fancybox-container")).on("click", function () {
		    $.fancybox.close()
		});
	    },
	    afterClose: function () {
		$("body").triggerHandler("onAjaxPopupClose", [$("#fancybox-container-3")]);
	    }
	};


	$.fancybox.close();

	if (objOpen.type != "ajax") {
	    $.fancybox.open(currentPopup);
	} else {
	    $.fancybox.open({type: currentPopup.data('type') ? currentPopup.data('type') : "ajax", }, objOpen);
	}


	return false;
    })

}



function linkForGAMetrics(event, bxobj) {
    $(".js_gametricLink", bxobj).off("click.metric").on("click.metric", sendEvent);
    $(".js_gametricSend", bxobj).each(function () {
	$(this).one("gametricSend", sendEvent).triggerHandler("gametricSend");
    });
}

function linkForMetrics(event, bxobj) {
    $(".js_metricLink", bxobj).off("click.metric").on("click.metric", sendMetric);
    $(".js_metricSend", bxobj).each(function () {
	$(this).one("metricSend", sendMetric).triggerHandler("metricSend");
    });
}


function sendEvent() {
    if ($(this).is(".disabled")) {
	return;
    }

    var
	    type = $(this).data("gatype") || "event",
	    category = $(this).data("gacategory") || "",
	    action = $(this).data("gaaction") || "",
	    opt_label = $(this).data("galabel") || "";

    if (window.ga !== undefined && type && category && action) {
	window.ga('send', type, category, action, opt_label);
    }
}


function sendMetric() {
    if ($(this).is(".disabled") || (typeof (window.yaMetricIdent) != "object")) {
	return;
    }

    var
	    action = $(this).data("metricaction"),
	    param = $(this).data("metricparam");

    if (action && !param) {
	window.yaMetricIdent.reachGoal(action);
    } else {
	window.yaMetricIdent.reachGoal(action, {Ya: param});

    }
}


function initNextPage(obj) {
    var currentPaginationLoad = obj;

    $('body').off('onAjaxBeforeInsert.PaginationInsert').one('onAjaxBeforeInsert.PaginationInsert', function (event, dataObj) {



	dataObj.insertNode = false;

	var oPrepareFilterInsert = $("<div>").appendTo($("body")).hide().append(dataObj.data);

	dataObj.obj = $(".js_insertAfterAjaxLazyLoad", oPrepareFilterInsert).insertAfter($(".js_insertAfterAjaxLazyLoad:last", dataObj.obj));


	currentPaginationLoad.remove();
	oPrepareFilterInsert.remove();
    });

    if (!currentPaginationLoad.is(".js_no_skip_push_state")) {
	$('body').one('onAjaxReloadHistoryPut.PaginationInsert', function (event, dataObj) {
	    dataObj.putState = false;
	});
    }
}

function LazyLoadPageInit(event, bxobj) {
    $(window).off('resize.LazyLoadPage', checkForLoadNextPage);
    $(window).off('scroll.LazyLoadPage', checkForLoadNextPage);

    if ($(".js_lazyloadPagination").length) {
	$(window).on('resize.LazyLoadPage', checkForLoadNextPage);
	$(window).on('scroll.LazyLoadPage', checkForLoadNextPage);
    }
    $('.js-load-items').on('click', function () {
	initNextPage($(this).closest('.js_more-items'));
    });

}


function LazyComponentInit() {
    $(window).off('resize.LazyComponentInit', checkForLoadComponent);
    $(window).off('scroll.LazyComponentInit', checkForLoadComponent);

    if ($(".js_smart_cache_load").length) {
	$(window).on('resize.LazyComponentInit', checkForLoadComponent);
	$(window).on('scroll.LazyComponentInit', checkForLoadComponent);
    }

    setTimeout(checkForLoadComponent, 1000);

}

function checkForLoadComponent() {
    var scrollTop = $(window).scrollTop() + windowHeight() + 200;


    $(".js_smart_cache_load").each(function () {

	if (scrollTop >= $(this).offset().top) {

	    $(window).off('resize.LazyLoadPage', checkForLoadNextPage);
	    $(window).off('scroll.LazyLoadPage', checkForLoadNextPage);


	    $(this).triggerHandler("click");
	    $(this).remove();
	}

    });

}

function checkForLoadNextPage() {
    var scrollTop = $(window).scrollTop() + windowHeight() + 200;


    $(".js_lazyloadPagination").each(function () {

	if (scrollTop >= $(this).offset().top) {

	    $(window).off('resize.LazyLoadPage', checkForLoadNextPage);
	    $(window).off('scroll.LazyLoadPage', checkForLoadNextPage);

	    initNextPage($(this));

	    $(".js_lazyloadPagination a").triggerHandler("click")
	}

    });

}


function preloadCatalogImages(event, bxobj) {

    var win = $(window);
    win.off('resize.preloadCatalogImages', preloadCatalogImages);
    win.off('scroll.preloadCatalogImages', preloadCatalogImages);
    win.off('touchmove.preloadCatalogImages', preloadCatalogImages);

    if (!$(".js_image_preload").length) {
	return false;
    }

    var scrollTop = $(window).scrollTop() + windowHeight() + 300;


    $(".js_image_preload").each(function () {
	var curImg = $(this);
	if (scrollTop >= curImg.offset().top) {
	    curImg.removeClass("js_image_preload");

	    var objParams = {
		width: 0,
		height: 0,
		"z-index": curImg.css("z-index"),
		position: "relative"
	    };



	    var img = new Image();

	    $(img).one("load", function () {
		curImg.attr("src", curImg.data("src"));


		curImg.data("preloader").fadeTo(500, 0, function () {
		    $(this).remove();
		});

	    });

	    img.src = curImg.data("src");

	    curImg.attr("src", "/images/shadow.png");

	    var preloader = $("<span class='js_proloader'></div>").insertBefore(curImg).css(objParams);

	    objParams = {
		width: curImg.width() + 5,
		height: curImg.height() + 5,
		display: "inline-block",
		position: "absolute",
		"z-index": curImg.css("z-index"),
		"background-color": "#FFFFFF",
		"background-position": "50% 50%",
		"background-image": "url(/local/components/newsite/basket/image/preloader_small.gif)",
		"background-repeat": "no-repeat"
	    };

	    preloader.append("<span></span>").css(objParams);

	    $(this).data("preloader", preloader);

	}

    });

    setTimeout(function () {
	win.one('resize.preloadCatalogImages', preloadCatalogImages);
	win.one('scroll.preloadCatalogImages', preloadCatalogImages);
	win.one('touchmove.preloadCatalogImages', preloadCatalogImages);
    }, 300);

}

function SrcollToTop(event) {

    var objectScroll;
    if ($(event.target).closest(".js_scroll_to_obj").length) {
	objectScroll = $(event.target).closest(".js_scroll_to_obj");
    } else {
	objectScroll = $(".main").length == 1 ? $(".main") : $("body");
    }
    var scrollOffset = objectScroll.data('scroll-values') ? objectScroll.data('scroll-values') : 140;
    ScrollToPageTop(objectScroll, scrollOffset);
}


function  ScrollToPageTop(objectScroll, scrollOffset) {
    $("html, body").animate({
	scrollTop: objectScroll.offset().top - scrollOffset
    }, 'fast');
}

function initScrollTop(event, bxobj) {
    $(".js_scroll_top", bxobj).on("click.initScrollTop", SrcollToTop);
}


function loadAjaxJs(event, bxobj) {

    if ($('.js_loader', bxobj).length > 0) {
	$('.js_loader', bxobj).each(function () {
	    jQuery.ajax({
		url: $(this).val(),
		type: "get",
		cache: true,
		dataType: "script",
		success: function () {}
	    });
	});
    }
}

function noInsertData(event, bxobj) {
    $(".js_no_reload_block", bxobj).off("click.noInsertData").on("click.noInsertData", function () {
	$("body").one("onAjaxBeforeInsert.noInsertData", function (event, result) {
	    result.insertNode = false;
	});
    })
}

function initLocal() {

}


function moveBlock(event, bxobj) {

    if ($(".js_insert_into", bxobj).length) {

	$(".js_insert_into", bxobj).each(function () {
	    if ($(this).data("into") && $($(this).data("into")).length) {
		$(this).removeClass("js_insert_into");

		$($(this).data("into")).html($(this));
	    }
	})
    }

}

$(document).ready(function () {

    var body = $(document.body);

    $(".bx-context-toolbar-pin-fixed").triggerHandler("click");
    $(".bx-context-toolbar").addClass("bx-context-toolbar-vertical-mode");

    LazyComponentInit();

    body.on("onAjaxReload.loadAjaxJs", loadAjaxJs).triggerHandler("onAjaxReload.loadAjaxJs", [body]);
    body.on("onAjaxReload.LazyLoadPageInit", LazyLoadPageInit).triggerHandler("onAjaxReload.LazyLoadPageInit", [body]);
    body.on("onAjaxReload.linkForGAMetrics", linkForGAMetrics).triggerHandler("onAjaxReload.linkForGAMetrics", [body]);
    body.on("onAjaxReload.linkForMetrics", linkForMetrics).triggerHandler("onAjaxReload.linkForMetrics", [body]);
    body.on("onAjaxReload.noInsertData", noInsertData).triggerHandler("onAjaxReload.noInsertData", [body]);

    var img = new Image();
    img.src = "/local/components/newsite/basket/image/preloader_small.gif";
    $(img).one("load", function () {
	body.on("onAjaxReload.preloadCatalogImages", preloadCatalogImages).triggerHandler("onAjaxReload.preloadCatalogImages", [body]);
    });


    body.on("onAjaxReload.LoadAjaxFancy", LoadAjaxFancy).triggerHandler("onAjaxReload.LoadAjaxFancy", [body]);
    body.on("onAjaxReload.checkForAjaxForms", checkForAjaxForms).triggerHandler("onAjaxReload.checkForAjaxForms", [body]);
    body.on("onAjaxReload.initLocal", initLocal).triggerHandler("onAjaxReload.initLocal", [body]);

    body.on("onAjaxReload.initScrollTop", initScrollTop).triggerHandler("onAjaxReload.initScrollTop", [body]);

    body.on("onAjaxReload.moveBlock", moveBlock).triggerHandler("onAjaxReload.moveBlock", [body]);


    body.on("onAjaxBeforeInsert.checkResponse", checkResponse).triggerHandler("onAjaxBeforeInsert.InitFormAfter", [{obj: body}]);
    body.on("onAjaxBeforeInsert.initErrorFields", initErrorFields).triggerHandler("onAjaxBeforeInsert.initErrorFields", [{obj: body}]);

});


function initErrorFields(scope) {
    setTimeout(function () {
	$('.js-field-error input').on('click', function (e) {
	    $(this).closest('.js-field-error').removeClass('error');
	});
    }, 0);
}





function checkResponse(event, dataObj) {


    var oPrepareFilterInsert = $("<div>").appendTo($("body")).hide().append(dataObj.data);

    if ($('.js-reload', oPrepareFilterInsert).length > 0) {

	dataObj.insertNode = false;

	var url = $('.js-reload', oPrepareFilterInsert).data("href");
	if (url) {
	    window.location = url;
	} else {
	    window.location.reload();
	}
    }

    oPrepareFilterInsert.remove();

}

