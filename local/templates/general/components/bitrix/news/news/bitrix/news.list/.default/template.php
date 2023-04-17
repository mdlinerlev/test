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
<?
$arrMonth =
    [
        '01' => 'янв',
        '02' => 'фев',
        '03' => 'мар',
        '04' => 'арп',
        '05' => 'май',
        '06' => 'июн',
        '07' => 'июл',
        '08' => 'авг',
        '09' => 'сен',
        '10' => 'окт',
        '11' => 'ноя',
        '12' => 'дек',
    ];
?>
<div class="news__list">
    <?php
if (array_key_exists('is_ajax', $_REQUEST) && $_REQUEST['is_ajax']=='y') {
    $APPLICATION->RestartBuffer();
}
?>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
        <article class="news__item">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__inner">
                  <div style="background-image:url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>)" class="news__image">
                  </div>
                  <div class="news__date">
                      <?list($day,$month) = explode(".", $arItem["TIMESTAMP_X"]);?>
                      <span class="news__date-number"><?= $day?>
                    </span><?= $arrMonth[$month]?>
                  </div>
                  <div class="news__text-container">
                    <div class="news__text"><?echo $arItem["NAME"]?>
                    </div>
                  </div>
                </a>
              </article>
<?endforeach;?>
       
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
     
        <?php

if (array_key_exists('is_ajax', $_REQUEST) && $_REQUEST['is_ajax']=='y') {
    die();
}
?>

</div>
