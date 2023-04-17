<tr valign="top" class="heading">
    <td colspan="2">Значения списка:</td>
</tr>
<tr>
    <td colspan="2" align="center">
        <table class="internal" id="list-tbl" width="100%"  style="margin: 0 auto;">
            <tbody>
            <tr class="heading">
                <td>Значение</td>
                <td>Человеко-понятное название</td>
                <td>Сортировка</td>
                <td>Подсказка (путь к файлу)</td>
            </tr>
            <?
            $values = $settings["VALUES"]? : array();

            for ($i = 0; $i < 10; $i++) {
                $values[] = array("ID" => "", "NAME" => "", "SORT" => 500);
            }
            ?>
            <? foreach ($values as $key => $value): ?>
                <tr>
                    <td width="30%"><input type="text" name=<?= $strHTMLControlName["NAME"] ?>[VALUES][<?= $key ?>][ID]"  value="<?= $value["ID"] ?>"  style="width:90%"></td>
                    <td width="50%"><input type="text" name=<?= $strHTMLControlName["NAME"] ?>[VALUES][<?= $key ?>][NAME]"   value="<?= $value["NAME"] ?>"  style="width:90%"></td>
                    <td width="30%"><input type="text" name=<?= $strHTMLControlName["NAME"] ?>[VALUES][<?= $key ?>][SORT]"  value="<?= $value["SORT"] ?>"  style="width:90%"></td>
                    <td width="30%"><input type="text" name=<?= $strHTMLControlName["NAME"] ?>[VALUES][<?= $key ?>][HINT]"  value="<?= $value["HINT"] ?>"  style="width:90%"></td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </td>
</tr>
