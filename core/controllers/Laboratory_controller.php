<?php

defined('_EXEC') or die;

class Laboratory_controller extends Controller
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
			define('_title', Configuration::$web_page . ' | {$lang.laboratory}');

			$template = $this->view->render($this, 'index');

			echo $template;
		}
    }

	public function create($params)
	{
        $go = false;

        if (!empty($params[0]))
        {
            global $data;

            $data['type'] = $params[0];

            if (($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping') AND !empty($params[1]))
            {
				$data['employee'] = $this->model->read_employee($params[1]);

				if (!empty($data['employee']))
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

                    if (Validations::empty($_POST['collection_hour']) == false)
    					array_push($errors, ['collection_hour','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['date']) == false)
    					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

    				if (empty($errors))
    				{
                        $_POST['employee'] = $data['employee']['id'];
                        $_POST['type'] = $data['type'];

						$query = $this->model->create_custody_chain($_POST);

    					if (!empty($query))
    					{
    						echo json_encode([
    							'status' => 'success',
    							'message' => '{$lang.operation_success}',
                                'path' => '/' . Session::get_value('vkye_account')['path'] . '/' . $data['employee']['nie']
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
    			define('_title', Configuration::$web_page . ' | {$lang.do_test}');

				global $data;

				$data['locations'] = $this->model->read_locations();

    			$template = $this->view->render($this, 'create');

    			echo $template;
    		}
        }
        else
            Permissions::redirection('laboratory');
	}

    public function alcoholic()
    {
        if (Format::exist_ajax_request() == true)
		{

		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.alcoholic}');

			$template = $this->view->render($this, 'alcoholic');

			echo $template;
		}
    }

	public function antidoping()
    {
        if (Format::exist_ajax_request() == true)
		{

		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.antidoping}');

			$template = $this->view->render($this, 'antidoping');

			echo $template;
		}
    }

	public function covid()
    {
        if (Format::exist_ajax_request() == true)
		{

		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.covid}');

			$template = $this->view->render($this, 'covid');

			echo $template;
		}
    }
}
