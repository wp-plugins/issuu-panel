<?php

class IssuuPanelConfig
{
	private static $issuuPanelDebug;

	private static $mobileDetect;

	public static function init()
	{
		// IssuuPanelDebug
		self::$issuuPanelDebug = new IssuuPanelDebug(get_option(ISSUU_PAINEL_PREFIX . 'debug'));
		self::$issuuPanelDebug->appendMessage("-----------------------", false);
		self::$issuuPanelDebug->appendMessage("Browser: " . $_SERVER['HTTP_USER_AGENT']);

		// Mobile_Detect
		self::$mobileDetect = new Mobile_Detect();
	}

    public static function getInstance()
    {
        return new static();
    }

	public function setVariable($name, $value)
	{
		$this->$name = $value;
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
}

IssuuPanelConfig::init();

function issuu_panel_debug($message)
{
	IssuuPanelConfig::getIssuuPanelDebug()->appendMessage($message);
}