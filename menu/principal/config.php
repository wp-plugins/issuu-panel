<?php

add_action(ISSUU_PAINEL_PREFIX . 'menu_page', 'issuu_panel_menu_admin');

function issuu_panel_menu_admin()
{
	global $issuu_painel_capacity;

	add_menu_page(
		'Issuu Panel',
		'Issuu Panel',
		$issuu_painel_capacity,
		ISSUU_PAINEL_MENU,
		'issuu_panel_menu_admin_init',
		ISSUU_PAINEL_URL . 'images/icon2.png'
	);
}

function issuu_panel_menu_admin_init()
{
	global $issuu_panel_api_key, $issuu_panel_api_secret, $issuu_painel_capabilities, $issuu_painel_capacity;

	echo '<div class="wrap">';

	$link_api_service = '<a target="_blank" href="https://issuu.com/home/settings/apikey">click here</a>';

	if (strlen($issuu_panel_api_key) <= 0)
	{
		echo "<div class=\"error\"><p>" . get_issuu_message('Insert API key. ') . 
			get_issuu_message("To create keys $link_api_service") . "</p></div>";
	}

	if (strlen($issuu_panel_api_secret) <= 0)
	{
		echo "<div class=\"error\"><p>" . get_issuu_message('Insert API secret. ') .
			get_issuu_message("To create keys $link_api_service") . "</p></div>";
	}

	echo '<h1>Issuu Panel Admin</h1>';

	echo "<form action=\"\" method=\"post\" accept-charset=\"utf-8\">";
	echo '<p><label for="api_key"><strong>' . get_issuu_message('API key') . '</strong></label><br>';
	echo "<input type=\"text\" name=\"api_key\" id=\"api_key\" placeholder=\"" .
		get_issuu_message('Insert API key') . "\" value=\"$issuu_panel_api_key\" style=\"width: 300px;\"><p>";
	echo '<p><label for="api_secret"><strong>' . get_issuu_message('API secret') . '</strong></label><br>';
	echo "<input type=\"text\" name=\"api_secret\" id=\"api_secret\" placeholder=\"" .
		get_issuu_message('Insert API secret') . "\" value=\"$issuu_panel_api_secret\" style=\"width: 300px;\"><p>";
	echo '<p>';
	the_issuu_message('Users with capacities from');
	echo ' <select name="enabled_user">';

	foreach ($issuu_painel_capabilities as $key => $value) {
		if ($value == $issuu_painel_capacity)
		{
			echo "<option value=\"$key\" selected>" . get_issuu_message($key) . "</option>";
		}
		else
		{
			echo "<option value=\"$key\">" . get_issuu_message($key) . "</option>";
		}
	}

	echo '</select> ';
	the_issuu_message('can use this plugin');
	echo '</p>';
	echo "<p><input type=\"submit\" class=\"button-primary\" value=\"Cadastrar\"></p>";
	echo "</form>";

	echo '</div>';
}