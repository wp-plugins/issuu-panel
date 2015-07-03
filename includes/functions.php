<?php

function issuu_panel_quick_sort($array, $order = 'asc')
{
	$length = count($array);
	
	if ($length <= 1)
	{
		return $array;
	}
	else
	{
		$pivot = $array[0];
		$left = $right = array();
		$c = count($array);
		for ($i = 1; $i < $c; $i++) {
			if ($order == 'asc')
			{
				if ($array[$i]['pubTime'] < $pivot['pubTime'])
				{
					$left[] = $array[$i];
				}
				else
				{
					$right[] = $array[$i];
				}
			}
			else
			{
				if ($array[$i]['pubTime'] > $pivot['pubTime'])
				{
					$left[] = $array[$i];
				}
				else
				{
					$right[] = $array[$i];
				}
			}
		}
		
		return array_merge(issuu_panel_quick_sort($left, $order), array($pivot), issuu_panel_quick_sort($right, $order));
	}
}

function get_issuu_message($text)
{
	return __($text, ISSUU_PAINEL_DOMAIN_LANG);
}

function the_issuu_message($text)
{
	_e($text, ISSUU_PAINEL_DOMAIN_LANG);
}

function issuu_panel_link_page($page, $permalink, $page_name)
{
	$QUERY_STRING = $_SERVER['QUERY_STRING'];

	if (strpos($permalink, '?') === false)
	{
		if ($QUERY_STRING == "")
		{
			$link = $permalink . '?' . $page_name . '=' . $page;
		}
		else
		{
			if (strpos($QUERY_STRING, $page_name) === false)
			{
				$link = $permalink . '?' . $QUERY_STRING . '&' . $page_name . '=' . $page;
			}
			else
			{

				$QUERY_STRING = preg_replace("/($page_name=\d)/", $page_name . '=' . $page, $QUERY_STRING);
				$link = $permalink . '?' . $QUERY_STRING;
			}
		}
	}
	else
	{
		$pos = strpos($permalink, '?') + 1;
		$substr = substr($permalink, $pos);
		$arr = array($substr => '');
		$QUERY_STRING = strtr($QUERY_STRING, $arr);
		$QUERY_STRING = preg_replace('/\&' . $page_name . '\=\d/', '', $QUERY_STRING);

		if ($QUERY_STRING == "")
		{
			$link = $permalink . '&' . $page_name . '=' . $page;
		}
		else
		{
			if (strpos($QUERY_STRING, $page_name) === false)
			{
				$link = $permalink . '&' . $QUERY_STRING . '&' . $page_name . '=' . $page;
			}
			else
			{
				$QUERY_STRING = preg_replace("/($page_name=\d)/", $page_name . '=' . $page, $QUERY_STRING);
				$link = $permalink . '&' . $QUERY_STRING;
			}
		}
	}

	return preg_replace('/\&{2,}/', '&', $link);
}

function ip_menu_admin()
{
	$issuu_panel_api_key = IssuuPanelConfig::getVariable('issuu_panel_api_key');
	$issuu_panel_api_secret = IssuuPanelConfig::getVariable('issuu_panel_api_secret');

	do_action(ISSUU_PAINEL_PREFIX . 'menu_page');
	issuu_panel_debug("Issuu Panel menu loaded");

	if ((!is_null($issuu_panel_api_key) && strlen($issuu_panel_api_key) > 0) &&
		(!is_null($issuu_panel_api_secret) && strlen($issuu_panel_api_secret) > 0))
	{
		do_action(ISSUU_PAINEL_PREFIX . 'submenu_pages');
		issuu_panel_debug("Issuu Panel submenus loaded");
	}
}

add_action('admin_menu', 'ip_menu_admin');