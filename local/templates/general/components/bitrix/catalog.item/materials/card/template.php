<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

$fileIco  = 'ico-txt.svg';
$fileName = $filePath = '';
if(!empty($item['PROPERTIES']['FILE']['VALUE'])){
    $file = CFile::GetFileArray($item['PROPERTIES']['FILE']['VALUE']);
    $filePath = $file['SRC'];
    $fileName = $file['ORIGINAL_NAME'];

    $finfo =  pathinfo($filePath);
    switch ($finfo['extension']){
        case 'xlsx':
        case 'xls':
            $fileIco = 'ico-xls.svg';
            break;
        case 'doc':
        case 'docx':
            $fileIco = 'ico-doc.svg';
            break;
        case 'pdf':
            $fileIco = 'ico-pdf.svg';
            break;
        case 'jpg':
        case 'jpeg':
            $fileIco = 'ico-jpg.svg';
            break;
        case 'png':
            $fileIco = 'ico-png.svg';
            break;
    }
}
?>
<a class="b2b-material__list-item" href="<?=$filePath?>" data-name="<?=$fileName?>" download="<?=$fileName?>">
    <img class="type-img" src="<?=SITE_TEMPLATE_PATH?>/img/<?=$fileIco?>">
    <span><?=$item['NAME']?></span>
    <div class="ico-download">
        <svg class="icon icon-download ">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/svg/symbol/sprite.svg#download"></use>
        </svg>
    </div>
</a>