<?
$result = ['success' => false, 'errorMsg' => ''];

if (\Bitrix\Main\Engine\CurrentUser::get()->getId()) {
    switch ($request['type']) {
        case 'update':
            if ($request['ID']) {
                $element = \Bitrix\Iblock\ElementTable::getByPrimary(intval($request['ID']), [
                    'select' => ['ID']
                ]);
                if ($arItem = $element->fetch()) {
                    $el = new CIBlockElement();
                    $arLoad = [
                        'NAME' => $request['NAME'],
                    ];
                    if ($isUpd = $el->Update($arItem['ID'], $arLoad)) {
                        $arProps =  $request['PROPERTIES'];

                        $arPropsHtml = [
                            'TEXT_TABLE_BEFORE', 'TEXT_TABLE_AFTER'
                        ];
                        foreach ($arPropsHtml as $arPropHtml){
                            if(isset($arProps[$arPropHtml])){
                                $arProps[$arPropHtml] = ['VALUE' => $arProps[$arPropHtml], 'type' => 'html'];
                            }
                        }

                        CIBlockElement::SetPropertyValuesEx($arItem['ID'], IBLOCK_ID_B2BPROFILE, $arProps);
                        $result['success'] = true;
                    } else {
                        $result['errorMsg'] = $el->LAST_ERROR;
                    }
                } else {
                    $result['errorMsg'] = 'Элемент не найден';
                }
            }
            break;
        case 'add':
            $el = new CIBlockElement();
            $arProps = $request['PROPERTIES'];

            $text_before = "Наша компания предлагает Вам ознакомится с предложением по следующим позициям:";
            $text_after = "Указанные цены и скидки действительны в течение 3 банковских дней.
Так же предлагаем Вам ознакомиться с остальными условиями нашего предложения: 
График оплаты: Предоплата 50% Гарантия на двери составляет 2 года с даты выпуска, при соблюдении необходимых условий эксплуатации и правильной установки.
Данная гарантия не распространяется на товар, конструкция которого была изменена покупателем в ходе эксплуатации.
Гарантийный  срок  на  фурнитуру,  входящую  в  комплект  товара,  составляет  6  месяцев  с  даты  передачи  товара,  и  в соответствии  со  сроками,  установленными  производителями  фурнитуры.  Руководство  по  транспортировке,  хранению, монтажу  и  эксплуатации  товара,  перечень  заводских  дефектов,  на  которые  распространяется  гарантия,  размещены  в сети Интернет на сайте: https://belwooddoors.com/garant
ОБРАТИТЕ  ВНИМАНИЕ!  Коммерческое  предложение  не  является  офертой  и  носит  информационный характер.
Для оплаты товара выставляется Счет-договор на поставку товара.";

            $arProps['TEXT_TABLE_BEFORE'] = ['VALUE' => $text_before, 'type' => 'html'];
            $arProps['TEXT_TABLE_AFTER'] = ['VALUE' => $text_after, 'type' => 'html'];

            $arProps['IS_MAIN'] = 0;
            $arProps['USER'] = \Bitrix\Main\Engine\CurrentUser::get()->getId();
            $arLoad = [
                'IBLOCK_ID' => IBLOCK_ID_B2BPROFILE,
                'ACTIVE' => 'Y',
                'NAME' => $request['NAME'],
                'PROPERTY_VALUES' => $arProps
            ];
            if ($itemId = $el->Add($arLoad)) {
                $result['success'] = true;
            } else {
                $result['errorMsg'] = $el->LAST_ERROR;
            }
            break;
        case 'del':
            if ($request['all'] == 'Y') {
                $arSelect = ['ID'];
                $arFilter = ['IBLOCK_ID' => IBLOCK_ID_B2BPROFILE, 'PROPERTY_IS_MAIN' => 0, 'PROPERTY_USER' => \Bitrix\Main\Engine\CurrentUser::get()->getId()];
                $iterator = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);
                while ($arItem = $iterator->GetNext()) {
                    $DB->StartTransaction();
                    if (!CIBlockElement::Delete($arItem['ID'])) {
                        $DB->Rollback();
                    } else {
                        $DB->Commit();
                    }
                }
            } else {
                if (!empty($request['itemIds'])) {
                    foreach ($request['itemIds'] as $arId) {
                        $DB->StartTransaction();
                        if (!CIBlockElement::Delete($arId)) {
                            $DB->Rollback();
                        } else {
                            $DB->Commit();
                        }
                    }
                    $result['success'] = true;
                }
            }

            break;
        default:
            $result['errorMsg'] = 'Такой операции не существует';
            break;
    }
} else {
    $result['errorMsg'] = 'Нет доступа';
}

echo \Bitrix\Main\Web\Json::encode($result);