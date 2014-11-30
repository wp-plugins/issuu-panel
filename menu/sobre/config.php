<?php

add_action(ISSUU_PAINEL_PREFIX . 'submenu_pages', 'issuu_painel_menu_about', 3);

function issuu_painel_menu_about()
{
	global $issuu_painel_capacity;

	add_submenu_page(
		'issuu-painel-admin',
		get_issuu_message('About'),
		get_issuu_message('About'),
		$issuu_painel_capacity,
		'issuu-painel-about',
		'issuu_painel_menu_about_init'
	);
}

function issuu_painel_menu_about_init()
{
	include(ISSUU_PAINEL_DIR . 'menu/sobre/page.php');
}