<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/burcev.option_iblock/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/burcev.option_iblock/include.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");

$BURCEV_OPTION_IBLOCK_RIGHT = $APPLICATION->GetGroupRight("burcev.option_iblock");
if($BURCEV_OPTION_IBLOCK_RIGHT<="D") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

CModule::IncludeModule('burcev.option_iblock');

ClearVars();

IncludeModuleLangFile(__FILE__);
$err_mess = "File: ".__FILE__."<br>Line: ";
define("HELP_FILE","burcev_option_iblock_settings.php");


$bEditTemplate = $USER->CanDoOperation('edit_php');

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("BURCEV_OPTION_IBLOCK_PROP"), "ICON" => "burcev.option_iblock_edit", "TITLE" => GetMessage("BURCEV_OPTION_IBLOCK_PROP")),
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$message = null;
/***************************************************************************
                           GET | POST processing
***************************************************************************/


$ID = intval($_REQUEST['ID']);
$strError = '';

if ($ID > 0)
{
	$F_RIGHT = array();
	for($i=0; $i<=50;){
		$F_RIGHT[] = $i;
		$i = $i + 5;
	}
	if ($F_RIGHT<25) $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

	#echo '<pre>'.print_r($_REQUEST,true).'</pre>';
	#echo '<pre>'.print_r($_POST,true).'</pre>';
	if ((strlen($_REQUEST['save'])>0 || strlen($_REQUEST['apply'])>0) && $_SERVER['REQUEST_METHOD']=="POST")
	{
		$strWarning = '';
				if (strlen($strWarning) <= 0)
				{
					$bs = new CBurcevOptionIblock;

                    $arDataSection = explode('_', $_POST["SECTION_ID"]);
                    $arFields = Array(
                        "NAME" => $_POST["NAME"],
                        "SECTION_ID" => $arDataSection[1],
                        "VALUE" => $_POST["VALUE"],
                        "IBLOCK_ID" => $arDataSection[0]
                    );
					
					#echo '<pre>'.print_r($arFields,true).'</pre>';
					
					if($ID > 0)
					{
						$bCreateRecord = false;
						$res = $bs->Update($ID, $arFields);
					}
					else{
						$bCreateRecord = true;
						$res = $bs->Add($arFields);
						
						$ID = $res;
					}
				}
				#exit();

		if(strlen($strWarning)>0)
		{
			$error = new _CIBlockError(2, "BAD_SAVE", $strWarning);
			$bVarsFromForm = true;
			$DB->Rollback();
		}
		else
		{
			$arFields['ID'] = $ID;

			$DB->Commit();

			if(strlen($apply) <= 0)
			{
				if ((true == defined('BT_UT_AUTOCOMPLETE')) && (1 == BT_UT_AUTOCOMPLETE))
				{
					?><script type="text/javascript">
					window.opener.<?php echo $strLookup; ?>.AddValue(<?php echo $ID;?>);
					window.close();
					</script><?php
				}
				else
				{
					LocalRedirect("/bitrix/admin/burcev_option_iblock_settings.php?lang=".LANGUAGE_ID);
				}
			}
			else
			{
				LocalRedirect("/bitrix/admin/burcev_option_iblock_settings_edit.php?lang=".LANGUAGE_ID."&ID=".$ID);
			}
		}
	}



$arParser = CBurcevOptionIblock::GetByID($ID, 'burcev.option_iblock');

if (strlen($strError)>0) $DB->InitTableVarsForEdit("b_burcev.option_iblock", "", "str_");

if ($ID>0)
{
	$sDocTitle = str_replace("#ID#", $ID, GetMessage("BURCEV_OPTION_IBLOCK_EDIT_RECORD"));
	$sDocTitle = str_replace("#NAME#", $arParser["NAME"], $sDocTitle);
}
else $sDocTitle = GetMessage("BURCEV_OPTION_IBLOCK_NEW_RECORD");

$APPLICATION->SetTitle($sDocTitle);

if ($ID > 0)
{
	$txt = "(".htmlspecialchars($arParser['PARSER_ID']).")&nbsp;".htmlspecialchars($arParser["NAME"]);
	$link = "burcev_option_iblock_settings_edit.php?lang=".LANGUAGE_ID."&ID=".$ID;
	$adminChain->AddItem(array("TEXT"=>$txt, "LINK"=>$link));
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
/***************************************************************************
                               HTML form
****************************************************************************/

if($strError)
{
	$aMsg=array();
	$arrErr = explode("<br>",$strError);
	reset($arrErr);
	while (list(,$err)=each($arrErr)) $aMsg[]['text']=$err;

	$e = new CAdminException($aMsg);
	$GLOBALS["APPLICATION"]->ThrowException($e);

	$message = new CAdminMessage(GetMessage("BURCEV_OPTION_IBLOCK_ERROR_SAVE"), $e);
	echo $message->Show();
}
echo ShowNote($strNote);
?>

<form name="burcev_option_iblock_forms" method="POST" action="">
<?=bitrix_sessid_post()?>
<input type="hidden" name="ID" value=<?=$ID?> />
<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>" />
<?
$tabControl->Begin();
?>
<?
//********************
//General Tab
//********************
$tabControl->BeginNextTab();
?>
	<tr>
		<td width="40%">ID:</td>
		<td width="60%"><?if(IntVal($arParser["ID"])>0) echo $arParser["ID"]; else echo "New";?></td>
	</tr>
	<tr>
		<td width="40%"><span class="required">*</span><?=GetMessage("BURCEV_OPTION_IBLOCK_NAME")?></td>
		<td width="60%"><input type="text" name="NAME" size="40" value="<?=$arParser["NAME"]?>"></td>
	</tr>
        <tr>
		<td width="40%"><span class="required">*</span><?=GetMessage("BURCEV_OPTION_IBLOCK_SECTION")?></td>
		<td width="60%">
                    <select name="SECTION_ID">
                    <?
					$arIblockForModule = CBurcevAdminControlOptionIblock::Options("active_module_iblock");
					foreach($arIblockForModule as $key=>$val):  
						$resIblockModule = CIBlock::GetByID($val)->GetNext();
						echo '<option value="'.$val.'_0'.'">IBLOCK #'.$val.' - '.$resIblockModule["NAME"].'</option>';
						$dbCatalogSection = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$val), array("ID", "NAME", "DEPTH_LEVEL"));
						while($arCatalogSection = $dbCatalogSection->GetNext()){
							echo '<option value="'.$val.'_'.$arCatalogSection["ID"].'"';
							if($arParser["SECTION_ID"] == $arCatalogSection["ID"]) echo ' selected="selected"';
							echo '>';
							echo str_repeat(" . ", $arCatalogSection["DEPTH_LEVEL"]);
							echo $arCatalogSection["NAME"];
							echo '</option>';
						}
					endforeach;
                    ?>
                    </select>
                </td>
	</tr>
	<tr id="tr_PROPERTY_3">
		<td width="40%" class="adm-detail-valign-top adm-detail-content-cell-l">
                    <span class="required">*</span><?=GetMessage("BURCEV_OPTION_IBLOCK_CONFIG")?>
                </td>
                <td width="60%">
                <textarea name="VALUE" style="width:500px; height:300px;"><?=$arParser["VALUE"]?></textarea>
                </td>
	</tr>
	
<?
$tabControl->EndTab();
	
	$tabControl->Buttons(
		array(
			"disabled" => false,
			"back_url" => "/bitrix/admin/burcev_option_iblock_settings.php?lang=".LANGUAGE_ID,
		)
	);
	
$tabControl->End();
?>

</form>
<?
if (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1):
	echo BeginNote();
?>
<span class="required">*</span> - <?echo GetMessage("REQUIRED_FIELDS")?>
<?
	echo EndNote();
endif; //if (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1):

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>