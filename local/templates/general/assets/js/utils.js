"use strict";var onYouTubeIframeAPIReady=function(a,b){if(b)return new YT.Player(a,{height:"315",width:"560",videoId:b,playerVars:{autoplay:1,playsinline:1},playsinline:1})};HTMLElement.prototype.slideToggle=function(a,b){0===this.clientHeight?_s(this,a,b,!0):_s(this,a,b)},HTMLElement.prototype.slideUp=function(a,b){_s(this,a,b)},HTMLElement.prototype.slideDown=function(a,b){_s(this,a,b,!0)};var _s=function(a,b,c,d){function e(g){f===void 0&&(f=g);var r=g-f;d?(a.style.height=m*r+"px",a.style.paddingTop=n*r+"px",a.style.paddingBottom=o*r+"px",a.style.marginTop=p*r+"px",a.style.marginBottom=q*r+"px"):(a.style.height=h-m*r+"px",a.style.paddingTop=i-n*r+"px",a.style.paddingBottom=j-o*r+"px",a.style.marginTop=k-p*r+"px",a.style.marginBottom=l-q*r+"px"),r>=b?(a.style.height="",a.style.paddingTop="",a.style.paddingBottom="",a.style.marginTop="",a.style.marginBottom="",a.style.overflow="",!d&&(a.style.display="none"),"function"==typeof c&&c()):window.requestAnimationFrame(e)}"undefined"==typeof b&&(b=400),"undefined"==typeof d&&(d=!1),a.style.overflow="hidden",d&&(a.style.display="block");var f,g=window.getComputedStyle(a),h=parseFloat(g.getPropertyValue("height")),i=parseFloat(g.getPropertyValue("padding-top")),j=parseFloat(g.getPropertyValue("padding-bottom")),k=parseFloat(g.getPropertyValue("margin-top")),l=parseFloat(g.getPropertyValue("margin-bottom")),m=h/b,n=i/b,o=j/b,p=k/b,q=l/b;window.requestAnimationFrame(e)},isMobileDevice=function(){return!!/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)},isMobilePoint=function(){return window.matchMedia("(max-width: ".concat(MOBILE_POINT,"px)")).matches},resizeFunction=function(a){var b=null;clearTimeout(b),b=setTimeout(function(){a.length&&a.forEach(function(a){a()})},250)},setCssVariable=function(a,b){root.style.setProperty(a,b)},OVERFLOW_NODES=[document.querySelector("html"),document.querySelector("body")],getSiblings=function(a){for(var b=[],c=a;c.previousSibling;)c=c.previousSibling,1==c.nodeType&&b.push(c);for(c=a;c.nextSibling;)c=c.nextSibling,1==c.nodeType&&b.push(c);return b},scrollDisable=function(){OVERFLOW_NODES.map(function(a){a&&a.classList.add(OVERFLOW_SELECTOR)})},scrollEnable=function(){OVERFLOW_NODES.map(function(a){a&&a.classList.remove(OVERFLOW_SELECTOR)});var a=document.querySelectorAll(".js-drag-to-scroll");a&&a.forEach(function(a){a&&a.classList.remove(ACTIVE_CLASS)})},scrollToggle=function(){OVERFLOW_NODES.map(function(a){a&&a.classList.toggle(OVERFLOW_SELECTOR)})},menuBgOverlayEnable=function(){document.querySelector(OVERLAY_CONTAINER_SELECTOR).classList.add(OVERLAY_SELECTOR)},menuBgOverlayDisable=function(){document.querySelector(OVERLAY_CONTAINER_SELECTOR).classList.remove(OVERLAY_SELECTOR)},menuBgOverlayToggle=function(){document.querySelector(OVERLAY_CONTAINER_SELECTOR).classList.toggle(OVERLAY_SELECTOR)},getNodeCssValue=function(a,b){return a?window.getComputedStyle(a).getPropertyValue(b):null};