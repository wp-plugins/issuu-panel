<?php
/*
Plugin Name: Issuu Panel
Plugin URI: https://github.com/pedromarcelojava/Issuu-Painel
Description: Admin panel for Issuu. You can upload your documents, create folders and embed documents in posts.
Version: 1.1.1
Author: Pedro Marcelo
Author URI: https://www.linkedin.com/profile/view?id=265534858
License: GPL3
*/

define('ISSUU_PAINEL_DIR', plugin_dir_path(__FILE__));
define('ISSUU_PAINEL_URL', plugin_dir_url(__FILE__));
define('ISSUU_PAINEL_PREFIX', 'issuu_painel_');
define('ISSUU_PAINEL_DOMAIN_LANG', 'issuu-painel-domain-lang');

$issuu_shortcode_index = 0;
$issuu_painel_capabilities = array(
	'Administrator' => 'manage_options',
	'Editor' => 'edit_private_pages',
	'Author' => 'upload_files'
);

add_action('plugins_loaded', 'issuu_painel_textdomain');

function issuu_painel_textdomain()
{
	load_plugin_textdomain(ISSUU_PAINEL_DOMAIN_LANG, false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

add_option(ISSUU_PAINEL_PREFIX . 'api_key', '');
add_option(ISSUU_PAINEL_PREFIX . 'api_secret', '');
add_option(ISSUU_PAINEL_PREFIX . 'enabled_user', 'Administrator');

$api_key = get_option(ISSUU_PAINEL_PREFIX . 'api_key');
$api_secret = get_option(ISSUU_PAINEL_PREFIX . 'api_secret');
$enabled_user = get_option(ISSUU_PAINEL_PREFIX . 'enabled_user');
$issuu_painel_capacity = $issuu_painel_capabilities[$enabled_user];

require(ISSUU_PAINEL_DIR . 'menu/principal/config.php');
require(ISSUU_PAINEL_DIR . 'issuuservice/issuu-lib.php');

$includes = glob(ISSUU_PAINEL_DIR . 'includes/*.php');

foreach ($includes as $include) {
	require($include);
}

include(ISSUU_PAINEL_DIR . 'menu/documento/config.php');
include(ISSUU_PAINEL_DIR . 'menu/pasta/config.php');
include(ISSUU_PAINEL_DIR . 'menu/sobre/config.php');
include(ISSUU_PAINEL_DIR . 'shortcode/document-list.php');
include(ISSUU_PAINEL_DIR . 'shortcode/folder-list.php');
include(ISSUU_PAINEL_DIR . 'shortcode/the-last-document.php');
include(ISSUU_PAINEL_DIR . 'widget/class.issuupanelwidget.php');

add_action('wp_enqueue_scripts', 'issuu_painel_wp_enqueue_scripts');

function issuu_painel_wp_enqueue_scripts()
{
	wp_enqueue_style('issuu-painel-documents', ISSUU_PAINEL_URL . 'css/issuu-painel-documents.css');
	wp_enqueue_script(
		'issuu-iframe-link',
		ISSUU_PAINEL_URL . 'js/issuu-iframe-link.min.js',
		array('jquery'),
		null,
		true
	);
	wp_enqueue_script(
		'issuu-replace-tags',
		ISSUU_PAINEL_URL . 'js/replace-tags-p.min.js',
		array('jquery'),
		null,
		true
	);
}

add_action('admin_enqueue_scripts', 'issuu_painel_admin_enqueue_scripts');

function issuu_painel_admin_enqueue_scripts()
{
	wp_enqueue_style(
		'issuu-painel-pagination',
		ISSUU_PAINEL_URL . 'css/issuu-painel-pagination.css',
		array(),
		null,
		'screen, print'
	);
	wp_enqueue_style('document-list', ISSUU_PAINEL_URL . 'css/document-list.css', array(), null, 'screen, print');
	wp_enqueue_style('folder-list', ISSUU_PAINEL_URL . 'css/folder-list.css', array('dashicons'), null, 'screen, print');
	wp_enqueue_script('json2');
	wp_enqueue_script('jquery');

	if (isset($_GET['page']) && $_GET['page'] == 'issuu-document-admin')
	{
		if (isset($_GET['upload']))
		{
			wp_enqueue_script(
				'issuu-painel-document-upload-js',
				ISSUU_PAINEL_URL . 'js/document-upload.min.js',
				array('jquery'),
				null,
				true
			);
		}
		else if (isset($_GET['update']))
		{
			wp_enqueue_script(
				'issuu-painel-document-update-js',
				ISSUU_PAINEL_URL . 'js/document-update.min.js',
				array('jquery'),
				null,
				true
			);
		}
		else if (isset($_GET['url_upload']))
		{
			wp_enqueue_script(
				'issuu-painel-document-url-upload-js',
				ISSUU_PAINEL_URL . 'js/document-url-upload.min.js',
				array('jquery'),
				null,
				true
			);
		}
	}
}

add_action('admin_menu', 'ip_menu_admin');

function ip_menu_admin()
{
	global $api_key, $api_secret, $issuu_painel_capabilities, $issuu_painel_capacity;

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_GET['page']) && $_GET['page'] == 'issuu-painel-admin'))
	{
		update_option(ISSUU_PAINEL_PREFIX . 'api_key', trim($_POST['api_key']));
		update_option(ISSUU_PAINEL_PREFIX . 'api_secret', trim($_POST['api_secret']));

		if (in_array($_POST['enabled_user'], array('Administrator', 'Editor', 'Author')))
		{
			update_option(ISSUU_PAINEL_PREFIX . 'enabled_user', $_POST['enabled_user']);
		}
		else
		{
			$_POST['enabled_user'] = 'Administrator';
			update_option(ISSUU_PAINEL_PREFIX . 'enabled_user', 'Administrator');
		}

		$api_key = trim($_POST['api_key']);
		$api_secret = trim($_POST['api_secret']);
		$issuu_painel_capacity = $issuu_painel_capabilities[$_POST['enabled_user']];
	}

	do_action(ISSUU_PAINEL_PREFIX . 'menu_page');

	if ((!is_null($api_key) && strlen($api_key) > 0) && (!is_null($api_secret) && strlen($api_secret) > 0))
	{
		do_action(ISSUU_PAINEL_PREFIX . 'submenu_pages');
	}
}