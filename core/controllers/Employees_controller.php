<?php

defined('_EXEC') or die;

include_once(PATH_MODELS . 'System_model.php');

class Employees_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_employee' OR $_POST['action'] == 'update_employee')
			{
				$errors = [];

				if (Validations::empty($_POST['firstname']) == false)
					array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['lastname']) == false)
					array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['ife']) == true AND $this->model->check_exist_employee($_POST['id'], 'ife', $_POST['ife']) == true)
					array_push($errors, ['ife','{$lang.this_record_already_exists}']);

				if (Validations::empty($_POST['nss']) == true AND $this->model->check_exist_employee($_POST['id'], 'nss', $_POST['nss']) == true)
					array_push($errors, ['nss','{$lang.this_record_already_exists}']);

				if (Validations::empty($_POST['rfc']) == true AND $this->model->check_exist_employee($_POST['id'], 'rfc', $_POST['rfc']) == true)
					array_push($errors, ['rfc','{$lang.this_record_already_exists}']);

				if (Validations::empty($_POST['curp']) == true AND $this->model->check_exist_employee($_POST['id'], 'curp', $_POST['curp']) == true)
					array_push($errors, ['curp','{$lang.this_record_already_exists}']);

				if (Validations::email($_POST['email'], true) == false)
					array_push($errors, ['email','{$lang.invalid_field}']);

				if (Validations::empty([$_POST['phone_country'],$_POST['phone_number']], true) == false)
					array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['phone_number'], true) == false)
					array_push($errors, ['phone_number','{$lang.invalid_field}']);

				if (Validations::empty($_POST['nie']) == false)
					array_push($errors, ['nie','{$lang.dont_leave_this_field_empty}']);
				else if ($this->model->check_exist_employee($_POST['id'], 'nie', $_POST['nie']) == true)
					array_push($errors, ['nie','{$lang.this_record_already_exists}']);

				if (Validations::empty([$_POST['emergency_contacts_first_phone_country'],$_POST['emergency_contacts_first_phone_number']], true) == false)
					array_push($errors, ['emergency_contacts_first_phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['emergency_contacts_first_phone_number'], true) == false)
					array_push($errors, ['emergency_contacts_first_phone_number','{$lang.invalid_field}']);

				if (Validations::empty([$_POST['emergency_contacts_second_phone_country'],$_POST['emergency_contacts_second_phone_number']], true) == false)
					array_push($errors, ['emergency_contacts_second_phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['emergency_contacts_second_phone_number'], true) == false)
					array_push($errors, ['emergency_contacts_second_phone_number','{$lang.invalid_field}']);

				if (Validations::empty([$_POST['emergency_contacts_third_phone_country'],$_POST['emergency_contacts_third_phone_number']], true) == false)
					array_push($errors, ['emergency_contacts_third_phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['emergency_contacts_third_phone_number'], true) == false)
					array_push($errors, ['emergency_contacts_third_phone_number','{$lang.invalid_field}']);

				if (Validations::empty([$_POST['emergency_contacts_fourth_phone_country'],$_POST['emergency_contacts_fourth_phone_number']], true) == false)
					array_push($errors, ['emergency_contacts_fourth_phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['emergency_contacts_fourth_phone_number'], true) == false)
					array_push($errors, ['emergency_contacts_fourth_phone_number','{$lang.invalid_field}']);

				if (empty($errors))
				{
					$_POST['files'] = $_FILES;

					if ($_POST['action'] == 'create_employee')
						$query = $this->model->create_employee($_POST);
					else if ($_POST['action'] == 'update_employee')
						$query = $this->model->update_employee($_POST);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
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
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'read_employee')
			{
				$query = $this->model->read_employee($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $query
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

			if ($_POST['action'] == 'block_employee' OR $_POST['action'] == 'unblock_employee' OR $_POST['action'] == 'delete_employee')
			{
				if ($_POST['action'] == 'block_employee')
					$query = $this->model->block_employee($_POST['id']);
				else if ($_POST['action'] == 'unblock_employee')
					$query = $this->model->unblock_employee($_POST['id']);
				else if ($_POST['action'] == 'delete_employee')
					$query = $this->model->delete_employee($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'message' => '{$lang.operation_success}'
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
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.employees}');

			global $global;

			$global['employees'] = $this->model->read_employees();

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}

	public function profile($params)
	{
		$go = false;

        if (!empty($params[0]) AND !empty($params[1]))
        {
			global $global;

			$global['employee'] = $this->model->read_employee($params[1], true);

			if (!empty($global['employee']))
			{
				if ($global['employee']['account'] != Session::get_value('vkye_account')['id'])
				{
					$break = true;

					foreach (Session::get_value('vkye_user')['accounts'] as $value)
					{
						if ($global['employee']['account'] == $value['id'])
							$break = false;
					}

					if ($break == false)
					{
						$session = new System_model();
						$session = $session->read_session($global['employee']['account'], 'id');

						Session::set_value('vkye_account', $session['account']);
						Session::set_value('vkye_user', $session['user']);
						Session::set_value('vkye_lang', $session['user']['language']);
						Session::set_value('vkye_temporal', []);

						header('Location: /' . $params[0] . '/' . $params[1]);
					}
				}
				else
					$go = true;
			}
        }

		if ($go == true)
		{
			if (Format::exist_ajax_request() == true)
			{
				if ($_POST['action'] == 'preview_doc')
				{
					$html = '';

					if ($_POST['doc'] == 'recommendation_letters_first')
						$_POST['doc'] = $global['employee']['docs']['recommendation_letters']['first'];
					else if ($_POST['doc'] == 'recommendation_letters_second')
						$_POST['doc'] = $global['employee']['docs']['recommendation_letters']['second'];
					else if ($_POST['doc'] == 'recommendation_letters_third')
						$_POST['doc'] = $global['employee']['docs']['recommendation_letters']['third'];
					else
						$_POST['doc'] = $global['employee']['docs'][$_POST['doc']];

					if (!empty($_POST['doc']))
					{
						$_POST['doc'] = explode('.', $_POST['doc']);

						if ($_POST['doc'][1] == 'pdf')
							$html .= '<iframe src="https://docs.google.com/viewer?url=https://' . Configuration::$domain . '/uploads/' . $_POST['doc'][0] . '.' . $_POST['doc'][1] . '&embedded=true"></iframe>';
						else
						{
							$html .=
							'<figure>
								<img src="{$path.uploads}' . $_POST['doc'][0] . '.' . $_POST['doc'][1] . '">
							</figure>';
						}
					}

					echo json_encode([
						'status' => 'success',
						'html' => $html
					]);
				}

				// if ($_POST['action'] == 'load_custody_chanin')
				// {
				// 	$html =
				// 	'';
				//
				// 	echo json_encode([
				// 		'status' => 'success',
				// 		'html' => $html
				// 	]);
				// }
			}
			else
			{
                define('_title', Configuration::$web_page . ' | ' . $global['employee']['firstname'] . ' ' . $global['employee']['lastname']);

    			$template = $this->view->render($this, 'profile');

    			echo $template;
			}
		}
	}
}
