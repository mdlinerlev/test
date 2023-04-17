<?

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

\Bitrix\Main\Loader::includeModule('ceteralabs.uservars');

//пережатие картинок
function i($id, $width, $height, $mode = BX_RESIZE_IMAGE_PROPORTIONAL_ALT)
{
    $small = CFile::ResizeImageGet($id, Array("height" => $height, "width" => $width), $mode, false);
    return $small["src"];
}

// dump вывод
function dump($pr)
{
    print_r("<pre>");
    print_r($pr);
    print_r("</pre>");
}

function urlDomain()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['SERVER_NAME'];
}

/*
** @param array arr массив для вывода
*/
function pRU($arr, $show, $break = false)
{
    global $USER;

    if ($USER->IsAdmin() || $show == 'all') {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";

        $break ? die() : '';
    }
}

/**
 * проверяет массив на пустоту
 * @param $arr
 * @return bool|void
 */
function eRU($arr)
{
    if (is_array($arr) && !empty($arr)) {
        return true;
    }
    return;
}

//падежи
function plural($n, $one, $two, $many)
{
    return $n % 10 == 1 && $n % 100 != 11 ? $one : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $two : $many);
}

function recursive_array_search($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        $current_key = $key;
        if ($needle === $value OR (is_array($value) && recursive_array_search($needle, $value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

/**
 * Обработка 404
 */
function check404Error()
{
    if (defined('ERROR_404') && ERROR_404 == 'Y' && !defined('ADMIN_SECTION')) {
        global $APPLICATION;
        global $USER;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
        require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/footer.php';
    }
}

function isDetailCatalogPage()
{
    global $APPLICATION, $DB;
    $curDir = $APPLICATION->GetCurDir();
    if (mb_strpos($curDir, '/catalog/') !== false) {
        $urlParts = explode('/', trim($curDir, '/'));
        array_shift($urlParts); # удаляем /catalog/
        if (sizeof($urlParts) > 1) {
            # подозрение на элемент - ибо он не может быть в корне
            $suspectPart = $urlParts[sizeof($urlParts) - 1];

            # ищем элемент с таким символьным кодом
            $res = $DB->Query("
                SELECT ID FROM b_iblock_element WHERE
                    IBLOCK_ID = " . IBLOCK_ID_CATALOG . " AND
                    ACTIVE = 'Y' AND
                    CODE = '" . $DB->ForSQL($suspectPart) . "'
                LIMIT 1
            ");
            return (bool)$res->SelectedRowsCount();
        }
    }
    return false;
}


/**
 * Позволяет не париться с foreach'ами и if'ами при выводе товаров или торговых предложений
 * @param $arItem
 * @param $callback
 */
function offersIterator(&$arItem, $callback)
{
    $dataString = '';
    $config = $arItem['PROPERTIES']['CONFIGURATION']['VALUE'];
    if (is_array($arItem['OFFERS'])) {
        if (sizeof($arItem['OFFERS']) > 0) {
            foreach ($arItem['OFFERS'] as $offer) {
                $dataString = ' data-offer-id="' . $offer['ID'] . '"';
                if ($offer['CATALOG_AVAILABLE'] == 'Y') {
                    $dataString .= ' data-available="1"';
                }
                $offer['CONFIG'] = $config;
                $callback($offer, $dataString);
            }
        } elseif ($arItem['OFFERS']) {
            $arItem['OFFERS'][0]['CONFIG'] = $config;
            $callback($arItem['OFFERS'][0], $dataString);
        } else {
            $arItem['CONFIG'] = $config;
            $callback($arItem, $dataString);
        }
    } else {
        $arItem['CONFIG'] = $config;
        $callback($arItem, $dataString);
    }
}

/**
 * Сортировка массива по полею сорт
 * вызывается из функции uasort
 */
function sortBySortField($a, $b)
{
    return ($a["SORT"] == $b["SORT"]) ? 0 : (($a["SORT"] < $b["SORT"]) ? -1 : 1);
}

/**
 * Сортировка массива по полею сорт
 * вызывается из функции uasort
 */
function sortBySortFieldName($a, $b)
{
    $strCmpResult = strcasecmp(trim($a["NAME"]), trim($b["NAME"]));
    if (!$strCmpResult)
        return 0;

    return ($strCmpResult < 0) ? -1 : 1;
}

function counter($n, $koren = "товар", $okonch = array("", "а", "ов"))
{
    $n = intval($n);
# Возвращаемая строка
    $str = "";
# Сразу узнаем остатки от деления
# на 10 и 100, чтобы не вычислять
# при каждом сравнении:
    $nmod10 = $n % 10;
    $nmod100 = $n % 100;

# Случай 1.
    if (!$n) {
        return "{$koren}{$okonch[2]}";
    } # Случай 2.
    else if (($n == 1) or ($nmod10 == 1 and $nmod100 != 11)) {
        $str = "{$koren}{$okonch[0]}";
    } # Случай 3.
    else if (($nmod10 > 1) and ($nmod10 < 5) and ($nmod100 != 12 and $nmod100 != 13 and $nmod100 != 14)) {
        $str = "{$koren}{$okonch[1]}";
    } # Случай 4. Все остальные варианты.
    else {
        $str = "{$koren}{$okonch[2]}";
    }
    return $n . ' ' . $str;
}

function getHightloadData($id, $data, $keyField = false)
{
    $result = [];
    if (Loader::includeModule('highloadblock')) {
        $hlblock = HL\HighloadBlockTable::getById($id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList($data);

        if ($keyField) {
            while ($arItem = $rsData->fetch()) {
                $result[$arItem[$keyField]] = $arItem;
            }
        } else {
            return $rsData->fetchAll();
        }
    }
    return $result;
}

function getPropertyListVariant(string $propCode, string $iblockid, array $select = [], array $filter = [], string $key = '', array $order = array())
{
    $filter['PROPERTY.CODE'] = $propCode;
    $filter['PROPERTY.IBLOCK_ID'] = $iblockid;
    $iterator = \Bitrix\Iblock\PropertyEnumerationTable::getList([
        'select' => ($select) ? $select : ['*'],
        'filter' => $filter,
        'order' => $order
    ]);
    $data = [];
    if (empty($key)) {
        $data = $iterator->fetchAll();
    } else {
        while ($arProp = $iterator->fetch()) {
            $data[$arProp[$key]] = $arProp;
        }
    }
    return $data;
}

function getComOfferNumber()
{
    $number = 0;
    $iterator = \Bitrix\Iblock\ElementTable::getList([
        'order' => ['ID' => 'desc'],
        'select' => ['ID'],
        'limit' => 1
    ]);
    if ($arItem = $iterator->fetch()) {
        $number = ++$arItem['ID'];
    }
    return $number;
}

function getB2bProfileImage()
{
    $picture = SITE_TEMPLATE_PATH . '/img/table-img.png';
    $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
    $iterator = $entity::getList([
        'filter' => [
            '=PROPERTY_IS_MAIN_VALUE' => 1,
            '!PREVIEW_PICTURE' => false,
            'PROPERTY_USER_VALUE' => \Bitrix\Main\Engine\CurrentUser::get()->getId(),
        ],
        'select' => [
            'ID', 'PREVIEW_PICTURE',
            'PROPERTY_IS_MAIN_' => 'IS_MAIN',
            'PROPERTY_USER_' => 'USER'
        ]
    ]);
    if ($arItem = $iterator->fetch()) {
        $img = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], ['width' => 120, 'height' => 120]);
        $picture = $img['src'];
    }
    return $picture;
}

function getUserPrice($userId = 0)
{
    $priceCode = 1;

    if ($userId == 0) {
        $userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();
    }

    $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_B2BPROFILE)->getEntityDataClass();
    $iterator = $entity::getList([
        'filter' => [
            'PROPERTY_USER_VALUE' => $userId,
            '=PROPERTY_IS_MAIN_VALUE' => 1,
            '!PROPERTY_PRICE_NAME_VALUE' => false
        ],
        'select' => [
            'ID',
            'PROPERTY_IS_MAIN_' => 'IS_MAIN',
            'PROPERTY_PRICE_NAME_' => 'PRICE_NAME',
            'PROPERTY_USER_' => 'USER'
        ]
    ]);
    if ($arItem = $iterator->fetch()) {
        $priceCode = $arItem['PROPERTY_PRICE_NAME_VALUE'];
    }
    return $priceCode;
}

function getWatPrice($price)
{
    $wat = (($price / 1.2) - $price) * (-1);
    $value = [
        'WAT' => number_format(round($wat, 2), 2, ',', ' '),
        '~WAT' => $wat,
        'PRICE' => number_format(round($price - $wat, 2), 2, ',', ' '),
        '~PRICE' => $price - $wat
    ];
    return $value;
}

function getBase64($imgPath)
{
    $img = file_get_contents($imgPath);
    $mimeType = mime_content_type($imgPath);
    $encoded = base64_encode($img);
    return "data:$mimeType;base64, $encoded";
}