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

//prnt($arParams);

//prnt($_GET);

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;


if(isset($_GET["F"]))
	$arParams["CACHE_TIME"] = 0;

$IBLOCK_ID = intval($arParams["IBLOCK_ID"]);
$IBLOCK_CLS_ID = intval($arParams["IBLOCK_CLS_ID"]);
$IBLOCK_CLS_PROP = $arParams["IBLOCK_CLS_PROP"];
$arParams["DETAIL_TEMPLATE"]=trim($arParams["DETAIL_TEMPLATE"]);

//постраничная нави
$arParams["NEWS_COUNT"] = intval($arParams["NEWS_COUNT"]);
if($arParams["NEWS_COUNT"]<=0)
	$arParams["NEWS_COUNT"] = 20;

$arNavParams = Array(
	"nPageSize"=>$arParams["NEWS_COUNT"],
	"bShowAll"=>true,
);
$arNavigation = CDBResult::GetNavParams($arNavParams);

//end постраничная нави

if($this->startResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arNavigation)))
{
	if(!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}

	//товары
	$arOrder = Array("name"=>"asc", "sort"=>"asc");
	$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", "DETAIL_PAGE_URL", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER","PROPERTY_".$IBLOCK_CLS_PROP);

	$filtr = array();
	if(isset($_GET["F"])){
		$filtr = array(
			"LOGIC" => "OR",
			array("<=PROPERTY_PRICE" => 1700, "PROPERTY_MATERIAL" => "Дерево, ткань"),
			array("<PROPERTY_PRICE" => 1500, "PROPERTY_MATERIAL" => "Металл, пластик"),
		);
	}

	$arFilter = Array(
		"IBLOCK_ID"=>$IBLOCK_ID,
		"GLOBAL_ACTIVE"=>"Y",
		"ACTIVE"=>"Y" ,
		"CHECK_PERMISSIONS" => "Y",
		"!PROPERTY_".$IBLOCK_CLS_PROP=>false,
		$filtr
	);



	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	$res->SetUrlTemplates($arParams["DETAIL_TEMPLATE"]);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();

		$arButtons = CIBlock::GetPanelButtons(
			$IBLOCK_ID,
			$arFields["ID"],
			0,
			array("SECTION_BUTTONS"=>false, "SESSID"=>false)
		);

		$arItems[$arFields["ID"]]["IBLOCK_ID"] = $IBLOCK_ID;
		$arItems[$arFields["ID"]]["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arItems[$arFields["ID"]]["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

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
	$allProdID = array();

	foreach($arItems as $items){
		if($items["PROPERTY_PRICE"]<$min){
			$arResult["MIN"] = $items["PROPERTY_PRICE"];
			$min = $items["PROPERTY_PRICE"];
		}
		if($items["PROPERTY_PRICE"]>$max) {
			$arResult["MAX"] = $items["PROPERTY_PRICE"];
			$max = $items["PROPERTY_PRICE"];
		}
		$allProdID = array_merge($allProdID,$items["PROPERTY_PROD"]);
	}

	$prodID = array_unique($allProdID);

	//классификатор
	$arSelect = Array("ID", "NAME");

	$arFilter = Array(
		"IBLOCK_ID"=>IntVal($IBLOCK_CLS_ID),
		"ACTIVE_DATE"=>"Y",
		"ACTIVE"=>"Y",
		"ID" => $prodID,
		"CHECK_PERMISSIONS" => "Y"
	);



	$res = CIBlockElement::GetList(Array(), $arFilter, false, $arNavParams, $arSelect);

	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arResult["ITEMS"][] = $arFields;
	}
	$arResult["NAV_STRING"] = $res->GetPageNavStringEx($navComponentObject, "", "", true); //постраничная



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
