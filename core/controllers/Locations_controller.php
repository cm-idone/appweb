<?php

defined('_EXEC') or die;

class Locations_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_location' OR $_POST['action'] == 'update_location')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_location')
						$query = $this->model->create_location($_POST);
					else if ($_POST['action'] == 'update_location')
						$query = $this->model->update_location($_POST);

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

			if ($_POST['action'] == 'read_location')
			{
				$query = $this->model->read_location($_POST['id']);

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

			if ($_POST['action'] == 'block_location' OR $_POST['action'] == 'unblock_location' OR $_POST['action'] == 'delete_location')
			{
				if ($_POST['action'] == 'block_location')
					$query = $this->model->block_location($_POST['id']);
				else if ($_POST['action'] == 'unblock_location')
					$query = $this->model->unblock_location($_POST['id']);
				else if ($_POST['action'] == 'delete_location')
					$query = $this->model->delete_location($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.locations}');

			global $data;

			$data['locations'] = $this->model->read_locations();

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
