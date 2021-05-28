<?php

defined('_EXEC') or die;

require_once 'plugins/nexmo/vendor/autoload.php';
require 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

class Laboratory_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($params)
    {
        if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'filter_custody_chains')
			{
				$filter = System::temporal('get', 'laboratory', 'filter');

				$filter['laboratory'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $_POST['laboratory'] : '';
				$filter['taker'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $_POST['taker'] : '';
				$filter['collector'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $_POST['collector'] : '';
				$filter['deleted_status'] = $_POST['deleted_status'];
				$filter['type'] = ($params[0] == 'covid') ? $_POST['type'] : $params[0];
				$filter['start_date'] = ($_POST['deleted_status'] == 'not_deleted') ? $_POST['start_date'] : $filter['start_date'];
				$filter['end_date'] = ($_POST['deleted_status'] == 'not_deleted') ? $_POST['end_date'] : $filter['end_date'];
				$filter['start_hour'] = ($_POST['deleted_status'] == 'not_deleted') ? $_POST['start_hour'] : $filter['start_hour'];
				$filter['end_hour'] = ($_POST['deleted_status'] == 'not_deleted') ? $_POST['end_hour'] : $filter['end_hour'];
				$filter['sent_status'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND $_POST['deleted_status'] == 'not_deleted') ? $_POST['sent_status'] : '';

				System::temporal('set_forced', 'laboratory', 'filter', $filter);

				echo json_encode([
					'status' => 'success',
					'message' => '{$lang.operation_success}'
				]);
			}

			if ($_POST['action'] == 'send_custody_chains')
			{
				$errors = [];

				if (Validations::empty($_POST['laboratory']) == false)
					array_push($errors, ['laboratory','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['taker']) == false)
					array_push($errors, ['taker','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['type']) == false)
					array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['start_date']) == false)
					array_push($errors, ['start_date','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['start_hour']) == false)
					array_push($errors, ['start_hour','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['end_date']) == false)
					array_push($errors, ['end_date','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['end_hour']) == false)
					array_push($errors, ['end_hour','{$lang.dont_leave_this_field_empty}']);

				if (($_POST['type'] == 'covid_pcr' OR $_POST['type'] == 'covid_an') AND Validations::empty($_POST['test_result']) == false)
					array_push($errors, ['test_result','{$lang.dont_leave_this_field_empty}']);

				if (($_POST['type'] == 'covid_pcr' OR $_POST['type'] == 'covid_an') AND Validations::empty($_POST['test_unity']) == false)
					array_push($errors, ['test_unity','{$lang.dont_leave_this_field_empty}']);

				if (($_POST['type'] == 'covid_pcr' OR $_POST['type'] == 'covid_an') AND Validations::empty($_POST['test_reference_values']) == false)
					array_push($errors, ['test_reference_values','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_result']) == false)
					array_push($errors, ['test_igm_result','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_unity']) == false)
					array_push($errors, ['test_igm_unity','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_reference_values']) == false)
					array_push($errors, ['test_igm_reference_values','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_result']) == false)
					array_push($errors, ['test_igg_result','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_unity']) == false)
					array_push($errors, ['test_igg_unity','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_reference_values']) == false)
					array_push($errors, ['test_igg_reference_values','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['end_process']) == false)
					array_push($errors, ['end_process','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['chemical']) == false)
					array_push($errors, ['chemical','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					$query1 = $this->model->read_custody_chains($_POST, 'sender');

					if (!empty($query1))
					{
						foreach ($query1 as $value)
						{
							set_time_limit(100000000);

							$_POST['qr']['filename'] = $value['laboratory_path'] . '_' . $value['type'] . '_qr_results_' . $value['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.png';
							$_POST['pdf']['filename'] = $value['laboratory_path'] . '_' . $value['type'] . '_pdf_results_' . $value['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.pdf';
							$_POST['custody_chain'] = $value;

							$query2 = $this->model->update_custody_chain($_POST, true);

							if (!empty($query2))
							{
								$mail = new Mailer(true);

								try
								{
									$mail->setFrom($value['laboratory_email'], $value['laboratory_name']);
									$mail->addAddress($value['contact']['email'], $value['contact']['firstname'] . ' ' . $value['contact']['lastname']);
									$mail->addAttachment(PATH_UPLOADS . $_POST['pdf']['filename']);
									$mail->Subject = '¡' . Languages::email('hi')[$value['lang']] . ' ' . explode(' ',  $value['contact']['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$value['lang']];
									$mail->Body =
									'<html>
										<head>
											<title>' . $mail->Subject . '</title>
										</head>
										<body>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $value['laboratory_colors']['first'] . ';">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100px;margin:0px;padding:20px 0px 20px 20px;border:0px;box-sizing:border-box;vertical-align:middle;">
														<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $value['laboratory_avatar'] . '">
													</td>
													<td style="width:auto;margin:0px;padding:20px;border:0px;box-sizing:border-box;vertical-align:middle;">
														<table style="width:100%;margin:0px;padding:0px;border:0px;">
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:600;text-align:right;text-transform:uppercase;color:#fff;">' . $value['laboratory_name'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $value['laboratory_rfc'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $value['laboratory_sanitary_opinion'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $value['laboratory_address']['first'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $value['laboratory_address']['second']. '</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:20px 0px;padding:0px;border:1px dashed #bdbdbd;box-sizing:border-box;background-color:#fff;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">¡' . Languages::email('ready_results')[$value['lang']] . '!</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:center;color:#757575;">¡' . Languages::email('hi')[$value['lang']] . ' <strong>' . explode(' ', $value['contact']['firstname'])[0] . '</strong>! ' . Languages::email('get_covid_results_1')[$value['lang']] . ' <strong>' . Dates::format_date($value['date'], 'short') . '</strong> ' . Languages::email('get_covid_results_2')[$value['lang']] . '</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
														<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">
													</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
														<a style="width:100%;display:block;margin:0px;padding:10px;border:0px;border-radius:5px;box-sizing:border-box;background-color:#009688;font-size:14px;font-weight:400;text-align:center;text-decoration:none;color:#fff;" href="https://' . Configuration::$domain . '/' . $value['laboratory_path'] . '/results/' . $value['token'] . '">' . Languages::email('view_online_results')[$value['lang']] . '</a>
													</td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $value['laboratory_colors']['second'] . ';">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="tel:' . $value['laboratory_phone'] . '">' . $value['laboratory_phone'] . '</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="mailto:' . $value['laboratory_email'] . '">' . $value['laboratory_email'] . '</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="https://' . $value['laboratory_website'] . '">' . $value['laboratory_website'] . '</a></td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $value['laboratory_colors']['first'] . ';">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">' . Languages::email('power_by')[$value['lang']] . ' <a style="font-weight:600;text-decoration:none;color:#fff;" href="https://id.one-consultores.com">' . Configuration::$web_page . ' ' . Configuration::$web_version . '</a></td>
												</tr
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Copyright (C) <a style="text-decoration:none;color:#fff;" href="https://one-consultores.com">One Consultores</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Software ' . Languages::email('development_by')[$value['lang']] . ' <a style="text-decoration:none;color:#fff;" href="https://codemonkey.com.mx">Code Monkey</a></td>
												</tr>
											</table>
										</body>
									</html>';
									$mail->send();
								}
								catch (Exception $e) {}

								$sms = new \Nexmo\Client\Credentials\Basic('51db0b68', 'd2TTUheuHp6BqYep');
								$sms = new \Nexmo\Client($sms);

								try
								{
									$sms->message()->send([
										'to' => $value['contact']['phone']['country'] . $value['contact']['phone']['number'],
										'from' => $value['laboratory_name'],
										'text' => '¡' . Languages::email('hi')[$value['lang']] . ' ' . explode(' ',  $value['contact']['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$value['lang']] . '. ' . Languages::email('we_send_email_1')[$value['lang']] . ' ' . $value['contact']['email'] . ' ' . Languages::email('we_send_email_3')[$value['lang']] . ': https://' . Configuration::$domain . '/' . $value['laboratory_path'] . '/results/' . $value['token'] . '. ' . Languages::email('power_by')[$value['lang']] . ' ' . Configuration::$web_page . ' ' . Configuration::$web_version . '.'
									]);
								}
								catch (Exception $e) {}
							}
						}

						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.not_found_custody_chains}'
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

			if ($_POST['action'] == 'report_custody_chains')
			{
				$errors = [];

				if (Validations::empty($_POST['laboratory']) == false)
					array_push($errors, ['laboratory','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['taker']) == false)
					array_push($errors, ['taker','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['type']) == false)
					array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['start_date']) == false)
					array_push($errors, ['start_date','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['start_hour']) == false)
					array_push($errors, ['start_hour','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['end_date']) == false)
					array_push($errors, ['end_date','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['end_hour']) == false)
					array_push($errors, ['end_hour','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['emails']) == false)
					array_push($errors, ['emails','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					$query = $this->model->read_custody_chains($_POST, 'report');

					if (!empty($query))
					{
						set_time_limit(100000000);

						$_POST['pdf'] = $query['laboratory']['path'] . '_day_report_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.pdf';

						$query['report'] = [];

						foreach ($query['custody_chains'] as $value)
						{
							if (array_key_exists($value['collector_name'], $query['report']))
							{
								if (array_key_exists($value['type'], $query['report'][$value['collector_name']]))
									array_push($query['report'][$value['collector_name']][$value['type']], $value);
								else
									$query['report'][$value['collector_name']][$value['type']][0] = $value;
							}
							else
								$query['report'][$value['collector_name']][$value['type']][0] = $value;
						}

						$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [0,0,0,0]);
						$writing =
						'<table style="width:100%;margin:0px;padding:20px 40px;border:0px;border-top:20px;border-color:' . $query['laboratory']['colors']['second'] . ';box-sizing:border-box;background-color:#fff;">
						    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
						            <img style="width:100%;" src="' . PATH_UPLOADS . $query['laboratory']['avatar'] . '">
						        </td>
						        <td style="width:80%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
						            <table style="width:100%;margin:0px;padding:0px;border:0px;">
						                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:24px;font-weight:600;text-transform:uppercase;text-align:right;color:' . $query['laboratory']['colors']['first'] . ';">' . Languages::email('day_report')[Session::get_value('vkye_lang')] . ': ' . (($_POST['start_date'] == $_POST['end_date']) ? $_POST['start_date'] : $_POST['start_date'] . ' ' . Languages::email('to')[Session::get_value('vkye_lang')] . ' ' . $_POST['start_date']) . '</td>
						                </tr>
						                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:18px;font-weight:400;text-transform:uppercase;text-align:right;color:' . $query['laboratory']['colors']['second'] . ';">' .  Languages::email('laboratory_analisys')[Session::get_value('vkye_lang')] . ' <span style="font-weight:600;">' . $query['laboratory']['name'] . '</span></td>
						                </tr>
						                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $query['laboratory']['colors']['second'] . ';">' . $query['laboratory']['address']['first'] . '</td>
						                </tr>
						                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $query['laboratory']['colors']['second'] . ';">' . $query['laboratory']['address']['second'] . '</td>
						                </tr>
						                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $query['laboratory']['colors']['second'] . ';">' . $query['laboratory']['rfc'] . ' | ' . $query['laboratory']['sanitary_opinion'] . '</td>
						                </tr>
						            </table>
						        </td>
						    </tr>
						</table>';

						foreach ($query['report'] as $key => $value)
						{
							$writing .=
							'<table style="width:100%;margin:0px;padding:0px 40px 5px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
							    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
							        <td style="width:100%;margin:0px;padding:0px 0px 0px 10px;border:0px;border-left:5px;border-color:' . $query['laboratory']['colors']['second'] . ';box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:' . $query['laboratory']['colors']['first'] . ';">' . $key . '</td>
							    </tr>
							</table>
							<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
								<tr style="width:100%;margin:0px;padding:0px;border:0px;">
									<td style="width:25%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $query['laboratory']['colors']['first'] . ';">' . Languages::email('total')[Session::get_value('vkye_lang')] . ': <span style="color:' . $query['laboratory']['colors']['second'] . ';">' . (!empty($query['report'][$key]['covid_pcr']) ? count($query['report'][$key]['covid_pcr']) : '0') . ' ' . Languages::email('tests')[Session::get_value('vkye_lang')] . '</span></td>
									<td style="width:25%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $query['laboratory']['colors']['first'] . ';">' . Languages::email('pcr')[Session::get_value('vkye_lang')] . ': <span style="color:' . $query['laboratory']['colors']['second'] . ';">' . (!empty($query['report'][$key]['covid_pcr']) ? count($query['report'][$key]['covid_pcr']) : '0') . ' ' . Languages::email('tests')[Session::get_value('vkye_lang')] . '</span></td>
									<td style="width:25%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $query['laboratory']['colors']['first'] . ';">' . Languages::email('antigen')[Session::get_value('vkye_lang')] . ': <span style="color:' . $query['laboratory']['colors']['second'] . ';">' . (!empty($query['report'][$key]['covid_an']) ? count($query['report'][$key]['covid_an']) : '0') . ' ' . Languages::email('tests')[Session::get_value('vkye_lang')] . '</span></td>
									<td style="width:25%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $query['laboratory']['colors']['first'] . ';">' . Languages::email('anticorps')[Session::get_value('vkye_lang')] . ': <span style="color:' . $query['laboratory']['colors']['second'] . ';">' . (!empty($query['report'][$key]['covid_ac']) ? count($query['report'][$key]['covid_ac']) : '0') . ' ' . Languages::email('tests')[Session::get_value('vkye_lang')] . '</span></td>
								</tr>
							</table>';

							if (!empty($value['covid_pcr']))
							{
								$writing .= '<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">';

								foreach ($value['covid_pcr'] as $subkey => $subvalue)
								{
									$writing .=
									'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
										<td style="width:15%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . $subvalue['token'] . '</td>
										<td style="width:5%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . Languages::email($subvalue['type'])[Session::get_value('vkye_lang')] . '</td>
										<td style="width:40%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . $subvalue['contact']['firstname'] . ' ' . $subvalue['contact']['lastname'] . '</td>
										<td style="width:10%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['date'] . '</td>
										<td style="width:10%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['hour'] . '</td>
										<td style="width:20%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['taker_name'] . '</td>
									</tr>';
								}

								$writing .= '</table>';
							}

							if (!empty($value['covid_an']))
							{
								$writing .= '<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">';

								foreach ($value['covid_an'] as $subkey => $subvalue)
								{
									$writing .=
									'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
										<td style="width:15%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . $subvalue['token'] . '</td>
										<td style="width:5%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . Languages::email($subvalue['type'])[Session::get_value('vkye_lang')] . '</td>
										<td style="width:40%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . $subvalue['contact']['firstname'] . ' ' . $subvalue['contact']['lastname'] . '</td>
										<td style="width:10%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['date'] . '</td>
										<td style="width:10%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['hour'] . '</td>
										<td style="width:20%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['taker_name'] . '</td>
									</tr>';
								}

								$writing .= '</table>';
							}

							if (!empty($value['covid_ac']))
							{
								$writing .= '<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">';

								foreach ($value['covid_ac'] as $subkey => $subvalue)
								{
									$writing .=
									'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
										<td style="width:15%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . $subvalue['token'] . '</td>
										<td style="width:5%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . Languages::email($subvalue['type'])[Session::get_value('vkye_lang')] . '</td>
										<td style="width:40%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:left;color:#757575;">' . $subvalue['contact']['firstname'] . ' ' . $subvalue['contact']['lastname'] . '</td>
										<td style="width:10%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['date'] . '</td>
										<td style="width:10%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['hour'] . '</td>
										<td style="width:20%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:10px;font-weight:400;text-align:right;color:#757575;">' . $subvalue['taker_name'] . '</td>
									</tr>';
								}

								$writing .= '</table>';
							}
						}

						$writing .=
						'<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
							<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						        <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $query['laboratory']['colors']['second'] . ';">' . $query['laboratory']['phone'] . ' | ' . $query['laboratory']['email'] . ' | ' . $query['laboratory']['website'] . '</td>
						    </tr>
						</table>
						<table style="width:100%;margin:0px;padding:0px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
						    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
						        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
						            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/secretaria_salud.png">
						        </td>
						        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
						            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/cofepris.png">
						        </td>
						        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
						            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_1.png">
						        </td>
						        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
						            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_2.png">
						        </td>
						        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
						            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_sesa.png">
						        </td>
						    </tr>
						</table>';
						$html2pdf->writeHTML($writing);
						$html2pdf->output(PATH_UPLOADS . $_POST['pdf'], 'F');

						$mail = new Mailer(true);

						try
						{
							$mail->setFrom($query['laboratory']['email'], $query['laboratory']['name']);

							$_POST['emails'] = explode(',', $_POST['emails']);

							foreach ($_POST['emails'] as $value)
								$mail->addAddress($value, $query['laboratory']['name']);

							$mail->addAttachment(PATH_UPLOADS . $_POST['pdf']);
							$mail->Subject = Languages::email('day_report')[Session::get_value('vkye_lang')] . ': ' . (($_POST['start_date'] == $_POST['end_date']) ? $_POST['start_date'] : $_POST['start_date'] . ' ' . Languages::email('to')[Session::get_value('vkye_lang')] . ' ' . $_POST['start_date']);
							$mail->Body =
							'<html>
								<head>
									<title>' . $mail->Subject . '</title>
								</head>
								<body>
									<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $query['laboratory']['colors']['first'] . ';">
										<tr style="width:100%;margin:0px;padding:0px;border:0px;">
											<td style="width:100px;margin:0px;padding:20px 0px 20px 20px;border:0px;box-sizing:border-box;vertical-align:middle;">
												<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $query['laboratory']['avatar'] . '">
											</td>
											<td style="width:auto;margin:0px;padding:20px;border:0px;box-sizing:border-box;vertical-align:middle;">
												<table style="width:100%;margin:0px;padding:0px;border:0px;">
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:600;text-align:right;text-transform:uppercase;color:#fff;">' . $query['laboratory']['name'] . '</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $query['laboratory']['rfc'] . '</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $query['laboratory']['sanitary_opinion'] . '</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $query['laboratory']['address']['first'] . '</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $query['laboratory']['address']['second']. '</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<table style="width:100%;max-width:600px;margin:20px 0px;padding:0px;border:1px dashed #bdbdbd;box-sizing:border-box;background-color:#fff;">
										<tr style="width:100%;margin:0px;padding:0px;border:0px;">
											<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">' . Languages::email('day_report')[Session::get_value('vkye_lang')] . ': '. (($_POST['start_date'] == $_POST['end_date']) ? $_POST['start_date'] : $_POST['start_date'] . ' ' . Languages::email('to')[Session::get_value('vkye_lang')] . ' ' . $_POST['start_date']) . '</td>
										</tr>
									</table>
									<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $query['laboratory']['colors']['second'] . ';">
										<tr style="width:100%;margin:0px;padding:0px;border:0px;">
											<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="tel:' . $query['laboratory']['phone'] . '">' . $query['laboratory']['phone'] . '</a></td>
										</tr>
										<tr style="width:100%;margin:0px;padding:0px;border:0px;">
											<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="mailto:' . $query['laboratory']['email'] . '">' . $query['laboratory']['email'] . '</a></td>
										</tr>
										<tr style="width:100%;margin:0px;padding:0px;border:0px;">
											<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="https://' . $query['laboratory']['website'] . '">' . $query['laboratory']['website'] . '</a></td>
										</tr>
									</table>
									<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $query['laboratory']['colors']['first'] . ';">
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
							'message' => '{$lang.not_found_custody_chains}'
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

			if ($_POST['action'] == 'restore_custody_chain' OR $_POST['action'] == 'empty_custody_chains' OR $_POST['action'] == 'delete_custody_chain')
			{
				if ($_POST['action'] == 'restore_custody_chain')
					$query = $this->model->restore_custody_chain($_POST['id']);
				else if ($_POST['action'] == 'empty_custody_chains')
					$query = $this->model->empty_custody_chains();
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
			{
				System::temporal('set_forced', 'laboratory', 'filter', [
					'laboratory' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'all' : '',
					'taker' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'all' : '',
					'collector' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'all' : '',
					'deleted_status' => 'not_deleted',
					'type' => ($params[0] == 'covid') ? 'all' : $params[0],
					'start_date' => Dates::past_date(Dates::current_date(), 1, 'days'),
					'end_date' => Dates::current_date(),
					'start_hour' => '00:00:01',
					'end_hour' => '23:59:59',
					'sent_status' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'all' : ''
				]);
			}

			global $global;

			$global['render'] = $params[0];
			$global['custody_chains'] = $this->model->read_custody_chains($params[0]);
			$global['laboratories'] = $this->model->read_laboratories();
			$global['takers'] = $this->model->read_takers();
			$global['collectors'] = $this->model->read_collectors();
			$global['chemicals'] = $this->model->read_chemicals();

			$template = $this->view->render($this, 'index');

			echo $template;
		}
    }

	public function create($params)
	{
		global $global;

		$global['type'] = $params[0];
		$global['employee'] = $this->model->read_employee($params[1]);

		if (!empty($global['employee']))
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

					if (($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an') AND Validations::empty($_POST['test_result']) == false)
						array_push($errors, ['test_result','{$lang.dont_leave_this_field_empty}']);

					if (($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an') AND Validations::empty($_POST['test_unity']) == false)
						array_push($errors, ['test_unity','{$lang.dont_leave_this_field_empty}']);

					if (($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an') AND Validations::empty($_POST['test_reference_values']) == false)
						array_push($errors, ['test_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_result']) == false)
						array_push($errors, ['test_igm_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_unity']) == false)
						array_push($errors, ['test_igm_unity','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_reference_values']) == false)
						array_push($errors, ['test_igm_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_result']) == false)
						array_push($errors, ['test_igg_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_unity']) == false)
						array_push($errors, ['test_igg_unity','{$lang.dont_leave_this_field_empty}']);

					if ($global['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_reference_values']) == false)
						array_push($errors, ['test_igg_reference_values','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['date']) == false)
    					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['hour']) == false)
    					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['chemical']) == false)
    					array_push($errors, ['chemical','{$lang.dont_leave_this_field_empty}']);

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
    			define('_title', Configuration::$web_page . ' | {$lang.create_test} | {$lang.' . $params[0] . '}');

				$global['locations'] = $this->model->read_locations();
				$global['chemicals'] = $this->model->read_chemicals();

    			$template = $this->view->render($this, 'create');

    			echo $template;
    		}
        }
	}

	public function update($params)
	{
		global $global;

		$global['custody_chain'] = $this->model->read_custody_chain($params[0]);

		if (!empty($global['custody_chain']))
		{
			if (Format::exist_ajax_request() == true)
			{
				if ($_POST['action'] == 'update_custody_chain')
				{
					$errors = [];

					if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
					{
						if (Validations::empty($_POST['firstname']) == false)
	    					array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['lastname']) == false)
	    					array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['sex']) == false)
					   		array_push($errors, ['sex','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['birth_date']) == false)
					   		array_push($errors, ['birth_date','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['age']) == false)
	    					array_push($errors, ['age','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['nationality']) == false)
	    					array_push($errors, ['nationality','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['ife']) == false)
	    					array_push($errors, ['ife','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['travel_to']) == false)
	    					array_push($errors, ['travel_to','{$lang.dont_leave_this_field_empty}']);

					   	if (Validations::empty($_POST['email']) == false)
					   		array_push($errors, ['email','{$lang.dont_leave_this_field_empty}']);
					   	else if (Validations::email($_POST['email']) == false)
					   		array_push($errors, ['email','{$lang.invalid_field}']);

						if (Validations::empty($_POST['phone_country']) == false)
							array_push($errors, ['phone_country','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['phone_number']) == false)
							array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);

						if (Validations::empty($_POST['lang']) == false)
						   array_push($errors, ['lang','{$lang.dont_leave_this_field_empty}']);
					}

    				if ((Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') AND Validations::empty($_POST['reason']) == false)
    					array_push($errors, ['reason','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND Validations::empty($_POST['start_process']) == false)
    					array_push($errors, ['start_process','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND Validations::empty($_POST['end_process']) == false)
    					array_push($errors, ['end_process','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_result']) == false)
						array_push($errors, ['test_result','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_unity']) == false)
						array_push($errors, ['test_unity','{$lang.dont_leave_this_field_empty}']);

					if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') AND Validations::empty($_POST['test_reference_values']) == false)
						array_push($errors, ['test_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_result']) == false)
						array_push($errors, ['test_igm_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_unity']) == false)
						array_push($errors, ['test_igm_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igm_reference_values']) == false)
						array_push($errors, ['test_igm_reference_values','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_result']) == false)
						array_push($errors, ['test_igg_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_unity']) == false)
						array_push($errors, ['test_igg_result','{$lang.dont_leave_this_field_empty}']);

					if ($global['custody_chain']['type'] == 'covid_ac' AND Validations::empty($_POST['test_igg_reference_values']) == false)
						array_push($errors, ['test_igg_reference_values','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['date']) == false)
    					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['hour']) == false)
    					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

                    if (Validations::empty($_POST['chemical']) == false)
    					array_push($errors, ['chemical','{$lang.dont_leave_this_field_empty}']);

    				if (empty($errors))
    				{
						if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
						{
							$_POST['qr']['filename'] = $global['custody_chain']['laboratory_path'] . '_' . $global['custody_chain']['type'] . '_qr_results_' . $global['custody_chain']['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.png';
							$_POST['pdf']['filename'] = $global['custody_chain']['laboratory_path'] . '_' . $global['custody_chain']['type'] . '_pdf_results_' . $global['custody_chain']['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.pdf';
						}

						$_POST['custody_chain'] = $global['custody_chain'];

						$query = $this->model->update_custody_chain($_POST);

    					if (!empty($query))
    					{
							if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND $_POST['save'] == 'save_and_send')
							{
								$mail = new Mailer(true);

								try
								{
									$mail->setFrom($global['custody_chain']['laboratory_email'], $global['custody_chain']['laboratory_name']);
									$mail->addAddress($_POST['email'], $_POST['firstname'] . ' ' . $_POST['lastname']);
									$mail->addAttachment(PATH_UPLOADS . $_POST['pdf']['filename']);
									$mail->Subject = '¡' . Languages::email('hi')[$global['custody_chain']['lang']] . ' ' . explode(' ',  $_POST['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$global['custody_chain']['lang']];
									$mail->Body =
									'<html>
										<head>
											<title>' . $mail->Subject . '</title>
										</head>
										<body>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $global['custody_chain']['laboratory_colors']['first'] . ';">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100px;margin:0px;padding:20px 0px 20px 20px;border:0px;box-sizing:border-box;vertical-align:middle;">
														<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $global['custody_chain']['laboratory_avatar'] . '">
													</td>
													<td style="width:auto;margin:0px;padding:20px;border:0px;box-sizing:border-box;vertical-align:middle;">
														<table style="width:100%;margin:0px;padding:0px;border:0px;">
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:600;text-align:right;text-transform:uppercase;color:#fff;">' . $global['custody_chain']['laboratory_name'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['custody_chain']['laboratory_rfc'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['custody_chain']['laboratory_sanitary_opinion'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['custody_chain']['laboratory_address']['first'] . '</td>
															</tr>
															<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['custody_chain']['laboratory_address']['second']. '</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:20px 0px;padding:0px;border:1px dashed #bdbdbd;box-sizing:border-box;background-color:#fff;">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">¡' . Languages::email('ready_results')[$global['custody_chain']['lang']] . '!</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:center;color:#757575;">¡' . Languages::email('hi')[Session::get_value('vkye_lang')] . ' <strong>' . explode(' ', $_POST['firstname'])[0] . '</strong>! ' . Languages::email('get_covid_results_1')[$global['custody_chain']['lang']] . ' <strong>' . Dates::format_date($global['custody_chain']['date'], 'short') . '</strong> ' . Languages::email('get_covid_results_2')[$global['custody_chain']['lang']] . '</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
														<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">
													</td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
														<a style="width:100%;display:block;margin:0px;padding:10px;border:0px;border-radius:5px;box-sizing:border-box;background-color:#009688;font-size:14px;font-weight:400;text-align:center;text-decoration:none;color:#fff;" href="https://' . Configuration::$domain . '/' . $global['custody_chain']['laboratory_path'] . '/results/' . $global['custody_chain']['token'] . '">' . Languages::email('view_online_results')[$global['custody_chain']['lang']] . '</a>
													</td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $global['custody_chain']['laboratory_colors']['second'] . ';">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="tel:' . $global['custody_chain']['laboratory_phone'] . '">' . $global['custody_chain']['laboratory_phone'] . '</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="mailto:' . $global['custody_chain']['laboratory_email'] . '">' . $global['custody_chain']['laboratory_email'] . '</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="https://' . $global['custody_chain']['laboratory_website'] . '">' . $global['custody_chain']['laboratory_website'] . '</a></td>
												</tr>
											</table>
											<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $global['custody_chain']['laboratory_colors']['first'] . ';">
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">' . Languages::email('power_by')[$global['custody_chain']['lang']] . ' <a style="font-weight:600;text-decoration:none;color:#fff;" href="https://id.one-consultores.com">' . Configuration::$web_page . ' ' . Configuration::$web_version . '</a></td>
												</tr
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Copyright (C) <a style="text-decoration:none;color:#fff;" href="https://one-consultores.com">One Consultores</a></td>
												</tr>
												<tr style="width:100%;margin:0px;padding:0px;border:0px;">
													<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Software ' . Languages::email('development_by')[$global['custody_chain']['lang']] . ' <a style="text-decoration:none;color:#fff;" href="https://codemonkey.com.mx">Code Monkey</a></td>
												</tr>
											</table>
										</body>
									</html>';
									$mail->send();
								}
								catch (Exception $e) {}

								$sms = new \Nexmo\Client\Credentials\Basic('51db0b68', 'd2TTUheuHp6BqYep');
								$sms = new \Nexmo\Client($sms);

								try
								{
									$sms->message()->send([
										'to' => $_POST['phone_country'] . $_POST['phone_number'],
										'from' => $global['custody_chain']['laboratory_name'],
										'text' => '¡' . Languages::email('hi')[$global['custody_chain']['lang']] . ' ' . explode(' ',  $_POST['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$global['custody_chain']['lang']] . '. ' . Languages::email('we_send_email_1')[$global['custody_chain']['lang']] . ' ' . $_POST['email'] . ' ' . Languages::email('we_send_email_3')[$global['custody_chain']['lang']] . ': https://' . Configuration::$domain . '/' . $global['custody_chain']['laboratory_path'] . '/results/' . $global['custody_chain']['token'] . '. ' . Languages::email('power_by')[$global['custody_chain']['lang']] . ' ' . Configuration::$web_page . ' ' . Configuration::$web_version . '.'
									]);
								}
								catch (Exception $e) {}
							}

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
				define('_title', Configuration::$web_page . ' | {$lang.update_test} | ' . $params[0]);

				$global['locations'] = $this->model->read_locations();
				$global['chemicals'] = $this->model->read_chemicals();

				$template = $this->view->render($this, 'update');

				echo $template;
			}
		}
	}

	public function authentication($params)
	{
		global $global;

		$global['laboratory'] = $this->model->read_laboratory($params[0]);
		$global['collector'] = $this->model->read_collector($params[1]);

		if (!empty($global['laboratory']) AND !empty($global['collector']))
		{
			if (Format::exist_ajax_request() == true)
	        {
				if ($_POST['action'] == 'start_authentication')
				{
					$errors = [];

					if (Validations::empty($_POST['taker']) == false)
						array_push($errors, ['taker','{$lang.dont_leave_this_field_empty}']);

					if (empty($errors))
					{
						$_POST['id'] = $global['collector']['id'];

						$query = $this->model->start_authentication($_POST);

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

				if ($_POST['action'] == 'end_authentication')
				{
					$query = $this->model->end_authentication($global['collector']['id']);

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
	            define('_title', $global['laboratory']['name'] . ' | {$lang.authentication}');

				if ($global['laboratory']['blocked'] == true)
					$global['render'] = 'laboratory_blocked';
				else if ($global['collector']['blocked'] == true)
					$global['render'] = 'collector_blocked';
				else if (!in_array($global['laboratory']['id'], $global['collector']['laboratories']))
					$global['render'] = 'out_of_laboratory';
				else if (Dates::current_hour() < $global['collector']['schedule']['open'] OR Dates::current_hour() > $global['collector']['schedule']['close'])
					$global['render'] = 'out_of_time';
				else if (Dates::current_hour() > $global['collector']['schedule']['open'] AND Dates::current_hour() < $global['collector']['schedule']['close'])
				{
					$global['render'] = 'go';
					$global['takers'] = $this->model->read_takers('not_blocked');
				}

	            $template = $this->view->render($this, 'authentication');

	            echo $template;
	        }
		}
	}

	public function record($params)
    {
		global $global;

		$global['laboratory'] = $this->model->read_laboratory($params[0]);
		$global['collector'] = $this->model->read_collector($params[1]);

		if (!empty($global['laboratory']) AND !empty($global['collector']))
		{
			if (Format::exist_ajax_request() == true)
			{
				if ($_POST['action'] == 'create_record')
				{
					if (!empty($_POST['signature']))
					{
						if (!empty($_POST['type']))
						{
							$errors = [];

							if (Validations::empty($_POST['firstname']) == false)
								array_push($errors, ['firstname','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['lastname']) == false)
								array_push($errors, ['lastname','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['sex']) == false)
								array_push($errors, ['sex','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['birth_date_year']) == false)
								array_push($errors, ['birth_date_year','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['birth_date_month']) == false)
								array_push($errors, ['birth_date_month','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['birth_date_day']) == false)
								array_push($errors, ['birth_date_day','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['age']) == false)
								array_push($errors, ['age','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['nationality']) == false)
								array_push($errors, ['nationality','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['ife']) == false)
								array_push($errors, ['ife','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['travel_to']) == false)
								array_push($errors, ['travel_to','{$lang.dont_leave_this_field_empty}']);

							if (Validations::equals($_POST['sex'], 'female') == true AND Validations::empty($_POST['pregnant']) == false)
								array_push($errors, ['pregnant','{$lang.dont_leave_this_field_empty}']);

							if (Validations::equals($_POST['symptoms'][0], 'nothing') == false AND Validations::empty($_POST['symptoms_time']) == false)
								array_push($errors, ['symptoms_time','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['previous_travel']) == false)
								array_push($errors, ['previous_travel','{$lang.dont_leave_this_field_empty}']);

							if (Validations::equals($_POST['previous_travel'], 'yeah') == true AND Validations::empty($_POST['previous_travel_countries']) == false)
								array_push($errors, ['previous_travel_countries','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['covid_contact']) == false)
								array_push($errors, ['covid_contact','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['covid_infection']) == false)
								array_push($errors, ['covid_infection','{$lang.dont_leave_this_field_empty}']);

							if (Validations::equals($_POST['covid_infection'], 'yeah') == true AND Validations::empty($_POST['covid_infection_time']) == false)
								array_push($errors, ['covid_infection_time','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['email']) == false)
								array_push($errors, ['email','{$lang.dont_leave_this_field_empty}']);
							else if (Validations::email($_POST['email']) == false)
								array_push($errors, ['email','{$lang.invalid_field}']);

							if (Validations::empty($_POST['phone_country']) == false)
								array_push($errors, ['phone_country','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['phone_number']) == false)
								array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);

							if (Validations::empty($_POST['lang']) == false)
								array_push($errors, ['lang','{$lang.dont_leave_this_field_empty}']);

							if (empty($errors))
							{
								$_POST['token'] = $global['collector']['authentication']['taker']['token'] . '-' . System::generate_random_string();
								$_POST['qr']['filename'] = $global['laboratory']['path'] . '_' . $_POST['type'] . '_qr_results_' . $_POST['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.png';
								$_POST['laboratory'] = $global['laboratory'];
								$_POST['collector'] = $global['collector'];

								$query = $this->model->create_custody_chain($_POST, true);

								if (!empty($query))
								{
									$mail = new Mailer(true);

									try
									{
										$mail->setFrom($global['laboratory']['email'], $global['laboratory']['name']);
										$mail->addAddress(strtolower($_POST['email']), ucwords($_POST['firstname'] . ' ' . $_POST['lastname']));
										$mail->Subject = '¡' . Languages::email('hi')[Session::get_value('vkye_lang')] . ' ' . ucwords(explode(' ', $_POST['firstname'])[0]) . '! ' . Languages::email('your_token_is')[Session::get_value('vkye_lang')] . ': ' . $_POST['token'];
										$mail->Body =
										'<html>
											<head>
												<title>' . $mail->Subject . '</title>
											</head>
											<body>
												<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $global['laboratory']['colors']['first'] . ';">
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100px;margin:0px;padding:20px 0px 20px 20px;border:0px;box-sizing:border-box;vertical-align:middle;">
															<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $global['laboratory']['avatar'] . '">
														</td>
														<td style="width:auto;margin:0px;padding:20px;border:0px;box-sizing:border-box;vertical-align:middle;">
															<table style="width:100%;margin:0px;padding:0px;border:0px;">
																<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																	<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:600;text-align:right;text-transform:uppercase;color:#fff;">' . $global['laboratory']['name'] . '</td>
																</tr>
																<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																	<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['laboratory']['rfc'] . '</td>
																</tr>
																<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																	<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['laboratory']['sanitary_opinion'] . '</td>
																</tr>
																<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																	<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['laboratory']['address']['first'] . '</td>
																</tr>
																<tr style="width:100%;margin:0px;padding:0px;border:0px;">
																	<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">' . $global['laboratory']['address']['second'] . '</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<table style="width:100%;max-width:600px;margin:20px 0px;padding:0px;border:1px dashed #bdbdbd;box-sizing:border-box;background-color:#fff;">
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">' . Languages::email('your_token_is')[Session::get_value('vkye_lang')] . ': </td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:28px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">' . $_POST['token'] . '</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:center;color:#757575;">¡' . Languages::email('hi')[Session::get_value('vkye_lang')] . ' <strong>' . ucwords(explode(' ', $_POST['firstname'])[0]) . '</strong>! ' . Languages::email('your_results_next_email')[Session::get_value('vkye_lang')] . '</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
															<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $_POST['qr']['filename'] . '">
														</td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
															<a style="width:100%;display:block;margin:0px;padding:10px;border:0px;border-radius:5px;box-sizing:border-box;background-color:#009688;font-size:14px;font-weight:400;text-align:center;text-decoration:none;color:#fff;" href="https://' . Configuration::$domain . '/' . $global['laboratory']['path'] . '/results/' . $_POST['token'] . '">' . Languages::email('view_online_results')[Session::get_value('vkye_lang')] . '</a>
														</td>
													</tr>
												</table>
												<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $global['laboratory']['colors']['second'] . ';">
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="tel:' . $global['laboratory']['phone'] . '">' . $global['laboratory']['phone'] . '</a></td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="mailto:' . $global['laboratory']['email'] . '">' . $global['laboratory']['email'] . '</a></td>
													</tr>
													<tr style="width:100%;margin:0px;padding:0px;border:0px;">
														<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="https://' . $global['laboratory']['website'] . '">' . $global['laboratory']['website'] . '</a></td>
													</tr>
												</table>
												<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:' . $global['laboratory']['colors']['first'] . ';">
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
										$mail->send();
									}
									catch (Exception $e) {}

									$sms = new \Nexmo\Client\Credentials\Basic('51db0b68', 'd2TTUheuHp6BqYep');
									$sms = new \Nexmo\Client($sms);

									try
									{
										$sms->message()->send([
											'to' => $_POST['phone_country'] . $_POST['phone_number'],
											'from' => $global['laboratory']['name'],
											'text' => '¡' . Languages::email('hi')[Session::get_value('vkye_lang')] . ' ' . ucwords(explode(' ',  $_POST['firstname'])[0]) . '! ' . Languages::email('your_token_is')[Session::get_value('vkye_lang')] . ': ' . $_POST['token'] . '. ' . Languages::email('we_send_email_1')[Session::get_value('vkye_lang')] . ' ' . strtolower($_POST['email']) . ' ' . Languages::email('we_send_email_2')[Session::get_value('vkye_lang')] . ': https://' . Configuration::$domain . '/' . $global['laboratory']['path'] . '/results/' . $_POST['token'] . '. ' . Languages::email('power_by')[Session::get_value('vkye_lang')] . ' ' . Configuration::$web_page . ' ' . Configuration::$web_version . '.'
										]);
									}
									catch (Exception $e) {}

									echo json_encode([
										'status' => 'success',
										'message' => '{$lang.operation_success}',
										'path' => '/' . $global['laboratory']['path'] . '/results/' . $_POST['token']
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
							echo json_encode([
								'status' => 'error',
								'message' => '{$lang.choose_a_covid_test_type_error}'
							]);
						}
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.accept_terms_error}'
						]);
					}
				}
			}
			else
			{
				define('_title', $global['laboratory']['name'] . ' | {$lang.record}');

				if ($global['laboratory']['blocked'] == true)
					$global['render'] = 'laboratory_blocked';
				else if ($global['collector']['blocked'] == true)
					$global['render'] = 'collector_blocked';
				else if (!in_array($global['laboratory']['id'], $global['collector']['laboratories']))
					$global['render'] = 'out_of_laboratory';
				else if (Dates::current_hour() < $global['collector']['schedule']['open'] OR Dates::current_hour() > $global['collector']['schedule']['close'])
					$global['render'] = 'out_of_time';
				else if ($global['collector']['authentication']['type'] == 'none')
					$global['render'] = 'out_of_authentication';
				else if (Dates::current_hour() > $global['collector']['schedule']['open'] AND Dates::current_hour() < $global['collector']['schedule']['close'])
				{
					if (!empty($params[2]))
						$global['render'] = 'go';
					else
						header('Location: https://' . Configuration::$domain . '/' . $global['laboratory']['path'] . '/record/' . $global['collector']['token'] . '/covid');
				}

				$template = $this->view->render($this, 'record');

				echo $template;
			}
		}
    }

	public function results($params)
	{
		global $global;

		$global['laboratory'] = $this->model->read_laboratory($params[0]);
		$global['custody_chain'] = $this->model->read_custody_chain($params[1]);

		if (!empty($global['laboratory']) AND !empty($global['custody_chain']))
		{
			define('_title', $global['laboratory']['name'] . ' | {$lang.results}');

			$template = $this->view->render($this, 'results');

			echo $template;
		}
	}
}
