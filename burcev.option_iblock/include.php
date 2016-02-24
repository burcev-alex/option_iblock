<?

IncludeModuleLangFile(__FILE__);

class CBurcevAdminControlOptionIblock {

	static public $addthis_options = array(
			'active_module_iblock' => array(5),
			'active_tabs' => 'Y',
			'active_admin_panel' => 'Y'
		);
		
	public function Options($key){
		$arParams = array();
		$arParams["active_module_iblock"] = array(5);
		$arParams["active_tabs"] = "Y";
		$arParams["active_admin_panel"] = "Y";
		
		$save_option = CBurcevAdminControlOptionIblock::getConfigOptions();
		
		if(isset($save_option[$key])){
			return $save_option[$key];
		}
		else{
			return $arParams[$key];
		}		
	}
	
    public function ModuleOnAdminTabControlBegin(&$form) {
        if($GLOBALS["APPLICATION"]->GetCurPage() == "/bitrix/admin/user_edit.php"){
            CModule::IncludeModule("burcev.option_iblock");
            CJSCore::Init(array("jquery"));
            echo '<script language="JavaScript" src="/bitrix/js/burcev.option_iblock/script.js"></script>';
			CBurcevOptionIblockInclude::IncludeModuleOption($form);
        }
    }
    
	
		function getModuleID()
		{
			return $result = basename(dirname(__FILE__));
		}

		function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
		{
			$MODULE_ID = self::getModuleID();
		}

		function setConfigOptions($options=array(),$rows=array())
		{
			foreach(self::$addthis_options as $key=>$value)
			{
				foreach($rows as $row)
				{
					if($row == $key)
					{
						$set_value = (isset($options[$key])) ? $options[$key] : "";
						$result = COption::SetOptionString(self::getModuleID(),$key,$set_value);
					}
				}
			}
		}

		function getConfigOptions()
		{
			$options = null;
			if(is_array(self::$addthis_options))
			{
				foreach(self::$addthis_options as $key=>$value)
				{
					$options[$key] = COption::GetOptionString(self::getModuleID(), $key, $value);
				}
			}
			return $options;
		}
    
}

CModule::AddAutoloadClasses("burcev.option_iblock", array(
    "CBurcevOptionInterfaceIblock" => "/classes/general/iblock_option_interface.php",
    "CBurcevOptionIblockInclude" => "/classes/general/iblock_option_include.php",
	"CBurcevOptionIblock" => "/classes/mysql/CBurcevOptionIblock.php",
));

$arJSBurcevIBlockConfig = array(
    'burcev.option_iblock' => array(
        'rel' => array('jquery'),
    )
);

foreach ($arJSBurcevIBlockConfig as $ext => $arExt) {
    CJSCore::RegisterExt($ext, $arExt);
}
?>