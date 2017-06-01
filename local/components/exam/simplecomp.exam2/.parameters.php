<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"IBLOCK_CLS_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_CLS_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"DETAIL_TEMPLATE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_DETAIL_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"IBLOCK_CLS_PROP" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_CLS_PROP"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);