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
$IBLOCK_CLS_ID = intval($arParams["IBLOCK_CLS_ID"]);
$IBLOCK_CLS_PROP = $arParams["IBLOCK_CLS_PROP"];
$arParams["DETAIL_TEMPLATE"]=trim($arParams["DETAIL_TEMPLATE"]);

if($this->startResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	//классификатор
	$arSelect = Array("ID", "NAME");
	$arFilter = Array("IBLOCK_ID"=>IntVal($IBLOCK_CLS_ID), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "CHECK_PERMISSIONS" => "Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arResult["ITEMS"][] = $arFields;
	}


	//товары
	$arOrder = Array("name"=>"asc", "sort"=>"asc");
	$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", "DETAIL_PAGE_URL", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER","PROPERTY_".$IBLOCK_CLS_PROP);
	$arFilter = Array("IBLOCK_ID"=>IntVal($IBLOCK_ID), "GLOBAL_ACTIVE"=>"Y", "ACTIVE"=>"Y" , "CHECK_PERMISSIONS" => "Y", "!PROPERTY_".$IBLOCK_CLS_PROP=>false);
	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	$res->SetUrlTemplates($arParams["DETAIL_TEMPLATE"]);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arItems[$arFields["ID"]]["ID"] = $arFields["ID"];
		$arItems[$arFields["ID"]]["NAME"] = $arFields["NAME"];
		$arItems[$arFields["ID"]]["PROPERTY_PRICE"] = $arFields["PROPERTY_PRICE_VALUE"];
		$arItems[$arFields["ID"]]["PROPERTY_MATERIAL"] = $arFields["PROPERTY_MATERIAL_VALUE"];
		$arItems[$arFields["ID"]]["PROPERTY_ARTNUMBER"] = $arFields["PROPERTY_ARTNUMBER_VALUE"];
		$arItems[$arFields["ID"]]["DETAIL_PAGE_URL"] = $arFields["DETAIL_PAGE_URL"];
		$arItems[$arFields["ID"]]["PROPERTY_PROD"][] = $arFields["PROPERTY_PROD_VALUE"];
	}


	$f_key = reset(array_keys($arItems));
	$min = $arItems[$f_key]["PROPERTY_PRICE"];
	$max = $arItems[$f_key]["PROPERTY_PRICE"];

	foreach($arItems as $items){
		if($items["PROPERTY_PRICE"]<$min){
			$arResult["MIN"] = $items["PROPERTY_PRICE"];
			$min = $items["PROPERTY_PRICE"];
		}
		if($items["PROPERTY_PRICE"]>$max) {
			$arResult["MAX"] = $items["PROPERTY_PRICE"];
			$max = $items["PROPERTY_PRICE"];
		}
	}

	foreach($arResult["ITEMS"] as &$arItem){

		foreach($arItems as $arI){
			if(in_array($arItem["ID"],$arI["PROPERTY_PROD"])){
				$arItem["PROD"][]= $arI;
			}
		}
	}


	foreach($arResult["ITEMS"] as $k=>$arItm){
		if(!$arItm["PROD"]) {
			unset($arResult["ITEMS"][$k]);
		}
	}


	$arResult["CNT"] = count($arResult["ITEMS"]);

	$this->setResultCacheKeys(array(
		"CNT"
	));

	$this->includeComponentTemplate();
}
$APPLICATION->SetTitle("Разделов: ".$arResult["CNT"]);