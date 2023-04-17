<?

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale;

$result = [
    'success' => false,
    'errorMsg' => '',
    'successMsg' => '',
    'needReload' => true
];

$userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();

if ($userId > 0) {
    $iterator = \Bitrix\Main\UserTable::getByPrimary($userId, [
        'select' => ['ID', 'NAME', 'UF_FAVORITES']
    ]);
    if ($arUser = $iterator->fetch()) {
        $arFavorites = ($arUser['UF_FAVORITES']) ? unserialize($arUser['UF_FAVORITES']) : [];

        switch ($request['type']) {
            case 'favorite':
                $type = 'add';
                if (isset($arFavorites[$request['itemId']])) {
                    unset($arFavorites[$request['itemId']]);
                    $type = 'del';
                } else {
                    $arFavorites[$request['itemId']] = $request['itemId'];
                }

                $user = new CUser();
                if (!$res = $user->Update($userId, ['UF_FAVORITES' => serialize($arFavorites)])) {
                    $result['errorMsg'] = $user->LAST_ERROR;
                } else {
                    if ($type == 'add') {
                        $result['successMsg'] = 'Товар успешно добавлен в избранное.';
                    } else {
                        $result['successMsg'] = 'Товар успешно удален из избранноего.';
                    }
                    $result['favorites'] = $arFavorites;
                }
                break;
            case 'basket':
                $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
                $entity = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_ID_OFFERS)->getEntityDataClass();

                $iterator = \Bitrix\Catalog\ProductTable::getList([
                    'select' => ['ID', 'TYPE'],
                    'filter' => ['ID' => $request['itemsId']]
                ]);
                while ($arItem = $iterator->fetch()) {

                    $id = $arItem['ID'];
                    if ($arItem['TYPE'] == \Bitrix\Catalog\ProductTable::TYPE_SKU) {
                        $res = CCatalogSKU::getOffersList($id, IBLOCK_ID_CATALOG, ['ACTIVE' => 'Y']);
                        $id = $res[$id][array_key_first($res[$id])]['ID'];
                    }

                    if ($item = $basket->getExistsItem('catalog', $id)) {
                        $item->setField('QUANTITY', $item->getQuantity() + 1);
                    } else {
                        $item = $basket->createItem('catalog', $id);
                        $item->setFields([
                            'QUANTITY' => 1,
                            'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                            'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                        ]);
                    }
                    $result['ids'][] = $id;
                }

                $basket->save();
                break;
            case 'delFavorite':
                foreach ($request['itemsId'] as $arItem) {
                    if (isset($arFavorites[$arItem])) {
                        unset($arFavorites[$arItem]);
                    }
                }

                $user = new CUser();
                if (!$res = $user->Update($userId, ['UF_FAVORITES' => serialize($arFavorites)])) {
                    $result['errorMsg'] = $user->LAST_ERROR;
                }
                break;
            default:
                $result['errorMsg'] = 'Не верный тип операции';
                break;
        }
    } else {
        $result['errorMsg'] = 'Пользователь не найден';
    }
} else {
    $result['errorMsg'] = 'Нет доступа';
}

$result['success'] = (empty($result['errorMsg']) ? true : false);
echo \Bitrix\Main\Web\Json::encode($result);
