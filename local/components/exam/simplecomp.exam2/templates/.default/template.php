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
?>
<ul>
<?foreach($arResult["ITEMS"] as $arItem):?>
    <li>
        <b><?=$arItem["NAME"]?></b>
        <br>
        <?if($arItem["PROD"]):?>
            <ul>
            <?foreach ($arItem["PROD"] as $arProd):?>
                <li><a href="<?=$arProd["DETAIL_PAGE_URL"]?>"><?=$arProd["NAME"]?></a> - <?=$arProd["PROPERTY_PRICE"]?> - <?=$arProd["PROPERTY_MATERIAL"]?> - <?=$arProd["PROPERTY_ARTNUMBER"]?> (<?=$arProd["DETAIL_PAGE_URL"]?>)</li>
            <?endforeach;?>
            </ul>
        <?endif;?>
    </li>

    <br><br>
<?endforeach;?>
</ul>
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