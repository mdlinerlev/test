<?php

use Bitrix\Main\UserTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class TestComponent extends \CBitrixComponent
{
    private $filter = [];

    public function __construct($component = \null)
    {
        parent::__construct($component);
    }

    public function onPrepareComponentParams($params)
    {
        if (empty($params['CACHE_TIME'])) {
            $params['CACHE_TIME'] = 3600000;
        }
        if (empty($params['FILTER_NAME'])) {
            $params['FILTER_NAME'] = 'arrFilter';
        }

        return $params;
    }

    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        global ${$this->arParams['FILTER_NAME']};
        $itemIds = self::getItemIds();

        if (!empty(${$this->arParams['FILTER_NAME']})) {
            $this->filter = ${$this->arParams['FILTER_NAME']};
        }

        $this->arResult['SECTIONS'] = self::getSections($itemIds);
        $this->arResult['ITEMS'] = self::getItems($itemIds);

        $this->includeComponentTemplate();
    }

    /**
     * @param $itemIds
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getItems($itemIds)
    {
        global $APPLICATION;
        $result = [];
        $prodIds = [];


        $nav = new \Bitrix\Main\UI\PageNavigation('favorites');
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['PAGEN_NUM'])
            ->initFromUri();

        $query = [
            'order' => ['sort' => 'desc'],
            'select' => [
                'ID',
                'NAME',
                'CODE',
                'IBLOCK_ID',
                'IBLOCK_SECTION_ID',
                'PREVIEW_PICTURE',
                'DETAIL_PICTURE',
                'QUANTITY' => 'PRODUCT.QUANTITY',
                'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL'
            ],
            'filter' => [
                'ID' => $itemIds,
            ],
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'PRODUCT',
                    \Bitrix\Catalog\ProductTable::class, \Bitrix\Main\Entity\Query\Join::on('this.ID', 'ref.ID')
                ),
            ],
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
            'cache' => ['ttl' => $this->arParams['CACHE_TIME']],
        ];

        if (!empty($this->filter)) {
            $query['filter'] = array_merge($query['filter'], $this->filter);
        }

        $iterator = \Bitrix\Iblock\ElementTable::getList($query);
        $nav->setRecordCount($iterator->getCount());

        while ($item = $iterator->fetch()) {
            $item['DETAIL_PAGE_URL'] = CIBlock::ReplaceDetailUrl($item['DETAIL_PAGE_URL'], $item, false, 'E');
            if ($item['PREVIEW_PICTURE']) {
                $item['PREVIEW_PICTURE'] = CFile::GetPath($item['PREVIEW_PICTURE']);
            }
            $result[$item['ID']] = $item;
            $prodIds[] = $item['ID'];
        }

        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation",
            $this->arParams['PAGEN_TEMPLATE'],
            array(
                "NAV_OBJECT" => $nav,
                "SEF_MODE" => "N",
            ),
            false
        );
        $this->arResult['NAV_STRING'] = ob_get_clean();

        $userPrice = getUserPrice();
        $arUserPrices = [
            $userPrice, PRICE_TYPE_DEFAULT_ID
        ];
        $this->arResult['PRICES']['DEFAULT'] = PRICE_TYPE_DEFAULT_ID;
        if ($userPrice != PRICE_TYPE_DEFAULT_ID) {
            $this->arResult['PRICES']['OPT'] = $userPrice;
        }

        $iterator = \Bitrix\Catalog\PriceTable::getList([
            'select' => ['PRICE', 'CATALOG_GROUP_ID', 'PRODUCT_ID'],
            'filter' => ['PRODUCT_ID' => $prodIds, 'CATALOG_GROUP_ID' => $arUserPrices],
            'cache' => ['ttl' => $this->arParams['CACHE_TIME']]
        ]);
        while ($price = $iterator->fetch()) {
            $result[$price['PRODUCT_ID']]['PRICE'][$price['CATALOG_GROUP_ID']] = $price['PRICE'];
        }

        return $result;
    }

    /**
     * @param $itemIds
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getSections(&$itemIds)
    {
        $newItemIds = $itemIds;

        $offers = [];
        $entityOffers = \Bitrix\Iblock\Iblock::wakeUp($this->arParams['IBLOCK_OFFERS_ID'])->getEntityDataClass();
        $iterator = $entityOffers::getList([
            'select' => ['ID', 'PROPERTY_CML2_LINK_' => 'CML2_LINK'],
            'filter' => ['ID' => $itemIds, '!=PROPERTY_CML2_LINK_VALUE' => false],
            'cache' => ['ttl' => $this->arParams['CACHE_TIME']]
        ]);
        while ($item = $iterator->fetch()) {
            $newItemIds[$item['PROPERTY_CML2_LINK_VALUE']] = $item['PROPERTY_CML2_LINK_VALUE'];
            $offers[$item['PROPERTY_CML2_LINK_VALUE']][] = $item['ID'];
        }

        $query = [
            'select' => ['ID', 'SECTION_NAME' => 'SECTION.NAME', 'SECTION_ID' => 'SECTION.ID'],
            'filter' => ['ID' => $newItemIds, '!=IBLOCK_SECTION_ID' => false],
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'SECTION',
                    \Bitrix\Iblock\SectionTable::class, \Bitrix\Main\Entity\Query\Join::on('this.IBLOCK_SECTION_ID', 'ref.ID')
                ),
            ],
            'cache' => ['ttl' => $this->arParams['CACHE_TIME']]
        ];

        $sections = [];
        $iterator = \Bitrix\Iblock\ElementTable::getList($query);
        while ($section = $iterator->fetch()) {
            $sections[$section['SECTION_ID']] = [
                'ID' => $section['SECTION_ID'],
                'NAME' => $section['SECTION_NAME'],
            ];

            if ($this->filter['IBLOCK_SECTION_ID']) {
                if ($this->filter['IBLOCK_SECTION_ID'] != $section['SECTION_ID']) {
                    if (isset($offers[$section['ID']])) {
                        foreach ($offers[$section['ID']] as $offer) {
                            unset($itemIds[$offer]);
                        }
                    } else {
                        unset($itemIds[$section['ID']]);
                    }
                }
            }

        }


        if ($this->filter['IBLOCK_SECTION_ID']) {
            unset($this->filter['IBLOCK_SECTION_ID']);
        }

        return $sections;
    }

    /**
     * @return array|mixed
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getItemIds()
    {
        $userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();
        $iterator = \Bitrix\Main\UserTable::getByPrimary($userId, [
            'select' => ['ID', 'NAME', 'UF_FAVORITES'],
            'filter' => ['!=UF_FAVORITES' => false],
        ]);
        if ($user = $iterator->fetch()) {
            $itemsId = unserialize($user['UF_FAVORITES']);
        } else {
            $itemsId = [0];
        }

        return $itemsId;
    }
}
