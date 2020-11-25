<?php

defined('_EXEC') or die;

class Index_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		header('Location: /login');

		if (Format::exist_ajax_request() == true)
		{

		}
		else
		{
			define('_title', Configuration::$web_page . ' | ' . System::settings('seo', 'title', $GLOBALS['_vkye_module'], true) . ' | ' . System::settings('seo', 'keywords', $GLOBALS['_vkye_module'], true));

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
