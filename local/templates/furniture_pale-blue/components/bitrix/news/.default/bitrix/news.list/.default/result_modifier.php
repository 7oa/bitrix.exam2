<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arParams["SPECIALDATE"]=="Y"){
	$arResult["F_DATE"] = $arResult["ITEMS"][0]["ACTIVE_FROM"];
	$cp = $this->GetComponent();
	$cp->SetResultCacheKeys(array("F_DATE"));
}
?>
