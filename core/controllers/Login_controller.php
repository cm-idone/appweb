<?php

defined('_EXEC') or die;

class Login_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Format::exist_ajax_request() == true)
		{
			$errors = [];

			if (Validations::empty($_POST['email']) == false)
				array_push($errors, ['email','{$lang.dont_leave_this_field_empty}']);

			if (Validations::empty($_POST['password']) == false)
				array_push($errors, ['password','{$lang.dont_leave_this_field_empty}']);

			if (empty($errors))
			{
				$query = $this->model->read_session($_POST['email']);

				if (!empty($query))
				{
					$query['user']['password'] = explode(':', $query['user']['password']);
					$query['user']['password'] = ($this->security->create_hash('sha1', $_POST['password'] . $query['user']['password'][1]) == $query['user']['password'][0]) ? true : false;

					if ($query['user']['password'] == true)
					{
						Session::init();
						Session::set_value('session', true);
						Session::set_value('vkye_account', $query['account']);
						Session::set_value('vkye_user', $query['user']);
						Session::set_value('vkye_lang', $query['user']['language']);
						Session::set_value('vkye_time_zone', $query['account']['time_zone']);
						Session::set_value('vkye_temporal', []);

						echo json_encode([
							'status' => 'success',
							'path' => Permissions::redirection(true)
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'errors' => [
								['password','{$lang.invalid_password}']
							]
						]);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => [
							['email','{$lang.this_user_not_exist}']
						]
					]);
				}
			}
			else
			{
				echo json_encode([
					'status' => 'error',
					'errors' => $errors
				]);
			}
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.login}');

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
