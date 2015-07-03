<?php

class IssuuPanelInitPlugin
{
	private static $options = array(
		'api_key' => '',
		'api_secret' => '',
		'enabled_user' => 'Administrator',
		'debug' => 'disable',
		'shortcode_cache' => array(),
		'cache_status' => 'disable',
		'reader' => 'issuu_embed',
		'cron' => array()
	);

	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'loadTextdomain'));
		add_action('init', array($this, 'initHook'));
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
		foreach (self::$options as $key => $value) {
			if (is_array($value))
			{
				add_option(ISSUU_PAINEL_PREFIX . $key, serialize($value));
			}
			else
			{
				add_option(ISSUU_PAINEL_PREFIX . $key, $value);
			}
		}
		issuu_panel_debug("Issuu Panel options initialized");
	}

	public function uninstallPlugin()
	{
		foreach (self::$options as $key => $value) {
			delete_option(ISSUU_PAINEL_PREFIX . $key);
		}
	}

	public function initHook()
	{
		IssuuPanelConfig::setVariable(
			'issuu_panel_shortcode_cache',
			unserialize(get_option(ISSUU_PAINEL_PREFIX . 'shortcode_cache'))
		);

		if (isset($_GET['issuu_panel_flush_cache']))
		{
			IssuuPanelConfig::flushCache();
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_GET['page']) && $_GET['page'] == ISSUU_PAINEL_MENU))
		{
			update_option(ISSUU_PAINEL_PREFIX . 'api_key', trim($_POST['api_key']));
			update_option(ISSUU_PAINEL_PREFIX . 'api_secret', trim($_POST['api_secret']));
			update_option(ISSUU_PAINEL_PREFIX . 'reader', trim($_POST['issuu_panel_reader']));

			if (in_array($_POST['enabled_user'], array('Administrator', 'Editor', 'Author')))
			{
				update_option(ISSUU_PAINEL_PREFIX . 'enabled_user', $_POST['enabled_user']);
			}
			else
			{
				$_POST['enabled_user'] = 'Administrator';
				update_option(ISSUU_PAINEL_PREFIX . 'enabled_user', 'Administrator');
			}

			if (isset($_POST['issuu_panel_debug']) && $_POST['issuu_panel_debug'] == 'active')
			{
				update_option(ISSUU_PAINEL_PREFIX . 'debug', 'active');
			}
			else
			{
				update_option(ISSUU_PAINEL_PREFIX . 'debug', 'disable');
			}

			if (isset($_POST['issuu_panel_cache_status']) && $_POST['issuu_panel_cache_status'] == 'active')
			{
				update_option(ISSUU_PAINEL_PREFIX . 'cache_status', 'active');
			}
			else
			{
				update_option(ISSUU_PAINEL_PREFIX . 'cache_status', 'disable');
			}

			IssuuPanelConfig::setVariable('issuu_panel_api_key', trim($_POST['api_key']));
			IssuuPanelConfig::setVariable('issuu_panel_api_secret', trim($_POST['api_secret']));
			IssuuPanelConfig::setVariable('issuu_panel_reader', trim($_POST['issuu_panel_reader']));
			IssuuPanelConfig::setVariable('issuu_panel_cache_status', trim($_POST['issuu_panel_cache_status']));
			$issuu_painel_capacity = IssuuPanelConfig::getCapability($_POST['enabled_user']);
			IssuuPanelConfig::setVariable('issuu_panel_capacity', $issuu_painel_capacity);
			issuu_panel_debug("Issuu Panel options updated in init hook");
		}
		else
		{
			IssuuPanelConfig::setVariable('issuu_panel_api_key', get_option(ISSUU_PAINEL_PREFIX . 'api_key'));
			IssuuPanelConfig::setVariable('issuu_panel_api_secret', get_option(ISSUU_PAINEL_PREFIX . 'api_secret'));
			IssuuPanelConfig::setVariable('issuu_panel_reader', get_option(ISSUU_PAINEL_PREFIX . 'reader'));
			IssuuPanelConfig::setVariable('issuu_panel_cache_status', get_option(ISSUU_PAINEL_PREFIX . 'cache_status'));
			$issuu_painel_capacity = IssuuPanelConfig::getCapability(get_option(ISSUU_PAINEL_PREFIX . 'enabled_user'));
			IssuuPanelConfig::setVariable('issuu_panel_capacity', $issuu_painel_capacity);
			issuu_panel_debug("Issuu Panel options initialized in init hook");
		}
	}
}

new IssuuPanelInitPlugin();