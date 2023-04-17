<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="wrap_sitemap">
    <ul id="tree" class="tree-first">
        <? foreach ($arResult["MAPS"] as $map1): ?>
            <? if ($map1["STATIC"] == false):
                $catalog = "";
                if($map1["URL"]=="/catalog/"){
                    $catalog = "catalog";
                }
                ?>
                <li class="<?=$catalog?>"><a class="level-1" href="<?= $map1["URL"] ?>"><?= $map1["NAME"] ?></a>
                    <? if ($map1["CHILD"]): ?>
                        <ul><? foreach ($map1["CHILD"] as $map2): ?>
                                <li><a href="<?= $map2["URL"] ?>"><?= $map2["NAME"] ?></a>
                                    <? if ($map2["CHILD"]): ?>
                                        <ul><? foreach ($map2["CHILD"] as $map3): ?>
                                                <li><a href="<?= $map3["URL"] ?>"><?= $map3["NAME"] ?></a>
                                                    <? if ($map3["CHILD"]): ?>
                                                        <ul><? foreach ($map3["CHILD"] as $map4): ?>
                                                                <li>
                                                                    <a href="<?= $map4["URL"] ?>"><?= $map4["NAME"] ?></a>
                                                                </li>
                                                            <? endforeach; ?>
                                                        </ul>
                                                    <? endif; ?>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    <? endif; ?>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <? endif; ?>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>

    <ul id="tree2" class="tree-last">
        <? foreach ($arResult["MAPS"] as $map1): ?>
            <? if ($map1["STATIC"] == true): ?>
                <li><a class="level-1" href="<?= $map1["URL"] ?>"><?= $map1["NAME"] ?></a>
                    <? if ($map1["CHILD"]): ?>
                        <ul><? foreach ($map1["CHILD"] as $map2): ?>
                                <li><a href="<?= $map2["URL"] ?>"><?= $map2["NAME"] ?></a>
                                    <? if ($map2["CHILD"]): ?>
                                        <ul><? foreach ($map2["CHILD"] as $map3): ?>
                                                <li><a href="<?= $map3["URL"] ?>"><?= $map3["NAME"] ?></a>
                                                    <? if ($map3["CHILD"]): ?>
                                                        <ul><? foreach ($map3["CHILD"] as $map4): ?>
                                                                <li>
                                                                    <a href="<?= $map4["URL"] ?>"><?= $map4["NAME"] ?></a>
                                                                </li>
                                                            <? endforeach; ?>
                                                        </ul>
                                                    <? endif; ?>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    <? endif; ?>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <? endif; ?>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>
</div>

