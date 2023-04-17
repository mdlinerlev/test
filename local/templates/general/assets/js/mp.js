"use strict";

$(document).ready(function () {
  // $('.sl-product__content-wrp').each(function () {
  //     const asNavFor =  $(this).closest('.sl-product').find('.sl-product__img')
  //     $(this).slick({
  //         asNavFor,
  //         arrows: false,
  //         speed: 1500,
  //         fade: true,
  //     })
  // })
  $(".sl-product__img").each(function () {
    var prevArrow = $(this).closest(".sl-product__img-wrp").find(".sl-product__arrow._prev");
    var nextArrow = $(this).closest(".sl-product__img-wrp").find(".sl-product__arrow._next"); // const asNavFor =  $(this).closest('.sl-product').find('.sl-product__content-wrp')

    $(this).slick({
      // asNavFor,
      prevArrow: prevArrow,
      nextArrow: nextArrow,
      speed: 500
    });
  });
  $(".sl-model__img").each(function () {
    var asNavFor = $(this).closest(".sl-model__wrp").find(".sl-model__content");
    $(this).slick({
      asNavFor: asNavFor,
      speed: 1000,
      arrows: false,
      infinite: false,
      swipe: false
    });
  });
  var $slider = $(".sl-model__content");

  if ($slider.length) {
    var currentSlide;
    var slidesCount;

    var updateSliderCounter = function updateSliderCounter(slick, currentIndex) {
      currentSlide = slick.slickCurrentSlide() + 1 < 10 ? "0".concat(slick.slickCurrentSlide() + 1) : slick.slickCurrentSlide() + 1;
      slidesCount = slick.slideCount < 10 ? "0".concat(slick.slideCount) : slick.slideCount;
      $(".sl-model__controll-counter .current").text(currentSlide + " /");
      $(".sl-model__controll-counter .total").text(slidesCount);
    };

    $slider.on("init", function (event, slick) {
      updateSliderCounter(slick);
    });
    $slider.on("afterChange", function (event, slick, currentSlide) {
      updateSliderCounter(slick, currentSlide);
    });
    $slider.each(function () {
      var prevArrow = $(this).closest(".sl-model").find(".sl-model__controll-arrow._prev");
      var nextArrow = $(this).closest(".sl-model").find(".sl-model__controll-arrow._next");
      var asNavFor = $(this).closest(".sl-model__wrp").find(".sl-model__img");
      $(this).slick({
        prevArrow: prevArrow,
        nextArrow: nextArrow,
        asNavFor: asNavFor,
        speed: 1000,
        infinite: false,
        swipe: true,
        swipeToSlide: true,
        fade: true
      });
    });
  } //Открытие-закрытие табов на главной в блоке с советами


  $(document).on("click", ".advice-list .js-head", function () {
    var parent = $(this).closest(".advice-list__item");
    var content = parent.find(".advice-list__item-content");

    if (parent.hasClass("_open")) {
      parent.removeClass("_open");
      content.slideUp(500, function () {
        AOS.refresh();
      });
    } else {
      parent.siblings(".advice-list__item._open").removeClass("_open").find(".advice-list__item-content").slideUp(500);
      parent.addClass("_open");
      content.slideDown(500, function () {
        AOS.refresh();
      });
    }

    AOS.refresh();
  });
  $(document).on("click", ".js-sl-product__item-mob", function (e) {
    e.preventDefault();
    $(this).toggleClass("_open");
    $(this).closest(".sl-product__item-content").find(".sl-product__item-mob__content").toggleClass("_open");
  });
});