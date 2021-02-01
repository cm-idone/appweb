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

				if (Validations::empty([$_POST['phone_country'],$_POST['phone_number']], true) == false)
					array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['phone_number'], true) == false)
					array_push($errors, ['phone_number','{$lang.invalid_field}']);

				if (Validations::empty($_POST['nie']) == false)
					array_push($errors, ['nie','{$lang.dont_leave_this_field_empty}']);
				else if ($this->model->check_exist_employee($_POST['id'], 'nie', $_POST['nie']) == true)
					array_push($errors, ['nie','{$lang.this_record_already_exists}']);

				if (Validations::empty($_POST['admission_date']) == true AND $_POST['admission_date'] > Dates::current_date())
					array_push($errors, ['admission_date','{$lang.invalid_field}']);

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
			$break = true;

			if ($params[0] == Session::get_value('vkye_account')['path'])
				$break = false;
			else
            {
				foreach (Session::get_value('vkye_user')['accounts'] as $value)
				{
					if ($params[0] == $value['path'])
						$break = false;
				}
            }

			if ($break == false)
			{
				if ($params[0] != Session::get_value('vkye_account')['path'])
				{
					$session = new System_model();
					$session = $session->read_session($params[0], 'path');

					Session::set_value('vkye_account', $session['account']);
					Session::set_value('vkye_user', $session['user']);
					Session::set_value('vkye_lang', $session['user']['language']);
					Session::set_value('vkye_temporal', []);
				}

				global $global;

				$global['employee'] = $this->model->read_employee($params[1], true);

				if (!empty($global['employee']))
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

				if ($_POST['action'] == 'load_custody_chanin')
				{
					$html =
					'<header>
	                    <figure>
	                        <img src="{$path.images}marbu_logotype_color.png">
	                    </figure>
	                    <h1>{$lang.custody_chanin}</h1>
	                    <figure>
	                        <img src="' . (!empty(Session::get_value('vkye_account')['avatar']) ? '{$path.uploads}' . Session::get_value('vkye_account')['avatar'] : '{$path.images}logotype_color.png') . '">
	                    </figure>
	                </header>
	                <form>
	                    <p>{$lang.custody_chanin_alert_1}</p>
	                    <h2>{$lang.donor_identification}</h2>
	                    <fieldset class="fields-group">
	                        <div class="row">
	                            <div class="span6">
	                                <div class="text">
	                                    <input type="text" value="' . $global['employee']['lastname'] . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.lastname} ({$lang.paternal_maternal})</h6>
	                                </div>
	                            </div>
	                            <div class="span6">
	                                <div class="text">
	                                    <input type="text" value="' . $global['employee']['firstname'] . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.firstname}</h6>
	                                </div>
	                            </div>
	                        </div>
	                    </fieldset>
	                    <fieldset class="fields-group">
	                        <div class="row">
	                            <div class="span8">
	                                <div class="text">
	                                    <input type="text" value="' . Session::get_value('vkye_account')['name'] . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.institution}</h6>
	                                </div>
	                            </div>
	                            <div class="span4">
	                                <div class="text">
	                                    <input type="text" value="' . (!empty($global['employee']['ife']) ? $global['employee']['ife'] : '{$lang.not_available}') . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.ife}</h6>
	                                </div>
	                            </div>
	                        </div>
	                    </fieldset>
	                    <fieldset class="fields-group">
	                        <div class="row">
	                            <div class="span3">
	                                <div class="text">
	                                    <input type="text" value="' . (!empty($global['employee']['birth_date']) ? Functions::format_age($global['employee']['birth_date']) : '{$lang.not_available}') . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.age}</h6>
	                                </div>
	                            </div>
	                            <div class="span6">
	                                <div class="text">
	                                    <input type="text" value="' . (!empty($global['employee']['birth_date']) ? Dates::format_date($global['employee']['birth_date'], 'long') : '{$lang.not_available}') . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.birth_date}</h6>
	                                </div>
	                            </div>
	                            <div class="span3">
	                                <div class="text">
	                                    <input type="text" value="' . (!empty($global['employee']['sex']) ? '{$lang.' . $global['employee']['sex'] . '}' : '{$lang.not_available}') . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.sex}</h6>
	                                </div>
	                            </div>
	                        </div>
	                    </fieldset>
	                    <h2>{$lang.exam_reasons}</h2>
	                    <fieldset class="fields-group">
	                        <div class="row">
	                            <div class="span3">
	                                <div class="text">
	                                    <input type="text" value="{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['type'] . '}" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.type}</h6>
	                                </div>
	                            </div>
	                            <div class="span3">
	                                <div class="text">
	                                    <input type="text" value="{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['reason'] . '}" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.reason}</h6>
	                                </div>
	                            </div>';

					if ($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['type'] == 'alcoholic')
					{
						$html .=
						'<div class="span2">
							<div class="text">
								<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['1']) ? number_format($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['1'], 2, '.', '') : '') . '" disabled>
							</div>
							<div class="title">
								<h6>{$lang.test} 1</h6>
							</div>
						</div>
						<div class="span2">
							<div class="text">
								<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['2']) ? number_format($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['2'], 2, '.', '') : '') . '" disabled>
							</div>
							<div class="title">
								<h6>{$lang.test} 2</h6>
							</div>
						</div>
						<div class="span2">
							<div class="text">
								<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['3']) ? number_format($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['3'], 2, '.', '') : '') . '" disabled>
							</div>
							<div class="title">
								<h6>{$lang.test} 3</h6>
							</div>
						</div>';
					}
					else if ($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['type'] == 'antidoping')
					{
						$html .=
						'<div class="span2">
							<div class="text">
								<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['COC']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['COC'] . '}' : '') . '" disabled>
							</div>
							<div class="title">
								<h6>COC</h6>
							</div>
						</div>
						<div class="span2">
							<div class="text">
								<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['THC']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['THC'] . '}' : '') . '" disabled>
							</div>
							<div class="title">
								<h6>THC</h6>
							</div>
						</div>
						<div class="span2">
							<div class="text">
								<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['MET']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['MET'] . '}' : '') . '" disabled>
							</div>
							<div class="title">
								<h6>MET</h6>
							</div>
						</div>';
					}

					$html .=
					'	</div>
	                </fieldset>';

					if ($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['type'] == 'antidoping')
					{
						$html .=
						'<fieldset class="fields-group">
							<div class="row">
								<div class="span2">
									<div class="text">
										<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['ANF']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['ANF'] . '}' : '') . '" disabled>
									</div>
									<div class="title">
										<h6>ANF</h6>
									</div>
								</div>
								<div class="span2">
									<div class="text">
										<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['BZD']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['BZD'] . '}' : '') . '" disabled>
									</div>
									<div class="title">
										<h6>BZD</h6>
									</div>
								</div>
								<div class="span2">
									<div class="text">
										<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['OPI']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['OPI'] . '}' : '') . '" disabled>
									</div>
									<div class="title">
										<h6>OPI</h6>
									</div>
								</div>
								<div class="span2">
									<div class="text">
										<input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['BAR']) ? '{$lang.' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['results']['BAR'] . '}' : '') . '" disabled>
									</div>
									<div class="title">
										<h6>BAR</h6>
									</div>
								</div>
							</div>
						</fieldset>';
					}

					$html .=
					'<h2>{$lang.medical_treatment}</h2>
	                <fieldset class="fields-group">
	                    <div class="text">
	                        <textarea disabled>' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['medicines']) ? $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['medicines'] : '') . '</textarea>
	                    </div>
	                    <div class="title">
	                        <h6>{$lang.prescription_drugs}</h6>
	                    </div>
	                </fieldset>
	                <fieldset class="fields-group">
	                    <div class="row">
	                        <div class="span8">
	                            <div class="text">
	                                <input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['prescription']['issued_by']) ? $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['prescription']['issued_by'] : '') . '" disabled>
	                            </div>
	                            <div class="title">
	                                <h6>{$lang.prescription_issued_by}</h6>
	                            </div>
	                        </div>
	                        <div class="span4">
	                            <div class="text">
	                                <input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['prescription']['date']) ? Dates::format_date($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['prescription']['date'], 'long') : '') . '" disabled>
	                            </div>
	                            <div class="title">
	                                <h6>{$lang.date}</h6>
	                            </div>
	                        </div>
	                    </div>
	                </fieldset>
	                <h2>{$lang.authorization}</h2>
	                <p>{$lang.custody_chanin_alert_' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['type'] . '_1}</p>
	                <fieldset class="fields-group">';

					if (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['signatures']['employee']))
					{
						$html .=
						'<div class="img">
							<figure>
								<img src="{$path.uploads}' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['signatures']['employee'] . '">
							</figure>
						</div>';
					}
					else
					{
						$html .=
						'<div class="text">
							<input type="text" value="{$lang.not_signature}" disabled>
						</div>';
					}

					$html .=
					'	<div class="title">
	                        <h6>{$lang.signature}</h6>
	                    </div>
	                </fieldset>
	                <h2>{$lang.collector}</h2>
	                <p>{$lang.custody_chanin_alert_' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['type'] . '_2}</p>
	                <fieldset class="fields-group">
	                    <div class="row">
	                        <div class="span8">
	                            <div class="text">
	                                <input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['collection']['place']) ? $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['collection']['place'] : '') . '" disabled>
	                            </div>
	                            <div class="title">
	                                <h6>{$lang.place}</h6>
	                            </div>
	                        </div>
	                        <div class="span4">
	                            <div class="text">
	                                <input type="text" value="' . Dates::format_hour($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['collection']['hour'], '12-long') . '" disabled>
	                            </div>
	                            <div class="title">
	                                <h6>{$lang.hour}</h6>
	                            </div>
	                        </div>
	                    </div>
	                </fieldset>
	                <fieldset class="fields-group">
	                    <div class="text">
	                        <input type="text" value="' . (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['comments']) ? $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['comments'] : '') . '" disabled>
	                    </div>
	                    <div class="title">
	                        <h6>{$lang.conformity_result}</h6>
	                    </div>
	                </fieldset>
	                <fieldset class="fields-group">
	                    <div class="row">
	                        <div class="span4">
	                            <div class="text">
	                                <input type="text" value="' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['collector_firstname'] . ' ' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['collector_lastname'] . '" disabled>
	                            </div>
	                            <div class="title">
	                                <h6>{$lang.name}</h6>
	                            </div>
	                        </div>
							<div class="span4">';

					if (!empty($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['signatures']['collector']))
					{
						$html .=
						'<div class="img">
							<figure>
								<img src="{$path.uploads}' . $global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['signatures']['collector'] . '">
							</figure>
						</div>';
					}
					else
					{
						$html .=
						'<div class="text">
							<input type="text" value="{$lang.not_signature}" disabled>
						</div>';
					}

					$html .=
	                '           	<div class="title">
	                                    <h6>{$lang.signature}</h6>
	                                </div>
	                            </div>
	                            <div class="span4">
	                                <div class="text">
	                                    <input type="text" value="' . Dates::format_date($global['employee']['custody_chanins'][$_POST['type']][$_POST['key']]['date'], 'long') . '" disabled>
	                                </div>
	                                <div class="title">
	                                    <h6>{$lang.date}</h6>
	                                </div>
	                            </div>
	                        </div>
	                    </fieldset>
	                </form>';

					echo json_encode([
						'status' => 'success',
						'html' => $html
					]);
				}
			}
			else
			{
                define('_title', Configuration::$web_page . ' | ' . $global['employee']['firstname'] . ' ' . $global['employee']['lastname']);

    			$template = $this->view->render($this, 'profile');

    			echo $template;
			}
		}
		else
			Permissions::redirection('employees');
	}
}
