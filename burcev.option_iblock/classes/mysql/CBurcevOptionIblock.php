<?
class CBurcevOptionIblock
{
	function err_mess()
	{
		$module_id = "burcev.option_iblock";
		@include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/install/version.php");
		return "<br>Module: ".$module_id." (".constant(strtoupper($module_id)."_VERSION").")<br>Class: CBurcevOptionIblock.php<br>File: ".__FILE__;
	}

	function GetList(&$by, &$order, $arFilter=Array(), &$is_filtered, $min_permission=10)
	{
		$err_mess = (CBurcevOptionIblock::err_mess())."<br>Function: GetList<br>Line: ";
		global $DB, $USER, $strError;
		$min_permission = intval($min_permission);

		$arSqlSearch = Array();
		$strSqlSearch = "";
		if (is_array($arFilter))
		{

			$filter_keys = array_keys($arFilter);
			for ($i=0; $i<count($filter_keys); $i++)
			{
				$key = $filter_keys[$i];
				$val = $arFilter[$filter_keys[$i]];
				if (strlen($val)<=0 || "$val"=="NOT_REF") continue;
				if (is_array($val) && count($val)<=0) continue;
				$match_value_set = (in_array($key."_EXACT_MATCH", $filter_keys)) ? true : false;
				$key = strtoupper($key);
				switch($key)
				{
					case "ID":
						$match = ($arFilter[$key."_EXACT_MATCH"]=="N" && $match_value_set) ? "Y" : "N";
						$arSqlSearch[] = GetFilterQuery("F.".$key, $val, $match);
						break;
					case "SECTION_ID":
						$match = ($arFilter[$key."_EXACT_MATCH"]=="Y" && $match_value_set) ? "N" : "Y";
						$arSqlSearch[] = GetFilterQuery("F.".$key, $val, $match);
						break;
					case "NAME":
						$match = ($arFilter[$key."_EXACT_MATCH"]=="Y" && $match_value_set) ? "N" : "Y";
						$arSqlSearch[] = GetFilterQuery("F.".$key, $val, $match);
						break;
				}
			}
		}

		if ($by == "s_id")
			$strSqlOrder = "ORDER BY F.ID";
		else
			$strSqlOrder = "ORDER BY F.SECTION_ID";

			
		if ($order!="desc")
		{
			$strSqlOrder .= " asc ";
			$order="asc";
		}
		else
		{
			$strSqlOrder .= " desc ";
			$order="desc";
		}


		$strSqlSearch = GetFilterSqlSearch($arSqlSearch);
		if (CBurcevOptionIblock::IsAdmin())
		{
			$strSql = "
				SELECT
					F.*
				FROM
					b_burcev_option_iblock F
				$left_join
				WHERE
				$strSqlSearch
				GROUP BY F.ID
				$strSqlOrder
				";

			#echo "<pre>".$strSql."</pre>";
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);
			$is_filtered = (IsFiltered($strSqlSearch));
			return $res;
		}
	}
	
	function IsAdmin()
	{
		/*
		global $USER, $APPLICATION;
		if (!is_object($USER)) $USER = new CUser;
		if ($USER->IsAdmin()) return true; else return false;
		*/
		return true;
	}

	function GetByID($ID, $GET_BY_SID="N")
	{
		$err_mess = (CBurcevOptionIblock::err_mess())."<br>Function: GetByID<br>Line: ";
		global $DB, $strError;
		$where = ($GET_BY_SID=="N") ? " F.ID = '".intval($ID)."' " : " F.ID='".$DB->ForSql($ID,50)."' ";
		$strSql = "
			SELECT
				F.*
			FROM b_burcev_option_iblock F
			WHERE
				$where
			";
		#echo "<pre>".$strSql."</pre>";
		$arrBind = '';
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		$arForms = array();
		While($arForm = $res->GetNext()){
			$arForms = $arForm;
		}
		return $arForms;
	}
	
	///////////////////////////////////////////////////////////////////
	// Add function
	///////////////////////////////////////////////////////////////////
	function Add($arFields)
	{
		global $DB, $USER;

		/*$strWarning = "";

		if(is_set($arFields, "SECTION_ID") && strlen($arFields["SECTION_ID"])==0)
			$arFields["SECTION_ID"]="";

			$ID = $DB->Add("b_burcev_option_iblock", $arFields, false, "burcev.option_iblock");

			$Result = $ID;
			$arFields["ID"] = &$ID;
			$_SESSION["SESS_RECOUNT_DB"] = "Y";

		$arFields["RESULT"] = &$Result;*/
		/*$arFields['USER_ID'] = 619;
		$arFields['USER_FILE_ID'] = 1488;
		$arFields['NAME'] = 'Название';
		$ID = $DB->Add("b_burcev_option_iblock", $arFields);
		
		$Result = $ID;
			$arFields["ID"] = &$ID;
			$_SESSION["SESS_RECOUNT_DB"] = "Y";

			$arFields["RESULT"] = &$Result;

		return $Result;*/
       
        $query1 = "INSERT INTO `b_burcev_option_iblock` (`USER_ID`, `USER_FILE_ID`, `NAME`) VALUE (`123`, `456`, `789`)";
       
        mysql_query ($query1) or die (mysql_error());
	}
	
	// ������� ������
	function Delete($ID, $CHECK_RIGHTS="Y")
	{
		global $DB, $strError;
		$err_mess = (CBurcevOptionIblock::err_mess())."<br>Function: Delete<br>Line: ";
		$ID = intval($ID);

		if ($CHECK_RIGHTS!="Y" || CBurcevOptionIblock::IsAdmin())
		{
			$DB->Query("DELETE FROM b_burcev_option_iblock WHERE ID='$ID'", false, $err_mess.__LINE__);

				return true;
		}
		else $strError .= "Error delete.<br>";
		return false;
	}
	
	///////////////////////////////////////////////////////////////////
	// Update element function
	///////////////////////////////////////////////////////////////////
	function Update($ID, $arFields)
	{
		global $DB;
		$ID = intval($ID);
		unset($arFields["ID"]);
		
		$strUpdate = $DB->PrepareUpdate("b_burcev_option_iblock", $arFields, "burcev.option_iblock");

			$strSql = "UPDATE b_burcev_option_iblock SET ".$strUpdate." WHERE ID=".$ID;
			$DB->Query($strSql, false, "FILE: ".__FILE__."<br> LINE: ".__LINE__);
		
		return $ID;
	}

}
?>
