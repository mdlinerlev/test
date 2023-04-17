<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);
pr($_SERVER["DOCUMENT_ROOT"]);

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

if($_REQUEST['del'] ==  'Y') {
    \Bitrix\Main\Loader::includeModule('iblock');

    $IBLOCKS_ID = [2, 12];
    $CLEAR_PROPERTY = 'TWO_LEAF_PHOTO';

    foreach ($IBLOCKS_ID as $IBLOCK_ID) {
        $res = CIBlockElement::GetList(
            ['ID' => 'ASC'],
            ['IBLOCK_ID' => $IBLOCK_ID, '!PROPERTY_'.$CLEAR_PROPERTY => false],
            false,
            [],
            ['ID']
        );

        while ($row = $res->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($row['ID'], $IBLOCK_ID, array($CLEAR_PROPERTY => Array ("VALUE" => array("del" => "Y"))));
            pr($row['ID']);
        }
    }
}
