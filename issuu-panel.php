<?php
/*
Plugin Name: Issuu Panel
Plugin URI: https://github.com/pedromarcelojava/Issuu-Painel
Description: Admin panel for Issuu. You can upload your documents, create folders and embed documents in posts.
Version: 1.6
Author: Pedro Marcelo
Author URI: https://www.linkedin.com/profile/view?id=265534858
License: GPL3
*/

/*
|--------------------------------------
|  CONSTANTS
|--------------------------------------
*/

define('ISSUU_PAINEL_DIR', plugin_dir_path(__FILE__));
define('ISSUU_PAINEL_URL', plugin_dir_url(__FILE__));
define('ISSUU_PAINEL_PREFIX', 'issuu_painel_');
define('ISSUU_PAINEL_DOMAIN_LANG', 'issuu-panel-domain-lang');
define('ISSUU_PAINEL_MENU', 'issuu-panel-admin');
define('ISSUU_PAINEL_PLUGIN_FILE', __FILE__);
define('ISSUU_PAINEL_PLUGIN_FILE_LANG', dirname(plugin_basename(__FILE__)) . '/lang/');

/*
|--------------------------------------
|  INCLUDES
|--------------------------------------
*/

require(ISSUU_PAINEL_DIR . 'includes/reader/class.issuupanelsimplereader.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelcatcher.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupaneldebug.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelcron.php');
require(ISSUU_PAINEL_DIR . 'includes/mobile-detect/Mobile_Detect.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelconfig.php');
require(ISSUU_PAINEL_DIR . 'issuuservice/issuu-lib.php');
require(ISSUU_PAINEL_DIR . 'includes/interfaces/interface.issuupanelpage.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelinitplugin.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelscripts.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupaneltinymcebutton.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelpaginate.php');
require(ISSUU_PAINEL_DIR . 'includes/classes/class.issuupanelsubmenu.php');
require(ISSUU_PAINEL_DIR . 'includes/functions.php');

/*
|--------------------------------------
|  MENU
|--------------------------------------
*/

include(ISSUU_PAINEL_DIR . 'menu/principal/config.php');
include(ISSUU_PAINEL_DIR . 'menu/documento/config.php');
include(ISSUU_PAINEL_DIR . 'menu/pasta/config.php');
include(ISSUU_PAINEL_DIR . 'menu/sobre/config.php');

/*
|--------------------------------------
|  SHORTCODES
|--------------------------------------
*/

include(ISSUU_PAINEL_DIR . 'shortcode/document-list.php');
include(ISSUU_PAINEL_DIR . 'shortcode/folder-list.php');
include(ISSUU_PAINEL_DIR . 'shortcode/the-last-document.php');

/*
|--------------------------------------
|  WIDGET
|--------------------------------------
*/

include(ISSUU_PAINEL_DIR . 'widget/class.issuupanelwidget.php');