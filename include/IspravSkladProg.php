<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обновление складской программы");

//global $DB;
/*
 $conn = new mysqli("192.168.10.10", "bitrix0", "pass", "dbbelwooddoorsru");
 if ($conn->connect_error) {
   die("Ошибка: не удается подключиться: " . $conn->connect_error);
 } 
*/ 
 $result = $DB->query("SELECT cat.id as ID FROM `b_catalog_product` as cat where cat.TYPE=4 and cat.QUANTITY=1000 and cat.ID not in 
   (select prop.IBLOCK_ELEMENT_ID from `b_iblock_element_property` as prop WHERE prop.IBLOCK_PROPERTY_ID=116)");
 $arr = array();
 while($res = $result->Fetch()){
	//  foreach ($result as $res) {
	   $arr[] = $res;
       echo "id: ".$res['ID']."</br>";
	   $que="insert into b_iblock_element_property values (0,116,".$res['ID'].",'Y','text',null,0,'')";
	   //echo "que: $que</br>";
       $res1 = $DB->query($que);
	   //break;
   }
 echo "Добавлено строк со складской программой: ".count($arr)."</br>";

 $result = $DB->query("SELECT cat.id as ID FROM `b_catalog_product` as cat where cat.TYPE=4 and cat.QUANTITY=0 and cat.ID not in 
   (select prop.IBLOCK_ELEMENT_ID from `b_iblock_element_property` as prop WHERE prop.IBLOCK_PROPERTY_ID=116)");

 $arr = array();
 while($res = $result->Fetch()){
	   $arr[] = $res;
       echo "id: ".$res['ID']."</br>";
	   $que="insert into b_iblock_element_property values (0,116,".$res['ID'].",'N','text',null,0,'')";
       $res1 = $DB->query($que);
   }

 echo "Добавлено строк без складской программы: ".count($arr)."</br>";

 $result = $DB->query("SELECT cat.id as ID FROM `b_catalog_product` as cat inner join `b_iblock_element_property` as prop on 
        cat.ID=prop.IBLOCK_ELEMENT_ID WHERE cat.QUANTITY=1000 and prop.IBLOCK_PROPERTY_ID=116 and prop.VALUE='N'");

 $arr = array();
 while($res = $result->Fetch()){
	   $arr[] = $res;
       echo "id: ".$res['ID']."</br>";
	   $que="update `b_iblock_element_property` set VALUE='Y' where IBLOCK_PROPERTY_ID=116 and IBLOCK_ELEMENT_ID=".$res['ID'];
       $res1 = $DB->query($que);
 }

 echo "Изменено строк со складской программой: ".count($arr)."</br>";

 $result = $DB->query("SELECT cat.id as ID FROM `b_catalog_product` as cat inner join `b_iblock_element_property` as prop on 
        cat.ID=prop.IBLOCK_ELEMENT_ID WHERE cat.QUANTITY=0 and prop.IBLOCK_PROPERTY_ID=116 and prop.VALUE='Y'");

 $arr = array();
 while($res = $result->Fetch()){
	   $arr[] = $res;
       echo "id: ".$res['ID']."</br>";
	   $que="update `b_iblock_element_property` set VALUE='N' where IBLOCK_PROPERTY_ID=116 and IBLOCK_ELEMENT_ID=".$res['ID'];
       $res1 = $DB->query($que);
 }

 echo "Изменено строк без складской программы: ".count($arr)."</br>";
//$result->close();
//$conn->close();
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>

