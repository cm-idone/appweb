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
			define('_title', Configuration::$web_page . ' | ' . System::seo('title') . ' | ' . System::seo('keywords'));

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
