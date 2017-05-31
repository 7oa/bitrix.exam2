<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent("exam:simplecomp.exam", "", Array(
	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"IBLOCK_ID" => "2",	// ID инфоблока с каталогом товаров
		"IBLOCK_NEWS_CODE" => "UF_NEWS_LINK",	// Код пользовательского свойства разделов каталога, в котором хранится привязка к новостям
		"IBLOCK_NEWS_ID" => "1",	// ID инфоблока с новостями
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>