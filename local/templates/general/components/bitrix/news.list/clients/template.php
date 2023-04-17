<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
?>
<section class="index-brands">
    <div class="content-container container-gray">
        <a href="<?=SITE_DIR?>about/realizovannye-proekty/" class="container-gray__title">
            Наши проекты
        <p class="container-gray__subtitle">Последние реализованные проекты под ключ</p>
        </a>
<!--        <div class="index-brands__slider">
            <?/* foreach ($arResult["ITEMS"] as $arItem): */?>
                <?/*
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                */?>
                <div class="index-brands__item">
                    <a href="javascript:void(0)" class="index-brands__link"><img
                                src="<?/*= SITE_TEMPLATE_PATH */?>/preload.svg"
                                data-src="<?/*= $arItem["PREVIEW_PICTURE"]["SRC"] */?>" class="index-brands__image"/>
                    </a>
                </div>
            <?/* endforeach; */?>
        </div>-->
        <div class="projects">
            <div class="project__box project__box--real project__box-slider">
                <a href="<?=SITE_DIR?>about/realizovannye-proekty/" class="our_project">
                    <div class="our_project__img"><img class="our_project__image" src="<?= SITE_TEMPLATE_PATH ?>/assets/images/best_project.png" alt=""></div>
                    <div class="our_project__title">
                        Средняя школа в городе Калуга
                    </div>
                </a>
                <a href="<?=SITE_DIR?>about/realizovannye-proekty/" class="our_project">
                    <div class="our_project__img"><img class="our_project__image" src="<?= SITE_TEMPLATE_PATH ?>/assets/images/best_project.png" alt=""></div>
                    <div class="our_project__title">
                        Средняя школа в городе Калуга
                    </div>
                </a>
                <a href="<?=SITE_DIR?>about/realizovannye-proekty/" class="our_project">
                    <div class="our_project__img"><img class="our_project__image" src="<?= SITE_TEMPLATE_PATH ?>/assets/images/best_project.png" alt=""></div>
                    <div class="our_project__title">
                        Средняя школа в городе Калуга
                    </div>
                </a>
            </div>
        </div>
        
    </div>
</section>
