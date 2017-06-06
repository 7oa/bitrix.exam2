<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

//prnt($arResult)
?>
Фильтр: <a href="http://ex2-1/ex2/simplecomp2/?F=Y">ex2-1/ex2/simplecomp2/?F=Y</a>
<br><br>
<ul>
<?foreach($arResult["ITEMS"] as $arItem):?>
    <li>
        <b><?=$arItem["NAME"]?></b>
        <br>
        <?if($arItem["PROD"]):?>
            <ul>
            <?foreach ($arItem["PROD"] as $arProd):
				$this->AddEditAction($arProd['ID'], $arProd['EDIT_LINK'], CIBlock::GetArrayByID($arProd["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arProd['ID'], $arProd['DELETE_LINK'], CIBlock::GetArrayByID($arProd["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
                <li id="<?=$this->GetEditAreaId($arProd['ID']);?>"><a href="<?=$arProd["DETAIL_PAGE_URL"]?>"><?=$arProd["NAME"]?></a> - <?=$arProd["PROPERTY_PRICE"]?> - <?=$arProd["PROPERTY_MATERIAL"]?> - <?=$arProd["PROPERTY_ARTNUMBER"]?> (<?=$arProd["DETAIL_PAGE_URL"]?>)</li>
            <?endforeach;?>
            </ul>
        <?endif;?>
    </li>

    <br><br>
<?endforeach;?>
</ul>
<?=$arResult["NAV_STRING"]?>
<?
$this->SetViewTarget("ex2");
?>
    <div style="color:red; margin: 34px 15px 35px 15px">
        Максимальная цена: <?=$arResult["MAX"]?><br>
        Минимальная цена: <?=$arResult["MIN"]?>
    </div>
<?
$this->EndViewTarget("ex2");
?>