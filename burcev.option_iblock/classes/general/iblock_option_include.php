<?
class CBurcevOptionIblockInclude {

    public static function IncludeModuleOption(&$form){
        
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("main");
        global $USER;
        
		$user_id = $_REQUEST["ID"];
		$CONTENT .= CBurcevOptionInterfaceIblock::InterfaceForm($user_id); // передаем user_id
		
		if($USER->isAdmin()){
			$form->tabs[] = array("DIV" => "module_option_iblock", "TAB" => "USER FILES", "ICON"=>"main_user_edit_1", "TITLE"=>"Настройки", "CONTENT"=> $CONTENT);
		}
    }
  
}
?>