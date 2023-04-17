<?
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("ResizeImgUP", "OnBeforeIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("ResizeImgUP", "OnBeforeIBlockElementAddHandler"));

AddEventHandler('main', 'OnEpilog', 'check404Error', 1);

AddEventHandler('iblock', 'OnAfterIBlockElementAdd', 'setTwoLeafImage');
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'setTwoLeafImage');
function setTwoLeafImage(&$fields) {
	if(in_array($fields['IBLOCK_ID'], array(IBLOCK_ID_CATALOG, IBLOCK_ID_OFFERS))) {
		$iblockId = $fields['IBLOCK_ID'];
		$el = CIBlockElement::GetList(false, array(
			'IBLOCK_ID' => $iblockId,
			'ID' => $fields['ID'],
			'PROPERTY_TWO_LEAF_PHOTO' => false,
			'PRODUCT_TYPE' => TYPE_INTERIOR_DOORS,
			'!DETAIL_PICTURE' => false
		), false, false, array(
			'ID', 'IBLOCK_ID', 'DETAIL_PICTURE'
		))->Fetch();
		if($el) {
			$path = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($el['DETAIL_PICTURE']);

			$imagine = new \Imagine\Gd\Imagine;

			$jambWidth = 25; # ширина наличника

			$singleImage = $imagine->open($path);
			$singleSize = $singleImage->getSize();
			$singleImage->crop(
				new \Imagine\Image\Point($jambWidth, 0),
				new \Imagine\Image\Box($singleSize->getWidth() - $jambWidth, $singleSize->getHeight())
			);
			$singleSize = $singleImage->getSize();

			$palette = new \Imagine\Image\Palette\RGB();
			$color = $palette->color(array(0,0,0), 0);

			$doubleSize = new \Imagine\Image\Box($singleSize->getWidth()*2, $singleSize->getHeight());

			$doubleImage = $imagine->create($doubleSize, $color);
			$doubleImage->paste($singleImage, new \Imagine\Image\Point($singleSize->getWidth(), 0));
			$doubleImage->paste($singleImage->flipHorizontally(), new \Imagine\Image\Point(0, 0));

			# сохраняем в темповый файл
			$stream = tmpfile();
			$tmpName = stream_get_meta_data($stream);
			$tmpName = $tmpName['uri'];

			$doubleImage->save($tmpName, array(
				'format' => 'png'
			));

			CIBlockElement::SetPropertyValuesEx($el['ID'], $iblockId, array(
				'TWO_LEAF_PHOTO' => CFile::MakeFileArray($tmpName)
			));
		}
	}
}