<?php

class IssuuPanelDebug
{
	/**
	*
	*	@var string $status
	*/
	private $status;

	/**
	*
	*	@var string $dir
	*/
	private $dir;

	/**
	*
	*	@var string $logDir
	*/
	private $logDir;

	/**
	*
	*	@var string $debugFile
	*/
	private $debugFile;

	public function __construct($status = 'active')
	{
		$this->status = $status;
		$this->dir = dirname(__FILE__);
		$this->logDir = $this->dir . '/../log/';
		$this->debugFile = $this->logDir . 'issuu-panel-debug.txt';


		if (!is_dir($this->logDir))
		{
			mkdir($this->logDir);
		}

		if (!is_file($this->debugFile))
		{
			file_put_contents($this->debugFile, "");
		}
	}

	public function appendMessage($message, $insertDate = true)
	{
		if ($this->status == 'active')
		{
			if ($insertDate === true)
			{
				$message = date_i18n('[Y-m-d H:i:s] - ') . $message;
			}

			file_put_contents($this->debugFile, $message . "\n", FILE_APPEND);
		}
	}

    /**
     * Gets the value of dir.
     *
     * @return string $dir
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Gets the value of logDir.
     *
     * @return string $logDir
     */
    public function getLogDir()
    {
        return $this->logDir;
    }

    /**
     * Gets the value of debugFile.
     *
     * @return string $debugFile
     */
    public function getDebugFile()
    {
        return $this->debugFile;
    }
}