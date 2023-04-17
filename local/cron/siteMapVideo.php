<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
header("Content-Type: text/xml");

global $USER;
if (!$USER->IsAuthorized() || !$USER->IsAdmin()) LocalRedirect("/");

$TEST = CIBlockElement::GetList(
    [],
    [
        "IBLOCK_ID"=> IBLOCK_ID_CATALOG,
        "!PROPERTY_VIDEO"=>false
    ],
    false,
    [],
    [
        "ID",
        "NAME",
        "CODE",
        "DATE_ACTIVE_FROM",
        "timestamp_x",
        "DETAIL_PAGE_URL",
        "PROPERTY_VIDEO"
    ]
);
unlink($_SERVER["DOCUMENT_ROOT"]."/sitemap_video.xml");
file_put_contents($_SERVER["DOCUMENT_ROOT"]."/sitemap_video.xml", '<?xml version="1.0" encoding="UTF-8"?>', FILE_APPEND);
file_put_contents($_SERVER["DOCUMENT_ROOT"]."/sitemap_video.xml", '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">', FILE_APPEND);
//echo "<pre><code>";
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">';

while($ob = $TEST->GetNext())
{
    $arFields = $ob;

    $oembedUT = json_decode(file_get_contents("https://youtube.com/oembed?url={$arFields["PROPERTY_VIDEO_VALUE"]}&format=json"));

    $title = "BELWOODDOORS";
    $DESCRIPTON = "BELWOODDOORS";
    $THUMB = "https://belwood.kz/bitrix/templates/general/assets/images/errorpage.jpg";

    if (isset($oembedUT) || is_object($oembedUT)) {
        $title = $oembedUT->title;
        $DESCRIPTON = $oembedUT->author_name.' '.$oembedUT->title;
        $THUMB = $oembedUT->thumbnail_url;
    }

    $data = '<url>';
    $data .= '<loc>https://belwood.kz'.$arFields["DETAIL_PAGE_URL"].'</loc>';
    $data .= '<lastmod>'.DateTime::createFromFormat("d.m.Y G:i:s", $arFields["TIMESTAMP_X"])->format("Y-m-d").'</lastmod>';
    $data .= '<video:video>
    <video:title>'.$title.'</video:title>
    <video:description>'.$DESCRIPTON.'</video:description>
    <video:player_loc>'.$arFields["PROPERTY_VIDEO_VALUE"].'</video:player_loc>
    <video:thumbnail_loc>'.$THUMB.'</video:thumbnail_loc>
    </video:video>';
    $data .= '</url>';
    echo $data;
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/sitemap_video.xml", $data, FILE_APPEND);
}
file_put_contents($_SERVER["DOCUMENT_ROOT"]."/sitemap_video.xml", '</urlset>', FILE_APPEND);
echo '</urlset>';
