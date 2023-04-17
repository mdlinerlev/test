<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<!-- Микроразметка Event -->
<script type="application/ld+json">
[
    {
        "@context":"http://schema.org",
        "@type":"Event",
        "name":"<?=$GLOBALS['promotionalHeadBanner']['MAIN_TEXT']?>",
        "description":"<?=$GLOBALS['promotionalHeadBanner']['MAIN_TEXT'].' '.$GLOBALS['promotionalHeadBanner']['ADDITIONAL_TEXT']?>",
        "url":"<?=$GLOBALS['promotionalHeadBanner']['LINK_FOR_PROMOTIONAL']?>",
        "endDate":"<?=$GLOBALS['promotionalHeadBanner']['DATE_BEFORE']?>",
        "location":{
            "@type":"Place",
            "name":"Межкомнатные двери",
            "address": {
                "@type" : "PostalAddress",
                "addressLocality" : "Москва",
                "streetAddress" : "Кутузовский проспект 36А",
                "postalCode" : "121170"
            }
        }
    }
]
</script>