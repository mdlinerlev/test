function getCookie(name) {
	let matches = document.cookie.match(new RegExp(
	    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options = {}) {

  	options = {
    	path: '/',
	    // при необходимости добавьте другие значения по умолчанию
	    ...options
	};

  	if (options.expires instanceof Date) {
    	options.expires = options.expires.toUTCString();
  	}

  	let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

  	for (let optionKey in options) {
    	updatedCookie += "; " + optionKey;
    	let optionValue = options[optionKey];
    	if (optionValue !== true) {
      		updatedCookie += "=" + optionValue;
    	}
  	}

  	document.cookie = updatedCookie;
}

function deleteCookie(name) {
	setCookie(name, "", {
	    'max-age': -1
	})
}


function showB24Dialog(show) {

	document.getElementsByClassName('b24-widget-button-popup-inner')[0].click();




	setTimeout(function () {

		document.getElementsByClassName('bx-livechat-control-btn-close')[0].addEventListener("click", function(){ setCookie('b24_livechat', show, { 'max-age': 60 * 60 * 24}); });
		//var els = document.getElementsByClassName('bx-livechat-control-btn-close');
		//console.log("ELS");
		//console.log(els);

		//var els2 = document.getElementsByClassName('bx-livechat-control-box');
		//console.log("ELS2");
		//console.log(els2);
	}, 1000);


	//document.getElementsByClassName('bx-livechat-control-btn-close')[0].addEventListener("click", function(){ setCookie('b24_livechat', show, { 'max-age': 60 * 60 * 24}); }); 
}


var count_show = getCookie('b24_livechat');

console.log("count_show = " + count_show);

if (count_show == 0 || typeof(count_show) == "undefined") {
	//deleteCookie('b24_sitebutton_hello');
	setTimeout(function () {
		showB24Dialog(1);
	}, 30000);

} else if (count_show == 1) {
	//deleteCookie('b24_sitebutton_hello');
	setTimeout(function () {
		showB24Dialog(2);
	}, 60000);
}
else if (count_show == 2) {
	//deleteCookie('b24_sitebutton_hello');
	setTimeout(function () {
		showB24Dialog(3);
	}, 200000);
}

        