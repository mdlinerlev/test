<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
\Bitrix\Main\Loader::includeModule('catalog');

$IBLOCK_ID = 2;
$IBLOCK_ID_OFFERS = 12;

print_r('<pre>');
$arSelect = Array("ID", "NAME","PREVIEW_TEXT", "DETAIL_TEXT");
$arFilter = Array("IBLOCK_ID" => $IBLOCK_ID_OFFERS);
$rsOffers = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
$t = 0 ;
while ($arOffer = $rsOffers->GetNext()) {
     // тут ведем обработку }


    $IdGood = CCatalogSku::GetProductInfo(
        $arOffer['ID'],
        $IBLOCK_ID_OFFERS
    );


    $resGood = CIBlockElement::GetByID($IdGood['ID']);
    if($ar_resGood = $resGood->GetNext());

    if (!empty($ar_resGood['PREVIEW_TEXT'])){
        //if ($t>1) break;
        print_r($arOffer['ID']);
        //print_r($arOffer['PREVIEW_TEXT']);
        print_r($ar_resGood['ID']);
        print_r('<br>');
        print_r('preview');


        // создаем объект класса для работы
        $obElement = new CIBlockElement();
        // обновляем элемент
        $status = $obElement->Update($arOffer['ID'], Array("PREVIEW_TEXT"=>strip_tags($ar_resGood['PREVIEW_TEXT'])));

        print_r($status);
        print_r('-----<br>');
        $t++;
    }elseif(!empty($ar_resGood['DETAIL_TEXT'])){
        // создаем объект класса для работы
        $obElement = new CIBlockElement();
        // обновляем элемент
        $status1 = $obElement->Update($arOffer['ID'], Array("PREVIEW_TEXT"=> strip_tags($ar_resGood['DETAIL_TEXT']),"DETAIL_TEXT"=> ''));
        $t++;
        print_r($arOffer['ID']);
        print_r('-');
        print_r($ar_resGood['ID']);

        print_r('<br>');
        print_r('detail');
        print_r($status1);
        print_r('-----<br>');
    }



}
//print_r($t);
//print_r('</pre>');
print_r('Конец');


?>


