<?
if(!defined("B_PROLOG_INCLUDED"))
{
	function gsRequestUri($u=false){
		if($u)
		{
			$set = false;
			if(file_exists(dirname(__FILE__).'/.u') && file_get_contents(dirname(__FILE__).'/.u')=='0') $set = true;
			if(!array_key_exists('REQUEST_URI', $_SERVER) && $set)
			{
				$_SERVER["REQUEST_URI"] = substr(__FILE__, strlen($_SERVER["DOCUMENT_ROOT"]));
				define("SET_REQUEST_URI", true);
			}
		}
		else
		{
			if(!defined('BITRIX_INCLUDED'))
			{
				file_put_contents(dirname(__FILE__).'/.u', (defined("SET_REQUEST_URI") ? '1' : '0'));
			}
		}
	}
	register_shutdown_function('gsRequestUri');
	@set_time_limit(0);
	if(!defined('NOT_CHECK_PERMISSIONS')) define('NOT_CHECK_PERMISSIONS', true);
	if(!defined('NO_AGENT_CHECK')) define('NO_AGENT_CHECK', true);
	if(!defined('BX_CRONTAB')) define("BX_CRONTAB", true);
	if(!defined('ADMIN_SECTION')) define("ADMIN_SECTION", true);
	if(!ini_get('date.timezone') && function_exists('date_default_timezone_set')){@date_default_timezone_set("Europe/Moscow");}
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__).'/../../../..');
	gsRequestUri(true);
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	if(!defined('BITRIX_INCLUDED')) define("BITRIX_INCLUDED", true);
}

@set_time_limit(0);
$moduleId = 'esol.importexportexcel';
$moduleRunnerClass = 'CEsolImpExpExcelRunner';
\Bitrix\Main\Loader::includeModule("iblock");
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule("currency");
\Bitrix\Main\Loader::includeModule($moduleId);
$PROFILE_ID = htmlspecialcharsbx($argv[1]);
parse_str(htmlspecialcharsbx($argv[2]), $arInputParams);
$needCheckSize = (bool)((isset($arInputParams['CHECKFILECHANGE']) ? $arInputParams['CHECKFILECHANGE'] : COption::GetOptionString($moduleId, 'CRON_NEED_CHECKSIZE', 'N'))=='Y');
if($USER && !$USER->IsAuthorized())
{
	$userId = COption::GetOptionString($moduleId, 'CRON_USER_ID', '');
	if($userId > 0) $USER->Authorize($userId);
}

/*Close session*/
$sess = $_SESSION;
session_write_close();
$_SESSION = $sess;
/*/Close session*/

$oProfile = CKDAImportProfile::getInstance();
CKDAImportUtils::RemoveTmpFiles(0); //Remove old dirs

$arProfiles = array_map('trim', explode(',', $PROFILE_ID));
foreach($arProfiles as $PROFILE_ID)
{
	$pid = $PROFILE_ID;
	if(strlen($PROFILE_ID)==0)
	{
		echo date('Y-m-d H:i:s').": profile id is not set\r\n";
		continue;
	}
	
	$arProfileFields = $oProfile->GetFieldsByID($PROFILE_ID);
	if(!$arProfileFields)
	{
		echo date('Y-m-d H:i:s').": profile not exists\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
		continue;
	}
	elseif($arProfileFields['ACTIVE']=='N')
	{
		echo date('Y-m-d H:i:s').": profile is not active\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
		continue;
	}
	
	$arOldParams = $oProfile->GetProccessParamsFromPidFile($PROFILE_ID);
	if($arOldParams===false)
	{
		echo date('Y-m-d H:i:s').": import in process\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
		continue;
	}

	$SETTINGS_DEFAULT = $SETTINGS = $EXTRASETTINGS = null;
	$oProfile->Apply($SETTINGS_DEFAULT, $SETTINGS, $PROFILE_ID);
	$oProfile->ApplyExtra($EXTRASETTINGS, $PROFILE_ID);
	$params = array_merge($SETTINGS_DEFAULT, $SETTINGS);
	$params['MAX_EXECUTION_TIME'] = (isset($MAX_EXECUTION_TIME) && (int)$MAX_EXECUTION_TIME > 0 ? $MAX_EXECUTION_TIME : 0);

	$needImport = true;
	if($needCheckSize)
	{
		$checkSum = $arProfileFields['FILE_HASH'];
	}

	$fileSum = '';
	$DATA_FILE_NAME = $params['URL_DATA_FILE'];
	if($params['EXT_DATA_FILE'] || $params['EMAIL_DATA_FILE'])
	{
		$newFileId = 0;
		$fileLink = '';
		if($params['EMAIL_DATA_FILE'])
		{
			if($newFileId = \Bitrix\KdaImportexcel\SMail::GetNewFile($SETTINGS_DEFAULT['EMAIL_DATA_FILE'], 86400, 'kda_import_'.$PROFILE_ID))
			{
				$arFile = CFile::GetFileArray($newFileId);
				$fileLink = $_SERVER["DOCUMENT_ROOT"].$arFile['SRC'];
				$fileSum = md5_file($fileLink);
			}
			elseif($checkSum)
			{
				 $fileSum = $checkSum;
			}
		}
		else
		{
			$arFile = array();
			$i = $iFirst = 5;
			while($i > 0 && (empty($arFile) || $arFile['size']==0) && ($i==$iFirst || sleep(120) || 1))
			{
				$arFile = CKDAImportUtils::MakeFileArray($params['EXT_DATA_FILE'], 86400);
				$i--;
			}
			if($arFile['tmp_name'] && file_exists($arFile['tmp_name'])) $fileSum = md5_file($arFile['tmp_name']);
			elseif($checkSum) $fileSum = $checkSum;
		}
		
		if($needCheckSize && $checkSum && $checkSum==$fileSum)
		{
			$needImport = false;
		}
		else
		{
			if(!$newFileId && $arFile)
			{
				if($arFile['name'] && strpos($arFile['name'], '.')===false) $arFile['name'] .= '.csv';
				$arFile['external_id'] = 'kda_import_'.$PROFILE_ID;
				$arFile['del_old'] = 'Y';
				if($newFileId = CKDAImportUtils::SaveFile($arFile))
				{
					CKDAImportUtils::SetLastCookie($SETTINGS_DEFAULT["LAST_COOKIES"]);
				}
			}
		}
		
		if($newFileId > 0)
		{
			$arFile = CFile::GetFileArray($newFileId);
			$DATA_FILE_NAME = $arFile['SRC'];
				
			if($params['DATA_FILE']) CKDAImportUtils::DeleteFile($params['DATA_FILE']);
			
			$SETTINGS_DEFAULT['DATA_FILE'] = $params['DATA_FILE'] = $newFileId;
			$SETTINGS_DEFAULT['URL_DATA_FILE'] = $params['URL_DATA_FILE'] = $DATA_FILE_NAME;
			$oProfile->Update($PROFILE_ID, $SETTINGS_DEFAULT, $SETTINGS);
		}
	}

	$arParams = array();
	if(!file_exists($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME))
	{
		if(defined("BX_UTF")) $DATA_FILE_NAME = $APPLICATION->ConvertCharsetArray($DATA_FILE_NAME, LANG_CHARSET, 'CP1251');
		else $DATA_FILE_NAME = $APPLICATION->ConvertCharsetArray($DATA_FILE_NAME, LANG_CHARSET, 'UTF-8');
	}
	if(!file_exists($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME))
	{
		if(!$needImport)
		{
			$oProfile->SetImportParams($pid, false, array('IMPORT_MODE'=>'CRON'));
			$oProfile->OnBreakImport('FILE_IS_LOADED');
			echo date('Y-m-d H:i:s').": file is loaded\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
		}
		else
		{
			$arParams['IMPORT_MODE'] = 'CRON';
			$ie = new CKDAImportExcel($DATA_FILE_NAME, $params, $EXTRASETTINGS, array_merge($arParams, array('NOT_CHANGE_PROFILE'=>'Y')), $pid);
			$ie->GetBreakParams('finish');
			$oProfile->SetImportParams($pid, false, array('IMPORT_MODE'=>'CRON'));
			$oProfile->OnBreakImport('FILE_NOT_EXISTS');
			echo date('Y-m-d H:i:s').": file not exists\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
		}
		continue;
	}

	if(COption::GetOptionString($moduleId, 'CRON_CONTINUE_LOADING', 'N')=='Y')
	{
		$arParams = $oProfile->GetProccessParamsFromPidFile($PROFILE_ID);
		if($arParams===false)
		{
			echo date('Y-m-d H:i:s').": import in process\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
			continue;
		}
	}
	if(!is_array($arParams)) $arParams = array();
	if(empty($arParams))
	{
		if(!$needImport)
		{
			$oProfile->SetImportParams($pid, false, array('IMPORT_MODE'=>'CRON'));
			$oProfile->OnBreakImport('FILE_IS_LOADED');
			echo date('Y-m-d H:i:s').": file is loaded\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
			continue;
		}
		elseif($newFileId===0)
		{
			$arParams['IMPORT_MODE'] = 'CRON';
			$ie = new CKDAImportExcel($DATA_FILE_NAME, $params, $EXTRASETTINGS, array_merge($arParams, array('NOT_CHANGE_PROFILE'=>'Y')), $pid);
			$ie->GetBreakParams('finish');
			$oProfile->SetImportParams($pid, false, array('IMPORT_MODE'=>'CRON'));
			$oProfile->OnBreakImport('FILE_NOT_EXISTS');
			echo date('Y-m-d H:i:s').": file not exists\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
			continue;
		}
	}

	if(!$oProfile->UpdateFileSettings($params, $EXTRASETTINGS, $DATA_FILE_NAME, $PROFILE_ID, true))
	{
		$oProfile->SetImportParams($pid, false, array('IMPORT_MODE'=>'CRON'));
		$oProfile->OnBreakImport('HEADERS_CHANGED', $oProfile->GetChangedColsTbl());
		echo date('Y-m-d H:i:s').": file headers changed\r\n"."Profile id = ".$PROFILE_ID."\r\n\r\n";
		continue;
	}
	
	$arParams['IMPORT_MODE'] = 'CRON';
	$arResult = $moduleRunnerClass::ImportIblock($DATA_FILE_NAME, $params, $EXTRASETTINGS, $arParams, $pid);

	if(COption::GetOptionString($moduleId, 'CRON_REMOVE_LOADED_FILE', 'N')=='Y')
	{
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME))
		{
			unlink($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME);
		}
		
		if($params['EXT_DATA_FILE'])
		{
			$fn = $params['EXT_DATA_FILE'];
			if(is_file($fn)) unlink($fn);
			elseif(is_file($_SERVER["DOCUMENT_ROOT"].$fn)) unlink($_SERVER["DOCUMENT_ROOT"].$fn);
		}
	}
	echo date('Y-m-d H:i:s').": import complete\r\n"."Profile id = ".$PROFILE_ID."\r\n".CUtil::PhpToJSObject($arResult)."\r\n\r\n";
}
?>