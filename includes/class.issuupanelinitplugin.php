<?php

class IssuuPanelInitPlugin
{
	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'loadTextdomain'));
		register_activation_hook(ISSUU_PAINEL_PLUGIN_FILE, array($this, 'activePlugin'));
		register_uninstall_hook(ISSUU_PAINEL_PLUGIN_FILE, array($this, 'uninstallPlugin'));
	}

	public function loadTextdomain()
	{
		load_plugin_textdomain(ISSUU_PAINEL_DOMAIN_LANG, false, ISSUU_PAINEL_PLUGIN_FILE_LANG);
		issuu_panel_debug("Text domain loaded");
	}

	public function activePlugin()
	{
		add_option(ISSUU_PAINEL_PREFIX . 'api_key', '');
		add_option(ISSUU_PAINEL_PREFIX . 'api_secret', '');
		add_option(ISSUU_PAINEL_PREFIX . 'enabled_user', 'Administrator');
		add_option(ISSUU_PAINEL_PREFIX . 'debug', 'disable');
		issuu_panel_debug("Issuu Panel options initialized");
	}

	public function uninstallPlugin()
	{
		delete_option(ISSUU_PAINEL_PREFIX . 'api_key');
		delete_option(ISSUU_PAINEL_PREFIX . 'api_secret');
		delete_option(ISSUU_PAINEL_PREFIX . 'enabled_user');
		delete_option(ISSUU_PAINEL_PREFIX . 'debug');
	}
}

new IssuuPanelInitPlugin();