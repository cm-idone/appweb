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
			define('_title', Configuration::$web_page . ' | Marbu Salud');

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

			global $global;

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
			{
				if ($global['custody_chain']['account'] != Session::get_value('vkye_account')['id'])
				{
					$break = true;

					foreach (Session::get_value('vkye_user')['accounts'] as $value)
					{
						if ($global['custody_chain']['account'] == $value['id'])
							$break = false;
					}

					if ($break == false)
					{
						$session = new System_model();
						$session = $session->read_session($global['custody_chain']['account'], 'id');

						Session::set_value('vkye_account', $session['account']);
						Session::set_value('vkye_user', $session['user']);
						Session::set_value('vkye_lang', $session['user']['language']);
						Session::set_value('vkye_temporal', []);

						header('Location: /laboratory/update/' . $params[0]);
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

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee']))
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

					if ($global['custody_chain']['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_1'], true) == false)
					   array_push($errors, ['test_1','{$lang.invalid_field}']);

					if ($global['custody_chain']['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_2'], true) == false)
					   array_push($errors, ['test_2','{$lang.invalid_field}']);

					if ($global['custody_chain']['type'] == 'alcoholic' AND Validations::number(['int','float'], $_POST['test_3'], true) == false)
					   array_push($errors, ['test_3','{$lang.invalid_field}']);

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
							if ($global['custody_chain']['closed'] == false)
								$_POST['qr']['filename'] = Session::get_value('vkye_account')['path'] . '_covid_qr_' . $global['custody_chain']['token'] . '.png';

							$_POST['pdf']['filename'] = Session::get_value('vkye_account')['path'] . '_covid_pdf_' . $global['custody_chain']['token'] . '_' . System::generate_random_string() . '.pdf';
						}

						$_POST['custody_chain'] = $global['custody_chain'];

						$query = $this->model->update_custody_chain($_POST);

    					if (!empty($query))
    					{
							if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee']))
							{
								$mail = new Mailer(true);

								try
								{
									$mail->setFrom(Configuration::$vars['marbu']['email'], 'Marbu Salud');
									$mail->addAddress($_POST['email'], $_POST['firstname'] . ' ' . $_POST['lastname']);
									$mail->addAttachment(PATH_UPLOADS . $_POST['pdf']['filename']);
									$mail->Subject = Languages::email('your_results_are_ready')[$global['custody_chain']['lang']];
									$mail->Body =
									'<html>
										<head>
											<title>' . $mail->Subject . '</title>
										</head>
										<body>
											<table style="width:600px;margin:0px;padding:0px;border: 1px dashed #757575;box-sizing:border-box;background-color:#fff;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
														<figure style="width:100%;margin:0px;padding:0px;text-align:center;">
															<img style="width:auto;height:160px;" src="https://' . Configuration::$domain . '/images/marbu_logotype_color.png">
														</figure>
													</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
														<h4 style="width:100%;margin:0px 0px 20px 0px;padding:0px;font-size:18px;font-weight:600;text-transform:uppercase;text-align:center;color:#000;">' . $mail->Subject . '</h4>
														<p style="width:100%;margin:0px 0px 20px 0px;padding:0px;font-size:14px;font-weight:400;text-align:center;color:#757575;">' . Languages::email('your_results_are_ready_text')[$global['custody_chain']['lang']] . '</p>
														<figure style="width:100%;margin:0px 0px 20px 0px;padding:0px;border:1px solid #000;box-sizing:border-box;text-align:center;">';

									if ($global['custody_chain']['closed'] == false)
										$mail->Body .= '<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">';
									else
										$mail->Body .= '<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $global['custody_chain']['qr'] . '">';

									$mail->Body .=
									'					</figure>
														<p style="width:100%;margin:0px;padding:0px;font-size:14px;font-weight:400;text-align:center;color:#757575;">
															' . Languages::email('get_covid_results_1')[$global['custody_chain']['lang']] . '
															<a href="https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/covid/' . $global['custody_chain']['token'] . '">' . Languages::email('click_here')[$global['custody_chain']['lang']] . '</a>
															' . Languages::email('get_covid_results_2')[$global['custody_chain']['lang']] . '
														</p>
													</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;">
														<a href="https://api.whatsapp.com/send?phone=' . Configuration::$vars['marbu']['phone'] . '" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">Whatsapp: ' . Configuration::$vars['marbu']['phone'] . '</p><br>
														<a href="tel:' . Configuration::$vars['marbu']['phone'] . '" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">' . Languages::email('call')[$global['custody_chain']['lang']] . ': ' . Configuration::$vars['marbu']['phone'] . '</p><br>
														<a href="mailto:' . Configuration::$vars['marbu']['email'] . '" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">' . Configuration::$vars['marbu']['email'] . '</p><br>
														<a href="https://facebook.com/' . Configuration::$vars['marbu']['facebook'] . '" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">FB: ' . Configuration::$vars['marbu']['facebook'] . '</p><br>
														<a href="https://linkedin.com/company/' . Configuration::$vars['marbu']['linkedin'] . '" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">IN: ' . Configuration::$vars['marbu']['linkedin'] . '</p><br>
														<a href="https://' . Configuration::$vars['marbu']['website'] . '" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">' . Configuration::$vars['marbu']['website'] . '</p><br>
														<a href="https://id.one-consultores.com" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">' . Languages::email('power_by')[$global['custody_chain']['lang']] . ' <strong>' . Configuration::$web_page . ' ' . Configuration::$web_version . '</strong></a><br>
														<a href="https://one-consultores.com" style="width:100%;margin:0px 0px 5px 0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">Copyright (C) One Consultores</a><br>
														<a href="https://codemonkey.com.mx" style="width:100%;margin:0px;padding:0px;font-size:12px;font-weight:400;text-align:left;color:#757575;">Software ' . Languages::email('development_by')[$global['custody_chain']['lang']] . ' Code Monkey</a>
													</td>
												</tr>
											</table>
										</body>
									</html>';
									$mail->send();
								}
								catch (Exception $e) {}
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
