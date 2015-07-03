<?php

class IssuuPanelConfig
{

	private static $issuuPanelDebug;

    private static $mobileDetect;

	private static $issuuPanelCatcher;

    private static $issuuPanelSimpleReader;

    private static $issuuPanelCron;

    /*
    |----------------------------------------
    |  VARIABLES
    |----------------------------------------
    */
    private static $issuu_panel_api_key;
    private static $issuu_panel_api_secret;
    private static $issuu_panel_enabled_user;
    private static $issuu_panel_capacity;
    private static $issuu_shortcode_index = 0;
    private static $issuu_panel_shortcode_cache;
    private static $issuu_panel_cache_status;
    private static $issuu_panel_reader;
    private static $iterator_per_template = array(
        '404' => 0,
        'page' => 0,
        'single' => 0,
        'tag' => 0,
        'author' => 0,
        'archive' => 0,
        'attachment' => 0,
        'category' => 0,
        'date' => 0,
        'day' => 0,
        'feed' => 0,
        'front_page' => 0,
        'home' => 0,
        'month' => 0,
        'search' => 0,
        'tax' => 0,
        'taxonomy_hierarchical' => 0,
        'time' => 0,
        'year' => 0,
    );

    /*
    |----------------------------------------
    |  CONSTANTS
    |----------------------------------------
    */
    private static $ISSUU_PANEL_CAPABILITIES = array(
        'Administrator' => 'manage_options',
        'Editor' => 'edit_private_pages',
        'Author' => 'upload_files'
    );

	public static function init()
	{
		// IssuuPanelDebug
		self::$issuuPanelDebug = new IssuuPanelDebug(get_option(ISSUU_PAINEL_PREFIX . 'debug'));
		self::$issuuPanelDebug->appendMessage("-----------------------", false);
		self::$issuuPanelDebug->appendMessage("Browser: " . $_SERVER['HTTP_USER_AGENT']);

		// Mobile_Detect
		self::$mobileDetect = new Mobile_Detect();

        // IssuuPanelCatcher
        self::$issuuPanelCatcher = new IssuuPanelCatcher();

        // IssuuPanelSimpleReader
        self::$issuuPanelSimpleReader = new IssuuPanelSimpleReader();

        // IssuuPanelCron
        self::$issuuPanelCron = new IssuuPanelCron();
        self::$issuuPanelCron->addScheduledAction('issuu_panel_flush_cache', array('IssuuPanelConfig', 'flushCache'), 'hour');
	}

    public static function getInstance()
    {
        return new static();
    }

	public static function setVariable($name, $value)
	{
		self::$$name = $value;
	}

    public static function getVariable($name)
    {
        return self::$$name;
    }

    public static function getNextIterator()
    {
        self::$issuu_shortcode_index++;
        return self::$issuu_shortcode_index;
    }

    public static function getNextIteratorByTemplate()
    {
        $key = self::$issuuPanelCatcher->getTemplate();
        self::$iterator_per_template[$key]++;
        return self::$iterator_per_template[$key];
    }

    public static function getCapability($name = null)
    {
        if (is_null($name) || !isset(self::$ISSUU_PANEL_CAPABILITIES[$name])) return self::$ISSUU_PANEL_CAPABILITIES;
        return self::$ISSUU_PANEL_CAPABILITIES[$name];
    }

    public static function generateShortcodeKey($shortcode, $params = array())
    {
        return md5($shortcode . http_build_query($params));
    }

    public static function inHeader()
    {
        return (self::$issuuPanelCatcher->getCurrentHookIs() == 'header');
    }

    public static function inFooter()
    {
        return (self::$issuuPanelCatcher->getCurrentHookIs() == 'footer');
    }

    public static function inSidebar()
    {
        return (self::$issuuPanelCatcher->getCurrentHookIs() == 'sidebar');
    }

    public static function inContent()
    {
        return (self::$issuuPanelCatcher->getCurrentHookIs() == 'content');
    }

    public static function getIssuuPanelCatcher()
    {
        return self::$issuuPanelCatcher;
    }

    /**
    *   Cache
    */
    public static function cacheIsActive()
    {
        return (self::$issuu_panel_cache_status == 'active');
    }

    public static function setCache($shortcode, $content = '', $params = array(), $page = 1)
    {
        $key = self::generateShortcodeKey($shortcode, $params);

        if (!isset(self::$issuu_panel_shortcode_cache[$key]))
        {
            self::$issuu_panel_shortcode_cache[$key] = array();
        }

        self::$issuu_panel_shortcode_cache[$key][$page] = $content;
    }

    public static function getCache($shortcode, $params = array(), $page = 1)
    {
        $key = self::generateShortcodeKey($shortcode, $params);

        if (isset(self::$issuu_panel_shortcode_cache[$key]))
        {
            if (isset(self::$issuu_panel_shortcode_cache[$key][$page]))
            {
                return self::$issuu_panel_shortcode_cache[$key][$page];
            }
        }

        return '';
    }

    public static function updateCache($shortcode = null, $content = '', $params = array(), $page = 1)
    {
        if (!is_null($shortcode))
        {
            self::setCache($shortcode, $content, $params, $page);
        }
        update_option(ISSUU_PAINEL_PREFIX . 'shortcode_cache', self::serializeCache());
    }

    public static function serializeCache()
    {
        return serialize(self::$issuu_panel_shortcode_cache);
    }

    public static function flushCache()
    {
        self::$issuu_panel_shortcode_cache = array();
        update_option(ISSUU_PAINEL_PREFIX . 'shortcode_cache', self::serializeCache());
    }

    public function isBot()
    {
        $utilities = self::$mobileDetect->getUtilities();
        $bots = spliti("\|", $utilities['Bot']);
        $mobileBots = spliti("\|", $utilities['MobileBot']);
        $userAgent = self::$mobileDetect->getHttpHeader('USER_AGENT');

        foreach ($bots as $bot) {
            if (strpos($userAgent, $bot) !== false)
            {
                return true;
            }
        }
        foreach ($mobileBots as $bot) {
            if (strpos($userAgent, $bot) !== false)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the value of issuuPanelDebug.
     *
     * @return mixed
     */
    public static function getIssuuPanelDebug()
    {
        return self::$issuuPanelDebug;
    }

    /**
     * Gets the value of mobileDetect.
     *
     * @return mixed
     */
    public static function getMobileDetect()
    {
        return self::$mobileDetect;
    }

    /**
     * Gets the value of issuuPanelSimpleReader.
     *
     * @return mixed
     */
    public static function getIssuuPanelSimpleReader()
    {
        return self::$issuuPanelSimpleReader;
    }

    /**
     * Gets the value of issuuPanelCron.
     *
     * @return mixed
     */
    public static function getIssuuPanelCron()
    {
        return self::$issuuPanelCron;
    }
}

IssuuPanelConfig::init();

function issuu_panel_debug($message)
{
	IssuuPanelConfig::getIssuuPanelDebug()->appendMessage($message);
}