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
//prnt($arResult);
?>
<?foreach($arResult["ITEMS"] as $arNews):?>
    <div>
        <b><?=$arNews["NAME"]?></b> - <?=$arNews["DATE_ACTIVE_FROM"]?> (<?=implode(", ", $arNews["SECT_NAME"]);?>)
        <br>
        <?if($arNews["SECT_ITEMS"]):?>
            <ul>
            <?foreach ($arNews["SECT_ITEMS"] as $arItem):?>
                <li><?=$arItem["NAME"]?> - <?=$arItem["PROPERTY_PRICE_VALUE"]?> - <?=$arItem["PROPERTY_MATERIAL_VALUE"]?> - <?=$arItem["PROPERTY_ARTNUMBER_VALUE"]?></li>
            <?endforeach;?>
            </ul>
        <?endif;?>
    </div>

    <br><br>
<?endforeach;?>