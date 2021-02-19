<?php

defined('_EXEC') or die;

include_once(PATH_MODELS . 'System_model.php');
require_once 'plugins/nexmo/vendor/autoload.php';

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
			define('_title', Configuration::$web_page . ' | Marbu Salud');

			$template = $this->view->render($this, 'marbu');

			echo $template;
		}
    }

	public function index($params)
    {
        if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'filter_custody_chains')
			{
				$filter = System::temporal('get', 'laboratory', 'filter');

				if ($_POST['filter'] == 'true')
				{
					$filter['account'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $_POST['account'] : '';
					$filter['deleted_status'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $_POST['deleted_status'] : '';
					$filter['type'] = ($params[0] == 'covid') ? $_POST['type'] : $params[0];
					$filter['start_date'] = $_POST['start_date'];
					$filter['end_date'] = $_POST['end_date'];
					$filter['start_hour'] = $_POST['start_hour'];
					$filter['end_hour'] = $_POST['end_hour'];
					$filter['sended_status'] = ($params[0] == 'covid') ? $_POST['sended_status'] : '';
				}
				else if ($_POST['filter'] == 'false')
					$filter = [];

				System::temporal('set_forced', 'laboratory', 'filter', $filter);

				echo json_encode([
					'status' => 'success',
					'message' => '{$lang.operation_success}'
				]);
			}

			if ($_POST['action'] == 'restore_custody_chain' OR $_POST['action'] == 'delete_custody_chain')
			{
				if ($_POST['action'] == 'restore_custody_chain')
					$query = $this->model->restore_custody_chain($_POST['id']);
				else if ($_POST['action'] == 'delete_custody_chain')
					$query = $this->model->delete_custody_chain($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.laboratory} | {$lang.' . $params[0] . '}');

			if (System::temporal('get_if_exists', 'laboratory', 'filter') == false)
				System::temporal('set_forced', 'laboratory', 'filter', []);

			global $global;

			$global['render'] = $params[0];
			$global['custody_chains'] = $this->model->read_custody_chains($params[0]);

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

					if (($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an' OR $global['type'] == 'covid_ac') AND Validations::empty($_POST['start_process']) == false)
    					array_push($errors, ['start_process','{$lang.dont_leave_this_field_empty}']);

					if (($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an' OR $global['type'] == 'covid_ac') AND Validations::empty($_POST['end_process']) == false)
    					array_push($errors, ['end_process','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_1'], true) == false)
					   array_push($errors, ['test_1','{$lang.invalid_field}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_2'], true) == false)
					   array_push($errors, ['test_2','{$lang.invalid_field}']);

					if ($global['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_3'], true) == false)
					   array_push($errors, ['test_3','{$lang.invalid_field}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_result']) == false)
						array_push($errors, ['test_result','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_unity']) == false)
						array_push($errors, ['test_unity','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_reference_values']) == false)
						array_push($errors, ['test_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_result']) == false)
						array_push($errors, ['test_igm_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_reference_values']) == false)
						array_push($errors, ['test_igm_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_result']) == false)
						array_push($errors, ['test_igg_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_reference_values']) == false)
						array_push($errors, ['test_igg_reference_values','{$lang.dont_leave_this_field_empty}']);

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
    							'message' => '{$lang.operation_success}',
    							'path' => 'go_back'
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

			$global['custody_chain'] = $this->model->read_custody_chain($params[0]);

			if (!empty($global['custody_chain']))
				$go = true;
		}

		if ($go == true)
		{
			if (Format::exist_ajax_request() == true)
			{
				if ($_POST['action'] == 'update_custody_chain')
				{
					$errors = [];

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee']))
					{
						if (Validations::empty($_POST['firstname']) == false)
	    					array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['lastname']) == false)
	    					array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['ife']) == false)
	    					array_push($errors, ['ife','{$lang.dont_leave_this_field_empty}']);

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

						if (Validations::empty($_POST['lang']) == false)
						   array_push($errors, ['lang','{$lang.dont_leave_this_field_empty}']);
					}

    				if (Validations::empty($_POST['reason']) == false)
    					array_push($errors, ['reason','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND Validations::empty($_POST['start_process']) == false)
    					array_push($errors, ['start_process','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND Validations::empty($_POST['end_process']) == false)
    					array_push($errors, ['end_process','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_1'], true) == false)
					   array_push($errors, ['test_1','{$lang.invalid_field}']);

					if ($global['custody_chain']['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_2'], true) == false)
					   array_push($errors, ['test_2','{$lang.invalid_field}']);

					if ($global['custody_chain']['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_3'], true) == false)
					   array_push($errors, ['test_3','{$lang.invalid_field}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_result']) == false)
						array_push($errors, ['test_result','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_unity']) == false)
						array_push($errors, ['test_unity','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_reference_values']) == false)
						array_push($errors, ['test_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_result']) == false)
						array_push($errors, ['test_igm_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_reference_values']) == false)
						array_push($errors, ['test_igm_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_result']) == false)
						array_push($errors, ['test_igg_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_reference_values']) == false)
						array_push($errors, ['test_igg_reference_values','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['date']) == false)
    					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['hour']) == false)
    					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['collector']) == false)
    					array_push($errors, ['collector','{$lang.dont_leave_this_field_empty}']);

    				if (empty($errors))
    				{
						if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee']))
						{
							$_POST['qr']['filename'] = 'covid_qr_' . $global['custody_chain']['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.png';
							$_POST['pdf']['filename'] = 'covid_pdf_' . $global['custody_chain']['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.pdf';
						}

						$_POST['custody_chain'] = $global['custody_chain'];

						$query = $this->model->update_custody_chain($_POST);

    					if (!empty($query))
    					{
							if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee']) AND $_POST['save'] == 'save_and_send')
							{
								$mail = new Mailer(true);

								try
								{
									$mail->setFrom(Configuration::$vars['marbu']['email'], 'Marbu Salud');
									$mail->addAddress($_POST['email'], $_POST['firstname'] . ' ' . $_POST['lastname']);
									$mail->addAttachment(PATH_UPLOADS . $_POST['pdf']['filename']);
									$mail->Subject = '¡' . Languages::email('hi')[$_POST['lang']] . ' ' . explode(' ',  $_POST['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$_POST['lang']];
									$mail->Body =
									'<html>
										<head>
											<title>' . $mail->Subject . '</title>
										</head>
										<body>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#004770;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100px;margin:0px;padding:20px 0px 20px 20px;border:0px;box-sizing:border-box;vertical-align:middle;">
														<img style="width:100px" src="https://' . Configuration::$domain . '/images/marbu_logotype_color_circle.png">
													</td>
													<td style="width:auto;margin:0px;padding:20px;border:0px;box-sizing:border-box;vertical-align:middle;">
														<table style="width:100%;margin:0px;padding:0px;border:0px;">
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:600;text-align:right;color:#fff;">Marbu Salud S.A. de C.V.</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">MSA1907259GA</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">Av. Nichupté SM51 M42 L1</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">CP: 77533 Cancún, Qroo. México</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:20px 0px;padding:0px;border:1px dashed #000;box-sizing:border-box;background-color:#fff;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">' . Languages::email('ready_results')[$_POST['lang']] . '</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:center;color:#757575;">¡' . Languages::email('hi')[Session::get_value('vkye_lang')] . ' <strong>' . explode(' ', $_POST['firstname'])[0] . '</strong>! ' . Languages::email('get_covid_results_1')[$_POST['lang']] . ' <strong>' . Dates::format_date($global['custody_chain']['date'], 'short') . '</strong> ' . Languages::email('get_covid_results_2')[$_POST['lang']] . '</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
														<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">
													</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
														<a style="width:100%;display:block;margin:0px;padding:10px;border:0px;border-radius:5px;box-sizing:border-box;background-color:#009688;font-size:14px;font-weight:400;text-align:center;text-decoration:none;color:#fff;" href="https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/covid/' . $global['custody_chain']['token'] . '">' . Languages::email('view_online_results')[$_POST['lang']] . '</a>
													</td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#0b5178;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="tel:' . Configuration::$vars['marbu']['phone'] . '">' . Configuration::$vars['marbu']['phone'] . '</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="mailto:' . Configuration::$vars['marbu']['email'] . '">' . Configuration::$vars['marbu']['email'] . '</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="https://' . Configuration::$vars['marbu']['website'] . '">' . Configuration::$vars['marbu']['website'] . '</a></td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#004770;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">' . Languages::email('power_by')[$_POST['lang']] . ' <a style="font-weight:600;text-decoration:none;color:#fff;" href="https://id.one-consultores.com">' . Configuration::$web_page . ' ' . Configuration::$web_version . '</a></td>
												</tr
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Copyright (C) <a style="text-decoration:none;color:#fff;" href="https://one-consultores.com">One Consultores</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Software ' . Languages::email('development_by')[$_POST['lang']] . ' <a style="text-decoration:none;color:#fff;" href="https://codemonkey.com.mx">Code Monkey</a></td>
												</tr>
											</table>
										</body>
									</html>';
									$mail->send();
								}
								catch (Exception $e) {}

								// $sms = new \Nexmo\Client\Credentials\Basic('51db0b68', 'd2TTUheuHp6BqYep');
								// $sms = new \Nexmo\Client($sms);
								//
								// try
								// {
								// 	$sms->message()->send([
								// 		'to' => $_POST['phone_country'] . $_POST['phone_number'],
								// 		'from' => 'Marbu Salud',
								// 		'text' => '¡' . Languages::email('hi')[$_POST['lang']] . ' ' . explode(' ',  $_POST['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$_POST['lang']] . '. ' . Languages::email('we_send_email_1')[Session::get_value('vkye_lang')] . ' ' . $_POST['email'] . ' ' . Languages::email('we_send_email_3')[Session::get_value('vkye_lang')] . ': https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/covid/' . $global['custody_chain']['token'] . '. ' . Languages::email('power_by')[Session::get_value('vkye_lang')] . ' ' . Configuration::$web_page . ' ' . Configuration::$web_version . '.'
								// 	]);
								// }
								// catch (Exception $e) {}
							}

    						echo json_encode([
    							'status' => 'success',
    							'message' => '{$lang.operation_success}',
								'path' => (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? '/laboratory/covid' : 'go_back'
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
	}
}
