<?php

abstract class IssuuPanelSubmenu implements IssuuPanelPage
{
	protected $slug;

	protected $menu_title;

	protected $page_title;

	protected $priority = 1;

	public function __construct()
	{
		add_action(ISSUU_PAINEL_PREFIX . 'submenu_pages', array($this, 'init'), $this->priority);
	}

	public function init()
	{
		add_submenu_page(
			ISSUU_PAINEL_MENU,
			get_issuu_message($this->page_title),
			get_issuu_message($this->menu_title),
			IssuuPanelConfig::getVariable('issuu_panel_capacity'),
			$this->slug,
			array($this, 'page')
		);	
	}
}