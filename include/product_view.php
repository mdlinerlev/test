<script>
  gtag('event', 'product-page', {
    'send_to': 'AW-609100904',
    'value': '<?php echo $arResult['MIN_PRICE']['VALUE']?>',
    'items': [{
      'id': '<?php echo $arResult['ID']; ?>',
      'google_business_vertical': 'retail'
    }]
  });
</script>
<script>
dataLayer.push({
    "ecommerce": {
	"currencyCode": "RUB",
        "detail": {
            "products": [
                {
                    "id": "<?php echo $arResult['ID']; ?>",
					"name" : "<?php echo $arResult['NAME']; ?>",
                    "price": "<?php echo $arResult['MIN_PRICE']['VALUE']?>",
                    "category": "<?php echo $arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE_ENUM_ID']?>"
                },
            ]
        }
    }
});
</script>
<!-- Rating@Mail.ru counter dynamic remarketing appendix -->
<script type="text/javascript">
var _tmr = _tmr || [];
_tmr.push({
   type: 'itemView',
   productid: '<?php echo $arResult['ID']; ?>',
   pagetype: 'product',
   list: '2',
   totalvalue: '<?php echo $arResult['MIN_PRICE']['VALUE']?>'
});
</script>
<!-- // Rating@Mail.ru counter dynamic remarketing appendix -->