<?
IncludeModuleLangFile(__FILE__);

global $USER;
if($USER->isAdmin())
{
    $aMenu = array();
	$aMenu[] = array(
		"parent_menu" => "global_menu_content",
		"section" => "option_iblock",
		"sort" => 2000,
		"url" => "burcev_option_iblock_index.php?lang=".LANGUAGE_ID,
		"text" => GetMessage("BURCEV_OPTION_IBLOCK_MENU_MAIN"),
		"title" => GetMessage("BURCEV_OPTION_IBLOCK_MENU_MAIN_TITLE"),
		"icon" => "iblock_menu_icon_settings",
		"page_icon" => "iblock_page_icon_iblocks",
		"module_id" => "burcev.option_iblock",
		"items_id" => "menu_burcev_option_iblock",
		"items" => array(
            array(
                "text" => GetMessage("BURCEV_OPTION_IBLOCK_MENU_SETTINGS"),
                "url" => "burcev_option_iblock_settings.php?lang=".LANGUAGE_ID,
                "title" => GetMessage("BURCEV_OPTION_IBLOCK_MENU_SETTINGS"),
                "more_url" => array(
                    "burcev_option_iblock_settings_edit.php",
                ),
                "sort" => 100,
            )
        ),
	);
    /*
	$aMenu["items"][] = array(
        "text" => GetMessage("BURCEV_OPTION_IBLOCK_MENU_FILTER"),
        "url" => "burcev_option_iblock_filter.php?lang=".LANGUAGE_ID,
        "title" => GetMessage("BURCEV_OPTION_IBLOCK_MENU_FILTER"),
        "more_url" => array(
            "burcev_option_iblock_filter_edit.php",
        ),
        "sort" => 200,
    );
	*/

    $aMenu[] = array(
        "parent_menu" => "global_menu_content",
        "section" => "option_iblock_import",
        "sort" => 2100,
        "url" => "burcev_iblock_data_import.php?lang=".LANGUAGE_ID,
        "text" => GetMessage("BURCEV_OPTION_IBLOCK_IMPORT_MENU_MAIN"),
        "title" => GetMessage("BURCEV_OPTION_IBLOCK_IMPORT_MENU_MAIN_TITLE"),
        "icon" => "iblock_menu_icon_settings",
        "page_icon" => "iblock_page_icon_iblocks",
        "module_id" => "burcev.option_iblock",
        "items_id" => "menu_burcev_import_option_iblock",
        "items" => array(
            array(
                "text" => GetMessage("BURCEV_OPTION_IBLOCK_IMPORT_MENU_SETTINGS"),
                "url" => "burcev_iblock_data_import.php?lang=".LANGUAGE_ID,
                "title" => GetMessage("BURCEV_OPTION_IBLOCK_IMPORT_MENU_SETTINGS"),
                "more_url" => array(
                    "burcev_iblock_data_import.php",
                ),
                "sort" => 100,
            )
        ),
    );

	return $aMenu;
}
return false;
?>
