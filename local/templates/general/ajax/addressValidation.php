<?
$result = [];

switch ($request['type']) {
    case 'city':
        $iterator = \Bitrix\Sale\Location\TypeTable::getList([
            'select' => array('ID', 'NAME_RU' => 'NAME.NAME'),
            'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID),
            'limit' => 10,
        ]);
        while ($item = $iterator->fetch()) {
            $result[] = $item['NAME_RU'];
        }
        break;
    case 'street':
        break;
}

/*$dataRequest = [
    'format' => 'json',
    'apikey' => YANDEX_MAP_KEY,
    'geocode' => $request['term'],
    'kind' => 'house'
];
$YandexMapUrl = 'https://geocode-maps.yandex.ru/1.x/?' . http_build_query($dataRequest);
if ($request['term']) {
    $yandexData = file_get_contents($YandexMapUrl);
    $data = \Bitrix\Main\Web\Json::decode($yandexData);
    foreach ($data['response']['GeoObjectCollection']['featureMember'] as $arAddress) {
        $result[] = $arAddress['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
    }
}*/

echo \Bitrix\Main\Web\Json::encode($result);