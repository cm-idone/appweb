<?php

defined('_EXEC') or die;

class System_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_account')
			{
				$query = $this->model->read_session($_POST['id'], 'id');

				if (!empty($query))
				{
					Session::set_value('vkye_account', $query['account']);
					Session::set_value('vkye_user', $query['user']);
					Session::set_value('vkye_lang', $query['user']['language']);
					Session::set_value('vkye_temporal', []);

					echo json_encode([
						'status' => 'success'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'change_god_mode')
			{
				$user = Session::get_value('vkye_user');

				if ($user['god'] == 'activate_but_sleep')
					$user['god'] = 'activate_and_wake_up';
				else if ($user['god'] == 'activate_and_wake_up')
					$user['god'] = 'activate_but_sleep';

				Session::set_value('vkye_user', $user);

				echo json_encode([
					'status' => 'success'
				]);
			}

			if ($_POST['action'] == 'logout')
			{
				Session::destroy();

				echo json_encode([
					'status' => 'success',
					'path' => '/login'
				]);
			}
		}
	}
}
