<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/burcev.option_iblock/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/burcev.option_iblock/include.php");

$sTableID = "tbl_burcev_option_iblock_list";
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

ClearVars();

$FORM_RIGHT = $APPLICATION->GetGroupRight("burcev.option_iblock");
if($FORM_RIGHT<="D") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

CModule::IncludeModule("burcev.option_iblock");
CModule::IncludeModule("iblock");

IncludeModuleLangFile(__FILE__);
$err_mess = "File: ".__FILE__."<br>Line: ";

$arFilterFields = Array(
	"find_id",
	"find_code",
	"find_name",
	);

$lAdmin->InitFilter($arFilterFields);

/***************************************************************************
			   GET | POST processing
****************************************************************************/

$reset_id = intval($reset_id);

$arFilter = Array(
	"ID"			=> $find_id,
	"NAME"			=> $find_name,
	"SECTION_ID"		=> $find_section_id,
	);

// simgle and group actions processing
if(($arID = $lAdmin->GroupAction()) && $FORM_RIGHT=="W" && check_bitrix_sessid())
{
	if($_REQUEST['action_target']=='selected')
	{
		$arID = Array();
		$rsData = CBurcevOptionIblock::GetList($by, $order, $arFilter, $is_filtered);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;
		$ID = IntVal($ID);
		switch($_REQUEST['action'])
		{
		case "delete":
			@set_time_limit(0);
			$DB->StartTransaction();
			if(!CBurcevOptionIblock::Delete($ID))
			{
				$DB->Rollback();
				$lAdmin->AddGroupError(GetMessage("DELETE_ERROR"), $ID);
			}
			$DB->Commit();
			break;
		}
	}
}
//////////////////////////////////////////////////////////////////////
// list initialization - get data
$rsData = CBurcevOptionIblock::GetList($by, $order, $arFilter, $is_filtered);
$arData = array();
while ($arForm = $rsData->Fetch())
{
	$arData[] = $arForm;
}

$rsData->InitFromArray($arData);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

// set navigation bar
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("BURCEV_OPTION_IBLOCK_PAGES")));

$headers = array(
			array("id"=>"ID", "content"=>"ID", "sort"=>"s_id", "default"=>true),
			array("id"=>"NAME", "content"=>GetMessage("BURCEV_OPTION_IBLOCK_NAME"), "sort"=>"s_name", "default"=>true),
			array("id"=>"SECTION_ID", "content"=>GetMessage("BURCEV_OPTION_IBLOCK_CONFIG"), "sort"=>false, "default"=>true),
		);

$lAdmin->AddHeaders($headers);

while($arRes = $rsData->NavNext(true, "f_"))
{
	#echo "<pre>"; print_r($arRes); echo "</pre>";
    
        $resSection = CIBlockSection::GetByID($arRes["SECTION_ID"])->GetNext();
        $arRes["SECTION_ID"] = $resSection["NAME"];
        
	$row =& $lAdmin->AddRow($f_ID, $arRes);

	$F_RIGHT = $f_F_RIGHT;

	//echo $F_RIGHT;
	unset($txt);

	$arActions = Array();
	$arActions[] = array("DEFAULT"=>"Y", "ICON"=>"edit", "TITLE"=>GetMessage("BURCEV_OPTION_IBLOCK_EDIT_ALT"), "ACTION"=>$lAdmin->ActionRedirect("burcev_option_iblock_settings_edit.php?lang=".LANGUAGE_ID."&ID=$f_ID"), "TEXT"=>GetMessage("BURCEV_OPTION_IBLOCK_EDIT"));
	$row->AddActions($arActions);


}

// list footer
$lAdmin->AddFooter(
	array(
		array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()),
		array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"),
	)
);

if ($FORM_RIGHT=="W")
	// add list buttons
	$lAdmin->AddGroupActionTable(Array(
		"delete"=>GetMessage("BURCEV_OPTION_IBLOCK_DELETE"),
		));

// context menu
if ($FORM_RIGHT=="W")
{
	$aMenu = array();
	$aMenu[] = array(
		"TEXT"	=> GetMessage("BURCEV_OPTION_IBLOCK_CREATE"),
		"TITLE"=>GetMessage("BURCEV_OPTION_IBLOCK_CREATE_TITLE"),
		"LINK"=>"burcev_option_iblock_settings_edit.php?lang=".LANG,
		"ICON" => "btn_new"
	);
	
	$aContext = $aMenu;
	$lAdmin->AddAdminContextMenu($aContext);
}


// check list output mode
$lAdmin->CheckListMode();

/***************************************************************************
							   HTML burcev.option_iblock
****************************************************************************/

$APPLICATION->SetTitle(GetMessage("BURCEV_OPTION_IBLOCK_PAGE_TITLE"));
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

?>
<a name="tb"></a>

<?
$lAdmin->DisplayList();

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"); ?>
