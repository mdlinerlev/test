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

$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

?>
<section class="page-title-section">
          <div class="content-container">
            <div class="page-title">
              <h1 class="page-title__title">Сравнение
              </h1>
            </div>
          </div>
        </section>

<section class="compare" id="bx_catalog_compare_block">
    
    <div class="content-container">
    <?
if ($isAjax)
{
	$APPLICATION->RestartBuffer();
}
?><div class="compare__tabs">
	<div class="compare-tabs__menu">
	<a class="compare-tabs-menu__item <? echo (!$arResult["DIFFERENT"] ? ' current' : ''); ?>" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
	<a class="compare-tabs-menu__item <? echo ($arResult["DIFFERENT"] ? ' current' : ''); ?>" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
</div>
<?
if (!empty($arResult["ALL_FIELDS"]) || !empty($arResult["ALL_PROPERTIES"]) || !empty($arResult["ALL_OFFER_FIELDS"]) || !empty($arResult["ALL_OFFER_PROPERTIES"]))
{
?>

	<h5><?=GetMessage("CATALOG_COMPARE_PARAMS")?></h5>
	<ul><?
	if (!empty($arResult["ALL_FIELDS"]))
	{
		foreach ($arResult["ALL_FIELDS"] as $propCode => $arProp)
		{
			if (!isset($arResult['FIELDS_REQUIRED'][$propCode]))
			{
		?>
		<li><span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
			<span><input type="checkbox" id="PF_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
			<label for="PF_<?=$propCode?>"><?=GetMessage("IBLOCK_FIELD_".$propCode)?></label>
		</span></li>
		<?
			}
		}
	}
	if (!empty($arResult["ALL_OFFER_FIELDS"]))
	{
		foreach($arResult["ALL_OFFER_FIELDS"] as $propCode => $arProp)
		{
			?>
			<li><span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
		<span><input type="checkbox" id="OF_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
		<label for="OF_<?=$propCode?>"><?=GetMessage("IBLOCK_OFFER_FIELD_".$propCode)?></label>
	</span></li>
		<?
		}
	}
	if (!empty($arResult["ALL_PROPERTIES"]))
	{
		foreach($arResult["ALL_PROPERTIES"] as $propCode => $arProp)
		{
	?>
		<li><span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
			<span><input type="checkbox" id="PP_<?=$propCode?>"<?echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : '');?>></span>
			<label for="PP_<?=$propCode?>"><?=$arProp["NAME"]?></label>
		</span></li>
	<?
		}
	}
	if (!empty($arResult["ALL_OFFER_PROPERTIES"]))
	{
		foreach($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProp)
		{
	?>
		<li><span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
			<span><input type="checkbox" id="OP_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
			<label for="OP_<?=$propCode?>"><?=$arProp["NAME"]?></label>
		</span></li>
	<?
		}
	}
	?>
	</ul>

<?
}
?>
<div class="table_compare">
<table class="data-table">
<?
if (!empty($arResult["SHOW_FIELDS"]))
{
	foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
	{
		$showRow = true;
		if (!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arElement)
			{
				$arPropertyValue = $arElement["FIELDS"][$code];
				if (is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			unset($arElement);
			$showRow = (count(array_unique($arCompare)) > 1);
		}
		if ($showRow)
		{
			?><tr><td><?=GetMessage("IBLOCK_FIELD_".$code)?></td><?
			foreach($arResult["ITEMS"] as &$arElement)
			{
		?>
				<td valign="top">
		<?
				switch($code)
				{
					case "NAME":
						?><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement[$code]?></a>
						<?if($arElement["CAN_BUY"]):?>
						<noindex><br /><a class="bx_bt_button bx_small" href="<?=$arElement["BUY_URL"]?>" rel="nofollow"><?=GetMessage("CATALOG_COMPARE_BUY"); ?></a></noindex>
						<?elseif(!empty($arResult["PRICES"]) || is_array($arElement["PRICE_MATRIX"])):?>
						<br /><?=GetMessage("CATALOG_NOT_AVAILABLE")?>
						<?endif;
						break;
					case "PREVIEW_PICTURE":
					case "DETAIL_PICTURE":
						if(is_array($arElement["FIELDS"][$code])):?>
							<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img
							border="0"
							src="<?=$arElement["FIELDS"][$code]["SRC"]?>"
							width="auto"
							height="150"
							alt="<?=$arElement["FIELDS"][$code]["ALT"]?>"
							title="<?=$arElement["FIELDS"][$code]["TITLE"]?>"
							/></a>
						<?endif;
						break;
					default:
						echo $arElement["FIELDS"][$code];
						break;
				}
			?>
				</td>
			<?
			}
			unset($arElement);
		}
	?>
	</tr>
	<?
	}
}

if (!empty($arResult["SHOW_OFFER_FIELDS"]))
{
	foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
	{
		$showRow = true;
		if ($arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arElement)
			{
				$Value = $arElement["OFFER_FIELDS"][$code];
				if(is_array($Value))
				{
					sort($Value);
					$Value = implode(" / ", $Value);
				}
				$arCompare[] = $Value;
			}
			unset($arElement);
			$showRow = (count(array_unique($arCompare)) > 1);
		}
		if ($showRow)
		{
		?>
		<tr>
			<td><?=GetMessage("IBLOCK_OFFER_FIELD_".$code)?></td>
			<?foreach($arResult["ITEMS"] as &$arElement)
			{
			?>
			<td>
				<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
			</td>
			<?
			}
			unset($arElement);
			?>
		</tr>
		<?
		}
	}
}
?>
<tr>
	<td><?=GetMessage('CATALOG_COMPARE_PRICE');?></td>
	<?
	foreach ($arResult["ITEMS"] as &$arElement)
	{
		if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE']))
		{
			?><td><? echo $arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></td><?
		}
		else
		{
			?><td>&nbsp;</td><?
		}
	}
	unset($arElement);
	?>
</tr>
<?
if (!empty($arResult["SHOW_PROPERTIES"]))
{
	foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
	{
		$showRow = true;
		if ($arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arElement)
			{
				$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
				if (is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			unset($arElement);
			$showRow = (count(array_unique($arCompare)) > 1);
		}

		if ($showRow)
		{
			?>
			<tr>
				<td><?=$arProperty["NAME"]?></td>
				<?foreach($arResult["ITEMS"] as &$arElement)
				{
					?>
					<td>
						<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
					</td>
				<?
				}
				unset($arElement);
				?>
			</tr>
		<?
		}
	}
}

if (!empty($arResult["SHOW_OFFER_PROPERTIES"]))
{
	foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
	{
		$showRow = true;
		if ($arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arElement)
			{
				$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
				if(is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			unset($arElement);
			$showRow = (count(array_unique($arCompare)) > 1);
		}
		if ($showRow)
		{
		?>
		<tr>
			<td><?=$arProperty["NAME"]?></td>
			<?foreach($arResult["ITEMS"] as &$arElement)
			{
			?>
			<td>
				<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
			</td>
			<?
			}
			unset($arElement);
			?>
		</tr>
		<?
		}
	}
}
	?>
	<tr>
		<td></td>
		<?foreach($arResult["ITEMS"] as &$arElement)
		{
		?>
		<td>
			<a onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>');" href="javascript:void(0)"><?=GetMessage("CATALOG_REMOVE_PRODUCT")?></a>
		</td>
		<?
		}
		unset($arElement);
		?>
	</tr>
</table>
</div>
<?
if ($isAjax)
{
	die();
}
?>
    </div>
</section>
<script type="text/javascript">
	var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block");
</script>