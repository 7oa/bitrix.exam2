<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

use Bitrix\Main\Loader;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$IBLOCK_ID = intval($arParams["IBLOCK_ID"]);
$IBLOCK_NEWS_ID = intval($arParams["IBLOCK_NEWS_ID"]);
$IBLOCK_NEWS_CODE = intval($arParams["IBLOCK_NEWS_CODE"]);

if($this->startResultCache(false, false))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	//новости
	$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
	$arFilter = Array("IBLOCK_ID"=>IntVal($IBLOCK_NEWS_ID), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		//prnt($arFields);
		$arResult["ITEMS"][] = $arFields;
	}


	//разделы каталога
	$arSelect = Array("ID", "NAME", $IBLOCK_NEWS_CODE);
	$arFilter = Array("IBLOCK_ID"=>IntVal($IBLOCK_ID), "GLOBAL_ACTIVE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arSectionsList[] = $arFields;
	}

	foreach($arResult["ITEMS"] as $key=>$arItems){
		foreach($arSectionsList as $arSect){
			if(in_array($arItems["ID"],$arSect["UF_NEWS_LINK"])){
				$arResult["ITEMS"][$key]["SECT_ID"][$arSect["ID"]]=$arSect["ID"];
				$arResult["ITEMS"][$key]["SECT_NAME"][$arSect["ID"]]=$arSect["NAME"];
			}
		}
	}

	//товары
	foreach ($arResult["ITEMS"] as &$arItems){
		$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER");
		$arFilter = Array("IBLOCK_ID"=>IntVal($IBLOCK_ID), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "SECTION_ID"=>$arItems["SECT_ID"]);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$mas[$arFields["ID"]] = $arFields["NAME"];
			$arItems["SECT_ITEMS"][] = $arFields;
		}
	}
	$arResult["CNT"] = count($mas);



	$this->setResultCacheKeys(array(
		"CNT"
	));
	$this->includeComponentTemplate();
}
$APPLICATION->SetTitle("В каталоге товаров представлено товаров: ".$arResult["CNT"]);