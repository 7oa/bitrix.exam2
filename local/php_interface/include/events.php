<?
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("ProductsDeactivate", "OnBeforeIBlockElementUpdateHandler"));

class ProductsDeactivate
{
	function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{
		if($arFields["IBLOCK_ID"]==2){
			$elementID = $arFields["ID"];

			if(CModule::IncludeModule("iblock")){
				$res = CIBlockElement::GetByID($elementID);
				if($ar_res = $res->GetNext()){
					$arr["ACTIVE"] = $ar_res["ACTIVE"];
					$arr["SHOW_COUNTER"] = $ar_res["SHOW_COUNTER"];
				}
			}
			if(($arr["ACTIVE"]=="Y") && ($arFields["ACTIVE"]=="N") && ($arr["SHOW_COUNTER"]>2)){
				global $APPLICATION;
				$APPLICATION->throwException("Товар невозможно деактивировать, у него ".$arr["SHOW_COUNTER"]." просмотров");
				return false;
			}
		}

	}
}

AddEventHandler("main", "OnBeforeEventAdd", array("MyClass", "OnBeforeEventAddHandler"));
class MyClass
{
	function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
	{
		if($event=="FEEDBACK_FORM"){
			global $USER;
			if($USER->IsAuthorized()){
				$auth = "Пользователь авторизован: ".$USER->GetID()." (".$USER->GetLogin().") ".$USER->GetFullName().", данные из формы: ".$arFields['AUTHOR'];
			}
			else{
				$auth = "Пользователь не авторизован, данные из формы: ".$arFields['AUTHOR'];
			}

			$arFields["AUTHOR"] = $auth;

			CEventLog::Add(array(
				"MODULE_ID" => "main",
				"DESCRIPTION" => "Замена данных в отсылаемом письме – ".$auth,
			));
		}
	}
}
?>