<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>


<main class="lp">
        <?=$arResult['PREVIEW_TEXT'];?>
        <?=$arResult['DETAIL_TEXT'];?>
</main>


<?php
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/lib/lazyload.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/ieRedirect.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/utils.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/const.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/lib/swiper-bundle.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/lib/inputmask.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/lib/aos.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/slider-carousel.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/lp.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/fv.js");
$this->addExternalJs(SITE_TEMPLATE_PATH."/assets/js/scripts_optom.js");
?>

