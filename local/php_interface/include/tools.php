<?

/**
 * Вывод всех ошибок
 */
function ShowAllError($errorOnly = 1) {
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "0");
    if ($errorOnly)
        ini_set('error_reporting', E_ERROR);
    else
        ini_set('error_reporting', E_ALL | E_STRICT);
}


define("CHACHE_IMG_PATH", "{$_SERVER["DOCUMENT_ROOT"]}/upload/cacheResize/");
define("RETURN_IMG_PATH", "/upload/cacheResize/");

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
function imageResize($params, $filePath) {

    $params["QUALITY"] = (!isset($params["QUALITY"]) || intval($params["QUALITY"]) <= 0) ? 100 : $params["QUALITY"];
    $params['HIQUALITY'] = $params["HIQUALITY"] ?: 'Y';
    if(empty($filePath) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
        return '';
    }
    
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



        $path = $reizerObj->resize($params, $filePath);

        if ($filePath != $path && (!file_exists($_SERVER["DOCUMENT_ROOT"] . $path . '.webp') || $_REQUEST["clear_cache_image"])) {
            $image = imagecreatefromstring(file_get_contents($_SERVER["DOCUMENT_ROOT"] . $path));
            imagewebp($image, $_SERVER["DOCUMENT_ROOT"] . $path . '.webp', $params["QUALITY"]);
        }
        // replace with webp copy
        if (strpos($_SERVER['HTTP_ACCEPT'], 'webp') !== false && file_exists($_SERVER["DOCUMENT_ROOT"] . $path . '.webp') && filesize($_SERVER["DOCUMENT_ROOT"] . $path . '.webp')) {
            $path .= '.webp';
        }

        return $path;
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

        if ($_REQUEST["clear_cache_image"] == 'Y' || $resetImage || !filesize(CHACHE_IMG_PATH . $pathToFile)) { //при очистке кэша
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
        $resizeParam = array("WIDTH" => 0, "HEIGHT" => 0);
        if ($imgInfo[0] < $imgInfo[1] && !empty($params["HEIGHT"])) {
            $params["WIDTH"] = 0;
        }
        elseif (!empty($params["WIDTH"])) {
            $params["HEIGHT"] = 0;
        }
    }


    if (intval($params["WIDTH"]) == 0)
        $params["WIDTH"] = intval($params["HEIGHT"] / $imgInfo[1] * $imgInfo[0]);

    if (intval($params["HEIGHT"]) == 0)
        $params["HEIGHT"] = intval($params["WIDTH"] / $imgInfo[0] * $imgInfo[1]);


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
            case 'image/jpeg' : case 'image/pjpeg' :
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
                if ($k_x > $k_y)
                    $k = $k_y;
                else
                    $k = $k_x;
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

                if ($k_x < $k_y)
                    $k = $k_y;
                else
                    $k = $k_x;

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
            default : imageCopyResampled($im, $i0, 0, 0, 0, 0, $params["WIDTH"], $params["HEIGHT"], $imgInfo[0], $imgInfo[1]);
                break;
        }


        if ($params["HIQUALITY"]) {
            $sharpenMatrix = array
            (
                array(-1.2, -1, -1.2),
                array(-1, 20, -1),
                array(-1.2, -1, -1.2)
            );
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

                if ($waterW > $widthWater)
                    $waterW = $widthWater;

                $waterH = intval($waterW / $widthWater * $heightWater);

                $waterMarkResize = array("HEIGHT" => $waterH, "WIDTH" => $waterW);

                if ($waterH > $params["HEIGHT"]) {
                    $waterH = intval($params["HEIGHT"] * 0.8);
                    $waterW = intval($waterH / $heightWater * $widthWater);
                    $waterMarkResize = array("HEIGHT" => $waterH, "WIDTH" => $waterW);
                }

                if (strpos($params["WATERMARK_PATH"], $_SERVER["DOCUMENT_ROOT"]) === 0)
                    $params["WATERMARK_PATH"] = substr($params["WATERMARK_PATH"], strlen($_SERVER["DOCUMENT_ROOT"]));

                if (!empty($params["RESET"]))
                    $waterMarkResize["RESET"] = $params["RESET"];

                $params["WATERMARK_PATH"] = $_SERVER["DOCUMENT_ROOT"] . imageResize($waterMarkResize, $params["WATERMARK_PATH"]);
                list($widthWater, $heightWater) = getimagesize($params["WATERMARK_PATH"]);
            }

            $waterMark = ImageCreateFromPng($params["WATERMARK_PATH"]);

            $waterTop = $waterLeft = 0;
            if (in_array($params["WATERMARK_POSITION"], array(4, 5, 6)))
                $waterTop = intval($params["HEIGHT"] / 2) - intval($heightWater / 2);
            if (in_array($params["WATERMARK_POSITION"], array(7, 8, 9)))
                $waterTop = $params["HEIGHT"] - $heightWater;
            if (in_array($params["WATERMARK_POSITION"], array(2, 5, 8)))
                $waterLeft = intval($params["WIDTH"] / 2) - intval($widthWater / 2);
            if (in_array($params["WATERMARK_POSITION"], array(3, 6, 9)))
                $waterLeft = $params["WIDTH"] - $widthWater;

            $widthWater--;
            $heightWater--;
            imageCopyResampled($im, $waterMark, $waterLeft, $waterTop, 0, 0, $widthWater, $heightWater, $widthWater, $heightWater);
        }



        switch (strtolower($imgInfo["mime"])) {
            case 'image/gif' :
                @imageGif($im, CHACHE_IMG_PATH . $pathToFile);
                break;
            case 'image/jpeg' : case 'image/pjpeg' :@imageJpeg($im, CHACHE_IMG_PATH . $pathToFile, $params["QUALITY"]);
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

function prent($mas, $show = false)
{
    global $USER;
    if ($USER->IsAdmin() || $show || defined('SHOW_DEBUG') || $_REQUEST['SHOW_DEBUG']) {
        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER['DOCUMENT_ROOT'];
        $dRoot = str_replace('/', "\\", $dRoot);
        $bt['file'] = str_replace($dRoot, '', $bt['file']);
        $dRoot = str_replace("\\", '/', $dRoot);
        $bt['file'] = str_replace($dRoot, '', $bt['file']); ?>
        <div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
            <div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?= $bt['file'] ?>
                [<?= $bt['line'] ?>]
            </div>
            <!--pre><?//=print_r($btEx);?></pre-->
            <pre style='padding:10px;'><?
                print_r($mas) ?></pre>
        </div>
    <? }
}

function includeEditHtmlFile($path, $name, $reset = true)
{
    /* @var $APPLICATION CMain */
    global $APPLICATION;
    global $USER;
    if (!$USER->isAdmin()) {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path) && filesize($_SERVER["DOCUMENT_ROOT"] . $path)) {
            include $_SERVER["DOCUMENT_ROOT"] . $path;
        }
        return;
    }

    if ($_SESSION["SESS_INCLUDE_AREAS"]) {
        echo "<span style='position:relative;'><span class='htmlblockedit' ondblclick='return false' title=\"" . htmlspecialchars($name) . "\" onclick=\"(new BX.CDialog({'content_url':'/bitrix/admin/public_file_edit.php?path=" . urlencode($path) . "&back_url=" . urlencode($APPLICATION->GetCurPageParam("", [], false)) . "&lang=" . LANGUAGE_ID . "&template=EMPTY','width':'770','height':'470'})).Show(); return false;\">" . (($name) ? "{$name}" : "Редактировать") . "</span></span>";
    }

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path) && filesize($_SERVER["DOCUMENT_ROOT"] . $path)) {
        include $_SERVER["DOCUMENT_ROOT"] . $path;
    }
}