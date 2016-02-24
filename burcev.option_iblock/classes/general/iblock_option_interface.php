<?
class CBurcevOptionInterfaceIblock {

    public static function InterfaceForm($USER_ID){
		
		CModule::IncludeModule('fileman');
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("main");
        global $USER, $APPLICATION;
				
		$CONTENT = "<span style='float: left; margin-right: 5px; margin-top: 4px;'>Файл:</span>";
		$CONTENT .= "<p style='margin: 0px; float: left;'><input class='file_here' type='file' name='f'>";
		$CONTENT .= "<span><input type='text' placeholder='Название' /></span>";
		$CONTENT .= "<div style='margin-left:10px;height:16px; 1eft:11px; float:left; border:1px solid #eaf1f3; border-radius:4px; background-color: #eaf1f3 !important; background-image: -moz-linear-gradient(center bottom , #d7e3e7, #fff); padding:5px 15px; padding-left:29px; cursor:pointer;' type='submit' class='roll_the_deep adm-input-file'>Загрузить файл</div>";
		$CONTENT .= "</p>";
		
        return $CONTENT;
    }
    
}
?>