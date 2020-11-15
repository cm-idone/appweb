<?php

defined('_EXEC') or die;

class Dashboard_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Format::exist_ajax_request() == true)
		{
			
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.dashboard}');

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
