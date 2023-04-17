(function () {
    'use strict';

    if (!!window.JCCatalogSectionComponent)
        return;

    window.JCCatalogSectionComponent = function (params) {
        this.formPosting = false;
        this.siteId = params.siteId || '';
        this.ajaxId = params.ajaxId || '';
        this.template = params.template || '';
        this.componentPath = params.componentPath || '';
        this.parameters = params.parameters || '';

        if (params.navParams) {
            this.navParams = {
                NavNum: params.navParams.NavNum || 1,
                NavPageNomer: parseInt(params.navParams.NavPageNomer) || 1,
                NavPageCount: parseInt(params.navParams.NavPageCount) || 1
            };
        }

        this.bigData = params.bigData || {enabled: false};
        this.container = document.querySelector('[data-entity="' + params.container + '"]');

        this.showMoreButton = null;
        this.showMoreButtonMessage = null;

        if (this.bigData.enabled && BX.util.object_keys(this.bigData.rows).length > 0) {
            BX.cookie_prefix = this.bigData.js.cookiePrefix || '';
            BX.cookie_domain = this.bigData.js.cookieDomain || '';
            BX.current_server_time = this.bigData.js.serverTime;

            BX.ready(BX.delegate(this.bigDataLoad, this));
        }

        if (params.initiallyShowHeader) {
            BX.ready(BX.delegate(this.showHeader, this));
        }

        if (params.deferredLoad) {
            BX.ready(BX.delegate(this.deferredLoad, this));
        }

        if (params.lazyLoad) {
            this.showMoreButton = document.querySelector('[data-use="show-more-' + this.navParams.NavNum + '"]');
            this.showMoreButtonMessage = this.showMoreButton.innerHTML;
            BX.bind(this.showMoreButton, 'click', BX.proxy(this.showMore, this));
        }

        if (params.loadOnScroll) {
            BX.bind(window, 'scroll', BX.proxy(this.loadOnScroll, this));
        }
    };

    window.JCCatalogSectionComponent.prototype =
        {
            checkButton: function () {
                if (this.showMoreButton) {
                    if (this.navParams.NavPageNomer == this.navParams.NavPageCount) {
                        BX.remove(this.showMoreButton);
                    } else {
                        //this.container.appendChild(this.showMoreButton);
                    }
                }
            },

            enableButton: function () {
                if (this.showMoreButton) {
                    BX.removeClass(this.showMoreButton, 'disabled');
                    this.showMoreButton.innerHTML = this.showMoreButtonMessage;
                }
            },

            disableButton: function () {
                if (this.showMoreButton) {
                    BX.addClass(this.showMoreButton, 'disabled');
                    this.showMoreButton.innerHTML = BX.message('BTN_MESSAGE_LAZY_LOAD_WAITER');
                }
            },

            loadOnScroll: function () {
                var scrollTop = BX.GetWindowScrollPos().scrollTop,
                    containerBottom = BX.pos(this.container).bottom;

                if (scrollTop + window.innerHeight > containerBottom) {
                    this.showMore();
                }
            },

            showMore: function () {
                if (this.navParams.NavPageNomer < this.navParams.NavPageCount) {
                    var data = {};
                    data['action'] = 'showMore';
                    data['PAGEN_' + this.navParams.NavNum] = this.navParams.NavPageNomer + 1;

                    if (!this.formPosting) {
                        this.formPosting = true;
                        this.disableButton();
                        this.sendRequest(data);
                    }
                }
            },

            bigDataLoad: function () {
                var url = 'https://analytics.bitrix.info/crecoms/v1_0/recoms.php',
                    data = BX.ajax.prepareData(this.bigData.params);

                if (data) {
                    url += (url.indexOf('?') !== -1 ? '&' : '?') + data;
                }

                var onReady = BX.delegate(function (result) {
                    this.sendRequest({
                        action: 'deferredLoad',
                        bigData: 'Y',
                        items: result && result.items || [],
                        rid: result && result.id,
                        count: this.bigData.count,
                        rowsRange: this.bigData.rowsRange,
                        shownIds: this.bigData.shownIds
                    });
                }, this);

                BX.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: url,
                    timeout: 3,
                    onsuccess: onReady,
                    onfailure: onReady
                });
            },

            deferredLoad: function () {
                this.sendRequest({action: 'deferredLoad'});
            },

            sendRequest: function (data) {
                var defaultData = {
                    siteId: this.siteId,
                    template: this.template,
                    parameters: this.parameters
                };

                if (this.ajaxId) {
                    defaultData.AJAX_ID = this.ajaxId;
                }

                BX.ajax({
                    url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
                    method: 'POST',
                    dataType: 'json',
                    timeout: 60,
                    data: BX.merge(defaultData, data),
                    onsuccess: BX.delegate(function (result) {
                        if (!result || !result.JS)
                            return;

                        BX.ajax.processScripts(
                            BX.processHTML(result.JS).SCRIPT,
                            false,
                            BX.delegate(function () {
                                this.showAction(result, data);
                                $('.styler').styler();
                                b2bTableEditor();
                            }, this)
                        );
                    }, this)
                });
            },

            showAction: function (result, data) {
                if (!data)
                    return;

                switch (data.action) {
                    case 'showMore':
                        this.processShowMoreAction(result);
                        break;
                    case 'deferredLoad':
                        this.processDeferredLoadAction(result, data.bigData === 'Y');
                        break;
                }
            },

            processShowMoreAction: function (result) {
                this.formPosting = false;
                this.enableButton();

                if (result) {
                    this.navParams.NavPageNomer++;
                    this.processItems(result.items);
                    this.processPagination(result.pagination);
                    this.processEpilogue(result.epilogue);
                    this.checkButton();
                }
            },

            processDeferredLoadAction: function (result, bigData) {
                if (!result)
                    return;

                var position = bigData ? this.bigData.rows : {};

                this.processItems(result.items, BX.util.array_keys(position));
            },

            processItems: function (itemsHtml, position) {
                if (!itemsHtml)
                    return;

                var processed = BX.processHTML(itemsHtml, false),
                    temporaryNode = BX.create('DIV');

                var items, k, origRows;

                temporaryNode.innerHTML = processed.HTML;
                items = temporaryNode.querySelectorAll('[data-entity="items-row"]');

                if (items.length) {
                    this.showHeader(true);

                    for (k in items) {
                        if (items.hasOwnProperty(k)) {
                            origRows = position ? this.container.querySelectorAll('[data-entity="items-row"]') : false;
                            items[k].style.opacity = 0;

                            if (origRows && BX.type.isDomNode(origRows[position[k]])) {
                                origRows[position[k]].parentNode.insertBefore(items[k], origRows[position[k]]);
                            } else {
                                this.container.appendChild(items[k]);
                            }
                        }
                    }

                    new BX.easing({
                        duration: 2000,
                        start: {opacity: 0},
                        finish: {opacity: 100},
                        transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
                        step: function (state) {
                            for (var k in items) {
                                if (items.hasOwnProperty(k)) {
                                    items[k].style.opacity = state.opacity / 100;
                                }
                            }
                        },
                        complete: function () {
                            for (var k in items) {
                                if (items.hasOwnProperty(k)) {
                                    items[k].removeAttribute('style');
                                }
                            }
                        }
                    }).animate();
                }

                BX.ajax.processScripts(processed.SCRIPT);
            },

            processPagination: function (paginationHtml) {
                if (!paginationHtml)
                    return;

                var pagination = document.querySelectorAll('[data-pagination-num="' + this.navParams.NavNum + '"]');
                for (var k in pagination) {
                    if (pagination.hasOwnProperty(k)) {
                        pagination[k].innerHTML = paginationHtml;
                    }
                }
            },

            processEpilogue: function (epilogueHtml) {
                if (!epilogueHtml)
                    return;

                var processed = BX.processHTML(epilogueHtml, false);
                BX.ajax.processScripts(processed.SCRIPT);
            },

            showHeader: function (animate) {
                var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}}),
                    header;

                if (parentNode && BX.type.isDomNode(parentNode)) {
                    header = parentNode.querySelector('[data-entity="header"]');

                    if (header && header.getAttribute('data-showed') != 'true') {
                        header.style.display = '';

                        if (animate) {
                            new BX.easing({
                                duration: 2000,
                                start: {opacity: 0},
                                finish: {opacity: 100},
                                transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
                                step: function (state) {
                                    header.style.opacity = state.opacity / 100;
                                },
                                complete: function () {
                                    header.removeAttribute('style');
                                    header.setAttribute('data-showed', 'true');
                                }
                            }).animate();
                        } else {
                            header.style.opacity = 100;
                        }
                    }
                }
            }
        };
})();

$(document).ready(function () {
    /*function openLinkList(linkList) {
        for (var i = 0; i < linkList.length; i++) {
            var a = document.createElement("a");
            a.href = linkList[i];
            a.target = "_blank"
            a.click();
        }
    };

    var isWork = false;

    $(document).on('submit', 'form.js-edit__editor', function (e) {
        e.preventDefault();
        if (isWork === false) {
            isWork = true;

            var data = {
                action: 'comOffersHandler',
                type: '',
                itemsId: [],
                items: {},
                all: false
            };

            $('.js-b2b-table .js-checkbox input:checked').each(function () {
                var itemContainer = $(this).parents('tr'),
                    item = {},
                    itemId = $(this).attr('data-id');

                data.itemsId.push(itemId);
                itemContainer.find('input,select').each(function () {
                    var code = $(this).attr('name'),
                        val = $(this).val();
                    item[code] = val;
                });
                item['ID'] = itemId;
                data.items[itemId] = item;
            });
            data.all = $(this).find('#all').get(0).checked;
            data.type = $(this).find('select.js-select').val();

            if (data.type.length) {
                BX.showWait('com-offers-search');
                $.ajax({
                    type: 'POST',
                    url: '/ajax/reAjax.php',
                    data: data,
                    dataType: 'json',
                    success: (result) => {
                        if (result.returnUrl !== undefined && result.returnUrl.length) {
                            openLinkList(result.returnUrl);
                        } else {
                            var title = result.title,
                                message = result.success_message;
                            if (result.errorMsg) {
                                message = result.errorMsg;
                            }

                            var messageText =
                                '<div class="popup-b2b">' +
                                '<div class="popup-b2b__wrp">' +
                                '<div class="popup-b2b__head">' +
                                '<div class="popup-b2b__zag">' + title + '</div>' +
                                '</div>' +
                                '<div class="popup-b2b__form">' + message + '</div>' +
                                '</div>' +
                                '</div>';
                            $.magnificPopup.open({
                                type: 'inline',
                                items: {
                                    src: messageText
                                },
                                overflowY: 'scroll',
                                mainClass: 'w440',
                                closeOnBgClick: false,
                                enableEscapeKey: false,
                                callbacks: {
                                    close: function () {
                                        if (result.needReload) {
                                            location.reload();
                                        }
                                    }
                                }
                            });
                        }

                        BX.closeWait('com-offers-search');
                        isWork = false;
                    }
                });
            } else {
                isWork = false;
            }
        }
    });
    $(document).on('click', '.js-edit-table-popup._active', function (e) {
        var formCont = $('.popup-b2b__detail'),
            id = formCont.attr('data-id'),
            items = {}, item = {};

        formCont.find('input').each(function () {
            item[$(this).attr('name')] = $(this).val();
        });
        items[id] = item;

        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: {
                action: 'comOffersHandler',
                items: items,
                itemsId: [id],
                type: 'edit',
            },
            dataType: 'json',
            success: (result) => {
                if (!result.success) {

                    $('.mfp-close').click();

                    var title = result.title,
                        message = result.success_message;
                    if (result.errorMsg) {
                        message = result.errorMsg;
                    }

                    var messageText =
                        '<div class="popup-b2b">' +
                        '<div class="popup-b2b__wrp">' +
                        '<div class="popup-b2b__head">' +
                        '<div class="popup-b2b__zag">' + title + '</div>' +
                        '</div>' +
                        '<div class="popup-b2b__form">' + message + '</div>' +
                        '</div>' +
                        '</div>';
                    $.magnificPopup.open({
                        type: 'inline',
                        items: {
                            src: messageText
                        },
                        overflowY: 'scroll',
                        mainClass: 'w440',
                        closeOnBgClick: false,
                        enableEscapeKey: false,
                        callbacks: {
                            close: function () {
                                if (result.needReload) {
                                    location.reload();
                                }
                            }
                        }
                    });
                } else {
                    $('.js-offer-item[data-id="' + id + '"]').click();
                }
            }
        });
    });
    $(document).on('click', '.js-basketItem-del', function () {
        var delId = $(this).attr('data-id'),
            id = $('#ComOfferId').val();
        $.ajax({
            type: 'POST',
            url: '/ajax/reAjax.php',
            data: {
                action: 'comOffersHandler',
                itemsId: id,
                delId: delId,
                type: 'del_basket-item',
            },
            success: (result) => {
                $('.js-offer-item[data-id="' + id + '"]').click();
            }
        });
    });
    $(document).on('change', '.js-sort', function () {
        if ($(this).attr('data-href').length) {
            location.href = $(this).attr('data-href');
        }
    });*/

    $(document).on('click', '.plus', function () {
        var input = $(this).siblings('input'),
            val = Number(input.val());
        input.val(++val);
    });
    $(document).on('click', '.minus', function () {
        var input = $(this).siblings('input'),
            val = Number(input.val());
        if (val <= 1) {
            val = 2;
        }
        input.val(--val);
    });
    $(document).on('input', '.count', function () {
        var val = Number($(this).val());
        if (isNaN(val) || val < 1) {
            val = 1;
        }
        $(this).val(val);
    });
});