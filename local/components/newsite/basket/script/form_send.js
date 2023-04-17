/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*Работа с выбором файлов*/
function fileInputActions(obj) {
    $('.js-file-input-btn', obj).on('click', function () {
		if ( $(this).closest('.js-file-input-group').find('.js-file-input').attr( 'multiple' ) ){
			console.log($(this).closest('.js-file-input-group').find('.js-file-input.js-file-input-base').clone().removeClass('js-file-input-base').appendTo( $(this).closest('.js-file-input-group') ).on( 'change', CheckFileInput));
			$(this).closest('.js-file-input-group').find('.js-file-input.js-file-input-base').clone().removeClass('js-file-input-base').on('change', CheckFileInput ).appendTo( $(this).closest('.js-file-input-group') ).trigger("click");
		}else{
	$(this).closest('.js-file-input-group').find('.js-file-input').trigger("click");
		}
    });

	function CheckFileInput(e){
    var fileInput = $(this),
	    fileInputNames = [],
	    fileInputList = fileInput.closest('.js-file-input-group').find('.js-file-input-list'),
			supportsMultiple = fileInput.attr('multiple'),
	    maxFileSize = (1024 * 1024),
	    isImg, fileName; // файлы не больше 1-ого метра

	    if (supportsMultiple) {
    for (var i = 0; i < $(this).get(0).files.length; ++i) {
    isImg = $($(this).get(0)).data("img");
	    fileName = $(this).get(0).files[i].name;
	    if ($(this).get(0).files[i].size > maxFileSize || (isImg && !fileName.match(/(jpg|png|gif)$/)))
    {
    $(this).get(0).value = "";
	    continue;
    }
    fileInputNames.push({name: fileName, file: $(this).get(0).files[i], isImg: isImg});
    }
    } else {

    isImg = $($(this).get(0)).data("img");
	    fileName = e.target.files[0].name;
	    if ($(this).get(0).files[0].size > maxFileSize || (isImg && !fileName.match(/(jpg|png|gif)$/)))
    {
    $(this).get(0).value = "";
    } else
    {
    fileInputNames.push({name: fileName, file: e.target.files[0], isImg: isImg});
    }

    }



    if (!supportsMultiple) {
    fileInputList.empty();
    }

    fileInputList.show();

	    fileInputNames.forEach(function (element) {


	    if (element.isImg) {
	    var fReader = new FileReader();
		    fReader.readAsDataURL(element.file);
		    fReader.onloadend = function (event) {

		    var curImage = $('<span class="file-input-list_item js-file-input-li"><img  style="max-width:100px; max-height: 100px" alt=""/></span>').prependTo(fileInputList);

			    $("img", curImage).attr("src", event.target.result)
		    }
	    } else {
	    fileInputList.prepend('<span class="file-input-list_item js-file-input-li">' + element.name + '</span>');
	    }

	    });
    }
    $('.js-file-input', obj).on('change', CheckFileInput);
    }


    function checkResponse(event, dataObj) {


    var oPrepareFilterInsert = $("<div>").appendTo($("body")).hide().append(dataObj.data);

    if ($('.js-reload', oPrepareFilterInsert).length > 0) {

	dataObj.insertNode = false;
	closeAll();
	var url = $('.js-reload', oPrepareFilterInsert).data("href");
	if (url) {
	    window.location = url;
	} else {
	    window.location.reload();
	}
    }

    if ($('.js_close_popups', oPrepareFilterInsert).length > 0) {
	closeAll();
    }




    if ($(".js-fixed-message", oPrepareFilterInsert).length > 0) {
	showFixedMessage($(".js-fixed-message", oPrepareFilterInsert).prependTo($("body")));
	$(".js-fixed-message", oPrepareFilterInsert).remove();
    }


    if ($(".js-fixed-message", dataObj.obj).length > 0) {
	showFixedMessage($(".js-fixed-message", dataObj.obj).prependTo($("body")));
    }

    if ($(".js_insert_contents", oPrepareFilterInsert).length > 0) {
	dataObj.data = $(".js_insert_contents", oPrepareFilterInsert).html();
    }


    oPrepareFilterInsert.remove();



}

function InitFormAfter(event, bxobj) {
    fileInputActions(bxobj);
    sendFormInit(bxobj);


    $(".js_select_change", bxobj).on("change", function () {
	if ($(".js_select_change_" + $(this).val()).length) {
	    $(".js_select_change_" + $(this).val()).triggerHandler("click");
	}
    });

    $(".js_select_change_submit", bxobj).each(function () {
	$(this).data("last-value", $(this).val());
    });

    $(".js_select_change_submit", bxobj).off("change.sumbitAfter").on("change.sumbitAfter", function () {


	if ($(this).closest("form").length) {

	    $(".js_ajax_check_field", $(this).closest("form")).each(function () {
		clearTimeout($(this).data("timer"));
	    });

	    $(this).closest("form").triggerHandler("submit");
	}
    })

    $(".js_ajax_check_field", bxobj).off("change.fieldCheck, keyup.fieldCheck").on("change.fieldCheck, keyup.fieldCheck", function () {
	$(this).removeClass("form-success");
	setTimer($(this), 1000);
    }).off("blur.fieldCheck").on("blur.fieldCheck", function () {
	setTimer($(this), 0);
    });

}

function setTimer(input, timer) {


    if (input.val() == input.data("last-value")) {
	return;
    }

    var parent = input.closest(".js_form_row");

    if (!parent.data("rowid")) {
	return
    }

    var timerID = input.data("timer");

    clearTimeout(timerID);

    $(this).data("timer", setTimeout(function () {
	sendCheckFormRequest(input)
    }, timer));
}


function sendCheckFormRequest(input) {

    var parent = input.closest(".js_form_row"),
	    form = parent.closest("form");

    if (!parent.data("rowid")) {
	return
    }

    $(".js_submit_mode", form).val("1");

    var timerID = input.data("timer");

    clearTimeout(timerID);

    $("body").one("onAjaxBeforeInsert.checkResponse", function (event, dataObj) {
	checkResponseChecker(input, dataObj);
    });

    $(".js_set_code:first", form).val("I_AM_NO_ROBOT");

    input.data("last-value", input.val());

    $("body").on("onAjaxReloadBefore.sendCheckFormRequest", function () {
	loader_Custom_ajax_remove();
    })


    parent.closest("form").triggerHandler("submit");
}

function checkResponseChecker(input, dataObj) {
    var parent = input.closest(".js_form_row");


    if (!parent.data("rowid")) {
	return
    }

    dataObj.insertNode = false;

    var oPrepareFilterInsert = $("<div>").appendTo($("body")).hide().append(dataObj.data);

    var serachObj = $(".js_form_row_" + parent.data("rowid"), oPrepareFilterInsert);

    if (serachObj.length) {

	if (serachObj.is(".js_error_field")) {
	    parent.addClass("js_error_field").addClass("form-error");
	    parent.removeClass("form-success");

	    $(".js_error_message", parent).html($(".js_error_message", serachObj)).show();
	} else {
	    parent.removeClass("js_error_field").removeClass("form-error");
	    $(".js_error_message", parent).html("");
	    parent.addClass("form-success");
	}

    }

    oPrepareFilterInsert.remove();
    dataObj.obj = false;
}

$(document).ready(function () {
    $("body").on("onAjaxReload.InitFormAfter", InitFormAfter).triggerHandler("onAjaxReload.InitFormAfter", [$("body")]);

    $("body").on("onAjaxBeforeInsert.checkResponse", checkResponse).triggerHandler("onAjaxBeforeInsert.checkResponse", [{obj: $("body")}]);
}
);


function sendFormInit(bxobj) {



    $('.js_close_form_btn', bxobj).on('click', function (event) {
	event.preventDefault();
	$(this).closest('.page_form_area').add('.js-page-overlay').removeClass('active');
    });


    if ($(".js_form_agreement", bxobj).length) {

	$(".js_form_agreement", bxobj).on("change.agreementChange", function () {

	    if ($(".js_form_agreement", bxobj).is(":checked")) {
		$(".js_submit_form", bxobj).prop("disabled", 0).removeClass("disabled");
	    } else {
		$(".js_submit_form", bxobj).prop("disabled", 1).addClass("disabled");
	    }
	});
	$(".js_form_agreement", bxobj).triggerHandler("change.agreementChange");

    }

    if ($('.js_edit_rating', bxobj).length) {
	$('.js_edit_rating .js_icon_star', bxobj).on({
	    mouseover: function () {
		$(this).addClass('on').prevAll().addClass('on');
		$(this).nextAll().removeClass('on');
	    },
	    click: function () {
		$(this).closest('.js_edit_rating').find('input[type="hidden"]').val($(this).index() + 1);
	    }
	});

	var curValue = $('.js_edit_rating input[type="hidden"]', bxobj).val();
	if (curValue > 0) {
	    $('.js_edit_rating .js_icon_star', bxobj).eq(curValue - 1).triggerHandler("mouseover");
	}

	$('.js_edit_rating', bxobj).on('mouseleave', function () {
	    var val = parseInt($('input[type="hidden"]', this).val());

	    $('.js_icon_star', this).removeClass('on');

	    if (val > 0) {
		$('.js_icon_star', this).eq(val - 1).addClass('on').prevAll(".js_icon_star").addClass('on');
	    }
	});
    }


    $(".js_submit_form", bxobj).off("click").on("click", function () {
	var fileBlock = $(".js-file-input-btn:first", $(this).closest("form"));
	$(".js_set_code:first", $(this).closest("form")).val("I_AM_NO_ROBOT");
	$(".js_submit_mode", $(this).closest("form")).val("0");
	fileBlock.replaceWith($("<progress/>"))
    });




    if ($(".js_error_field", bxobj).length) {

	$(".js_error_field .input, .js_error_field input, .js_error_field select", bxobj).off("focus.formSend  click.formSend").one("focus.formSend click.formSend", function () {
	    if ($(this).closest(".js_error_field").length) {
		var parentObj = $(this).closest(".js_error_field");
		parentObj.removeClass("js_error_field");
		parentObj.removeClass("form-error");
		$(".js_error_message", parentObj).hide();
	    }
	});

	function HideToolTips() {


	    $(".js_error_message", bxobj).fadeTo(1000, 0, function () {
		$(".js_error_message", bxobj).hide();
	    });
	}
	setTimeout(HideToolTips, 2000);
    }
}
 