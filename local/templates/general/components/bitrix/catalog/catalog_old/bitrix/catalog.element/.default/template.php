<?php 
defined('B_PROLOG_INCLUDED') or die;
require_once 'functions.php';
?>
<script type='text/javascript'>
	var dataLayer = [];
	dataLayer.push({
	'ecomm_prodid':'[<?php echo $arResult['ID']; ?>]',
	'ecomm_pagetype' : '[product-page]',
	'ecomm_totalvalue' : '[<?php echo $arResult['MIN_PRICE']['VALUE']?>]'
	});
</script>
<?php
$type = $arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE_ENUM_ID'];
$isDoor = in_array($type, array(TYPE_INTERIOR_DOORS, TYPE_EXTERIOR_DOORS));

# js offers
$jsPrices = array();
$jsOffers = array();
$jsOffers[$arResult['ID']] = array();

if(!is_array($arResult['JS_OFFERS'])) {
	$arResult['JS_OFFERS'] = array();
}
foreach($arResult['JS_OFFERS'] as $jsOffer) {
	$tree = array();
	foreach($jsOffer['TREE'] as $key => $val) {
		$tree[] = (int)str_replace('PROP_', '', $key).'_'.$val;
	}
	$jsOffers[$arResult['ID']][$jsOffer['ID']] = $tree;

	# цена
	if($arResult['OFFERS']) {
		foreach($arResult['OFFERS'] as $offer) {
			if($offer['ID'] == $jsOffer['ID']) {
				$jsPrices[$offer['ID']] = $offer['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
			}
		}
	}
}


$complect = array();
$maxQuantity = false;
if($type == TYPE_INTERIOR_DOORS) {
	# макс. кол-во товара
	$maxQuantity = 7;

	# комплектация двери
	$complect = array(
		'BOX' => array(
			'PLURAL' => array('стойка коробки', 'стойки коробки', 'стойек коробок')
		),
		'JAMB' => array(
			'PLURAL' => array('наличник', 'наличника', 'наличников')
		),
		'TRANSOMS' => array(
			'PLURAL' => array('добор', 'добора', 'доборов')
		),
		'BAR' => array(
			'PLURAL' => array('притворная планка', 'пртворные планки', 'притворных планок')
		)
	);
}

/*foreach($complect as $key => $compEl) {
	$delete = true;
	$compRefId = $arResult['PROPERTIES'][$key]['VALUE'];
	if($compRefId) {
		$compRef = CIBlockElement::GetByID($compRefId)->Fetch();
		if($compRef) {
			$compRef['PRICE'] = CCatalogProduct::GetOptimalPrice($compRefId, 1, $USER->GetUserGroupArray());
			$complect[$key]['EL'] = $compRef;
			$delete = false;
		}
	}
	if($delete) {
		unset($complect[$key]);
	}
}*/

$hasComplect = false;
if($arResult['OFFERS']) {
	foreach($arResult['OFFERS'] as &$offer) {
		$offer['COMPLECT'] = $complect;
		foreach($complect as $key => $compEl) {
			$delete = true;
			$compRefId = $offer['PROPERTIES'][$key]['VALUE'];
			if($compRefId) {
				$compRef = CIBlockElement::GetByID($compRefId)->Fetch();
				if($compRef) {
					$compRef['PRICE'] = CCatalogProduct::GetOptimalPrice($compRefId, 1, $USER->GetUserGroupArray());
					$offer['COMPLECT'][$key]['EL'] = $compRef;
					$delete = false;
					$hasComplect = true;
				}
			}
			if($delete) {
				unset($offer['COMPLECT'][$key]);
			}
		}
	}
	unset($offer);
}
?>

<script>
	(function() {
		var offers = <?= json_encode($jsOffers) ?>, k;
		window.jsOffers = window.jsOffers || {};
		for(k in offers) {
			if(offers.hasOwnProperty(k)) {
				window.jsOffers[k] = offers[k];
			}
		}
		window.jsPrices = <?= json_encode($jsPrices) ?>;

		window.getComplectMap = function(count) {
			return {
				BASE: count,
				BOX: count%2 ? (count*3 - Math.floor(count/2)) : Math.round(count*2.5),
				JAMB: count*5,
				TRANSOMS: 0,
				BAR: false
			};
		};

		window.getComplectMapTwoLeaf = function(count) {
			return {
				BASE: 1,
				BOX: count*3,
				JAMB: count*6,
				TRANSOMS: 0,
				BAR: 0
			}
		};
	})();
</script>


<div class="wide detail-product" data-product-id="<?= $arResult['ID'] ?>">

	<section class="product-top">
		<div class="content-container">
			<div class="product-top__title-container">
				<div class="product-top__title-inner">
					<div class="product-top__title-row">
						<h1 class="product-top__title" id="pagetitle"><?= $APPLICATION->ShowTitle(false); ?></h1>
						<div class="product-top__badge-container">
							<?php
							$props = $arResult['PROPERTIES'];
							$topBadges = '';

							if($props['SALELEADER']['VALUE']) {
								$topBadges .= '<div class="product-top__badge product-top__badge--hit">хит</div>';
							}
							if($props['NEWPRODUCT']['VALUE']) {
								$topBadges .= '<div class="product-top__badge product-top__badge--new">новинка</div>';
							}
							if($props['SPECIALOFFER']['VALUE']) {
								$topBadges .= '<div class="product-top__badge product-top__badge--discount">скидка</div>';
							}
							
if($props['SKLAD'] == 'Y') {
								$topBadges .= '<div class="product-top__badge product-top__badge--discount">складская программа</div>';
							}
							echo $topBadges;
							?>
                                                        <?offersIterator($arResult, function($item, $dataString) {
                                                            
                                                            $sklad = $item['PROPERTIES']['SKLAD']['VALUE'];
                                                            if($sklad == 'Y') {
						echo '<div style="padding: 2px 8px; width: 76px;font: 12px/12px Arial, Helvetica, sans-serif;background: #491760" class="product-top__badge product-top__badge--sklad"'.$dataString.'>Складская программа</div>';
					}
                                                            
                                                            
                                                        ?>
                                                      
                                                        <?})?>
                                                </div>
					</div>
				</div>
				<?php
				offersIterator($arResult, function($item, $dataString) {
					$article = $item['PROPERTIES']['ARTICLE']['VALUE'];
					$article = $article ? "Арт. $article" : false;
					if($article) {
						echo '<div class="product-top__number"'.$dataString.'>'.$article.'</div>';
					}
				});
				?>
			</div>
			<div class="product-top__topbar">
				<?php if($type == TYPE_INTERIOR_DOORS) { ?>
                    <div class="product-top__left">
                        <a href="javascript:void(0);" class="product-top__button product-top__button--type product-top__button--single button active"><span>Одностворчатая</span></a>
                        <a href="javascript:void(0);" class="product-top__button product-top__button--type product-top__button--double button"><span>Двухстворчатая</span></a>
                    </div>
				<?php } ?>
				<div class="product-top__right">
					<?php
					offersIterator($arResult, function($item, $dataString) {
						//if($item['CAN_BUY']) {
						if($item['CATALOG_QUANTITY']) {
							echo '<div class="product-top__availability product-top__availability--available"'.$dataString.'>В наличии</div>';
                                                }else{
                                                    echo '<div style="color: #e03629;" class="product-top__availability_not not_product-top__availability--available"'.$dataString.'>Под заказ</div>';
                                                }
					});
					offersIterator($arResult, function($item, $dataString) {
						$compareUrl = $item['COMPARE_URL'].'&ajax_action=Y';
						itc\CUncachedArea::show('productCompareBlock', array('id' => $item['ID'], 'dataString' => $dataString, 'url' => $compareUrl));
					});
					?>
				</div>
			</div>
		</div>
	</section>

	<?php if($isDoor) { ?>
		<?php
		$bgString = '';
		if($arResult['BGS']) {
			$bgImage = CFile::ResizeImageGet($arResult['BGS'][0]['DETAIL_PICTURE'], array(
				'width' => 1600,
				'height' => 635
			), BX_RESIZE_IMAGE_EXACT);
			$bgColor = $arResult['BGS'][0]['PROPERTY_COLOR_VALUE'];

			$bgString = ' style="background-image: url('.$bgImage['src'].'); background-color: #'.$bgColor.';"';
		}
		?>
		<section<?= $bgString ?> class="product-preview">

			<?php
			offersIterator($arResult, function($item, $dataString) use ($arResult, $type) {
				$image = $item['DETAIL_PICTURE']['ID'] ?: $arResult['DETAIL_PICTURE']['ID'];
				if(!$image) {
					return;
				}
				$bigImage = CFile::ResizeImageGet($image, array(
					'width' => 225,
					'height' => 485
				), BX_RESIZE_IMAGE_PROPORTIONAL);
				$smallImage = CFile::ResizeImageGet($image, array(
					'width' => 113,
					'height' => 244
				), BX_RESIZE_IMAGE_PROPORTIONAL);

				$twoLeafImageId = $item['PROPERTIES']['TWO_LEAF_PHOTO']['VALUE'] ?: $arResult['PROPERTIES']['TWO_LEAF_PHOTO']['VALUE'];
				if($twoLeafImageId) {
					$twoLeafImage = CFile::ResizeImageGet($twoLeafImageId, array(
						'width' => 398,
						'height' => 485
					), BX_RESIZE_IMAGE_PROPORTIONAL);
					$twoLeafSmallImage = CFile::ResizeImageGet($twoLeafImageId, array(
						'width' => 199,
						'height' => 244
					), BX_RESIZE_IMAGE_PROPORTIONAL);
				}

				# внешняя сторона
				$innerImage = false;
				if($type == TYPE_EXTERIOR_DOORS) {
					$innerImage = $item['PROPERTIES']['INNER_PHOTO']['VALUE'] ?: $arResult['PROPERTIES']['INNER_PHOTO']['VALUE'];
					if($innerImage) {
						$innerImage = CFile::ResizeImageGet($innerImage, array(
							'width' => 225,
							'height' => 485
						), BX_RESIZE_IMAGE_PROPORTIONAL);
						$innerSmallImage = CFile::ResizeImageGet($innerImage, array(
							'width' => 113,
							'height' => 244
						), BX_RESIZE_IMAGE_PROPORTIONAL);
					}
				}


				?>
				<div class="product-preview__door-container"<?= $dataString ?>>
					<div class="product-preview__big-images">
						<img src="<?= $bigImage['src'] ?>" class="product-preview__door-image one-leaf-image" width="225" height="485">

						<?php if($twoLeafImageId) { ?>
							<img src="<?= $twoLeafImage['src'] ?>" class="product-preview__door-image two-leaf-image hidden" width="398" height="485">
						<?php } ?>

						<?php if($innerImage) { ?>
							<img src="<?= $innerImage['src'] ?>" class="product-preview__door-image inner-image hidden" width="225" height="485">
						<?php } ?>
					</div>
					
					<div class="product-preview__small-images">
						<img src="<?= $smallImage['src'] ?>" class="product-preview__door-image-small one-leaf-image" width="113" height="244">
						<?php if($twoLeafImageId) { ?>
							<img src="<?= $twoLeafSmallImage['src'] ?>" class="product-preview__door-image-small two-leaf-image hidden" width="199" height="244">
						<?php } ?>
						<?php if($innerImage) { ?>
							<img src="<?= $innerSmallImage['src'] ?>" class="product-preview__door-image-small inner-image hidden" width="113" height="244">
						<?php } ?>
					</div>
				</div>
				<?php
			});
			?>
		</section>
	<?php } ?>

	<section class="product">
		<div class="content-container">
<?php
						if (!empty($arResult['PROPERTIES']['VIDEO']['VALUE'])){
						echo '<div class="product_video">';
							CUtil::InitJSCore(array('ajax', 'popup')); ?>
							<script src="https://use.fontawesome.com/461dbb3a4b.js"></script>
							<script>
								BX.ready(function () {

									var Confirmer = new BX.PopupWindow("videoreview", null, {
										content: '<iframe class="yt_player_iframe" width="640" height="480" src="<?=str_replace("watch?v=","embed/",$arResult['PROPERTIES']['VIDEO']['VALUE'])?>?enablejsapi=1" frameborder="0" allowfullscreen id="video"></iframe>',
										closeIcon: {},
										titleBar: {
											content: BX.create("span", {
												html: 'Видео обзор',
												'props': {'className': 'access-title-bar'}
											})
										},
										zIndex: 0,
										offsetLeft: 0,
										offsetTop: 0,
										draggable: {restrict: false},
										overlay: {backgroundColor: 'black', opacity: '10'}, /* затемнение фона */
										events: {
										 //onPopupClose: function(PopupWindow) {
										 //	player.pauseVideo();
										 //}
										},
										buttons: [
										]

									});

								//$('.popup-window-content').css("background-color", "#fff");
								//$('.popup-window-with-titlebar').css("border-style", "solid");
								//$('.popup-window-with-titlebar').css("border-color", "#009e45");
									$('.video_link').on('click',function(){
										Confirmer.show();
										return false;
									});
									$('#videoreview > a').attr('class','fa fa-times');
									$('#videoreview > a').attr('aria-hidden','true');
								//$('#popup-window-overlay-videoreview').attr('style','');
									$('.fa').on('click',function(){
										$('.yt_player_iframe').each(function(){
										  this.contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*')
										});
									});
									var w =$(window).width();
								if(w<=768){
									$('.product_video div').html('<div class="product__title" style="text-align: left;margin-bottom: 0px"><a class="product-title__toggler">Видео обзор</a></div><div class="product-filter__price-tabs toggle-block" style="margin-top: 10px"><iframe class="yt_player_iframe" width="'+(w-30)+'" height="'+(w*0.75)+'" src="<?=str_replace("watch?v=","embed/",$arResult['PROPERTIES']['VIDEO']['VALUE'])?>?enablejsapi=1" frameborder="0" allowfullscreen id="video"></iframe></div>')
								}

								});

							</script>
							<?
						echo '<div><a class="video_link" href="#"><img class="video_img bump" alt="" src="/images/play.png">Видео обзор</a></div></div>';
						}
?>
			<?php if($isDoor) { ?>
				<?php if($arResult['BGS']) { ?>
					<div class="product__view-links">
						<div class="product__title">
							<a class="product-title__toggler">Интерьер</a>
						</div>
						<div class="product-view-links__inner toggle-block">
							<?php foreach($arResult['BGS'] as $key => $bg) {
								$smallImage = CFile::ResizeImageGet($bg['PREVIEW_PICTURE'], array(
									'width' => 100,
									'height' => 70
								), BX_RESIZE_IMAGE_EXACT);

								$bigImage = CFile::ResizeImageGet($bg['DETAIL_PICTURE'], array(
									'width' => 1600,
									'height' => 635
								), BX_RESIZE_IMAGE_EXACT);
								$dataInner = $bg['PROPERTY_INNER_VALUE'] ? ' data-inner="1"' : '';
								?>
								<a<?= $dataInner ?> data-image="<?= $bigImage['src'] ?>" data-color="#<?= $bg['PROPERTY_COLOR_VALUE'] ?>" class="product-view-links__link<?= $key ? '' : ' active' ?>">
									<div style="background-image: url(<?= $smallImage['src'] ?>);" class="product-view-links__image"></div>
                                    <span class="product-view-links__title"><?= $bg['NAME'] ?></span>
								</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php
				$photos = $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'];
				if(is_array($photos) && $photos) {
					?>
					<div class="product__gallery">
						<div class="product__title">
							<a class="product-title__toggler">Фотографии</a>
						</div>
						<div class="product-gallery__inner toggle-block">
							<?php foreach($photos as $photoId) {
								$smallPhoto = CFile::ResizeImageGet($photoId, array(
									'width' => 70,
									'height' => 70
								), BX_RESIZE_IMAGE_EXACT);

								$bigPhoto = CFile::ResizeImageGet($photoId, array(
									'width' => 1000,
									'height' => 800
								), BX_RESIZE_IMAGE_PROPORTIONAL);
								?>
								<a href="<?= $bigPhoto['src'] ?>" class="product-gallery__link">
									<img src="<?= $smallPhoto['src'] ?>" class="product-gallery__image">
								</a>
							<?php } ?>
						</div>
					</div>
					<?php
				}
				?>


				<div class="product__filter">
					<?php
					$propsOrder = array('SIZE',	'COLOR', 'GLASS_COLOR');

					# размеры
					if($arResult['OFFERS'] && $arResult['SKU_PROPS']) {
						foreach($arResult['SKU_PROPS'] as $prop) {
							if($prop['CODE'] == 'SIZE') {
								if($prop['VALUES']) {
									$thisVals = array();
									foreach($prop['VALUES'] as $val) {
										# проверяем, есть ли такое предложение
										foreach ($arResult['JS_OFFERS'] as $offer) {
											if($offer['TREE'] && isset($offer['TREE']['PROP_'.$prop['ID']]) && $offer['TREE']['PROP_'.$prop['ID']] == $val['ID']) {
												$thisVals['_' . $val['ID']] = $val['NAME'];
												break;
											}
										}
									}
									if($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
										?>

										<div class="first-base product-filter__select-container sku-wrapper">
											<label for="product-size" class="product-filter__label">Размер полотна</label>

											<div class="hidden">
												<?php
												foreach($thisVals as $key => $val) {
													echo '<a class="sku-value select-sku-value" href="javascript:void(0);" data-id="'.$prop['ID'].'_'.substr($key, 1).'">'.$val.'</a><br>';
												}
												?>
											</div>

											<select id="product-size" class="product-filter__select not-auto-init product-filter__select--size">
												<?php
												foreach($thisVals as $key => $val) {
													echo '<option value="'.$prop['ID'].'_'.substr($key, 1).'">'.$val.'</option>';
												}
												?>
											</select>
										</div>

										<div class="second-base product-filter__select-container product-filter__select-container--second hidden">
											<label for="product-size2" class="product-filter__label">Второе полотно
											</label>
											<select id="product-size2" class="product-filter__select product-filter__select--size">
											</select>
										</div>

										<?php
									}
								}
								break;
							}
						}
					}

					?>

					<?php if($type == TYPE_EXTERIOR_DOORS) {







						# сторона открывания
						if($arResult['OFFERS'] && $arResult['SKU_PROPS']) {
							foreach($arResult['SKU_PROPS'] as $prop) {
								if($prop['CODE'] == 'SIDE') {
									if($prop['VALUES']) {
										$thisVals = array();
										foreach($prop['VALUES'] as $val) {
											# проверяем, есть ли такое предложение
											foreach ($arResult['JS_OFFERS'] as $offer) {
												if($offer['TREE'] && isset($offer['TREE']['PROP_'.$prop['ID']]) && $offer['TREE']['PROP_'.$prop['ID']] == $val['ID']) {
													$thisVals['_' . $val['ID']] = $val['NAME'];
													break;
												}
											}
										}
										if($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
											?>



											<div class="product-filter__door-open-side">
												<span class="product-filter__label">Сторона открывания</span>
												<div class="product-filter__door-type sku-wrapper">
													<?php
													foreach($thisVals as $key => $val) {
														$key = substr($key, 1);
														if(!in_array($key, array(OPEN_SIDE_LEFT, OPEN_SIDE_RIGHT))) {
															continue;
														}
														?>
														<a data-id="<?= $prop['ID'].'_'.$key ?>" class="sku-value side-type product-filter__type product-filter__type--<?= ($key==OPEN_SIDE_LEFT) ? 'left' : 'right' ?> active"><?= $val ?></a>
														<?
													}
													?>
												</div>
											</div>

											<?php
										}
									}
									break;
								}
							}
						}










						?>

						<!--<div class="product-filter__door-open-side">
							<span class="product-filter__label">Сторона открывания</span>
							<div class="product-filter__door-type">
								<a class="side-type product-filter__type product-filter__type--left active">Левая</a>
								<a class="side-type product-filter__type product-filter__type--right">Правая</a>
							</div>
						</div>-->

					<?php } ?>

					<?php if($arResult['GLASS_REF']) { ?>
						<div class="product-filter__door-type">
							<?php if($arResult['PROPERTIES']['GLASS']['VALUE']) { ?>
								<a href="javascript:void(0);" class="glass-type product-filter__type active">Остекленная</a>
								<a href="<?= $arResult['GLASS_REF']['DETAIL_PAGE_URL'] ?>" class="glass-type product-filter__type">Глухая</a>
							<?php } else { ?>
								<a href="<?= $arResult['GLASS_REF']['DETAIL_PAGE_URL'] ?>" class="glass-type product-filter__type">Остекленная</a>
								<a href="javascript:void(0);" class="glass-type product-filter__type active">Глухая</a>
							<?php } ?>
						</div>
					<?php } ?>

					<?php
					if($arResult['OFFERS'] && $arResult['SKU_PROPS']) {

						$skuMap = array(
							'COLOR' => array('NAME' => 'Цвет полотна', 'CLASS' => 'product-filter__color filter-main-color'),
							'GLASS_COLOR' => array('NAME' => 'Стекло', 'CLASS' => 'product-filter__color product-filter__color--second filter-glass-color'),
							'COLOR_OUT' => array('NAME' => 'Цвет снаружи', 'CLASS' => 'product-filter__color'),
							'COLOR_IN' => array('NAME' => 'Цвет внутри', 'CLASS' => 'product-filter__color product-filter__color--second')
						);

						foreach($skuMap as $skuProp => $sku) {
							foreach($arResult['SKU_PROPS'] as $prop) {
								if($prop['CODE'] == $skuProp) {
									if($prop['VALUES']) {
										$thisVals = array();
										foreach($prop['VALUES'] as $val) {
											# проверяем, есть ли такое предложение
											foreach ($arResult['JS_OFFERS'] as $offer) {
												if($offer['TREE'] && isset($offer['TREE']['PROP_'.$prop['ID']]) && $offer['TREE']['PROP_'.$prop['ID']] == $val['ID']) {
													$thisVals['_' . $val['ID']] = $val['NAME'];
													break;
												}
											}
										}
										if($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
											?>

											<div class="<?= $sku['CLASS'] ?>">
												<div class="product-filter-color__title"><?= $sku['NAME'] ?></div>
												<div class="product-filter-color__inner sku-wrapper">
													<?php
													foreach($thisVals as $key => $val) {
														$colorId = substr($key, 1);
														$color = getColorById($colorId);
														$colorFile = CFile::ResizeImageGet($color['UF_FILE'], array(
															'width' => 23,
															'height' => 23
														), BX_RESIZE_IMAGE_EXACT);
														$colorFile = $colorFile['src'];
														//echo '<a data-id="'.$prop['ID'].'_'.substr($key, 1).'" title="'.$val.'" style="background-image: url('.$colorFile.')" class="sku-value product-filter-color__link"></a>';
echo '<div class="tooltip1"><a data-id="'.$prop['ID'].'_'.substr($key, 1).'" class="sku-value product-filter-color__link" title=""><img src="'.$colorFile.'" alt="'.$val.'" /><span class="tooltiptext">'.$val.'</span></a></div>';
													}
													?>
												</div>
											</div>

											<?php
										}
									}
									break;
								}
							}
						}
					}
					?>

					<?php if($type == TYPE_INTERIOR_DOORS) { ?>
						<div class="product-filter__price-tabs">
							<div class="detail-price-base product-filter-price-tabs__tab active">
								<?php
								offersIterator($arResult, function($item, $dataString) {
									$price = $item['MIN_PRICE']['VALUE_NOVAT'];
									if(trim($item['PROPERTIES']['OLD_PRICE']['VALUE'])) {
										$price = (float)str_replace(',', '.', trim($item['PROPERTIES']['OLD_PRICE']['VALUE']));
									}
									$discountPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
									?>
									<div<?= $dataString ?>>
										<div class="product-filter-price-tabs__title leaf-switch-text" data-oneleaf-text="За полотно" data-twoleaf-text="За два полотна">За полотно</div>
										<div class="product-filter-price-tabs__discount left-base-price" data-base-price="<?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY, true) ?>">
											<?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY, true) ?> руб.
                                                                                        <?//= SaleFormatCurrency($discountPrice*KOEF_OLD_PRICE, MAIN_CURRENCY) ?>
										</div>

										<?php if($price > $discountPrice) { ?>
											<div class="product-filter-price-tabs__base">
												<span class="product-filter-price-tabs__number"><?= SaleFormatCurrency($price, MAIN_CURRENCY, true) ?></span>
												<span class="product-filter-price-tabs__badge">-<?= round((1 - $discountPrice/$price)*100) ?>%</span>
											</div>
										<?php } ?>
									</div>
									<?php
								});
								?>
							</div>
							<?php if(/*$complect*/$hasComplect) {

								?>
								<div class="detail-price-complect product-filter-price-tabs__tab tooltip-link tooltip-top">
									<div class="product-filter-price-tabs__title">За комплект
									</div>
									<?php
									offersIterator($arResult, function($item, $dataString) use ($complectMap) {
										$complect = $item['COMPLECT'];
										$complectPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
										foreach($complect as $key => $el) {
											/*$count = $complectMap[1][$key] ?: 0;*/
											$count = 0;

											$complectPrice += $el['EL']['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] * $count;
										}

										?>
										<div class="product-filter-price-tabs__discount"<?= $dataString ?>>
											<span class="complect-full-price" data-base-price="<?= $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'] ?>">
												<?= SaleFormatCurrency($complectPrice, MAIN_CURRENCY, true); ?> руб.
											</span>
                                                                                    
													
										</div>
										<?php
									});
									?>
									<a class="product-filter-price-tabs__configure">Настроить</a>

									<?php

									$complectMap = array(
										1 => array(
											'BASE' => 1,
											'BOX' => 3,
											'JAMB' => 5,
											'TRANSOMS' => 0
										),
										2 => array(
											'BASE' => 2,
											'BOX' => 3,
											'JAMB' => 6,
											'TRANSOMS' => 0
										)
									);

									$texts = array();
									foreach($complectMap as $complectItem) {
										$text = array();
										$text[] = $complectItem['BASE'].'&nbsp;'.plural($complectItem['BASE'], 'полотно', 'полотна', 'полотен');
										foreach(array_keys($complect) as $key) {
											$plural = $complect[$key]['PLURAL'];
											if(/*$complect[$key]['EL'] && */$complectItem[$key]) {
												$text[] = $complectItem[$key].'&nbsp;'.plural($complectItem[$key], $plural[0], $plural[1], $plural[2]);
											}
										}
										if(sizeof($text) > 1) {
											$lastEl = array_pop($text);
											$text = implode(', ', $text);
											$text .= ' и '.$lastEl;
										}
										else {
											$text = $text[0];
										}
										$texts[] = $text;
									}


									?>
									<div class="tooltip one-leaf">В стандартный комплект входит <?= $texts[0] ?></div>
									<div class="tooltip two-leaf hidden">В стандартный комплект входит <?= $texts[1] ?></div>
								</div>
							<?php } ?>
						</div>

						<?php if(/*$complect*/$hasComplect) { ?>
							<div class="product-filter__complect">

								<?php offersIterator($arResult, function($item, $dataString) { ?>

								<div class="product-filter-complect__inner complect-inner"<?= $dataString ?>>
									<table class="product-filter-complect__table">
										<tr class="product-filter-complect__row">
											<td class="product-filter-complect__cell product-filter-complect__cell--title">Полотно</td>
											<td class="product-filter-complect__cell product-filter-complect__cell--price">
												<?php
												//offersIterator($arResult, function($item, $dataString) {
													$price = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
													echo '<div'.$dataString.'>';
													echo SaleFormatCurrency($price, MAIN_CURRENCY, false);
													echo '</div>';
												//});
												?>
											</td>
											<td class="product-filter-complect__cell product-filter-complect__cell--quantity quantity">
												<div class="quantity__container simple-quantity">
													1
												</div>
											</td>
										</tr>
										<?php foreach($item['COMPLECT'] as $key => $el) {
											//$count = $complectMap[1][$key] ?: 0;
											$count = 0;
											?>
											<tr class="product-filter-complect__row">
												<td class="product-filter-complect__cell product-filter-complect__cell--title"><?= $el['EL']['NAME'] ?></td>
												<td class="product-filter-complect__cell product-filter-complect__cell--price"><?= SaleFormatCurrency($el['EL']['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'], $el['EL']['PRICE']['RESULT_PRICE']['CURRENCY']); ?></td>
												<td class="product-filter-complect__cell product-filter-complect__cell--quantity quantity">
													<div class="quantity__container">
														<label for="quantity-input" class="sr-only">Количество</label>
														<a class="quantity__button quantity__button--minus">-</a>
														<input data-code="<?= $key ?>" type="text" data-id="<?= $el['EL']['ID'] ?>" data-price="<?= $el['EL']['PRICE']['RESULT_PRICE']['DISCOUNT_PRICE'] ?>" value="<?= $count ?>" maxlength="3" class="quantity__input not-auto-init complect-count-input"/>
														<a class="quantity__button quantity__button--plus">+</a>
													</div>
												</td>
											</tr>
										<?php } ?>
									</table>
								</div>

								<?php }); ?>




							</div>
						<?php } ?>
					<?php } else { ?>
						<div class="product-filter__price">
							<div class="product-filter-price__title">Цена</div>
							<?php
							offersIterator($arResult, function($item, $dataString) {
								$price = $item['MIN_PRICE']['VALUE_NOVAT'];
								$discountPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
								?>
								<div<?= $dataString ?>>
									<div class="product-filter-price__discount">
										<span class="complect-full-price" data-base-price="<?= $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'] ?>">
											<?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY, true) ?><br>
                                                                                  
										</span>
									
									</div>

									<?php if($price != $discountPrice) { ?>
										<div class="product-filter-price-tabs__base">
											<span class="product-filter-price-tabs__number"><?= SaleFormatCurrency($price, MAIN_CURRENCY, true) ?></span>
								
										</div>
									<?php } ?>
								</div>
								<?php
							});
							?>

						</div>
					<?php } ?>

					<div class="product-filter__submit">
						<?php
						offersIterator($arResult, function($item, $dataString) {
							?>

							<div class="product-filter-submit__prices"<?= $dataString ?>>
								<span class="total-price product-filter-submit__price"><?= SaleFormatCurrency($item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'], MAIN_CURRENCY, true) ?></span>
                                                        </div>
                                            

							<?php
						});
						?>
						<div class="product-filter-submit__quantity quantity">
							<div class="quantity__container">
								<label for="total-quantity-input" class="sr-only">Количество</label>
								<a class="quantity__button quantity__button--minus">-</a>
								<input<?= $maxQuantity ? ' data-max="'.$maxQuantity.'"' : '' ?> type="text" value="1" id="total-quantity-input" maxlength="5" class="quantity__input not-auto-init"/>
								<a class="quantity__button quantity__button--plus">+</a>
							</div>
						</div>
						<?php
						offersIterator($arResult, function($item, $dataString) {
							//itc\CUncachedArea::show('productCartBlock', array('id' => $item['ID'], 'dataString' => $dataString, 'quantity' => $item['CATALOG_QUANTITY']));
							$class = 'product-filter-submit__button product-filter-submit__button--submit button door-to-cart';

							if($item['CATALOG_QUANTITY']) {
								echo '<!--noindex--><a onclick="yaCounter35453395.reachGoal (\'add_to_cart\'); return true;" rel="nofollow" href="/personal/cart/" data-id="'.$item['ID'].'" class="'.$class.' detail-to-cart"'.$dataString.'>Купить</a><!--/noindex-->';
							}
							else {
								$tooltip = '<div class="tooltip">Средний срок ожидания заказной позиции 30 дней. Пожалуйста, уточняйте срок у оператора</div>';
								echo '<!--noindex--><a rel="nofollow" href="/personal/cart/" data-id="'.$item['ID'].'" class="'.$class.' detail-to-cart tooltip-link tooltip-top"'.$dataString.'>'.$tooltip.'Заказать</a><!--/noindex-->';
							}
						});
						?>
					</div>
				</div>

				<div class="product__buttons">
					<a data-popup="popup--measurement" class="product-buttons__button product-buttons__button--measurement button popup-link"><span>Заказать замер</span>
					</a>
					<a data-popup="popup--callback" class="product-buttons__button product-buttons__button--consulting button popup-link"><span>Консультация</span>
					</a>
					<?php if($type == TYPE_INTERIOR_DOORS) { ?>
						<a data-popup="popup--splitable-door" class="product-buttons__button product-buttons__button--splitable-door button popup-link"><span>Эту дверь можно сделать раздвижной</span>
						</a>
						<a href="/razdvizhnye-dveri/" class="product-buttons__button product-buttons__button--splitable-door-view button"><span>Посмотреть как это будет выглядеть</span>
						</a>
					<?php } ?>
				</div>

			<?php } else { ?>

				<div class="product__gallery-large">
					<?php
					$photos = array();
					if($arResult['DETAIL_PICTURE']['ID']) {
						$photos[] = $arResult['DETAIL_PICTURE']['ID'];
					}
					if($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']) {
						$photos = array_merge($photos, $arResult['PROPERTIES']['MORE_PHOTO']['VALUE']);
					}
					?>
					<?php if($photos) { ?>
						<a class="product-gallery-large__inner">
							<div class="product-gallery-large__container product-gallery-large__container--bottom">
								<div><img class="product-gallery-large__image"/>
								</div>
							</div>
							<div class="product-gallery-large__container product-gallery-large__container--top">
								<div>
									<?php
									$mainImage = CFile::resizeImageGet($photos[0], array(
										'width' => 355,
										'height' => 355
									), BX_RESIZE_IMAGE_PROPORTIONAL);

									?>
									<img src="<?= $mainImage['src'] ?>" class="product-gallery-large__image"/>
								</div>
							</div>
						</a>
						<?php if(sizeof($photos) > 1) { ?>
							<div class="product-gallery-large__previews">
								<?php
								foreach($photos as $key => $photo) {
									$bigImage = CFile::resizeImageGet($photo, array(
										'width' => 355,
										'height' => 355
									), BX_RESIZE_IMAGE_PROPORTIONAL);
									$smallImage = CFile::resizeImageGet($photo, array(
										'width' => 66,
										'height' => 66
									), BX_RESIZE_IMAGE_PROPORTIONAL);
									?>
									<a data-medium="<?= $bigImage['src'] ?>" href="<?= $bigImage['src'] ?>" class="product-gallery-large-previews__item<?= $key ? '' : ' active' ?>">
										<div><img src="<?= $smallImage['src'] ?>" class="product-gallery-large-previews__image"/>
										</div>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } ?>
				</div>

				<div class="product__price">
					<div class="product-price__inner">
						<div class="product-price__block">
							<div class="product-price__title"><?= ($type == TYPE_FLOOR) ? 'Цена за упаковку' : 'Цена' ?></div>
							<?php
							offersIterator($arResult, function($item, $dataString) {
								$price = $item['MIN_PRICE']['VALUE_NOVAT'];
								$discountPrice = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
								?>
								<div<?= $dataString ?>>
									<div class="product-price__discount">
										<span class="complect-full-price" data-base-price="<?= $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'] ?>">
											<?= SaleFormatCurrency($discountPrice, MAIN_CURRENCY, true) ?><br>
                                                                                        <?//= SaleFormatCurrency($discountPrice*KOEF_OLD_PRICE, MAIN_CURRENCY) ?>
										</span>
										<br>
                                                                                
									</div>

									<?php if($price != $discountPrice) { ?>
										<div class="product-price__base">
											<span class="product-price__number"><?= SaleFormatCurrency($price, MAIN_CURRENCY, true) ?></span>
											<span class="product-price__badge">-<?= round((1 - $discountPrice/$price)*100) ?>%</span>
										</div>
									<?php } ?>
								</div>
								<?php
							});
							?>
						</div>

						<?php
						if($type == TYPE_FLOOR && $arResult['PROPERTIES']['BOX_SQUARE']['VALUE']) {
							offersIterator($arResult, function($item, $dataString) use ($arResult) {
								$price = $item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'];
								$mPrice = round($price / $arResult['PROPERTIES']['BOX_SQUARE']['VALUE']);
								?>
								<div class="product-price__block product-price__block--small"<?= $dataString ?>>
									<div class="product-price__title">Цена за м<sup>2</sup></div>
									<div class="product-price__discount">
										<span><?= SaleFormatCurrency($mPrice, MAIN_CURRENCY, true) ?></span>
							
									</div>
								</div>
								<?php
							});
						}
						?>

						<?php
						foreach($arResult['SKU_PROPS'] as $prop) {
							if($prop['CODE'] == 'COLOR') {
								if($prop['VALUES']) {
									$thisVals = array();
									foreach($prop['VALUES'] as $val) {
										# проверяем, есть ли такое предложение
										foreach ($arResult['JS_OFFERS'] as $offer) {
											if($offer['TREE'] && isset($offer['TREE']['PROP_'.$prop['ID']]) && $offer['TREE']['PROP_'.$prop['ID']] == $val['ID']) {
												$thisVals['_' . $val['ID']] = $val['NAME'];
												break;
											}
										}
									}
									if($thisVals && !(sizeof($thisVals) == 1 && key($thisVals) == '_0')) {
										?>

										<div class="product-price__block product-price__block--small">
											<div class="product-price__title">Цвет</div>
											<div class="product-price__colors sku-wrapper">
												<?php
												foreach($thisVals as $key => $val) {
													$colorId = substr($key, 1);
													$color = getColorById($colorId);
													$colorFile = CFile::ResizeImageGet($color['UF_FILE'], array(
														'width' => 23,
														'height' => 23
													), BX_RESIZE_IMAGE_EXACT);
													$colorFile = $colorFile['src'];
													echo '<a data-id="'.$prop['ID'].'_'.substr($key, 1).'" title="'.$val.'" style="background-image: url('.$colorFile.')" class="sku-value product-price__color-link"></a>';
												}
												?>
											</div>
										</div>

										<?php
									}
								}
								break;
							}
						}
						?>
						<div class="product-price__block">
							<div class="product-price__title"><?= ($type == TYPE_FLOOR) ? 'Кол-во упаковок' : 'Кол-во' ?>:</div>
							<div class="product-price__quantity quantity">
								<div class="quantity__container">
									<label for="quantity-input" class="sr-only">Количество</label>
									<a class="quantity__button quantity__button--minus">-</a>
									<input type="text" value="1" id="total-quantity-input" maxlength="5" class="quantity__input"/>
									<a class="quantity__button quantity__button--plus">+</a>
								</div>
							</div>
						</div>

						<?php
						if($type == TYPE_FLOOR && $arResult['PROPERTIES']['BOX_SQUARE']['VALUE']) {
							offersIterator($arResult, function($item, $dataString) use ($arResult) {
								$square = $arResult['PROPERTIES']['BOX_SQUARE']['VALUE'];
								?>
								<div class="product-price__block product-price__block--area"<?= $dataString ?>>
									<label for="product-area-<?= $item['ID'] ?>" class="product-price__title">Площадь:</label>
									<input data-square="<?= $square ?>" value="<?= str_replace('.', ',', $square) ?>" id="product-area-<?= $item['ID'] ?>" class="total-square product-price__input product-price__input--area"/>м<sup>2</sup>
								</div>
								<?php
							});
						}
						?>

						<?php
						offersIterator($arResult, function($item, $dataString) {
							?>
							<div class="product-price__submit"<?= $dataString ?>>
								<div class="product-price-submit__prices">
									<span class="total-price">
										<?= SaleFormatCurrency($item['MIN_PRICE']['DISCOUNT_VALUE_NOVAT'], MAIN_CURRENCY, true) ?>
									</span>
									
                                                                        
								</div>

								<?php itc\CUncachedArea::show('productCartBlock', array('id' => $item['ID'], 'dataString' => $dataString, 'notFilter' => true)); ?>
							</div>
							<?php
						});
						?>

					</div>
				</div>

				<a data-popup="popup--callback" class="product__button product__button--consulting button popup-link"><span>Консультация</span></a>

			<?php } ?>







			<div class="product__info">
				<div class="product-info__description">
					<?php if($arResult['DETAIL_TEXT'] || $arResult['PREVIEW_TEXT']) { ?>
						<div class="product__title">Описание</div>
						<div class="product-info__text"><?= $arResult['DETAIL_TEXT'] ?: $arResult['PREVIEW_TEXT'] ?></div>
					<?php } ?>
				</div>
				<div class="product-info__parameters">
					<div class="product__title">Характеристики
					</div>
					<ul class="product-info-parameters__list">
						<?php
						$dpMap = array(
							'FURNITURE' => array(
								'BOOLEAN' => $isDoor
							),
							'WIDTH' => array(
								'SIZE' => true,
								'NAME' => ($isDoor) ? 'Толщина полотна, см':'Толщина доски, мм'
							)
						);
						foreach($arResult['DISPLAY_PROPERTIES'] as $prop) { ?>
                                     
							<?php
							if($prop['CODE'] == 'BOX_SQUARE') {
                                $prop['VALUE'] = str_replace('.', ',', $prop['VALUE']);
                            }
							if(isset($dpMap[$prop['CODE']])) {
								if($dpMap[$prop['CODE']]['BOOLEAN']) {
									$prop['VALUE'] = $prop['VALUE'] ? 'Да' : 'Нет';
								}
								if($dpMap[$prop['CODE']]['NAME']) {
									$prop['NAME'] = $dpMap[$prop['CODE']]['NAME'];
								}

								if($dpMap[$prop['CODE']]['SIZE']) {
									offersIterator($arResult, function($item, $dataString) {
										$sizes = preg_split('#\s*x\s*#', $item['PROPERTIES']['SIZE']['VALUE']);
										if(sizeof($sizes) == 2) {
											?>

											<li class="product-info-parameters__item"<?= $dataString ?>>
												<div class="product-info-parameters__key">Высота, см</div>
												<div class="product-info-parameters__value"><?= round($sizes[1]/10) ?></div>
											</li>
											<li class="product-info-parameters__item"<?= $dataString ?>>
												<div class="product-info-parameters__key">Ширина, см</div>
												<div class="product-info-parameters__value"><?= round($sizes[0]/10) ?></div>
											</li>

											<?php
										}
									});
								}
							}
							?>
                               <?if ($prop['NAME'] == 'Остекление' && $arResult["IBLOCK_SECTION_ID"] == 35){?>
                                                                                        <li class="product-info-parameters__item">
									<div class="product-info-parameters__key">Исполнение</div>
									<div class="product-info-parameters__value"><?=$prop['VALUE'] != 1 ? 'Глухая' : 'Остекленная';?></div>
								</li>
                               <?php }elseif($prop['VALUE']) { ?>
                                                         
                                                                                        
                                                                                 
                                                                                        
								<li class="product-info-parameters__item">
									<div class="product-info-parameters__key"><?= $prop['NAME'] ?></div>
									<div class="product-info-parameters__value"><?= is_array($prop['VALUE']) ? implode(', ', $prop['VALUE']) : $prop['DISPLAY_VALUE'] ?></div>
								</li>
                                                                              
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<?php
	$addProductsParams = array(
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_SALT" => "",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_CONTROLS" => "Y",
		"ELEMENT_FIELDS" => array(0=>"NAME",1=>"DETAIL_PICTURE",2=>"DETAIL_PAGE_URL",3=>"PREVIEW_PICTURE"),
		"ELEMENT_PROPERTIES" => array(0=>"",1=>"",),
		"FILTER_NAME" => "similarFilter",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "",
		"PAGE_ELEMENT_COUNT" => "18",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(0=>"",1=>"",),
		"SECTION_ID" => array(0=>"30",1=>"35",2=>"36",3=>"22",),
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
		"SORT_FIELD" => "RAND",
		"SORT_FIELD2" => "id",
		"SORT_ORDER" => "asc",
		"SORT_ORDER2" => "desc",
		"URL_404" => "/404.php",
		'HEADER' => 'Сопутствующие товары'
	);


	# сопут. товары
	$sectionIds = array(30, 36, 35, 23, 24, 25);
	$ids =  array();
	foreach($sectionIds as $sectionId) {
		$el = CIBlockElement::GetList(array('RAND' => 'ASC'), array(
			'IBLOCK_ID' => IBLOCK_ID_CATALOG,
			'ACTIVE' => 'Y',
			'SECTION_ID' => $sectionIds,
			'INCLUDE_SUBSECTIONS' => 'Y'
		), false, false, array(
			'ID', 'IBLOCK_ID'
		))->Fetch();

		if($el) {
			$ids[] = $el['ID'];
		}
	}
	if($ids) {
		$GLOBALS['similarFilter'] = array(
			'=ID' => $ids,
			'!=ID' => $arResult['ID']
		);

		$APPLICATION->IncludeComponent(
			"jorique:iblock.element.list",
			"similar",
			array_merge($addProductsParams, array(
				'HEADER' => 'Сопутствующие товары',
'FILTER_NAME' => 'similarFilter',
			)),
			$component,
			array(
				'HIDE_ICONS' => 'Y'
			)
		);
	}

	# промотренные товары

	/** @var RecentHelper $recentHelper */
	$recentHelper = $GLOBALS['recentHelper'];
	$ids = $recentHelper->get();
	if($ids) {
		$GLOBALS['recentHelper'] = array(
			'=ID' => $ids,
			'!=ID' => $arResult['ID']
		);
		$APPLICATION->IncludeComponent(
			"jorique:iblock.element.list",
			"similar",
			array_merge($addProductsParams, array(
				'FILTER_NAME' => 'recentHelper',
				'HEADER' => 'Вы уже смотрели'
			)),
			$component,
			array(
				'HIDE_ICONS' => 'Y'
			)
		);
	}
	?>
</div>

<script type="text/javascript">
	$(function() {
		initDetailProduct();
	});
</script>
<style>
.tooltip1 {
    position: relative;
    display: inline-block;
}


.tooltip1 .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 1px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 20%;
    margin-left: -20px;
    opacity: 0;
    transition: opacity 1s;
}

.tooltip1 .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 20%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.tooltip1:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
	}
.product-filter-color__link img{
    width: 32px;
		height: 32px;		
}
</style>