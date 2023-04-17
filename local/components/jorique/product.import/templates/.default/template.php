<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

echo $arResult['MESSAGES'];
?>
<?if(empty($_FILES)) {?>
    <form method="post" enctype="multipart/form-data" class="goodsImport">
        <h3>Выгрузка товаров</h3>
        <input type="file" name="goodsXml">
        <input type="submit" name="xmlSubmit" value="<?/*Выгрузить товары*/?>Загрузить файл и запустить выгрузку">
    </form>
<?}?>