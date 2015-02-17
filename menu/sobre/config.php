<?php

class IssuuPageAbout extends IssuuPanelSubmenu
{
	protected $slug = 'issuu-panel-about';

	protected $page_title = 'About';

	protected $menu_title = 'About';

	protected $priority = 3;

	public function page()
	{
		include(ISSUU_PAINEL_DIR . 'menu/sobre/page.php');
	}
}

new IssuuPageAbout();