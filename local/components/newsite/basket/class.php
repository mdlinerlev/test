<?
use \Bitrix\Sale\DiscountCouponsManager as DiscountCouponsManager;
use \Bitrix\Main\Localization\Loc;

define("CHACHE_IMG_PATH", "{$_SERVER["DOCUMENT_ROOT"]}/upload/cacheResize/");
define("RETURN_IMG_PATH", "/upload/cacheResize/");

class CShBasket extends CBitrixComponent
{

    protected static
        $actionIsChecked = false,
        $updateBasket = false;

    protected $action, $request;
    /** @var \Newsite\Sale\Order $order */
    public static $order;

    /** @var \Newsite\Sale\Basket $basket */
    public static $basket, $items;

    public function executeComponent()
    {

        CJSCore::Init(array("ajax"));
        \Bitrix\Main\Page\Asset::getInstance()->addCss($this->__path . '/css/ajax.css', true);
        \Bitrix\Main\Page\Asset::getInstance()->addJs($this->__path . '/script/ajaxPlugins.js', true);
        \Bitrix\Main\Page\Asset::getInstance()->addJs($this->__path . '/script/ajax.js', true);
        \Bitrix\Main\Page\Asset::getInstance()->addJs($this->__path . '/script/form_send.js', true);
        \Bitrix\Main\Page\Asset::getInstance()->addJs($this->__path . '/script/order.js', true);


        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/fakeajaxsubmit.php")) {
            file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/fakeajaxsubmit.php", "form is submit!");
        }
        return $this->__includeComponent();
    }

    public function prepareAction()
    {
        $action = 'basket';
        if (
            $this->request->get("ORDERHASH") &&
            $this->request->get("ORDER_ID") &&
            $this->request->get("ORDERHASH") == $this->getOrderHash($this->request->get("ORDER_ID"))
        ) {
            $action = 'showOrder';
        }


        return $action;
    }

    protected function doAction($action)
    {
        if (is_callable([$this, $action . "Action"])) {
            call_user_func(
                [$this, $action . "Action"]
            );
        }
    }

    public function basketAction()
    {
        $this->initOrder(0, 1);
        if (!self::$basket->getTotalQuantity())
        {
            $this->arResult["TEMPLATE"] = "empty";
            return;
        }

        $this->arResult["TEMPLATE"] = "";
        $this->processOrderAction();

    }

    public function showOrderAction()
    {

        $this->initOrder( $this->request->get("ORDER_ID") );

        $this->arResult["TEMPLATE"] = "showOrder";

    }

    public function _getUserBy($field, $value) {
        if( empty( $field ) && empty( $value ) ) {
            return false;
        }
        return \Bitrix\Main\UserTable::getList(array(
            'filter'    => array($field => $value),
            'select'    => array('ID'),
            'limit'     => 1
        ))->fetch();
    }

    public function processRegistrationUser()
    {
        $result = new \Bitrix\Main\Result();

        global $USER;


        /** @var  $propertyCollection  \Newsite\Sale\PropertyValueCollection */
        $propertyCollection = static::$order->getPropertyCollection();

        $randHash = '';
        if( $arUser = $this->_getUserBy('EMAIL', $propertyCollection->getItemPropertyByCode("EMAIL")->getValue()))
            $randHash = "bx". randString(4)."_";

        $password = randString(8);
        $arDataReg = [
            'ACTIVE'           => "Y",
            'NAME'             => $propertyCollection->getItemPropertyByCode((static::$order->getPersonTypeId() <= 1 ? "FIO" : "CONTACT_PERSON"))->getValue(),
            'LOGIN'            => $randHash.$propertyCollection->getItemPropertyByCode("EMAIL")->getValue(),
            'EMAIL'            => $randHash.$propertyCollection->getItemPropertyByCode("EMAIL")->getValue(),
            'PERSONAL_PHONE'   => $propertyCollection->getItemPropertyByCode("PHONE")->getValue(),
            'PASSWORD'         => $password,
            'CONFIRM_PASSWORD' => $password,
        ];
        $userId = $USER->Add($arDataReg);

        if ($userId > 0) {
            static::$order->setFieldNoDemand("USER_ID", $userId);
            //$USER->Authorize($userId);
            //\CEvent::Send('USER_INFO', SITE_ID, $arDataReg);
        } else {
            $result->addError(new \Bitrix\Main\Error($USER->LAST_ERROR));
        }

        return $result;
    }

    public function processOrderAction()
    {
        self::$order->fillOrder();

        $this->arResult["PERSON_TYPE"] = self::$order->getAvailablePersonType();
        $this->arResult["AVAILABLE_DELIVERIES"] = self::$order->getAvailableDeliveries();
        $this->arResult["AVAILABLE_PAYSYSTEMS"] = self::$order->getAvailablePaySystems();
        $this->arResult["BUYER_STORE"] = self::$order->getAvailableDeliveriesStores();
        $this->arResult["STORES"] = self::$order->getStoreDelivery();
        $this->arResult["CALC_DELIVERIES"] = self::$order->calcDeliveries($this->arResult["AVAILABLE_DELIVERIES"]);

        self::$order->getPropertyCollection()->fillPropValue();

        $requestState = $this->request->get("submit");
        if (static::$order->getOrderStepCollection()->isValid() && $this->request->isPost() && $requestState == "Y") {

            global $USER;
            if (!$USER->IsAuthorized()) {
                $result = $this->processRegistrationUser();
                if (!$result->isSuccess()) {
                    $this->arResult["ERRORS"]["REGISTERED"] = $result->getErrorMessages();
                    return;
                }
            }
            $result = self::$order->save();
            if ($result->isSuccess()) {

                DiscountCouponsManager::clear(true);
                DiscountCouponsManager::clearApply(true);

                $this->arResult["RESTORE_LINK"] = "/personal/cart/?ORDER_ID=" . $result->getId() . "&ORDERHASH=" . $this->getOrderHash($result->getId());
                $this->arResult["TEMPLATE"] = "showOrder";

                return;
            }
        }

    }

    public function initOrder($orderId = 0, $loadAdditionalProductData = false)
    {

        \Bitrix\Sale\DiscountCouponsManager::init();

        global $USER;
        if ($orderId > 0) {
            self::$order = \Bitrix\Sale\Order::load($orderId);
        } else {
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                \Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite()
            );
            self::$order = \Bitrix\Sale\Order::create(
                \Bitrix\Main\Context::getCurrent()->getSite(),
                $USER->GetID()
            );
            self::$order->setPersonTypeId($this->arParams['PERSON_TYPE_ID']);
            self::$order->setBasket($basket);
            self::$order->isStartField();
        }

        $this->getCouponList();

        $_SESSION["BASKET"]["ORDER_ITEMS"] = [];
        foreach (self::$order->getBasket()->getBasketItems() as $basketItem)
            $_SESSION["BASKET"]["ORDER_ITEMS"][$basketItem->getProductId()] = $basketItem->getProductId();


        if (!empty(self::$order)) {
            self::$order->doFinalAction(true);
            self::$basket = self::$order->getBasket();

            if ($loadAdditionalProductData) {
                self::$basket->getAdditionalProductsData($orderId > 0 ? false : true, true);
            }

            self::$items = self::$basket->getProductInfo();
        }
    }

    public function getOrderHash($orderId)
    {
        return md5("order" . $orderId);
    }

    public function initCompParams()
    {

        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
        \Bitrix\Main\Loader::includeModule('sale');

        $this->arResult["KILLARG"] = [
            "CLEAR_BASKET",
            "BASKET_ADD",
            "BASKET_DELETE",
            "GROUPDELETE",
            "PERSON_TYPE_ID",
            "OPENURL",
            "clear_cache",
            "bxajaxid",
            "ORDERHASH",
            "ORDER_ID",
            "PRINT",
            "STEP",
            "DELIVERY_ID",
            "PAY_SYSTEM_ID",
            "DELIVERY_PROFILE_ID",
            "LOCATION_ID",
            "FINDDISCOUNT",
            "FINDDISCOUNT",
            "DELDISCOUNT",
            "DELSERT",
            "FINDSERT",
            "DELPROMO",
            "FINDPROMO",
            "STORE_SELECT",
            "ONSTORE_SELECT",
            "ORDERMAKE",
            "STEP",
            "action",
            "id"
        ];
        $this->arParams["PERSON_TYPE_ID"] = $this->request->get('PERSON_TYPE') ? : 1;
        $this->arParams["CURRENCY"] = \Bitrix\Currency\CurrencyManager::getBaseCurrency();

        $this->arResult["TEMPLATE"] = "";
    }

    public function GetImages()
    {
        foreach (self::$basket->getBasketItems() as $basketItem) {
            $imageId = $basketItem->getField("IMAGE");
            if ($imageId > 0) {
                $this->arResult["IMAGES"][$imageId] = $imageId;
            }
        }

        if (!empty($this->arResult["IMAGES"])) {
            $dbl = CFile::GetList(array(), array("@ID" => implode(",", array_filter($this->arResult["IMAGES"], "intval"))));

            $uploadDir = COption::GetOptionString("main", "upload_dir", "upload");
            while ($res = $dbl->fetch()) {
                $this->arResult["IMAGES"][$res["ID"]] = "/$uploadDir/" . $res["SUBDIR"] . "/" . $res["FILE_NAME"];

                if (!empty($res["DESCRIPTION"])) {
                    $this->arResult["IMAGES_DESCRIPTION"][$res["ID"]] = $res["DESCRIPTION"];
                }
            }

            $this->arResult["IMAGES"] = array_filter($this->arResult["IMAGES"], function ($a) {
                return !is_numeric($a);
            });
        }

        $this->arResult["IMAGES"][0] = false; //default image

    }


    function couponClear(){
        DiscountCouponsManager::init();
        DiscountCouponsManager::clear(true);
    }
    function couponApply($coupon)
    {
        DiscountCouponsManager::init();
        DiscountCouponsManager::add($coupon);
        DiscountCouponsManager::setApply($coupon, ['BASKET' => self::$basket->getBasketItems()]);
        DiscountCouponsManager::saveApplied();
    }

    function couponRemove($coupon)
    {
        DiscountCouponsManager::init();
        DiscountCouponsManager::delete($coupon);
    }


    function __construct($component = null)
    {
        Loc::loadMessages(__FILE__);
        $this->request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        parent::__construct($component);
        $this->CheckBasketAction();
    }

    function getCouponList()
    {
        $this->arResult['COUPON_LIST'] = [];

        $arCoupons = DiscountCouponsManager::get(true, [], true, true);
        if (!empty($arCoupons)) {
            foreach ($arCoupons as &$oneCoupon) {
                if ($oneCoupon['ACTIVE'] != 'Y' || $oneCoupon['STATUS'] != DiscountCouponsManager::STATUS_APPLYED) {
                    DiscountCouponsManager::delete($oneCoupon['COUPON']);
                    $oneCoupon['STATUS_TEXT'] = Loc::getMessage("COUPON_STATUS_NOT_APPLYED");
                }
            }
            unset($oneCoupon);
            $this->arResult['COUPON_LIST'] = array_values($arCoupons);
        }
        unset($arCoupons);
    }

    protected function CheckBasketAction()
    {
        if (CShBasket::$actionIsChecked) {
            return;
        }
        $this->initOrder();
        CShBasket::$actionIsChecked = true;


        if ($this->request->get("BASKET_ADD")) {
            $this->AddBasketProduct(
                $this->request->get("BASKET_ADD"),
                floatval($this->request->get("COUNT"))
            );
        }

        if ($this->request->get("ADD_COUPON")) {
            $this->couponApply($this->request->get("ADD_COUPON"));
        }

        if ($this->request->get("CLEAR_COUPON")) {
            $this->couponClear();
        }


        if (!empty($this->request->get("action"))) {
            switch ($this->request->get("action")) {
                case "clear":
                    $this->ClearBasket();
                    break;
                case "remove":
                    $deleteArr = (array)$this->request->get("id");
                    $this->DeleteBasketProduct($deleteArr);
                    break;
            }
        }

    }

    /**
     * Удаляет товары из корзины
     * @param type $deleteArr
     */
    protected function DeleteBasketProduct($deleteArr)
    {

        if (empty($deleteArr)) {
            return false;
        }
        $this->initOrder();
        foreach ($deleteArr as $productId) {
            if ($item = self::$basket->getItemByProductId($productId)) {
                $item->delete();
            }
        }
        self::$basket->save();
        self::$updateBasket = true;

        return true;
    }

    /**
     * Добавление/обновление количества товара в корзину
     * @param type $productID
     * @param type $quantity
     */
    function AddBasketProduct($productId, $quantity = 0, $props = [])
    {

        $quantity = empty($quantity) ? 1 : floatval($quantity);

        if ($productId) {

            $this->initOrder();

            if ($item = self::$basket->getItemByProductId($productId)) {
                $item->setField('QUANTITY',$quantity);
            } else {
                //Добавление товара
                $item = self::$basket->createItem('catalog', $productId);
                $item->setFields([
                    'QUANTITY' => $quantity,
                    'CURRENCY' => $this->arParams["CURRENCY"],
                    'LID'      => \Bitrix\Main\Context::getCurrent()->getSite(),
                ]);
            }
            self::$basket->save();

            self::$updateBasket = true;
        }
    }

    /**
     * Функция масшатибрования изображений с поддержкой кеширования
     * Поддерживает разные режимы работы MODE
     * MODE принимает значения: cut, in
     *
     * @param array $params - массив параметров ресайзера
     * @param string $filePath - Params array
     *
     * @return string - result image src
     */
    public function imageResize($params, $filePath) {

        $params["QUALITY"] = (!isset($params["QUALITY"]) || intval($params["QUALITY"]) <= 0) ? 100 : $params["QUALITY"];
        $params['HIQUALITY'] = $params["HIQUALITY"] ?: 'Y';

        if (\Bitrix\Main\Loader::IncludeModule("sh.image") && class_exists("Imagick")) {

            if (class_exists("Imagick")) {

                if ($filePath == NO_IMAGE_SRC) {
                    unset($params["FILTER_BEFORE"]);
                }

                $reizerObj = Sh\Image\Resizer::GetInstance(new Sh\Image\Imagic\Resizer());
            }
            else {
                unset($params["FILTER_BEFORE"]);
                $reizerObj = Sh\Image\Resizer::GetInstance(new Sh\Image\GD\Resizer());
            }



            return $reizerObj->resize($params, $filePath);
        }


        $params["WIDTH"] = !isset($params["WIDTH"]) ? 100 : intval($params["WIDTH"]);
        $params["HEIGHT"] = !isset($params["HEIGHT"]) ? 100 : intval($params["HEIGHT"]);
        $params["MODE"] = !isset($params["MODE"]) ? 'in' : strtolower($params["MODE"]);

        $params["RESET"] = !empty($params["RESET"]);
        $params["QUALITY"] = (!isset($params["QUALITY"]) || intval($params["QUALITY"]) <= 0) ? 100 : $params["QUALITY"];
        $params["HIQUALITY"] = !isset($params["HIQUALITY"]) ? (($params["WIDTH"] <= 200 || $params["HEIGHT"] <= 200) ? 1 : 0) : 0;
        $params["SETWATERMARK"] = !empty($params["SETWATERMARK"]);

        $resetImage = !empty($params["RESET"]);

        unset($params["RESET"]);
        $pathToOriginalFile = "{$_SERVER["DOCUMENT_ROOT"]}/{$filePath}";

        $salt = md5(strtolower($filePath) . implode('_', $params));
        $salt = substr($salt, 0, 3) . '/' . substr($salt, 3, 3) . "/";

        $fileType = end(explode(".", basename($filePath)));
        $filename = md5(basename($filePath));
        $pathToFile = $salt . $filename . "." . $fileType;

        // если изображение существует
        if (is_file(CHACHE_IMG_PATH . $pathToFile) == true) {
            $timeCreate = time() - filemtime(CHACHE_IMG_PATH . $pathToFile);

            if ($_REQUEST["clear_cache_image"] == 'Y' || $resetImage || $timeCreate > (24 * 60 * 60) * 7 || !filesize(CHACHE_IMG_PATH . $pathToFile)) { //при очистке кэша
                unlink(RETURN_IMG_PATH . $pathToFile);
            }
            else {
                return RETURN_IMG_PATH . $pathToFile;
            }
        }


        if (!file_exists($pathToOriginalFile)) {
            $filePath = NO_IMAGE_SRC;

            $pathToOriginalFile = "{$_SERVER["DOCUMENT_ROOT"]}/{$filePath}";
            $salt = md5(strtolower($filePath) . implode('_', $params));
            $salt = substr($salt, 0, 3) . '/' . substr($salt, 3, 3) . "/";
            $fileType = end(explode(".", basename($filePath)));
            $filename = md5(basename($filePath));
            $pathToFile = $salt . $filename . "." . $fileType;
            $params["MODE"] = "in";
            if (is_file(CHACHE_IMG_PATH . $pathToFile) == true) {
                return RETURN_IMG_PATH . $pathToFile;
            }
        }
        CheckDirPath(CHACHE_IMG_PATH . $salt);

        $imgInfo = getImageSize($pathToOriginalFile);


        if ($params["MODE"] == "maxsize") {
            if ($imgInfo[0] < $imgInfo[1] && !empty($params["HEIGHT"])) {
                $params["WIDTH"] = 0;
            }
            elseif (!empty($params["WIDTH"])) {
                $params["HEIGHT"] = 0;
            }
        }


        if (intval($params["WIDTH"]) == 0) {
            $params["WIDTH"] = intval($params["HEIGHT"] / $imgInfo[1] * $imgInfo[0]);
        }

        if (intval($params["HEIGHT"]) == 0) {
            $params["HEIGHT"] = intval($params["WIDTH"] / $imgInfo[0] * $imgInfo[1]);
        }


        //если вырезаться будет cut проверка размеров
        if (($params["WIDTH"] > $imgInfo[0] || $params["HEIGHT"] > $imgInfo[1]) && ($params["MODE"] != "in" && $params["MODE"] != "inv")) {
            $params["WIDTH"] = $imgInfo[0];
            $params["HEIGHT"] = $imgInfo[1];
        }

        if (!($imgInfo[0] == $params["WIDTH"] && $imgInfo[1] == $params["HEIGHT"]) || $params["SETWATERMARK"]) {

            $im = ImageCreateTrueColor($params["WIDTH"], $params["HEIGHT"]);

            imageAlphaBlending($im, false);

            switch (strtolower($imgInfo["mime"])) {
                case 'image/gif' :

                    $params["HIQUALITY"] = false;
                    $black = imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
                    imagesavealpha($im, true);
                    imagefilledrectangle($im, 0, 0, $params["WIDTH"], $params["HEIGHT"], $black);
                    $i0 = ImageCreateFromGif($pathToOriginalFile);
                    break;
                case 'image/jpeg' :
                case 'image/pjpeg' :
                    $icolor = ImageColorAllocate($im, 255, 255, 255);
                    imagefill($im, 0, 0, $icolor);
                    $i0 = ImageCreateFromJpeg($pathToOriginalFile);
                    break;
                case 'image/png' :
                    $params["HIQUALITY"] = false;
                    $black = imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
                    imagesavealpha($im, true);
                    imagefilledrectangle($im, 0, 0, $params["WIDTH"], $params["HEIGHT"], $black);
                    $i0 = ImageCreateFromPng($pathToOriginalFile);
                    break;
                default :
                    return;
            }

            switch (strtolower($params["MODE"])) {
                case 'cut' :
                    $k_x = $imgInfo[0] / $params["WIDTH"];
                    $k_y = $imgInfo[1] / $params["HEIGHT"];
                    if ($k_x > $k_y) {
                        $k = $k_y;
                    }
                    else {
                        $k = $k_x;
                    }
                    $pn["WIDTH"] = $imgInfo[0] / $k;
                    $pn["HEIGHT"] = $imgInfo[1] / $k;
                    $x = ($params["WIDTH"] - $pn["WIDTH"]) / 2;
                    $y = ($params["HEIGHT"] - $pn["HEIGHT"]) / 2;


                    imageCopyResampled($im, $i0, $x, $y, 0, 0, $pn["WIDTH"], $pn["HEIGHT"], $imgInfo[0], $imgInfo[1]);
                    break;

                case "maxsize":
                    //вписана в квадрат без маштабирования (картинка может быть увеличена больше своего размера)
                case 'in' :

                    if (($imgInfo[0] < $params["WIDTH"]) && ($imgInfo[1] < $params["HEIGHT"])) {
                        $k_x = 1;
                        $k_y = 1;
                    }
                    else {
                        $k_x = $imgInfo[0] / $params["WIDTH"];
                        $k_y = $imgInfo[1] / $params["HEIGHT"];
                    }

                    if ($k_x < $k_y) {
                        $k = $k_y;
                    }
                    else {
                        $k = $k_x;
                    }

                    $pn["WIDTH"] = $imgInfo[0] / $k;
                    $pn["HEIGHT"] = $imgInfo[1] / $k;

                    $x = ($params["WIDTH"] - $pn["WIDTH"]) / 2;
                    $y = ($params["HEIGHT"] - $pn["HEIGHT"]) / 2;

                    imageCopyResampled($im, $i0, $x, $y, 0, 0, $pn["WIDTH"], $pn["HEIGHT"], $imgInfo[0], $imgInfo[1]);


                    // 1 первый параметр изборажение источник
                    // 2 изображение которое вставляется
                    // 3 4 -х и у с какой точки будет вставятся в изображении источник
                    // 5 6 - ширина и высота куда будет вписано изображение

                    break;
                default :
                    imageCopyResampled($im, $i0, 0, 0, 0, 0, $params["WIDTH"], $params["HEIGHT"], $imgInfo[0], $imgInfo[1]);
                    break;
            }


            if ($params["HIQUALITY"]) {
                $sharpenMatrix = [
                    [-1.2, -1, -1.2],
                    [-1, 20, -1],
                    [-1.2, -1, -1.2],
                ];
                // calculate the sharpen divisor
                $divisor = array_sum(array_map('array_sum', $sharpenMatrix));
                $offset = 0;
                // apply the matrix
                imageconvolution($im, $sharpenMatrix, $divisor, $offset);
            }


            $params["WATERMARK_PATH"] = $_SERVER["DOCUMENT_ROOT"] . (empty($params["WATERMARK_PATH"]) ? "/img/watermark.png" : $params["WATERMARK_PATH"]);

            if ($params["SETWATERMARK"] && file_exists($params["WATERMARK_PATH"])) {
                imageAlphaBlending($im, true);

                $params["WATERMARK_POSITION"] = empty($params["WATERMARK_POSITION"]) || abs($params["WATERMARK_POSITION"]) > 9 ? 5 : abs($params["WATERMARK_POSITION"]);


                list($widthWater, $heightWater) = getimagesize($params["WATERMARK_PATH"]);

                if ($params["WIDTH"] < $widthWater || $params["HEIGHT"] < $heightWater) {


                    //ресайз по ширине
                    $waterW = intval($params["WIDTH"] * 0.8);

                    if ($waterW > $widthWater) {
                        $waterW = $widthWater;
                    }

                    $waterH = intval($waterW / $widthWater * $heightWater);

                    $waterMarkResize = ["HEIGHT" => $waterH, "WIDTH" => $waterW];

                    if ($waterH > $params["HEIGHT"]) {
                        $waterH = intval($params["HEIGHT"] * 0.8);
                        $waterW = intval($waterH / $heightWater * $widthWater);
                        $waterMarkResize = ["HEIGHT" => $waterH, "WIDTH" => $waterW];
                    }

                    if (strpos($params["WATERMARK_PATH"], $_SERVER["DOCUMENT_ROOT"]) === 0) {
                        $params["WATERMARK_PATH"] = substr($params["WATERMARK_PATH"], strlen($_SERVER["DOCUMENT_ROOT"]));
                    }

                    if (!empty($params["RESET"])) {
                        $waterMarkResize["RESET"] = $params["RESET"];
                    }

                    $params["WATERMARK_PATH"] = $_SERVER["DOCUMENT_ROOT"] . imageResize($waterMarkResize, $params["WATERMARK_PATH"]);
                    list($widthWater, $heightWater) = getimagesize($params["WATERMARK_PATH"]);
                }

                $waterMark = ImageCreateFromPng($params["WATERMARK_PATH"]);

                $waterTop = $waterLeft = 0;
                if (in_array($params["WATERMARK_POSITION"], [4, 5, 6])) {
                    $waterTop = intval($params["HEIGHT"] / 2) - intval($heightWater / 2);
                }
                if (in_array($params["WATERMARK_POSITION"], [7, 8, 9])) {
                    $waterTop = $params["HEIGHT"] - $heightWater;
                }
                if (in_array($params["WATERMARK_POSITION"], [2, 5, 8])) {
                    $waterLeft = intval($params["WIDTH"] / 2) - intval($widthWater / 2);
                }
                if (in_array($params["WATERMARK_POSITION"], [3, 6, 9])) {
                    $waterLeft = $params["WIDTH"] - $widthWater;
                }

                $widthWater--;
                $heightWater--;
                imageCopyResampled($im, $waterMark, $waterLeft, $waterTop, 0, 0, $widthWater, $heightWater, $widthWater, $heightWater);
            }


            switch (strtolower($imgInfo["mime"])) {
                case 'image/gif' :
                    @imageGif($im, CHACHE_IMG_PATH . $pathToFile);
                    break;
                case 'image/jpeg' :
                case 'image/pjpeg' :
                    @imageJpeg($im, CHACHE_IMG_PATH . $pathToFile, $params["QUALITY"]);
                    break;
                case 'image/png' :
                    @imagePng($im, CHACHE_IMG_PATH . $pathToFile);
                    break;
            }

            imagedestroy($i0);
            imagedestroy($im);
        }
        else {
            copy($pathToOriginalFile, CHACHE_IMG_PATH . $pathToFile);
        }

        return RETURN_IMG_PATH . $pathToFile;
    }




    public static function getUpdateBasket()
    {
        return self::$updateBasket;
    }


    /**
     * Очищает корзину
     */
    protected function ClearBasket()
    {
        $this->initOrder();
        self::$basket->clearCollection();
        self::$basket->save();
        self::$updateBasket = true;

    }

}

class ByPricePayment extends \Bitrix\Sale\Services\PaySystem\Restrictions\Price
{

    function checkItem($entityParams, $params, $paymentId)
    {
        return \Bitrix\Sale\Services\PaySystem\Restrictions\Price::check($entityParams, $params, $paymentId);
    }

}

class ByDeliveryPayment extends \Bitrix\Sale\Services\PaySystem\Restrictions\Delivery
{

    function checkItem($entityParams, $params, $paymentId)
    {

        return \Bitrix\Sale\Services\PaySystem\Restrictions\Delivery::check($entityParams, $params, $paymentId);
    }

}

class ByPersonType extends \Bitrix\Sale\Services\PaySystem\Restrictions\Delivery
{

    function checkItem($personTypeId, $params, $paymentId)
    {
        return \Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::check($personTypeId, $params, $paymentId);
    }

}
