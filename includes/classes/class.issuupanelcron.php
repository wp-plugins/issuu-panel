<?php

class IssuuPanelCron
{
	/**
	*	Hour in seconds
	*
	*	@var integer
	*	@access protected
	*/
	protected static $HOUR = 3600;

	/**
	*	Day in seconds
	*
	*	@var integer
	*	@access protected
	*/
	protected static $DAY = 86400;

	/**
	*	Week in seconds
	*
	*	@var integer
	*	@access protected
	*/
	protected static $WEEK = 604800;

	/**
	*	Month in seconds
	*
	*	@var integer
	*	@access protected
	*/
	protected static $MONTH = 2592000;

	/**
	*	Array of scheduled actions
	*
	*	@var array
	*	@access private
	*/
	private $scheduledActions = array();

	public function __construct()
	{
		$this->setActions(get_option(ISSUU_PAINEL_PREFIX . 'cron'));
		add_action('init', array($this, 'trigger'));
	}

	public function trigger()
	{
		foreach ($this->scheduledActions as $key => $action) {
			if ($action['init'] + $action['next_trigger'] <= current_time('timestamp'))
			{
				$run = call_user_func($action['callback'], $action['args']);

				if ($run === false)
				{
					unset($this->scheduledActions[$key]);
				}
				else
				{
					$this->updateAction($key);
				}
			}
		}

		update_option(ISSUU_PAINEL_PREFIX . 'cron', serialize($this->scheduledActions));
	}

	public function addScheduledAction($key, $callback, $interval = 'week')
	{
		$args = func_get_args();

		if (count($args) > 3)
		{
			$args = array_slice($args, 3);
		}
		else
		{
			$args = array();
		}

		switch ($interval) {
			case 'hour':
				$time = self::$HOUR;
				break;
			case 'day':
				$time = self::$DAY;
				break;
			case 'week':
				$time = self::$WEEK;
				break;
			case 'month':
				$time = self::$MONTH;
				break;
			default:
				$time = self::$WEEK;
				break;
		}

		if (!isset($this->scheduledActions[$key]))
		{
			$this->scheduledActions[$key] = array(
				'init' => current_time('timestamp'),
				'next_trigger' => $time,
				'callback' => $callback,
				'args' => $args
			);
		}
		else
		{
			$this->scheduledActionsForUpdate[$key] = array();

			if ($this->scheduledActions[$key]['callback'] != $callback)
			{
				$this->scheduledActions[$key]['callback'] = $callback; 
			}

			if ($this->scheduledActions[$key]['args'] != $args)
			{
				$this->scheduledActions[$key]['args'] = $args;
			}

			if ($this->scheduledActions[$key]['next_trigger'] != $time)
			{
				$this->scheduledActions[$key]['next_trigger'] = $time;
			}
		}

		return $this;
	}

	protected function updateAction($key)
	{
		if (isset($this->scheduledActions[$key]))
		{
			$this->scheduledActions[$key]['init'] = current_time('timestamp');
		}
	}

	protected function setActions($actions)
	{
		if (is_string($actions))
		{
			$this->scheduledActions = unserialize($actions);
		}
		else if (is_array($actions))
		{
			$this->scheduledActions = $actions;
		}
		else
		{
			$this->scheduledActions = array();
		}

		return $this;
	}
}