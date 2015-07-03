<?php

class IssuuPanelMenu implements IssuuPanelPage
{
	public function __construct()
	{
		add_action(ISSUU_PAINEL_PREFIX . 'menu_page', array($this, 'init'));
	}

	public function init()
	{
		add_menu_page(
			'Issuu Panel',
			'Issuu Panel',
			IssuuPanelConfig::getVariable('issuu_panel_capacity'),
			ISSUU_PAINEL_MENU,
			array($this, 'page'),
			ISSUU_PAINEL_URL . 'images/icon2.png'
		);
		issuu_panel_debug("Issuu Panel Page (Main)");
	}

	public function page()
	{
		$issuu_panel_api_key = IssuuPanelConfig::getVariable('issuu_panel_api_key');
		$issuu_panel_api_secret = IssuuPanelConfig::getVariable('issuu_panel_api_secret');
		$issuu_panel_capacity = IssuuPanelConfig::getVariable('issuu_panel_capacity');
		$issuu_panel_reader = IssuuPanelConfig::getVariable('issuu_panel_reader');
		$issuu_embed = ($issuu_panel_reader == 'issuu_embed')? 'checked' : '';
		$issuu_panel_simple_reader = ($issuu_panel_reader == 'issuu_panel_simple_reader')? 'checked' : '';

		$link_api_service = '<a target="_blank" href="https://issuu.com/home/settings/apikey">click here</a>';
		$issuu_panel_debug = (get_option(ISSUU_PAINEL_PREFIX . 'debug') == 'active')? 'checked' : '';
		$issuu_panel_cache_status = (get_option(ISSUU_PAINEL_PREFIX . 'cache_status') == 'active')? 'checked' : '';

		require(ISSUU_PAINEL_DIR . 'menu/principal/page.phtml');
	}
}

new IssuuPanelMenu();