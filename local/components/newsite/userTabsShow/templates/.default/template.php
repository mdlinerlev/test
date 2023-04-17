<?
if (empty($arResult["TABS"])) {
    return;
}
?>
<div class="main_slider_product content-container">
    <div class="tabset">
        <? foreach (array_values($arResult["TABS"]) as $indexTab => $tabParams): ?>
        <input type="radio" name="tabset" id="tab_<?= $tabParams["HASH"] ?>" aria-controls="<?= $tabParams["HASH"] ?>"<?= empty($indexTab) ? " checked" : "" ?>>
        <label for="tab_<?= $tabParams["HASH"] ?>"><?= $tabParams["NAME"] ?></label>
        <? endforeach; ?>

        <div class="tab-panels">
            <? foreach (array_values($arResult["TABS"]) as $indexTab => $tabParams): ?>
            <section id="<?= $tabParams["HASH"] ?>" class="tab-panel">
                <? if (!$USER->isAdmin() && !$tabParams["INCLUDE_FILE"]): ?>
                    <?= $tabParams["CONTENT"] ?>
                <? else: ?>
                    <? $component->includeEditHtmlFile($tabParams["INCLUDEFILE"], $tabParams["NAME"]); ?>
                <? endif; ?>
            </section>
            <? endforeach;?>
        </div>
    </div>
</div>
