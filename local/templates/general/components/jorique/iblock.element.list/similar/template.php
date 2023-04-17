<?php
if(!$arResult['ITEMS']) {
	return;
}

CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');
?>

<section class="items-slider items-slider--similar">
	<div class="content-container">
		<div class="items-slider__title-container">
			<div class="items-slider__title"><?= $arParams['HEADER'] ?></div>
		</div>
		<div class="items-slider__slider">
			<?php foreach($arResult['ITEMS'] as $item) {
				?>
				<div class="catalog-item-detail">
					<div class="catalog-item__inner">
						<div class="catalog-item__image-container-detail">
							<div>
								<a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="catalog-item__image-link">
									<?php
									offersIterator($item, function($offer) use ($item) {
										$image = $offer['DETAIL_PICTURE'] ?: $item['DETAIL_PICTURE'];
										if($image) {
											$image = CFile::ResizeImageGet($image, array(
												'width' => 130,
												'height' => 280
											), BX_RESIZE_IMAGE_PROPORTIONAL);
											echo '<img data-src="'.$image['src'].'"  src="'.SITE_TEMPLATE_PATH.'/preload.svg"  class="catalog-item__image">';
										}
									});
									?>
								</a>
							</div>
						</div>
						<div class="catalog-item__title-container">
							<a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="catalog-item__title"><?= $item['NAME'] ?></a>
						</div>
						<div class="catalog-item__price">
							<?php
							offersIterator($item, function($item) use ($USER) {
								$price = CCatalogProduct::GetOptimalPrice($item['ID'], 1, $USER->GetUserGroupArray());
								$price = $price['RESULT_PRICE'];
								?>
								<div class="catalog-item-price__discount"><?= SaleFormatCurrency($price['DISCOUNT_PRICE'], MAIN_CURRENCY) ?></div>
								<?php if($price['BASE_PRICE'] != $price['DISCOUNT_PRICE']) { ?>
									<div class="catalog-item-price__base">
										<span class="catalog-item-price__number"><?= SaleFormatCurrency($price['BASE_PRICE'], MAIN_CURRENCY) ?></span>
										<span class="catalog-item-price__badge">-<?= $price['PERCENT'] ?>%</span>
									</div>
								<?php } ?>
								<?php
							});
							?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
