<?php

defined('_EXEC') or die;

class Covid_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($params)
	{
		global $global;

		$global['account'] = $this->model->read_account($params[0]);

        if (Format::exist_ajax_request() == true)
        {
            $errors = [];

            if (Validations::empty($_POST['firstname']) == false)
                array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

            if (Validations::empty($_POST['lastname']) == false)
                array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

            if (Validations::empty($_POST['birth_date']) == false)
                array_push($errors, ['birth_date','{$lang.dont_leave_this_field_empty}']);

            if (Validations::empty($_POST['age']) == false)
                array_push($errors, ['age','{$lang.dont_leave_this_field_empty}']);
            else if (Validations::number('int', $_POST['age']) == false)
                array_push($errors, ['age','{$lang.invalid_field}']);

            if (Validations::empty($_POST['id']) == false)
                array_push($errors, ['id','{$lang.dont_leave_this_field_empty}']);
            else if (Validations::string(['uppercase','lowercase','int'], $_POST['id']) == false)
                array_push($errors, ['id','{$lang.invalid_field}']);

            if (Validations::empty($_POST['email']) == false)
                array_push($errors, ['email','{$lang.dont_leave_this_field_empty}']);

            if (Validations::empty([$_POST['phone_country'],$_POST['phone_number']]) == false)
                array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);
			else if (Validations::number('int', $_POST['phone_number']) == false)
				array_push($errors, ['phone_number','{$lang.invalid_field}']);

            if (Validations::empty($_POST['test']) == false)
                array_push($errors, ['test','{$lang.dont_leave_this_field_empty}']);

            if (Validations::empty($_POST['travel']) == false)
                array_push($errors, ['travel','{$lang.dont_leave_this_field_empty}']);

            if (empty($errors))
            {
                $_POST['token'] = System::generate_random_string();
				$_POST['account'] = $global['account'];

                $query = $this->model->create_custody_chain($_POST);

                if (!empty($query))
                {
                    System::temporal('set_forced', 'covid', 'result', $_POST);

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
        else
        {
            define('_title', Configuration::$web_page . ' | Marbu {$lang.laboratory}');

            if (System::temporal('get_if_exists', 'covid', 'result') == false)
                System::temporal('set_forced', 'covid', 'result', []);

            $template = $this->view->render($this, 'index');

            echo $template;
        }
	}
}
