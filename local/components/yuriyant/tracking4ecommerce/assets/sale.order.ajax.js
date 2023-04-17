/**
 * yuriyant.tracking4ecommerce
 *
 * Компонент для работы с модулем регистрации данных электронной коммерции 
 * в Google Analitycs Ecommerce
 * 
 * Информация о плагине
 *
 * 
 *
 * @copyright 2015, Yuriyant, http://yuriyant.com/
 * @author Антохин Юрий <support@yuriyant.com>
 *
 * @package yuriyant.tracking4ecommerce
 *
 */

if (typeof BX !== 'undefined') {

    BX.ready(function () {

        if (typeof BX.Sale === 'undefined' || typeof BX.Sale.OrderAjaxComponent === 'undefined') {
            return;
        }

        //Перегружаемый метод sale.order.ajax
        var editActiveDeliveryBlock = BX.Sale.OrderAjaxComponent.editActiveDeliveryBlock;
        var editActivePaySystemBlock = BX.Sale.OrderAjaxComponent.editActivePaySystemBlock;
        BX.namespace('BX.Sale.OrderAjaxGoogleAnalyticsTracking');


        BX.Sale.OrderAjaxGoogleAnalyticsTracking = BX.Sale.OrderAjaxComponent;

        /**
         * 
         * @param {type} action
         * @returns {undefined}
         */
        var request = function (action, params) {
            var data = {
                site: 's1',
                action: action,
                deliveryToPaysystem: "d2p"
            }
            try {
                var options = {
                    sessid: BX.bitrix_sessid(),
                    site: BX.message('SITE_ID'),
                    deliveryToPaysystem: BX.Sale.OrderAjaxComponent.params.DELIVERY_TO_PAYSYSTEM
                }
                data = Object.assign({}, data, options, params);
            } catch (e) {
                console.log(e);
            }

            BX.ajax({
                url: '/bitrix/components/yuriyant/tracking4ecommerce/api/sale.order.ajax.php',
                data: data,
                method: 'POST',
                dataType: 'json',
                timeout: 10,
                async: true,
                processData: false,
                scriptsRunFirst: false,
                emulateOnload: false,
                start: true,
                cache: false,
            });
        }


        /**
         * Этап выбора службы доставки
         * @param {type} paySystemNode
         * @returns {undefined}
         */

        BX.Sale.OrderAjaxGoogleAnalyticsTracking.editActiveDeliveryBlock = function (active) {
            editActiveDeliveryBlock.apply(this, arguments);
            if (active) {
                var deliveryInfo = {}
                try {
                    deliveryInfo = BX.Sale.OrderAjaxComponent.getSelectedDelivery();
                } catch (e) {
                    console.log(e);
                }
                if (!deliveryInfo) {
                    deliveryInfo = {ID: false}
                }
                request('delivery', {deliveryID: deliveryInfo.ID});
            }
        }


        /**
         * Этап выбора платежной системы
         * @param {type}  paySystemNode
         * @returns {undefined}
         */
        BX.Sale.OrderAjaxGoogleAnalyticsTracking.editActivePaySystemBlock = function (active) {
            editActivePaySystemBlock.apply(this, arguments);
            if (active) {
                var paysystenInfo = {}
                try {
                    paysystenInfo = BX.Sale.OrderAjaxComponent.getSelectedPaySystem();
                } catch (e) {
                    console.log(e);
                }
                if (!paysystenInfo) {
                    paysystenInfo = {ID: false}
                }
                request('payment', {paysystemID: paysystenInfo.ID});
            }
        };
    });
}
