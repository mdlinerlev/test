<?

use Bitrix\Sale;

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

$number = getComOfferNumber();

$stock = 0;
if (intval($_SESSION['B2B_STOCK_PERCENT']) > 0) {
    $stock = intval($_SESSION['B2B_STOCK_PERCENT']);
}

$hlID = 6;


$arItems = $arProductIDs = [];
foreach ($basket as $key => $item) {
    $arHlElements = HLHelpers::getInstance()->getElementList($hlID, ['UF_ID' => $item->getID(), 'UF_PRODUCT_ID' => $item->getProductId()]);
    $arItems[$item->getProductId()] = [
        'name' => $item->getField('NAME'),
        'count' => $item->getQuantity(),
        'price' => number_format($item->getPrice(), 2, ",", " "),
        'sum_price' => number_format($item->getFinalPrice(), 2, ",", " "),
        'sum_price_value' => $item->getFinalPrice(),
        'price_value' => $item->getPrice()
    ];

    if($arHlElements[0]['UF_PROCENT']) {

        $arItems[$item->getProductId()]['price'] = number_format($arItems[$item->getProductId()]['price_value'] + $arItems[$item->getProductId()]['price_value'] * $arHlElements[0]['UF_PROCENT'] / 100, 2, ",", " ");
        $arItems[$item->getProductId()]['sum_price'] = number_format($arItems[$item->getProductId()]['sum_price_value'] + $arItems[$item->getProductId()]['sum_price_value'] * $arHlElements[0]['UF_PROCENT'] / 100, 2, ",", " ");
        $arItems[$item->getProductId()]['sum_price_value'] += $arItems[$item->getProductId()]['sum_price_value'] * $arHlElements[0]['UF_PROCENT'] / 100;
        $arItems[$item->getProductId()]['price_value'] += $arItems[$item->getProductId()]['price_value'] * $arHlElements[0]['UF_PROCENT'] / 100;
//        pr($arItems[$item->getProductId()]['price']);
//        pr($arItems[$item->getProductId()]['sum_price']);
//        pr($arItems[$item->getProductId()]['sum_price_value']);
//        pr($arItems[$item->getProductId()]['price_value']);
    }
    $totalPrice += $arItems[$item->getProductId()]['sum_price_value'];

    if($arHlElements[0]['UF_HEIGHT'] && $arHlElements[0]['UF_WIDTH'])
        $arItems[$item->getProductId()]['name'].= ' ( Высота: '.$arHlElements[0]['UF_HEIGHT']. ' Ширина: '.$arHlElements[0]['UF_WIDTH'].' )';

    if($arHlElements[0]['UF_COLOR'])
        $arItems[$item->getProductId()]['name'].= ' ( Цвет RAL: '.$arHlElements[0]['UF_COLOR'].' )';
    $arProductIDs[] = $item->getProductId();
}

$arPictures = [];
if (!empty($arProductIDs)) {
    $iterator = \Bitrix\Iblock\ElementTable::getList([
        'select' => ['ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE'],
        'filter' => ['ID' => $arProductIDs]
    ]);
    $count = $key = 0;
    $row = 4;
    while ($arItem = $iterator->fetch()) {
        $picture = '';

        if (!empty($arItem['PREVIEW_PICTURE'])) {
            $picture = CFile::GetPath($arItem['PREVIEW_PICTURE']);
        } elseif ($arItem['DETAIL_PICTURE']) {
            $picture = CFile::GetPath($arItem['DETAIL_PICTURE']);
        }

        if (!empty($picture)) {
            $count++;
            $arPictures[$key][$arItem['ID']] = $picture;

            if ($count >= $row) {
                $count = 0;
                $key++;
            }
        }
    }
}

//$totalPrice = $basket->getPrice();
$priceDiscount = $totalPrice;
$stockTotal = 0;
if ($stock > 0) {
    $stockTotal = ($totalPrice / 100) * $stock;
    $priceDiscount -= $stockTotal;
}

$text_before = 'Наша компания предлагает Вам ознакомится с предложением по следующим позициям:';
$text_after = "Указанные цены и скидки действительны в течение 3 банковских дней.
Так же предлагаем Вам ознакомиться с остальными условиями нашего предложения: 
График оплаты: Предоплата 50% Гарантия на двери составляет 2 года с даты выпуска, при соблюдении необходимых условий эксплуатации и правильной установки.
Данная гарантия не распространяется на товар, конструкция которого была изменена покупателем в ходе эксплуатации.
Гарантийный  срок  на  фурнитуру,  входящую  в  комплект  товара,  составляет  6  месяцев  с  даты  передачи  товара,  и  в соответствии  со  сроками,  установленными  производителями  фурнитуры.  Руководство  по  транспортировке,  хранению, монтажу  и  эксплуатации  товара,  перечень  заводских  дефектов,  на  которые  распространяется  гарантия,  размещены  в сети Интернет на сайте: https://belwooddoors.com/garant
ОБРАТИТЕ  ВНИМАНИЕ!  Коммерческое  предложение  не  является  офертой  и  носит  информационный характер.
Для оплаты товара выставляется Счет-договор на поставку товара.";
$suggest = '';

$entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
$iterator = $entity::getByPrimary(intval($_SESSION['COM_OFFER']['PROPERTIES']['REQUISITE']), [
    'select' => [
        'ID', 'NAME',
        'PROPERTY_LAW_ADDRESS_' => 'LAW_ADDRESS',
        'PROPERTY_UNP_' => 'UNP',
        'PROPERTY_KPP_' => 'KPP',
        'PROPERTY_PAYMENT_ACCOUNT_' => 'PAYMENT_ACCOUNT',
        'PROPERTY_BANK_NAME_' => 'BANK_NAME',
        'PROPERTY_CASH_ACCOUNT_' => 'CASH_ACCOUNT',
        'PROPERTY_BIC_' => 'BIC',
        'PROPERTY_TEXT_TABLE_BEFORE_' => 'TEXT_TABLE_BEFORE',
        'PROPERTY_TEXT_TABLE_AFTER_' => 'TEXT_TABLE_AFTER',
        'PROPERTY_MANAGER_' => 'MANAGER',
        'PROPERTY_EMAIL_' => 'EMAIL',
        'PROPERTY_PHONE_' => 'PHONE',

    ],
]);
if ($arItem = $iterator->fetch()) {
    $value = unserialize($arItem['PROPERTY_TEXT_TABLE_BEFORE_VALUE']);
    if (!empty($value['TEXT'])) {
        $text_before = $value['TEXT'];
    }
    $value = unserialize($arItem['PROPERTY_TEXT_TABLE_AFTER_VALUE']);
    if (!empty($value['TEXT'])) {
        $text_after = $value['TEXT'];
    }

    $suggest .= $arItem['NAME'].PHP_EOL;
    $suggest .= 'Телефон: '.$arItem['PROPERTY_PHONE_VALUE'].PHP_EOL;
    $suggest .= 'Email: '. $arItem['PROPERTY_EMAIL_VALUE'].PHP_EOL;
    $suggest .= 'Менеджер: '.$arItem['PROPERTY_MANAGER_VALUE'].PHP_EOL;

    $arRequisits = [
        'NAME' => $arItem['NAME'],
        'LAW_ADDRESS' => $arItem['PROPERTY_LAW_ADDRESS_VALUE'],
        'INN' => $arItem['PROPERTY_UNP_VALUE'],
        'KPP' => $arItem['PROPERTY_KPP_VALUE'],
        'PC' => $arItem['PROPERTY_PAYMENT_ACCOUNT_VALUE'],
        'BANK_NAME' => $arItem['PROPERTY_BANK_NAME_VALUE'],
        'CA' => $arItem['PROPERTY_CASH_ACCOUNT_VALUE'],
        'BIC' => $arItem['PROPERTY_BIC_VALUE'],
        'IMAGE' => getB2bProfileImage()
    ];
}
$arResult['REQUISITS'] = $arRequisits;

$priceDiscountValue = $priceDiscount;
$priceDiscount = number_format($priceDiscount, 2, ",", " ");
$stockTotal = number_format($stockTotal, 2, ",", " ");
$totalPrice = number_format($totalPrice, 2, ",", " ");
$wat = number_format(($priceDiscount / 100) * 20, 2, ",", " ");


?>
<div class="popup-b2b">
    <div class="popup-b2b__wrp">
        <div class="popup-b2b__head">
            <div class="popup-b2b__zag">Формирование коммерческого предложения</div>
        </div>
        <div class="popup-b2b__kp">
            <div class="popup-b2b__kp-pdf">
                <div class="popup-b2b__kp-zag"> Отображение КП</div>
                <div class="popup-b2b__kp-wrp">
                    <table style="border: 0;">
                        <tbody>
                        <tr>
                            <td style="width: 80%;border: 0;">
                                <p>
                                    <?= $arResult['REQUISITS']['NAME'] ?><br>
                                    <?= ($arResult['REQUISITS']['LAW_ADDRESS']) ? 'Юр.адреc: ' . $arResult['REQUISITS']['LAW_ADDRESS'] : '' ?>
                                    <br>
                                    <?= ($arResult['REQUISITS']['INN']) ? 'ИНН ' . $arResult['REQUISITS']['INN'] : '' ?><?= ($arResult['REQUISITS']['KPP']) ? ' КПП ' . $arResult['REQUISITS']['KPP'] : '' ?>
                                    <br>
                                    <?= ($arResult['REQUISITS']['PC']) ? 'р/с ' . $arResult['REQUISITS']['PC'] : '' ?><?= ($arResult['REQUISITS']['BANK_NAME']) ? ' в ' . $arResult['REQUISITS']['BANK_NAME'] : '' ?>
                                    <br>
                                    <?= ($arResult['REQUISITS']['CA']) ? ' к/с ' . $arResult['REQUISITS']['CA'] : '' ?> <?= ($arResult['REQUISITS']['BIC']) ? ' БИК ' . $arResult['REQUISITS']['BIC'] : '' ?>
                                    <br>
                                </p>
                            </td>
                            <td style="width: 20%;text-align: right;border: 0;">
                                <img src="<?= $arResult['REQUISITS']['IMAGE'] ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0;font-size: 20px;text-align: center;border: 0;" colspan="2"><b>Коммерческое
                                    предложения <?= $number ?> от <?= date("d.m.Y") ?>г</b></td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="border: 0;">
                        <tr>
                            <td style="border: 0;color:#FF562E;font-size: 14px;padding-bottom: 0;text-transform: uppercase;">
                                Текст до таблицы
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 0;font-size: 14px;padding-bottom: 0;" data-cont-val="text-before"><?=$text_before?></td>
                        </tr>
                    </table>
                    <table style="border: 0;">
                        <thead>
                        <tr>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">№</th>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Наименование</th>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Кол-во</th>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Ед.</th>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Цена</th>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Сумма</th>
                            <th style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">Общая сумма с НДС</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($arItems as $key => $item) { ?>
                            <tr>
                                <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= ++$key ?></td>
                                <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000">
                                    <?= $item['name'] ?>
                                </td>
                                <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= $item['count']; ?></td>
                                <td style="border: 0;font-size: 14px;padding:3px;border:1px solid #000"> шт.</td>
                                <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= $item['price']; ?></td>
                                <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= getWatPrice($item['sum_price_value'])['PRICE'] ?></td>
                                <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"> <?= $item['sum_price']; ?></td>
                            </tr>
                        <? } ?>
                        <tr>
                            <td colspan="6" style="border: 0;font-size: 14px;padding: 3px;text-align: right">Итого</td>
                            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= $totalPrice; ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="border: 0;font-size: 14px;padding: 3px;text-align: right">
                                <b>Скидка (%)</b></td>
                            <td style="border: 0;font-size: 14px;padding: 3px;border:1px solid #000"><?= $stock ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="border: 0;font-size: 14px;padding: 3px;text-align: right"><b>Итого со скидкой</b></td>
                            <td style="border: 0;font-size: 14px;padding: 5px;border:1px solid #000"><?= $priceDiscount ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" style="border: 0;font-size: 14px;padding: 5px;text-align: right">НДС включен в сумму</td>
                            <td style="border: 0;font-size: 14px;padding: 5px;border:1px solid #000"><?= getWatPrice($priceDiscountValue)['WAT'] ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <table style="border: 0; text-align: center">
                        <? foreach ($arPictures as $arRow) { ?>
                            <tr>
                                <? foreach ($arRow as $key => $picture) { ?>
                                    <td style="border: 0;">
                                        <div>
                                            <img src="<?= $picture ?>" width="40" style="margin-right: 10px"/>
                                            <p><?= $arItems[$key]['name'] ?></p>
                                        </div>
                                    </td>
                                <? } ?>
                            </tr>
                        <? } ?>
                    </table>
                    <table style="border: 0;">
                        <tr>
                            <td style="border: 0;color:#FF562E;font-size: 14px;padding-bottom: 0;text-transform: uppercase;">
                                Текст после таблицы
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 0;font-size: 14px;padding-bottom: 0;"
                                data-cont-val="text-after"><?= str_replace("\n", '<br>', $text_after) ?></td>
                        </tr>
                    </table>
                    <table style="border: 0;">
                        <tr>
                            <td style="border: 0;color:#FF562E;font-size: 14px;padding-bottom: 0;text-transform: uppercase;">Подпись</td>
                        </tr>
                        <tr>
                            <td style="border: 0;font-size: 14px;padding-bottom: 0;" data-cont-val="suggest"><?=str_replace("\n", '<br>', $suggest)?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <form type="post" class="popup-b2b__kp-form js-kp-create" id="kp-create">
                <input type="hidden" name="NUMBER" value="<?= $number ?>">
                <div class="popup-b2b__kp-zag">Редактирование текстовой информации для КП</div>
                <div class="popup-b2b__kp-wrp">
                    <div class="popup-b2b__form-item">
                        <label>Текст до таблицы</label>
                        <textarea name="PROPERTIES[TABLE_TEXT_1]" style="height: 200px" class="js-copy-value"
                                  data-cont="text-before"
                                  placeholder="Введите текстовую информацию, которая будет располагаться перед таблицей"><?=$text_before?></textarea>
                    </div>
                    <div class="popup-b2b__form-item">
                        <label>Текст после таблицы</label>
                        <textarea name="PROPERTIES[TABLE_TEXT_2]" style="height: 400px" class="js-copy-value"
                                  data-cont="text-after"
                                  placeholder="Введите текстовую информацию, которая будет располагаться после таблицы"><?= $text_after ?></textarea>
                    </div>
                    <div class="popup-b2b__form-item">
                        <label>Подпись</label>
                        <textarea name="PROPERTIES[SIGNATURE]" style="height: 200px" class="js-copy-value"
                                  data-cont="suggest" placeholder="Введите подпись"><?=$suggest?></textarea>
                    </div>
                    <div class="popup-b2b__form-item">
                        <button class="button" type="submit" form="kp-create">Сформировать КП</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>