<?php

class JoriqueIblockElementList extends CBitrixComponent {

	private $_nav = null;

	/**
	 * Устанавливает дополнительные параметры вызова компонента
	 */
	public function setParams() {
		$params = &$this->arParams;

		if(!$params['IBLOCK_ID']) {
			ShowError('Не указан ID инфоблока');
		}

		# поля раздела
		is_array($params['SECTION_FIELDS']) or $params['SECTION_FIELDS'] = array();
		is_array($params['SECTION_USER_FIELDS']) or $params['SECTION_USER_FIELDS'] = array();
		in_array('ID', $params['SECTION_FIELDS']) or $params['SECTION_FIELDS'][] = 'ID';
		in_array('IBLOCK_ID', $params['SECTION_FIELDS']) or $params['SECTION_FIELDS'][] = 'IBLOCK_ID';

		# поля элементов
		is_array($params['ELEMENT_FIELDS']) or $params['ELEMENT_FIELDS'] = array();
		is_array($params['ELEMENT_PROPERTIES']) or $params['ELEMENT_PROPERTIES'] = array();
		in_array('ID', $params['ELEMENT_FIELDS']) or $params['ELEMENT_FIELDS'][] = 'ID';
		in_array('IBLOCK_ID', $params['ELEMENT_FIELDS']) or $params['ELEMENT_FIELDS'][] = 'IBLOCK_ID';
		
		# навигация
		$params['DISPLAY_TOP_PAGER'] = $params['DISPLAY_TOP_PAGER']=='Y';
		$params['DISPLAY_BOTTOM_PAGER'] = $params['DISPLAY_BOTTOM_PAGER']!='N';
		$params['PAGER_TITLE'] = trim($params['PAGER_TITLE']);
		$params['PAGER_SHOW_ALWAYS'] = $params['PAGER_SHOW_ALWAYS']=='Y';
		$params['PAGER_TEMPLATE'] = trim($params['PAGER_TEMPLATE']);
		$params['PAGER_DESC_NUMBERING'] = $params['PAGER_DESC_NUMBERING']=='Y';
		$params['PAGER_DESC_NUMBERING_CACHE_TIME'] = intval($params['PAGER_DESC_NUMBERING_CACHE_TIME']);
		$params['PAGER_SHOW_ALL'] = $params['PAGER_SHOW_ALL']=='Y';
		
		# остальное
		$params['ADD_SECTIONS_CHAIN'] = $params['ADD_SECTIONS_CHAIN']=='Y';

		if(!$params['URL_404']) {
			ShowError('Не указана 404 страница');
		}
	}

	/**
	 * Возвращает массив для сортировки элементов
	 * @return array
	 */
	public function getSortArray() {
		return array(
			$this->arParams['SORT_FIELD'] => $this->arParams['SORT_ORDER'],
			$this->arParams['SORT_FIELD2'] => $this->arParams['SORT_ORDER2']
		);
	}

	/**
	 * Массив навигации для выборки элементов
	 * @return array
	 */
	public function getNavArray() {
		if($this->_nav === null) {
			$params = &$this->arParams;
			if($params['DISPLAY_TOP_PAGER'] || $params['DISPLAY_BOTTOM_PAGER']) {
				$this->_nav = array(
					'nPageSize' => $params['PAGE_ELEMENT_COUNT'],
					'bDescPageNumbering' => $params['PAGER_DESC_NUMBERING'],
					'bShowAll' => $params['PAGER_SHOW_ALL'],
				);
			}
			else {
				$this->_nav = array(
					'nTopCount' => $params['PAGE_ELEMENT_COUNT'],
					'bDescPageNumbering' => $params['PAGER_DESC_NUMBERING'],
				);
			}
		}
		return $this->_nav;
	}

	/**
	 * Массив навигации для кеша
	 * @return array|bool
	 */
	public function getNavForCache() {
		$params = &$this->arParams;
		if($params['DISPLAY_TOP_PAGER'] || $params['DISPLAY_BOTTOM_PAGER']) {
			$nav = $this->getNavArray();
			$dbResult = new CDBResult;
			$nc = $dbResult->GetNavParams($nav);
			if ($nc['PAGEN'] == 0 && $params['PAGER_DESC_NUMBERING_CACHE_TIME'] > 0) {
				$params['CACHE_TIME'] = $params['PAGER_DESC_NUMBERING_CACHE_TIME'];
			}
		}
		else {
			$nc = false;
		}
		return $nc;
	}

	/**
	 * Устанавливает 404 страницу
	 */
	public function set404() {
		$params = &$this->arParams;
		AddEventHandler('main', 'OnEpilog', function() use ($params) {
			if(defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION')) {
				global $APPLICATION;
				global $USER;
				$APPLICATION->RestartBuffer();
				require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
				require $_SERVER['DOCUMENT_ROOT'].$params['URL_404'];
				require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
			}
		});
		defined('ERROR_404') or define('ERROR_404', 'Y');
	}
}