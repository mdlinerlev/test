<?

use Bitrix\Sale;

$result = ['success' => false, 'errorMsg' => ''];
if (\Bitrix\Main\Engine\CurrentUser::get()->getId()) {
    switch ($request['type']) {
        case 'setStock':
            $stock = intval($request['stock']);

            if($stock > 0){
                $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
                $price = $basket->getPrice();

                $filter = [
                    'order' => ['UF_PRIORITY' => 'ASC'],
                    'select' => ['*'],
                    'filter' => [
                        '<=UF_BEFORE' => $price,
                        '>=UF_AFTER' => $price,
                    ],
                    'limit' => 1
                ];
                $stockData = getHightloadData(HLBLOCK_ID_B2BSTOCK, $filter);
                if (!empty($stockData)) {
                    $minStock = $stockData[array_key_first($stockData)];
                    if ($minStock['UF_VALUE_BEFORE'] > $stock) {
                        $stock = $minStock['UF_VALUE_BEFORE'];
                        //$result['errorMsg'] = 'Минимальный размер скидки ' . $minStock['UF_VALUE_BEFORE'].'%';
                    }
                    if ($minStock['UF_VALUE_AFTER'] < $stock) {
                        $stock = $minStock['UF_VALUE_AFTER'];
                        //$result['errorMsg'] = 'Максимальный размер скидки ' . $minStock['UF_VALUE_AFTER'].'%';
                    }
                }
            }

            if (!empty($result['errorMsg'])) {
                $stock = 0;
            } else {
                $result['success'] = true;
            }
            $_SESSION['B2B_STOCK_PERCENT'] = ($stock > 100) ? 100 : $stock;
            break;
        case 'clearAll':
            CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
            $result['success'] = true;
            break;
        default:
            $result['errorMsg'] = 'Не верный тип операции';
            break;
    }
}

echo \Bitrix\Main\Web\Json::encode($result);