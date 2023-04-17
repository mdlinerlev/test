<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);?>

<?if($arResult['ITEMS']):?>
    <?$obParser = new CTextParser;?>
    <div class="inst">
        <div class="content-container">
            <div class="inst-wrp">
                <div class="inst-info">
                    <div class="inst-info__wrp">
                        <div class="inst-info__top">
                            <img src="/images/inst-logo.svg" alt="">
                            <div class="inst-info__top-text">
                                <div class="name"><?=$arResult['TITLE']?></div>
                                <a href="https://www.instagram.com/<?=$arResult['USER']['username']?>/" target="_blank" class="link"><?=GetMessage('INSTAGRAM_LINK_TITLE');?></a>
                            </div>
                        </div>
                        <div class="inst-info__bottom">
                            <div class="zag"><?=GetMessage('INSTAGRAM_INFO_BOTTOM');?></div>
                            <img src="/images/right-arrow.svg" alt="">
                        </div>
                    </div>

                </div>
                <div class="inst-img">
                    <?foreach($arResult['ITEMS'] as $arItem):?>
                        <?$arItem['IMAGE'] = $arItem['thumbnail_url'] ? $arItem['thumbnail_url'] : $arItem['media_url'];?>
                        <img class="lazy"
                             src="<?= SITE_TEMPLATE_PATH ?>/preload.svg"
                             data-src="<?=$arItem['IMAGE'];?>"
                             width="190"
                             height="190"
                             title="<?=($obParser->html_cut($arItem['caption'], $arResult['TEXT_LENGTH']))?>"
                             alt="<?=($obParser->html_cut($arItem['caption'], 20))?>">
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </div>
<?endif;?>