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

	/**
	*
	*	@var string $message
	*/
	private $message;

	public function __construct($status = 'disable')
	{
		$this->status = $status;
		$this->dir = dirname(__FILE__);
		$this->logDir = $this->dir . '/../log/';
		$this->debugFile = $this->logDir . 'issuu-panel-debug.txt';
		$this->message = '';

		if (!$this->status) $this->status = 'disable';

		if (!is_dir($this->logDir))
		{
			mkdir($this->logDir);
		}

		if (!is_file($this->debugFile))
		{
			file_put_contents($this->debugFile, "");
		}
	}

	public function __destruct()
	{
		file_put_contents($this->debugFile, $this->message, FILE_APPEND);
	}

	public function appendMessage($message, $insertDate = true)
	{
		if ($this->status == 'active')
		{
			if ($insertDate === true)
			{
				$message = date_i18n('[Y-m-d H:i:s] - ') . $message;
			}

			$this->message .= $message . "\n";
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

    /**
     * Gets the value of message.
     *
     * @return string $message
     */
    public function getMessage()
    {
        return $this->message;
    }
}