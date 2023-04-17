<?

namespace Ml\Soap\Wsdl\Action;

use Bitrix\Iblock\ElementTable;

class Product
{
    private static $error = '';

    public static function Update($request)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/local/logs/products_1c.log', print_r(['time' => date('d.m.Y'), 'data' => $request],1), FILE_APPEND);
        $arProductIDs = $items = [];
        if (isset($request['products_list']['product'][0])) {
            foreach ($request['products_list']['product'] as $arItem) {
                $items[$arItem['guid']] = intval($arItem['count']);
                $arProductIDs[] = $arItem['guid'];
            }
        } else {
            $items[$request['products_list']['product']['guid']] = intval($request['products_list']['product']['count']);
            $arProductIDs[] = $request['products_list']['product']['guid'];
        }

        if(!empty($arProductIDs)){
            $iterator = ElementTable::getList([
                'select' => ['ID', 'XML_ID'],
                'filter' => ['XML_ID' => $arProductIDs]
            ]);
            while ($arItem = $iterator->fetch()){
                if(isset($items[$arItem['XML_ID']])){

                    $arFields = [
                        "ID" => $arItem['ID'],
                        "QUANTITY" => ($items[$arItem['XML_ID']]) ? intval($items[$arItem['XML_ID']]) : 0
                    ];

                    $existProduct = \Bitrix\Catalog\Model\Product::getCacheItem($arItem['ID'], true);
                    if (!empty($existProduct)) {
                        \Bitrix\Catalog\Model\Product::update($arItem['ID'], $arFields);
                    } else {
                        \Bitrix\Catalog\Model\Product::add($arFields);
                    }
                }
            }
        }

        return [
            'errorMsg' => self::$error
        ];
    }
}
