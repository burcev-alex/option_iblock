<?

IncludeModuleLangFile(__FILE__);

if (class_exists("burcev_option_iblock"))
    return;

class burcev_option_iblock extends CModule {

    var $MODULE_ID = "burcev.option_iblock";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct() {
        $arModuleVersion = array();
        include(dirname(__FILE__) . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("inst_module_name");
        $this->MODULE_DESCRIPTION = GetMessage("inst_module_desc");
        $this->PARTNER_NAME = GetMessage("PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("PARTNER_URI");
    }

    function InstallDB($arParams = array()) {
		global $APPLICATION, $DB, $errors;

		if (!$DB->Query("SELECT * FROM b_burcev_option_iblock", true)) $EMPTY = "Y"; else $EMPTY = "N";

		if ($EMPTY=="Y")
		{
			$errors = $DB->RunSQLBatch(__DIR__."/db/".strtolower($DB->type)."/install.sql");

			if (!empty($errors))
			{
				$APPLICATION->ThrowException(implode("", $errors));
				return false;
			}
		}
		
        RegisterModule($this->MODULE_ID);
        RegisterModuleDependences("main", "OnAdminTabControlBegin", $this->MODULE_ID, 'CBurcevAdminControlOptionIblock', 'ModuleOnAdminTabControlBegin');
		
        return true;
    }

    function UnInstallDB($arParams = array()) {
		global $APPLICATION, $DB, $errors;

		if ($DB->Query("SELECT * FROM b_burcev_option_iblock", true)) $EMPTY = "Y"; else $EMPTY = "N";

		if ($EMPTY=="Y")
		{
			$errors = $DB->RunSQLBatch(__DIR__."/db/".strtolower($DB->type)."/uninstall.sql");

			if (!empty($errors))
			{
				$APPLICATION->ThrowException(implode("", $errors));
				return false;
			}
		}
		
        UnRegisterModuleDependences('main', 'OnAdminTabControlBegin', $this->MODULE_ID, 'CBurcevAdminControlOptionIblock', 'ModuleOnAdminTabControlBegin');
		
        UnRegisterModule($this->MODULE_ID);
        return true;
    }

    function InstallFiles($arParams = array()) {
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/burcev.option_iblock/install/public/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/include/', true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/burcev.option_iblock/install/js/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/', true, true);
		
        return true;
    }

    function UnInstallFiles() {	
		DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/burcev.option_iblock/");
        DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"].'/bitrix/js/burcev.option_iblock/');
		
        return true;
    }

    function DoInstall() {
        global $DOCUMENT_ROOT, $APPLICATION, $step;
        $FM_RIGHT = $APPLICATION->GetGroupRight($this->MODULE_ID);
        if ($FM_RIGHT != "D") {
            $this->InstallDB();
            $this->InstallFiles();
        }
        $APPLICATION->IncludeAdminFile(GetMessage("SCOM_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/burcev.option_iblock/install/step.php");
    }

    function DoUninstall() {
        global $DOCUMENT_ROOT, $APPLICATION, $step;
        $FM_RIGHT = $APPLICATION->GetGroupRight($this->MODULE_ID);
        if ($FM_RIGHT != "D") {
            $this->UnInstallDB();
            $this->UnInstallFiles();
        }
        $APPLICATION->IncludeAdminFile(GetMessage("SCOM_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/burcev.option_iblock/install/unstep.php");
    }

}

?>