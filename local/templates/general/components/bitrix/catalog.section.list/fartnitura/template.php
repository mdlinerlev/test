<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (!empty($arResult["SECTIONS"])) {
    ?>
    <div class="furniture">
        <div class="product__title">Фурнитура к этой двери</div>
        <div class="furniture-inner">
            <?
            foreach ($arResult["SECTIONS"] as $arSection) {
				if($arSection['ID']==29) continue;
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="furniture__item"
                   id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
                    <div class="furniture__img">
                        <img src="<?= $arSection["PICTURE"]['SRC'] ?>" alt="">
                    </div>
                    <div class="furniture__title">
                        <?= $arSection["NAME"] ?>
                    </div>
                </a>
                <?
            }
            ?>
        </div>
    </div>
    <?
}
?>
