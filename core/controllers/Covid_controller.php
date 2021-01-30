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

		if (!empty($global['account']))
		{
			if (Format::exist_ajax_request() == true)
	        {
	            if ($_POST['action'] == 'contact')
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

		            if (Validations::empty($_POST['type']) == false)
		                array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

		            if (Validations::empty($_POST['travel_to']) == false)
		                array_push($errors, ['travel_to','{$lang.dont_leave_this_field_empty}']);

		            if (empty($errors))
		            {
		                $_POST['token'] = System::generate_random_string();
						$_POST['qr']['filename'] = 'tmp_' . $params[0] . '_covid_qr_' . $_POST['token'] . '.png';
						$_POST['account'] = $global['account']['id'];

		                $query = $this->model->create_custody_chain($_POST);

		                if (!empty($query))
		                {
		                    System::temporal('set_forced', 'covid', 'contact', $_POST);

							$mail = new Mailer(true);

							try
							{
								$mail->setFrom(Configuration::$vars['marbu']['email'], 'Marbu ' . Languages::email('laboratory')[Session::get_value('vkye_lang')]);
								$mail->addAddress($_POST['email'], $_POST['firstname'] . ' ' . $_POST['lastname']);
								$mail->Subject = Languages::email('your_token_is')[Session::get_value('vkye_lang')] . ': ' . $_POST['token'] ;
								$mail->Body =
								'<html>
									<head>
										<title>' . $mail->Subject . '</title>
									</head>
									<body>
										<table style="width:600px;margin:0px;padding:0px;border:0px;background-color:#fff">
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:40px;border:0px;box-sizing:border-box;background-color:#fff;">
													<figure style="width:100%;margin:0px;padding:0px;text-align:center;">
														<img style="width:auto;height:200px;" src="https://' . Configuration::$domain . '/images/marbu_logotype_color.png">
														<img style="width:auto;height:200px;margin-left:40px;" src="https://' . Configuration::$domain . '/' . (!empty($global['account']['avatar']) ? 'uploads/' . $global['account']['avatar'] : 'images/logotype_color.png') . '">
													</figure>
												</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:40px;border:0px;box-sizing:border-box;background-color:#fff;">
													<h4 style="width:100%;margin:0px 0px 20px 0px;padding:0px;font-size:18px;font-weight:600;text-align:center;color:#212121;">' . $mail->Subject . '</h4>
													<figure style="width:100%;margin:0px;padding:0px;text-align:center;">
														<img style="width:auto;height:300px;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">
													</figure>
												</td>
											</tr>
											<tr style="width:100%;margin:0px;padding:0px;border:0px;">
												<td style="width:100%;margin:0px;padding:40px;border:0px;box-sizing:border-box;background-color:#fff;">
													<p>' . Configuration::$vars['marbu']['phone'] . ' | ' . Configuration::$vars['marbu']['email'] . ' | ' . Configuration::$vars['marbu']['website'] . '</p>
													<p>' . Languages::email('power_by')[Session::get_value('vkye_lang')] . ' <strong>' . Configuration::$web_page . ' ' . Configuration::$web_version . '</strong> | Software ' . Languages::email('development_by')[Session::get_value('vkye_lang')] . ' Code Monkey</p>
												</td>
											</tr>
										</table>
									</body>
								</html>';
								$mail->send();
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

				if ($_POST['action'] == 'reload_form')
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
	            define('_title', 'Marbu {$lang.laboratory} | Covid');

	            if (System::temporal('get_if_exists', 'covid', 'contact') == false)
	                System::temporal('set_forced', 'covid', 'contact', []);

	            $template = $this->view->render($this, 'index');

	            echo $template;
	        }
		}
		else
			header('Location: /');
	}
}
