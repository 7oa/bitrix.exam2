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
		"IBLOCK_NEWS_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_NEWS_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"IBLOCK_NEWS_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_NEWS_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);