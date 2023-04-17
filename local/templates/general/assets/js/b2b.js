"use strict";

$(document).ready(function () {
  if ($('.scroll-X').length && window.innerWidth > 990) {
    $('.scroll-X').mCustomScrollbar({
      axis: 'x',
      callbacks: {
        onTotalScroll: function onTotalScroll() {
          $('.scroll-X').addClass('_hide-gradient');
        },
        onScroll: function onScroll() {
          $('.scroll-X').removeClass('_hide-gradient');
        }
      }
    });
  }

  if ($('.styler').length) {
    $('.styler').styler();
  }

  b2bTableEditor();
  $(document).on('click', '.js-b2b-req__item .js-open', function () {
    var parent = $(this).closest('.js-b2b-req__item');
    $(this).toggleClass('_open');
    parent.toggleClass('_open').find('.b2b-req__item-content').slideToggle(300);
  });
  $(document).on('click', '.js-b2b-req__item-step .js-arrow', function () {
    var parent = $(this).closest('.js-b2b-req__item-step');
    $(this).toggleClass('_open');
    var content = parent.find('> .b2b-req__item-step__content') || parent.find('.b2b-req__item-step__content');
    parent.toggleClass('_open');
    content.slideToggle(300);
  });
  $(document).on('click', '.js-change-state', function (e) {
    e.preventDefault();

    if ($(this).hasClass('_active')) {
      var input = $(this).closest('.b2b-profile__item-form__elem').find('[type="password"]');
      $(this).removeClass('_active');
      input.attr('disabled', true);
    } else {
      $(this).addClass('_active');

      var _input = $(this).closest('.b2b-profile__item-form__elem').find('[type="password"]');

      _input.attr('disabled', false).val('');
    }
  });
  $(document).on('click', '.js-edit', function (e) {
    e.preventDefault();
    $(this).closest('.js-b2b-req__item').find('.js-editable').addClass('_edit');
    $(this).closest('.js-b2b-req__item').find('.js-save').removeClass('_hide');
    $(this).addClass('_hide');
  });
  $(document).on('click', '.js-save', function (e) {
    e.preventDefault();
    $(this).closest('.js-b2b-req__item').find('.js-editable').removeClass('_edit');
    $(this).closest('.js-b2b-req__item').find('.js-edit').removeClass('_hide');
    $(this).addClass('_hide');
  });
  $(document).on('click', '.js-reset', function () {
    $(this).closest('.js-editable').find('input, textarea').val('');
  });
  $('body').on('click', '.ajax-form', function (e) {
    $.magnificPopup.close();
    e.preventDefault();
    var url = $(this).attr('data-href');
    var mainClass = $(this).attr('data-class');
    $.magnificPopup.open({
      type: 'ajax',
      items: {
        src: url
      },
      overflowY: 'scroll',
      mainClass: mainClass,
      closeOnBgClick: false,
      enableEscapeKey: false,
      callbacks: {
        ajaxContentAdded: function ajaxContentAdded() {
          if ($('.styler').length) $('.styler').styler();
        }
      }
    });
  });
  $(document).on('click', '.js-edit-table-popup', function (e) {
    $(this).closest('.js-wrp').find('.b2b-favorite__table').toggleClass('_edit');
    $(this).toggleClass('_active');
  });
  photoFunc();
});

var b2bTableEditor = function b2bTableEditor() {
  var checkboxes = $('.js-edit-wrp').find('.js-checkbox');
  var editor = $('.js-edit__editor');
  var editorWrp = $('.js-edit__editor-wrp');
  checkboxes.on('change', function () {
    var checked = checkboxes.find('input:checked');

    if (checked.length) {
      editor.addClass('_show');
      tableCoordinates();
      $(window).on('scroll', function () {
        tableCoordinates();
      });
    } else {
      editor.removeClass('_show');
      editorWrp.removeAttr('style');
    }
  });
  $(document).on('change', '.js-edit__editor .js-checkbox input', function () {
    if ($(this).prop('checked') === true) {
      checkboxes.find('input').prop('checked', true);
    } else {
      checkboxes.find('input').prop('checked', false);
    }
  });
  $(document).on('change', 'select.js-select', function () {
    var val = $(this).val();
    var table = $(this).closest('.b2b-content__wrp').find('.b2b-table__wrp');
    val === 'edit' ? table.addClass('_edit') : table.removeClass('_edit');
  });
  $(document).on('change', '.js-control-checked .checkbox input', function () {
    var val = $(this).prop('checked');
    var tr = $(this).closest('tr');
    val ? tr.addClass('_checked') : tr.removeClass('_checked');
  });

  function tableCoordinates() {
    var editorHeight = $('.js-edit__editor').outerHeight(true);
    $('.js-edit__editor-wrp').css('height', "".concat(editorHeight, "px"));
    var wBot = $(window).scrollTop() + window.innerHeight;
    var editorCoord = $('.js-editor__coord');
    var maxScrollBottom = editorCoord.offset().top + editorCoord.outerHeight(true) + editorHeight;
    var tableParam = document.querySelector('.js-edit__editor-wrp').getBoundingClientRect();
    editor.css({
      'bottom': 0
    });

    if (wBot < maxScrollBottom) {
      editor.css({
        position: 'fixed',
        left: tableParam.left,
        width: tableParam.width
      });
    } else {
      editor.css({
        position: 'absolute',
        left: 0,
        width: '100%'
      });
    }
  }
};

var photoFunc = function photoFunc() {
  var item = $('.js-input-photo');
  var itemImg = item.find('.js-image');
  var itemAdd = item.find('.js-add input');
  var itemSize = itemAdd.attr('data-size');
  var itemInfo = item.find('.js-info');

  var readURL = function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var size = input.files[0].size / 1024;

      if (size <= itemSize) {
        reader.onload = function (e) {
          itemImg.addClass('_added');
          itemImg.css('background-image', 'url(' + e.target.result + ')');
        };

        reader.readAsDataURL(input.files[0]);
        itemInfo.removeClass('_error');
      } else {
        input.value = '';
        itemInfo.addClass('_error');
      }
    }
  };

  var removeURL = function removeURL(input) {
    input.val();
    itemImg.removeAttr('style').removeClass('_added');
    itemInfo.removeClass('_error');
  };

  itemAdd.on('change', function () {
    readURL(this);
  });
  $('body').on('click', '.js-input-photo .js-remove', function (e) {
    e.preventDefault();
    removeURL(itemAdd);
  });
};