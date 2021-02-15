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
		$go = false;

		global $global;

		$global['account'] = $this->model->read_account($params[0]);

		if (!empty($global['account']))
		{
			Session::set_value('vkye_time_zone', $global['account']['time_zone']);

			if (!empty($params[1]))
			{
				$global['custody_chain'] = $this->model->read_custody_chain($params[1]);

				if (!empty($global['custody_chain']) AND ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac'))
				{
					Session::set_value('vkye_lang', $global['custody_chain']['lang']);

					if ($global['custody_chain']['closed'] == true)
						$global['render'] = 'results';
					else
					{
						$global['render'] = 'create';

						System::temporal('set_forced', 'covid', 'contact', [
							'token' => $global['custody_chain']['token'],
							'qr' => [
								'filename' => $global['custody_chain']['qr']
							],
							'email' => $global['custody_chain']['contact']['email']
						]);
					}

					$go = true;
				}
			}
			else
			{
				$global['render'] = 'create';
				$go = true;
			}
		}

		if ($go == true)
		{
			if (Format::exist_ajax_request() == true)
	        {
	            if ($_POST['action'] == 'registry')
				{
					$errors = [];

		            if (Validations::empty($_POST['firstname']) == false)
		                array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

		            if (Validations::empty($_POST['lastname']) == false)
		                array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

					if (Validations::empty($_POST['ife']) == false)
		                array_push($errors, ['ife','{$lang.dont_leave_this_field_empty}']);

		            if (Validations::empty($_POST['birth_date_year']) == false)
		                array_push($errors, ['birth_date_year','{$lang.dont_leave_this_field_empty}']);

		            if (Validations::empty($_POST['birth_date_month']) == false)
		                array_push($errors, ['birth_date_month','{$lang.dont_leave_this_field_empty}']);

		            if (Validations::empty($_POST['birth_date_day']) == false)
		                array_push($errors, ['birth_date_day','{$lang.dont_leave_this_field_empty}']);

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

		            if (Validations::empty($_POST['type']) == false)
		                array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

		            if (Validations::empty($_POST['travel_to']) == false)
		                array_push($errors, ['travel_to','{$lang.dont_leave_this_field_empty}']);

		            if (empty($errors))
		            {
		                $_POST['token'] = System::generate_random_string();
						$_POST['birth_date'] = $_POST['birth_date_year'] . '-' . $_POST['birth_date_month'] . '-' . $_POST['birth_date_day'];
						$_POST['qr']['filename'] = 'tmp_' . $params[0] . '_covid_qr_' . $_POST['token'] . '.png';
						$_POST['account'] = $global['account']['id'];

		                $query = $this->model->create_custody_chain($_POST);

		                if (!empty($query))
		                {
		                    System::temporal('set_forced', 'covid', 'contact', $_POST);

							$mail1 = new Mailer(true);

							try
							{
								$mail1->setFrom(Configuration::$vars['marbu']['email'], 'Marbu Salud');
								$mail1->addAddress($_POST['email'], $_POST['firstname'] . ' ' . $_POST['lastname']);
								$mail1->Subject = Languages::email('your_token_is')[Session::get_value('vkye_lang')] . ': ' . $_POST['token'];
								$mail1->Body =
								'<html>
									<head>
										<title>' . $mail1->Subject . '</title>
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
												<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">' . $mail1->Subject . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
													<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">
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
												<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">' . Languages::email('power_by')[Session::get_value('vkye_lang')] . ' <a style="font-weight:600;text-decoration:none;color:#fff;" href="https://id.one-consultores.com">' . Configuration::$web_page . ' ' . Configuration::$web_version . '</a></td>
											</tr
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Copyright (C) <a style="text-decoration:none;color:#fff;" href="https://one-consultores.com">One Consultores</a></td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Software ' . Languages::email('development_by')[Session::get_value('vkye_lang')] . ' <a style="text-decoration:none;color:#fff;" href="https://codemonkey.com.mx">Code Monkey</a></td>
											</tr>
										</table>
									</body>
								</html>';
								$mail1->send();
							}
							catch (Exception $e) {}

							$mail2 = new Mailer(true);

							try
							{
								$mail2->setFrom(Configuration::$vars['marbu']['email'], 'Marbu Salud');
								$mail2->addAddress(Configuration::$vars['marbu']['email'], 'Marbu Salud');
								$mail2->Subject = 'Nueva prueba Covid. ' . $_POST['firstname'] . ' ' . $_POST['lastname'] . '. Folio: ' . $_POST['token'];
								$mail2->Body =
								'<html>
									<head>
										<title>' . $mail1->Subject . '</title>
									</head>
									<body>
										<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#fff;">
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Nombre:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['firstname'] . ' ' . $_POST['lastname'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Pasaporte:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['ife'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Nacimiento:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['birth_date'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Edad:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['age'] . ' Años</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Sexo:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['sex'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Email:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['email'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Teléfono:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">+' . $_POST['phone_country'] . ' ' . $_POST['phone_number'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Viaja a:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['travel_to'] . '</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#000;">Tipo de prueba:</td>
												<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#757575;">' . $_POST['type'] . '</td>
											</tr>
										</table>
									</body>
								</html>';
								$mail2->send();
							}
							catch (Exception $e) {}

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

				if ($_POST['action'] == 'restore_registry')
				{
					System::temporal('set_forced', 'covid', 'contact', []);

					echo json_encode([
						'status' => 'success',
						'message' => '{$lang.operation_success}'
					]);
				}
	        }
	        else
	        {
	            define('_title', 'Marbu Salud | Covid');

	            if ($global['render'] == 'results' OR System::temporal('get_if_exists', 'covid', 'contact') == false)
	                System::temporal('set_forced', 'covid', 'contact', []);

	            $template = $this->view->render($this, 'index');

	            echo $template;
	        }
		}
	}
}
