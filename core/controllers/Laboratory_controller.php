<?php

defined('_EXEC') or die;

include_once(PATH_MODELS . 'System_model.php');

class Laboratory_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function marbu()
    {
        if (Format::exist_ajax_request() == true)
		{

		}
		else
		{
			define('_title', Configuration::$web_page . ' | Marbu {$lang.laboratory}');

			$template = $this->view->render($this, 'marbu');

			echo $template;
		}
    }

    public function index($params)
    {
        if (Format::exist_ajax_request() == true)
		{

		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.laboratory} | {$lang.' . $params[0] . '}');

			$template = $this->view->render($this, 'index');

			echo $template;
		}
    }

	public function create($params)
	{
        $go = false;

        if (!empty($params[0]))
        {
            global $global;

            $global['type'] = $params[0];

            if (($global['type'] == 'alcoholic' OR $global['type'] == 'antidoping' OR $global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an' OR $global['type'] == 'covid_ac') AND !empty($params[1]))
            {
				$global['employee'] = $this->model->read_employee($params[1]);

				if (!empty($global['employee']))
					$go = true;
            }
        }

		if ($go == true)
        {
            if (Format::exist_ajax_request() == true)
    		{
                if ($_POST['action'] == 'create_custody_chain')
                {
                    $errors = [];

					if (Validations::empty($_POST['reason']) == false)
    					array_push($errors, ['reason','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_1'], true) == false)
					   array_push($errors, ['test_1','{$lang.invalid_field}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_2'], true) == false)
					   array_push($errors, ['test_2','{$lang.invalid_field}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_3'], true) == false)
					   array_push($errors, ['test_3','{$lang.invalid_field}']);

                    if (Validations::empty($_POST['date']) == false)
    					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['hour']) == false)
    					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['collector']) == false)
    					array_push($errors, ['collector','{$lang.dont_leave_this_field_empty}']);

    				if (empty($errors))
    				{
                        $_POST['employee'] = $global['employee']['id'];
                        $_POST['type'] = $global['type'];

						$query = $this->model->create_custody_chain($_POST);

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
    		}
    		else
    		{
    			define('_title', Configuration::$web_page . ' | {$lang.do_test} | {$lang.' . $params[0] . '}');

				$global['locations'] = $this->model->read_locations();

    			$template = $this->view->render($this, 'create');

    			echo $template;
    		}
        }
        else
            Permissions::redirection('laboratory');
	}

	public function update($params)
	{
		$go = false;

		if (!empty($params[0]))
		{
			global $global;

			$global['custody_chanin'] = $this->model->read_custody_chanin($params[0]);

			if (!empty($global['custody_chanin']))
			{
				if ($global['custody_chanin']['account'] != Session::get_value('vkye_account')['id'])
				{
					$break = true;

					foreach (Session::get_value('vkye_user')['accounts'] as $value)
					{
						if ($global['custody_chanin']['account'] == $value['id'])
							$break = false;
					}

					if ($break == false)
					{
						$session = new System_model();
						$session = $session->read_session($params[0], 'id');

						Session::set_value('vkye_account', $session['account']);
						Session::set_value('vkye_user', $session['user']);
						Session::set_value('vkye_lang', $session['user']['language']);
						Session::set_value('vkye_temporal', []);

						$go = true;
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
				if ($_POST['action'] == 'update_custody_chain')
				{
					$errors = [];

					if (($global['custody_chanin']['type'] == 'covid_pcr' OR $global['custody_chanin']['type'] == 'covid_an' OR $global['custody_chanin']['type'] == 'covid_ac') AND empty($global['custody_chanin']['employee']))
					{
						if (Validations::empty($_POST['firstname']) == false)
	    					array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['lastname']) == false)
	    					array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['ife']) == false)
	    					array_push($errors, ['ife','{$lang.dont_leave_this_field_empty}']);
						else if (Validations::string(['uppercase','int'], $_POST['ife']) == false)
			                array_push($errors, ['ife','{$lang.invalid_field}']);

						if (Validations::empty($_POST['birth_date']) == false)
					   		array_push($errors, ['birth_date','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['age']) == false)
	    					array_push($errors, ['age','{$lang.dont_leave_this_field_empty}']);
						else if (Validations::number('int', $_POST['age']) == false)
						   array_push($errors, ['age','{$lang.invalid_field}']);

					   	if (Validations::empty($_POST['sex']) == false)
					   		array_push($errors, ['sex','{$lang.dont_leave_this_field_empty}']);

					   	if (Validations::empty($_POST['email']) == false)
					   		array_push($errors, ['email','{$lang.dont_leave_this_field_empty}']);
					   	else if (Validations::email($_POST['email']) == false)
					   		array_push($errors, ['email','{$lang.invalid_field}']);

						if (Validations::empty([$_POST['phone_country'],$_POST['phone_number']]) == false)
			                array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);
						else if (Validations::number('int', $_POST['phone_number']) == false)
							array_push($errors, ['phone_number','{$lang.invalid_field}']);

						if (Validations::empty($_POST['travel_to']) == false)
						   array_push($errors, ['travel_to','{$lang.dont_leave_this_field_empty}']);
					}

    				if (Validations::empty($_POST['reason']) == false)
    					array_push($errors, ['reason','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_1'], true) == false)
					   array_push($errors, ['test_1','{$lang.invalid_field}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_2'], true) == false)
					   array_push($errors, ['test_2','{$lang.invalid_field}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_3'], true) == false)
					   array_push($errors, ['test_3','{$lang.invalid_field}']);

                    if (Validations::empty($_POST['date']) == false)
    					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['hour']) == false)
    					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['collector']) == false)
    					array_push($errors, ['collector','{$lang.dont_leave_this_field_empty}']);

    				if (empty($errors))
    				{
						$_POST['custody_chanin'] = $global['custody_chanin'];

						$query = $this->model->update_custody_chain($_POST);

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
			}
			else
			{
				define('_title', Configuration::$web_page . ' | {$lang.update_test} | ' . $params[0]);

				$global['locations'] = $this->model->read_locations();

				$template = $this->view->render($this, 'update');

				echo $template;
			}
		}
		else
            Permissions::redirection('laboratory');
	}
}
