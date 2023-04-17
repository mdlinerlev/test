/* 
 * Для передачи в Google Analitycs 
 * правильных настроек клиента
 * записываем их себе для доступа из
 * backend
 */


/**
 * DOCUMENT READY
 * @param {type} funcName
 * @param {type} baseObj
 * @returns {undefined}
 */
(function (funcName, baseObj) {
// The public function name defaults to window.docReady
// but you can pass in your own object and own function name and those will be used
// if you want to put them in a different namespace
    funcName = funcName || "yuriyantDocReady";
    baseObj = baseObj || window;
    var readyList = [];
    var readyFired = false;
    var readyEventHandlersInstalled = false;
    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
        if (!readyFired) {
            // this must be set to true before we start calling callbacks
            readyFired = true;
            for (var i = 0; i < readyList.length; i++) {
                // if a callback here happens to add new ready handlers,
                // the docReady() function will see that it already fired
                // and will schedule the callback to run right after
                // this event loop finishes so all handlers will still execute
                // in order and no new ones will be added to the readyList
                // while we are processing the list
                readyList[i].fn.call(window, readyList[i].ctx);
            }
            // allow any closures held by these functions to free
            readyList = [];
        }
    }

    function readyStateChange() {
        if (document.readyState === "complete") {
            ready();
        }
    }

    // This is the one public interface
    // docReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function (callback, context) {
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function () {
                callback(context);
            }, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        if (document.readyState === "complete") {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }
    }
})("yuriyantDocReady", window);
/**
 * Передача в backend параметров
 * @returns {undefined}
 */
function yuriyantGASaveUserSettings() {
    var xhr = new XMLHttpRequest();
    var data = {
        screenResolution: window.screen.width + 'x' + window.screen.height,
        screenColorDepth: window.screen.colorDepth,
        javaEnabled: navigator.javaEnabled(),
        documentTitle: document.title,
        pageUrl: encodeURIComponent(window.location.href),
        documentReferrer: encodeURIComponent(document.referrer),
        cid: yuriyantGAGetGACID()
    }
    var body = 'userParams=' + JSON.stringify(data);
    xhr.open("POST", '/bitrix/components/yuriyant/tracking4ecommerce/component.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = '';
    xhr.send(body);
}

function yuriyantGAsetCookie(name, value, days) {
    var getDomainName = function () {
        var hostName = window.location.hostname;
        return hostName.substring(hostName.lastIndexOf(".", hostName.lastIndexOf(".") - 1) + 1);
    }
    try {
        var expires = "";
        if (!days) {
            days = 365;
        }
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/; domain=." + getDomainName() + ";";
    } catch (e) {
        console.error('Module yuriyant.tracking4ecommerce', e)
    }
}

function yuriyantGAgetCookie(name) {
    try {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ')
                c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0)
                return c.substring(nameEQ.length, c.length);
        }
    } catch (e) {
        console.error('Module yuriyant.tracking4ecommerce', e)
    }
    return null;
}

function yuriyantGAGetGACID() {
    try {
        if (typeof ga !== 'undefined' && typeof ga.getAll !== 'undefined') {
            var clientId = ga.getAll()[0].get('clientId');
            if (clientId) {
                return clientId;
            }
        }
        var cidLong = yuriyantGAgetCookie('_ga');
        if (!cidLong) {
            return false;
        }
        var tmp = cidLong.split('.');
        if (typeof tmp[3] !== 'undefined') {
            var cid = tmp[2] + '.' + tmp[3];
            return cid;
        }
        if (typeof tmp[2] !== 'undefined' && tmp[2].includes("amp")) {
            var cid = tmp[2];
            return cid;
        }
    } catch (e) {
        console.error('Module yuriyant.tracking4ecommerce', e)
    }
}

yuriyantDocReady(function () {
    try {
        if (!yuriyantGAgetCookie('_yriant4ecom_isInit')) {
            yuriyantGAsetCookie('_yriant4ecom_isInit', 1);
            var waitSec = 11;
            var connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (connection && typeof connection.effectiveType !== 'undefined') {
                switch (connection.effectiveType) {
                    case 'slow-2g':
                    case '2g':
                    default:
                        waitSec = 17;
                        break;
                    case '3g':
                        waitSec = 11;
                        break;
                    case '4g':
                    case '5g':
                        waitSec = 5;
                        break
                }
            }
            var i = 0;
            var yuriyantGAInterval = setInterval(function () {
                var cid = yuriyantGAGetGACID();
                if (cid || i >= (waitSec * 2)) {
                    clearInterval(yuriyantGAInterval);
                    yuriyantGAsetCookie('_yriant4ecom_gaExists', cid ? 1 : 0);
                    yuriyantGASaveUserSettings();
                }
                i++;
            }, 500)
        } else {
            yuriyantGASaveUserSettings();
        }
        if (yuriyantGAGetGACID() && !yuriyantGAgetCookie('_yriant4ecom_gaExists')) {
            yuriyantGAsetCookie('_yriant4ecom_gaExists', 1);
        }
    } catch (e) {
        console.error('Module yuriyant.tracking4ecommerce', e)
    }
});