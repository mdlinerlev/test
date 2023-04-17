<?php
/**
 * User: sasha
 * Date: 26.07.18
 * Time: 16:25
 */
namespace Newsite\Sale;
use \Bitrix\Main\Localization\Loc;
class Basket extends \Bitrix\Sale\Basket
{

    public $items = [];

    public function getTotalQuantity()
    {
        $productQuantity = $this->getQuantityList();
        return (int)array_sum($productQuantity);
    }

    public function getProductIds()
    {
        $productIds = [];

        foreach ($this->getBasketItems() as $basketItem) {
            /**
             * @var $basketItem \Newsite\Sale\BasketItem
             */
            $productIds[ $basketItem->getProductId() ] = $basketItem->getProductId();
        }

        return $productIds;
    }
    public function getProductInfo()
    {
        return $this->items;
    }

    /** @return \Bitrix\Sale\BasketItem|null */
    public function getItemByProductId($productId)
    {
        $item = null;
        foreach ($this->getBasketItems() as $basketItem) {
            if ($basketItem->getProductId() == $productId) {
                $item = $basketItem;
                break;
            }
        }

        return $item;
    }

    public function getAdditionalProductsData( $updatePriceFromCatalog = true )
    {
        $productIds = $this->getProductIds();

        if (empty($productIds)) {
            return;
        }
        $arItems = [];
        $arIblockFilter = ['OFFERS' => 12,  'ITEMS' => 2];

        foreach ($arIblockFilter as $iblockID) {
            if(!empty($productIds)) {
                $arFilter = ['IBLOCK_ID' => $iblockID, '=ID' => $productIds];
                $arSelect = ['ID', 'IBLOCK_ID', 'CATALOG_QUANTITY', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_*'];
                $res = \CIBlockElement::GetList([], $arFilter,false, false, $arSelect);
                while ($ob = $res->GetNextElement()) {
                    $arData = $ob->GetFields();
                    $arData['PROPERTIES'] = $ob->GetProperties();
                    $this->items[$arData['ID']] = $arData;

                    if (!empty($arData['PREVIEW_PICTURE']))
                        $this->arResult['IMAGES'][] = $arData['PREVIEW_PICTURE'];

                    if (!empty($arData['DETAIL_PICTURE']))
                        $this->arResult['IMAGES'][] = $arData['DETAIL_PICTURE'];

                    unset($productIds[$arData['ID']]);
                }
            }
        }


        foreach ($this->getBasketItems() as $basketItem) {
            $id = $basketItem->getProductId();
            $itemProductData =  $this->items[$id];
            try {

                if(!empty($itemProductData)){
                    $img = $itemProductData['PREVIEW_PICTURE'] ? : $itemProductData['DETAIL_PICTURE'];
                    $basketItem->setField('DETAIL_PAGE_URL', $itemProductData['DETAIL_PAGE_URL']);
                    $basketItem->setField('IMAGE', $img ? : false);
                    $basketItem->setField('ARTICLE', $itemProductData['PROPERTIES']['ARTICLE']['VALUE']);
                } else {
                    $basketItem->delete();
                }

            } catch (\ArgumentOutOfRangeException $e) {}

        }

    }

}