<?php

class IssuuPanelConfig
{
	private $issuuPanelDebug;

	private $mobileDetect;

	public function __construct()
	{
		// IssuuPanelDebug
		$this->issuuPanelDebug = new IssuuPanelDebug(get_option(ISSUU_PAINEL_PREFIX . 'debug'));
		$this->issuuPanelDebug->appendMessage("-----------------------", false);
		$this->issuuPanelDebug->appendMessage("Browser: " . $_SERVER['HTTP_USER_AGENT']);

		// Mobile_Detect
		$this->mobileDetect = new Mobile_Detect();
	}

	public function setVariable($name, $value)
	{
		$this->$name = $value;
	}

    public function isBot()
    {
        $utilities = $this->mobileDetect->getUtilities();
        $bots = spliti("\|", $utilities['Bot']);
        $mobileBots = spliti("\|", $utilities['MobileBot']);
        $userAgent = $this->mobileDetect->getHttpHeader('USER_AGENT');

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
    public function getIssuuPanelDebug()
    {
        return $this->issuuPanelDebug;
    }

    /**
     * Gets the value of mobileDetect.
     *
     * @return mixed
     */
    public function getMobileDetect()
    {
        return $this->mobileDetect;
    }
}

$issuuPanelConfig = new IssuuPanelConfig();

function issuu_panel_debug($message)
{
	global $issuuPanelConfig;
	$issuuPanelConfig->getIssuuPanelDebug()->appendMessage($message);
}