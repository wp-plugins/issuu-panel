<?php

add_action(ISSUU_PAINEL_PREFIX . 'submenu_pages', 'issuu_panel_menu_about', 3);

function issuu_panel_menu_about()
{
	global $issuu_painel_capacity;

	add_submenu_page(
		ISSUU_PAINEL_MENU,
		get_issuu_message('About'),
		get_issuu_message('About'),
		$issuu_painel_capacity,
		'issuu-panel-about',
		'issuu_panel_menu_about_init'
	);
}

function issuu_panel_menu_about_init()
{
	include(ISSUU_PAINEL_DIR . 'menu/sobre/page.php');
}