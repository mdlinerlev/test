<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
global $APPLICATION;
#pRU($arResult,'all');
/*if(preg_match('#^/catalog/[a-z0-9_-]+/[a-z0-9_-]+/(.*)?$#ui', $APPLICATION->GetCurPage())){
	#$arResult[] = array('TITLE' => $APPLICATION->sDocTitle);
}*/

/*$mainArr = array(
	'LINK' => '/',
	'TITLE' => 'Главная',
);
array_unshift($arResult,$mainArr);*/

$partUrl = explode("/", $APPLICATION->GetCurPage());

//delayed function must return a string
if(empty($arResult))
	return "";

$itemSize = count($arResult);
$itemSize_ = $itemSize - 1;



$strReturn = '<ul class="breadcrumbs__list" itemscope itemtype="http://schema.org/BreadcrumbList">';

for ( $index = 0; $index < $itemSize; $index++ ){
	if (($arResult[$index]["LINK"] == '/personal/' && ($partUrl[1] == 'personal' && $partUrl[2] == 'cart')) || $arResult[$index]["LINK"] == '/catalog/') continue;// Пропустим "Заказы" из навигации пункт, в корзине
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if($itemSize_ == $index && strlen($arResult[$index]["LINK"])){
		$strReturn .= '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a itemprop="item" class="breadcrumbs__title" href="'.$arResult[$index]["LINK"].'" title="'.$title.'"><span  itemprop="name">'.$title.'</span></a><meta itemprop="position" content="'.($index+1).'" /></li>';
	}
	elseif($arResult[$index]["LINK"] <> ""){
		$strReturn .= '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a itemprop="item" class="breadcrumbs__link" href="'.$arResult[$index]["LINK"].'" title="'.$title.'"><span  itemprop="name">'.$title.'</span></a><meta itemprop="position" content="'.($index+1).'" /></li>';
	}
	else{
//		$strReturn .= '<span>'.$title.'</span>';
	}
}

$strReturn .= '</ul>';
return $strReturn;
?>