<?php

defined('_EXEC') or die;

require 'plugins/php_qr_code/qrlib.php';
require 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

class Laboratory_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function create_custody_chain($data, $record = false)
    {
		if ($record == true)
		{
			$data['qr']['content'] = 'https://' . Configuration::$domain . '/' . $data['laboratory']['path'] . '/results/' . $data['token'];
			$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
			$data['qr']['level'] = 'H';
			$data['qr']['size'] = 5;
			$data['qr']['frame'] = 3;
		}

        $query = $this->database->insert('custody_chains', [
			'account' => ($record == true) ? null : Session::get_value('vkye_account')['id'],
			'token' => ($record == true) ? $data['token'] : System::generate_random_string(),
            'employee' => ($record == true) ? null : $data['employee'],
			'contact' => ($record == true) ? (($data['collector']['authentication']['type'] == 'alcoholic') ? json_encode([

			]) : (($data['collector']['authentication']['type'] == 'antidoping') ? json_encode([

			]) : (($data['collector']['authentication']['type'] == 'covid') ? json_encode([
				'firstname' => ucwords($data['firstname']),
                'lastname' => ucwords($data['lastname']),
				'sex' => $data['sex'],
                'birth_date' => $data['birth_date_year'] . '-' . $data['birth_date_month'] . '-' . $data['birth_date_day'],
                'age' => $data['age'],
                'nationality' => $data['nationality'],
				'ife' => $data['ife'],
				'travel_to' => $data['travel_to'],
				'pregnant' => $data['pregnant'],
				'symptoms' => $data['symptoms'],
				'symptoms_time' => $data['symptoms_time'],
				'previous_travel' => $data['previous_travel'],
				'previous_travel_countries' => $data['previous_travel_countries'],
				'covid_contact' => $data['covid_contact'],
				'covid_infection' => $data['covid_infection'],
				'covid_infection_time' => $data['covid_infection_time'],
                'email' => strtolower($data['email']),
                'phone' => [
                    'country' => $data['phone_country'],
                    'number' => $data['phone_number']
                ]
			]) : null))) : null,
			'type' => ($record == true) ? (($data['collector']['authentication']['type'] == 'covid') ? $data['type'] : $data['collector']['authentication']['type']) : $data['type'],
			'reason' => ($record == true) ? 'random' : $data['reason'],
			'start_process' => ($record == true) ? (($data['collector']['authentication']['type'] == 'covid') ? Dates::current_date() : null) : (($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an' OR $data['type'] == 'covid_ac') ? $data['start_process'] : null),
			'end_process' => ($record == false AND ($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an' OR $data['type'] == 'covid_ac')) ? $data['end_process'] : null,
			'results' => (($record == true AND $data['collector']['authentication']['type'] == 'alcoholic') OR ($record == false AND $data['type'] == 'alcoholic')) ? json_encode([
                '1' => ($record == true) ? '' : (!empty($data['test_1']) ? $data['test_1'] : ''),
                '2' => ($record == true) ? '' : (!empty($data['test_2']) ? $data['test_2'] : ''),
                '3' => ($record == true) ? '' : (!empty($data['test_3']) ? $data['test_3'] : '')
            ]) : ((($record == true AND $data['collector']['authentication']['type'] == 'antidoping') OR ($record == false AND $data['type'] == 'antidoping')) ? json_encode([
                'COC' => ($record == true) ? '' : (!empty($data['test_COC']) ? $data['test_COC'] : ''),
                'THC' => ($record == true) ? '' : (!empty($data['test_THC']) ? $data['test_THC'] : ''),
                'MET' => ($record == true) ? '' : (!empty($data['test_MET']) ? $data['test_MET'] : ''),
                'ANF' => ($record == true) ? '' : (!empty($data['test_ANF']) ? $data['test_ANF'] : ''),
                'BZD' => ($record == true) ? '' : (!empty($data['test_BZD']) ? $data['test_BZD'] : ''),
                'OPI' => ($record == true) ? '' : (!empty($data['test_OPI']) ? $data['test_OPI'] : ''),
                'BAR' => ($record == true) ? '' : (!empty($data['test_BAR']) ? $data['test_BAR'] : '')
            ]) : (((($record == true AND $data['collector']['authentication']['type'] == 'covid') OR $record == false) AND ($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an')) ? json_encode([
				'result' => ($record == true) ? '' : $data['test_result'],
				'unity' => ($record == true) ? '' : $data['test_unity'],
				'reference_values' => ($record == true) ? '' : $data['test_reference_values']
			]) : (((($record == true AND $data['collector']['authentication']['type'] == 'covid') OR $record == false) AND $data['type'] == 'covid_ac') ? json_encode([
				'igm' => [
					'result' => ($record == true) ? '' : $data['test_igm_result'],
					'unity' => ($record == true) ? '' : $data['test_igm_unity'],
					'reference_values' => ($record == true) ? '' : $data['test_igm_reference_values']
				],
				'igg' => [
					'result' => ($record == true) ? '' : $data['test_igg_result'],
					'unity' => ($record == true) ? '' : $data['test_igg_unity'],
					'reference_values' => ($record == true) ? '' : $data['test_igg_reference_values']
				]
			]) : null))),
			'medicines' => ((($record == true AND ($data['collector']['authentication']['type'] == 'alcoholic' OR $data['collector']['authentication']['type'] == 'antidoping')) OR ($record == false AND ($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping'))) AND !empty($data['medicines'])) ? $data['medicines'] : null,
			'prescription' => (($record == true AND ($data['collector']['authentication']['type'] == 'alcoholic' OR $data['collector']['authentication']['type'] == 'antidoping')) OR ($record == false AND ($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping'))) ? json_encode([
				'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
				'date' => !empty($data['prescription_date']) ? $data['prescription_date'] : ''
			]) : null,
			'location' => ($record == false AND !empty($data['location'])) ? $data['location'] : null,
			'laboratory' => ($record == true) ? $data['laboratory']['id'] : null,
			'taker' => ($record == true) ? $data['collector']['authentication']['taker']['id'] : null,
			'collector' => ($record == true) ? $data['collector']['id'] : null,
			'chemical' => ($record == true) ? null : $data['chemical'],
			'date' => ($record == true) ? Dates::current_date() : $data['date'],
			'hour' => ($record == true) ? Dates::current_hour() : $data['hour'],
			'comments' => ($record == false AND !empty($data['comments'])) ? $data['comments'] : null,
			'signature' => ($record == true OR ($record == false AND !empty($data['signature']))) ? Fileloader::base64($data['signature']) : null,
			'qr' => ($record == true) ? $data['qr']['filename'] : null,
			'pdf' => null,
			'lang' => ($record == true) ? Session::get_value('vkye_lang') : null,
			'version' => 'v2',
			'user' => ($record == true) ? null : Session::get_value('vkye_user')['id'],
			'sent' => false,
			'closed' => ($record == true) ? false : true,
			'deleted' => false
        ]);

		if ($record == true AND !empty($query))
			QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);

        return $query;
    }

	public function read_custody_chains($type)
	{
		$AND = [];

		if (Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep')
			$AND['custody_chains.account'] = Session::get_value('vkye_account')['id'];
		else if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND System::temporal('get', 'laboratory', 'filter')['laboratory'] != 'all')
			$AND['custody_chains.laboratory'] = System::temporal('get', 'laboratory', 'filter')['laboratory'];

		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND System::temporal('get', 'laboratory', 'filter')['taker'] != 'all')
			$AND['custody_chains.taker'] = System::temporal('get', 'laboratory', 'filter')['taker'];

		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND System::temporal('get', 'laboratory', 'filter')['collector'] != 'all')
			$AND['custody_chains.collector'] = System::temporal('get', 'laboratory', 'filter')['collector'];

		if (System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'not_deleted')
			$AND['custody_chains.deleted'] = false;
		else if (System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted')
			$AND['custody_chains.deleted'] = true;

		if ($type == 'covid' AND System::temporal('get', 'laboratory', 'filter')['type'] == 'all')
			$AND['custody_chains.type'] = ['covid_pcr','covid_an','covid_ac'];
		else if ($type == 'covid' AND System::temporal('get', 'laboratory', 'filter')['type'] != 'all')
			$AND['custody_chains.type'] = System::temporal('get', 'laboratory', 'filter')['type'];
		else
			$AND['custody_chains.type'] = $type;

		if (System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'not_deleted')
			$AND['custody_chains.date[<>]'] = [System::temporal('get', 'laboratory', 'filter')['start_date'],System::temporal('get', 'laboratory', 'filter')['end_date']];

		if (System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'not_deleted')
			$AND['custody_chains.hour[<>]'] = [System::temporal('get', 'laboratory', 'filter')['start_hour'],System::temporal('get', 'laboratory', 'filter')['end_hour']];

		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'not_deleted' AND System::temporal('get', 'laboratory', 'filter')['sent_status'] != 'all')
		{
			if (System::temporal('get', 'laboratory', 'filter')['sent_status'] == 'not_sent')
				$AND['custody_chains.sent'] = false;
			else if (System::temporal('get', 'laboratory', 'filter')['sent_status'] == 'sent')
				$AND['custody_chains.sent'] = true;
		}

		$query = System::decode_json_to_array($this->database->select('custody_chains', [
			'[>]employees' => [
				'employee' => 'id'
			],
			'[>]system_laboratories' => [
				'laboratory' => 'id'
			],
			'[>]system_takers' => [
				'taker' => 'id'
			],
			'[>]system_collectors' => [
				'collector' => 'id'
			]
		], [
			'custody_chains.id',
			'custody_chains.account',
			'custody_chains.token',
			'custody_chains.employee',
			'employees.firstname(employee_firstname)',
			'employees.lastname(employee_lastname)',
			'custody_chains.contact',
			'custody_chains.type',
			'custody_chains.results',
			'custody_chains.laboratory',
			'system_laboratories.name(laboratory_name)',
			'custody_chains.taker',
			'system_takers.name(taker_name)',
			'custody_chains.collector',
			'system_collectors.name(collector_name)',
			'custody_chains.date',
			'custody_chains.hour',
			'custody_chains.pdf',
			'custody_chains.sent',
			'custody_chains.deleted'
		], [
			'AND' => $AND,
			'ORDER' => [
				'custody_chains.date' => 'DESC',
				'custody_chains.hour' => 'DESC'
			]
		]));

		foreach ($query as $key => $value)
		{
			$query[$key]['status'] = '';

			if ($value['type'] == 'alcoholic')
			{
				if (($value['results']['1'] > 0 AND $value['results']['1'] < 0.20) OR ($value['results']['2'] > 0 AND $value['results']['2'] < 0.20) OR ($value['results']['3'] > 0 AND $value['results']['3'] < 0.20))
					$query[$key]['status'] = 'warning';
				else if ($value['results']['1'] >= 0.20 OR $value['results']['2'] >= 0.20 OR $value['results']['3'] >= 0.20)
					$query[$key]['status'] = 'positive';
			}
			else if ($value['type'] == 'antidoping' AND ($value['results']['COC'] == 'positive' OR $value['results']['THC'] == 'positive' OR $value['results']['ANF'] == 'positive' OR $value['results']['MET'] == 'positive' OR $value['results']['BZD'] == 'positive' OR $value['results']['OPI'] == 'positive' OR $value['results']['BAR'] == 'positive'))
				$query[$key]['status'] = 'positive';
			else if ($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an')
			{
				if ($value['results']['result'] == 'negative')
					$query[$key]['status'] = 'negative';
				else if ($value['results']['result'] == 'positive')
					$query[$key]['status'] = 'positive';
			}
			else if ($value['type'] == 'covid_ac')
			{
				if ($value['results']['igm']['result'] == 'not_reactive' AND $value['results']['igg']['result'] == 'not_reactive')
					$query[$key]['status'] = 'negative';
				else if ($value['results']['igm']['result'] == 'reactive' OR $value['results']['igg']['result'] == 'reactive')
					$query[$key]['status'] = 'positive';
			}
		}

		return $query;
	}

	public function read_custody_chain($token)
	{
		$query = System::decode_json_to_array($this->database->select('custody_chains', [
			'[>]accounts' => [
				'account' => 'id'
			],
			'[>]employees' => [
				'employee' => 'id'
			],
			'[>]system_laboratories' => [
				'laboratory' => 'id'
			],
			'[>]system_takers' => [
				'taker' => 'id'
			],
			'[>]system_collectors' => [
				'collector' => 'id'
			],
			'[>]system_chemicals' => [
				'chemical' => 'id'
			]
		], [
			'custody_chains.id',
			'custody_chains.account',
			'accounts.name(account_name)',
			'accounts.path(account_path)',
			'custody_chains.token',
			'custody_chains.employee',
			'employees.firstname(employee_firstname)',
			'employees.lastname(employee_lastname)',
			'employees.ife(employee_ife)',
			'employees.birth_date(employee_birth_date)',
			'employees.sex(employee_sex)',
			'custody_chains.contact',
			'custody_chains.type',
			'custody_chains.reason',
			'custody_chains.start_process',
			'custody_chains.end_process',
			'custody_chains.results',
			'custody_chains.medicines',
			'custody_chains.prescription',
			'custody_chains.location',
			'custody_chains.laboratory',
			'system_laboratories.avatar(laboratory_avatar)',
			'system_laboratories.name(laboratory_name)',
			'system_laboratories.path(laboratory_path)',
			'system_laboratories.business(laboratory_business)',
			'system_laboratories.rfc(laboratory_rfc)',
			'system_laboratories.sanitary_opinion(laboratory_sanitary_opinion)',
			'system_laboratories.address(laboratory_address)',
			'system_laboratories.email(laboratory_email)',
			'system_laboratories.phone(laboratory_phone)',
			'system_laboratories.website(laboratory_website)',
			'system_laboratories.colors(laboratory_colors)',
			'custody_chains.taker',
			'system_takers.name(taker_name)',
			'custody_chains.collector',
			'system_collectors.name(collector_name)',
			'custody_chains.chemical',
			'system_chemicals.name(chemical_name)',
			'system_chemicals.signature(chemical_signature)',
			'system_chemicals.card(chemical_card)',
			'custody_chains.date',
			'custody_chains.hour',
			'custody_chains.comments',
			'custody_chains.signature',
			'custody_chains.qr',
			'custody_chains.pdf',
			'custody_chains.lang',
			'custody_chains.version',
			'custody_chains.user',
			'custody_chains.closed',
			'custody_chains.deleted'
		], [
			'custody_chains.token' => $token
		]));

		return !empty($query) ? $query[0] : null;
	}

	// public function update_custody_chain($data)
    // {
	// 	if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
	// 	{
	// 		$data['qr']['content'] = 'https://' . Configuration::$domain . '/' . $data['custody_chain']['laboratory_path'] . '/results/' . $data['custody_chain']['token'];
	// 		$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
	// 		$data['qr']['level'] = 'H';
	// 		$data['qr']['size'] = 5;
	// 		$data['qr']['frame'] = 3;
	//
	// 		QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);
	//
	// 		$data['chemical'] = $this->database->select('system_chemicals', [
	// 			'id',
	// 			'name',
	// 			'signature',
	// 			'card'
	// 		], [
	// 			'id' => $data['chemical']
	// 		]);
	//
	// 		$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [0,0,0,0]);
	// 		$writing =
	// 		'<table style="width:100%;margin:0px;padding:20px 40px;border:0px;border-top:20px;border-color:' . $data['custody_chain']['laboratory_colors']['second'] . ';box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 		            <img style="width:100%;" src="' . PATH_UPLOADS . $data['custody_chain']['laboratory_avatar'] . '">
	// 		        </td>
	// 		        <td style="width:80%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 		            <table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:24px;font-weight:600;text-transform:uppercase;text-align:right;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('result_report')[$data['lang']] . '</td>
	// 		                </tr>
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:18px;font-weight:400;text-transform:uppercase;text-align:right;color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' .  Languages::email('laboratory_analisys')[$data['lang']] . ' <span style="font-weight:600;">' . $data['custody_chain']['laboratory_name'] . '</span></td>
	// 		                </tr>
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['custody_chain']['laboratory_address']['first'] . '</td>
	// 		                </tr>
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['custody_chain']['laboratory_address']['second'] . '</td>
	// 		                </tr>
	// 		            </table>
	// 		        </td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 5px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:0px 0px 0px 10px;border:0px;border-left:5px;border-color:' . $data['custody_chain']['laboratory_colors']['second'] . ';box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('general_patient_data')[$data['lang']] . '</td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('n_petition')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['custody_chain']['token'] . '</span></td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('name')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['firstname'] . ' ' . $data['lastname'] . '</span></td>
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('birth_date')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['birth_date'] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('sex')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['sex'])[$data['lang']] . '</span></td>
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('company')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">N/A</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:50%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('age')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['age'] . ' ' . Languages::email('years')[$data['lang']] . '</span></td>
	// 				<td style="width:50%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('id')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['ife'] . '</span></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 5px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:0px 0px 0px 10px;border:0px;border-left:5px;border-color:' . $data['custody_chain']['laboratory_colors']['second'] . ';box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('results')[$data['lang']] . '</td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('get_date')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['date'] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('method')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">';
	//
	// 		if ($data['custody_chain']['type'] == 'covid_pcr')
	// 			$writing .= Languages::email('pcr_atila_biosystem')[$data['lang']];
	// 		else if ($data['custody_chain']['type'] == 'covid_an')
	// 			$writing .= Languages::email('an_atila_biosystem')[$data['lang']];
	// 		else if ($data['custody_chain']['type'] == 'covid_ac')
	// 			$writing .= Languages::email('ac_atila_biosystem')[$data['lang']];
	//
	// 		$writing .=
	// 		'			</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('test')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an') ? Languages::email('nasopharynx_secretion')[$data['lang']] : Languages::email('sanguine')[$data['lang']]) . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('get_hour')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['hour'] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('start_process')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['start_process'] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('end_process')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['end_process'] . '</span></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:20px 40px;border:0px;box-sizing:border-box;background-color:#e1f5fe;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">';
	//
	// 		if ($data['custody_chain']['type'] == 'covid_pcr')
	// 			$writing .= 'PCR-SARS-CoV-2 (COVID-19)';
	// 		else if ($data['custody_chain']['type'] == 'covid_an')
	// 			$writing .= 'Ag-SARS-CoV-2 (COVID-19)';
	// 		else if ($data['custody_chain']['type'] == 'covid_ac')
	// 			$writing .= 'SARS-CoV-2 (2019) IgG/IgM';
	//
	// 		$writing .=
	// 		'        </td>
	// 		    </tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('immunological_analysis')[$data['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#e1f5fe;">';
	//
	// 		if ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an')
	// 		{
	// 			$writing .=
	// 			'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('result')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_result'])[$data['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('unity')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_unity'])[$data['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">' . Languages::email('reference_values')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_reference_values'])[$data['lang']] . '</span></td>
	// 			</tr>';
	// 		}
	// 		else if ($data['custody_chain']['type'] == 'covid_ac')
	// 		{
	// 			$writing .=
	// 			'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">IgM ' . Languages::email('result')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_igm_result'])[$data['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">IgM ' . Languages::email('unity')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_igm_unity'])[$data['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">IgM ' . Languages::email('reference_values')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_igm_reference_values'])[$data['lang']] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">IgG ' . Languages::email('result')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_igg_result'])[$data['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">IgG ' . Languages::email('unity')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_igg_unity'])[$data['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">IgG ' . Languages::email('reference_values')[$data['lang']] . ' <span style="color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . Languages::email($data['test_igg_reference_values'])[$data['lang']] . '</span></td>
	// 			</tr>';
	// 		}
	//
	// 		$writing .=
	// 		'</table>
	// 		<table style="width:100%;margin:0px;padding:20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:justify;color:' . $data['custody_chain']['laboratory_colors']['first'] . ';">';
	//
	// 		if ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an')
	// 			$writing .= Languages::email('notes_pcr_an_1')[$data['lang']] . ' ' . Languages::email('notes_pcr_an_2')[$data['lang']] . ' ' . Languages::email('notes_pcr_an_3')[$data['lang']];
	// 		else if ($data['custody_chain']['type'] == 'covid_ac')
	// 			$writing .= Languages::email('notes_ac_1')[$data['lang']] . ' ' . Languages::email('notes_ac_2')[$data['lang']] . ' ' . Languages::email('notes_ac_3')[$data['lang']] . ' ' . Languages::email('notes_ac_4')[$data['lang']] . ' ' . Languages::email('notes_ac_5')[$data['lang']];
	//
	// 		$writing .=
	// 		'		</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:60%;margin:0px;padding:0px;border:0px;"></td>
	// 		        <td style="width:40%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 		            <table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;text-align:center;">
	// 		                    	<img style="width:100px" src="' . PATH_UPLOADS . $data['chemical'][0]['signature'] . '">
	// 		                    </td>
	// 		                </tr>
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:#212121;">' . Languages::email('valid_results_by')[$data['lang']] . '</td>
	// 		                </tr>
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:#212121;">' . $data['chemical'][0]['name'] . '</td>
	// 		                </tr>
	// 		            </table>
	// 		        </td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:80%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 		            <table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#212121;">' . Languages::email('alert_pdf_covid')[$data['lang']] . ' ' . Languages::email('accept_terms_1')[$data['lang']] . ' ' . $data['custody_chain']['laboratory_name'] . ' ' . Languages::email('accept_terms_2')[$data['lang']] . ' ' . Languages::email('our_proccess_available_1')[$data['lang']] . ' ' . $data['custody_chain']['laboratory_sanitary_opinion'] . ' ' . Languages::email('our_proccess_available_2')[$data['lang']] . ' ' . $data['custody_chain']['laboratory_rfc'] . '</td>
	// 		                </tr>
	// 		                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:#212121;">' . Languages::email('expedition_date')[$data['lang']] . ' ' . $data['end_process'] . '</td>
	// 		                </tr>
	// 		            </table>
	// 		        </td>
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;font-size:8px;font-weight:400;text-align:center:color:#212121">
	// 		            <img style="width:100%;" src="' . PATH_UPLOADS . $data['qr']['filename'] . '">
	// 					' . Languages::email('scan_to_security')[$data['lang']] . '
	// 		        </td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $data['custody_chain']['laboratory_colors']['second'] . ';">' . $data['custody_chain']['laboratory_phone'] . ' | ' . $data['custody_chain']['laboratory_email'] . ' | ' . $data['custody_chain']['laboratory_website'] . '</td>
	// 		    </tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 		    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 		            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/secretaria_salud.png">
	// 		        </td>
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 		            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/cofepris.png">
	// 		        </td>
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 		            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_1.png">
	// 		        </td>
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 		            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_2.png">
	// 		        </td>
	// 		        <td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 		            <img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_sesa.png">
	// 		        </td>
	// 		    </tr>
	// 		</table>';
	// 		$html2pdf->writeHTML($writing);
	// 		$html2pdf->output(PATH_UPLOADS . $data['pdf']['filename'], 'F');
	// 	}
	//
	// 	$query = $this->database->update('custody_chains', [
	// 		'contact' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? json_encode([
	// 			'firstname' => $data['firstname'],
	// 			'lastname' => $data['lastname'],
	// 			'sex' => $data['sex'],
	// 			'birth_date' => $data['birth_date'],
	// 			'age' => $data['age'],
	// 			'nationality' => $data['nationality'],
	// 			'ife' => $data['ife'],
	// 			'travel_to' => $data['travel_to'],
	// 			'pregnant' => $data['custody_chain']['contact']['pregnant'],
	// 			'symptoms' => $data['custody_chain']['contact']['symptoms'],
	// 			'symptoms_time' => $data['custody_chain']['contact']['symptoms_time'],
	// 			'previous_travel' => $data['custody_chain']['contact']['previous_travel'],
	// 			'previous_travel_countries' => $data['custody_chain']['contact']['previous_travel_countries'],
	// 			'covid_contact' => $data['custody_chain']['contact']['covid_contact'],
	// 			'covid_infection' => $data['custody_chain']['contact']['covid_infection'],
	// 			'covid_infection_time' => $data['custody_chain']['contact']['covid_infection_time'],
	// 			'email' => $data['email'],
	// 			'phone' => [
	// 				'country' => $data['phone_country'],
	// 				'number' => $data['phone_number']
	// 			]
	// 		]) : null,
	// 		'reason' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'random' : $data['reason'],
	// 		'start_process' => ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') ? $data['start_process'] : null,
	// 		'end_process' => ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') ? $data['end_process'] : null,
	// 		'results' => ($data['custody_chain']['type'] == 'alcoholic') ? json_encode([
	// 			'1' => !empty($data['test_1']) ? $data['test_1'] : '',
	// 			'2' => !empty($data['test_2']) ? $data['test_2'] : '',
	// 			'3' => !empty($data['test_3']) ? $data['test_3'] : ''
	// 		]) : (($data['custody_chain']['type'] == 'antidoping') ? json_encode([
	// 			'COC' => !empty($data['test_COC']) ? $data['test_COC'] : '',
	// 			'THC' => !empty($data['test_THC']) ? $data['test_THC'] : '',
	// 			'MET' => !empty($data['test_MET']) ? $data['test_MET'] : '',
	// 			'ANF' => !empty($data['test_ANF']) ? $data['test_ANF'] : '',
	// 			'BZD' => !empty($data['test_BZD']) ? $data['test_BZD'] : '',
	// 			'OPI' => !empty($data['test_OPI']) ? $data['test_OPI'] : '',
	// 			'BAR' => !empty($data['test_BAR']) ? $data['test_BAR'] : ''
	// 		]) : (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an') ? json_encode([
	// 			'result' => $data['test_result'],
	// 			'unity' => $data['test_unity'],
	// 			'reference_values' => $data['test_reference_values']
	// 		]) : (($data['custody_chain']['type'] == 'covid_ac') ? json_encode([
	// 			'igm' => [
	// 				'result' => $data['test_igm_result'],
	// 				'unity' => $data['test_igm_unity'],
	// 				'reference_values' => $data['test_igm_reference_values']
	// 			],
	// 			'igg' => [
	// 				'result' => $data['test_igg_result'],
	// 				'unity' => $data['test_igg_unity'],
	// 				'reference_values' => $data['test_igg_reference_values']
	// 			]
	// 		]) : null))),
	// 		'medicines' => (($data['custody_chain']['type'] == 'alcoholic' OR $data['custody_chain']['type'] == 'antidoping') AND !empty($data['medicines'])) ? $data['medicines'] : null,
	// 		'prescription' => ($data['custody_chain']['type'] == 'alcoholic' OR $data['custody_chain']['type'] == 'antidoping') ? json_encode([
	// 			'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
	// 			'date' => !empty($data['prescription_date']) ? $data['prescription_date'] : ''
	// 		]) : null,
	// 		'location' => ((Session::get_value('vkye_user')['god'] == 'active' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') AND !empty($data['location'])) ? $data['location'] : null,
	// 		'chemical' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $data['chemical'][0]['id'] : $data['chemical'],
	// 		'date' => $data['date'],
	// 		'hour' => $data['hour'],
	// 		'comments' => !empty($data['comments']) ? $data['comments'] : null,
	// 		'signature' => !empty($data['signature']) ? Fileloader::base64($data['signature']) : $data['custody_chain']['signature'],
	// 		'qr' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $data['qr']['filename'] : $data['custody_chain']['qr'],
	// 		'pdf' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $data['pdf']['filename'] : $data['custody_chain']['pdf'],
	// 		'lang' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $data['lang'] : $data['custody_chain']['lang'],
	// 		'user' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? Session::get_value('vkye_user')['id'] : $data['custody_chain']['user'],
	// 		'sent' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND $data['save'] == 'save_and_send') ? true : false
	// 	], [
	// 		'id' => $data['custody_chain']['id']
	// 	]);
	//
	// 	if (!empty($query))
	// 	{
	// 		if (!empty($data['custody_chain']['signature']))
	// 			Fileloader::down($data['custody_chain']['signature']);
	//
	// 		if (!empty($data['custody_chain']['qr']))
	// 			Fileloader::down($data['custody_chain']['qr']);
	//
	// 		if (!empty($data['custody_chain']['pdf']))
	// 			Fileloader::down($data['custody_chain']['pdf']);
	// 	}
	// 	else
	// 	{
	// 		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
	// 			Fileloader::down($data['qr']['filename']);
	// 	}
	//
    //     return $query;
    // }

	public function restore_custody_chain($id)
	{
		$query = $this->database->update('custody_chains', [
			'deleted' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function empty_custody_chains()
    {
		$AND = [];

		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
			$AND['laboratory[>=]'] = 1;
		else
			$AND['account'] = Session::get_value('vkye_account')['id'];

		$AND['deleted'] = true;

		$deleteds = System::decode_json_to_array($this->database->select('custody_chains', [
			'id',
			'signature',
			'qr',
			'pdf'
        ], [
            'AND' => $AND
        ]));

		foreach ($deleteds as $value)
		{
			$query = $this->database->delete('custody_chains', [
				'id' => $value['id']
			]);

			if (!empty($query))
			{
				if (!empty($value['signature']))
					Fileloader::down($value['signature']);

				if (!empty($value['qr']))
					Fileloader::down($value['qr']);

				if (!empty($value['pdf']))
					Fileloader::down($value['pdf']);
			}
		}

        return true;
    }

	public function delete_custody_chain($id)
    {
		$query = null;

		$deleted = System::decode_json_to_array($this->database->select('custody_chains', [
			'signature',
			'qr',
			'pdf',
			'deleted'
        ], [
            'id' => $id
        ]));

		if (!empty($deleted))
		{
			if ($deleted[0]['deleted'] == false)
			{
				$query = $this->database->update('custody_chains', [
					'deleted' => true
				], [
					'id' => $id
				]);
			}
			else if ($deleted[0]['deleted'] == true)
			{
				$query = $this->database->delete('custody_chains', [
					'id' => $id
				]);

				if (!empty($query))
				{
					if (!empty($deleted[0]['signature']))
						Fileloader::down($deleted[0]['signature']);

					if (!empty($deleted[0]['qr']))
						Fileloader::down($deleted[0]['qr']);

					if (!empty($deleted[0]['pdf']))
						Fileloader::down($deleted[0]['pdf']);
				}
			}
		}

        return $query;
    }

    // public function read_employee($nie)
	// {
	// 	$query = System::decode_json_to_array($this->database->select('employees', [
    //         'id',
	// 		'firstname',
	// 		'lastname',
	// 		'birth_date',
    //         'sex',
    //         'ife',
	// 		'nie',
	// 		'nss',
	// 		'rfc',
	// 		'curp'
	// 	], [
    //         'nie' => $nie
    //     ]));
	//
    //     return !empty($query) ? $query[0] : null;
	// }

	public function read_locations()
	{
		$query = $this->database->select('locations', [
			'id',
			'name'
		], [
            'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			],
            'ORDER' => [
    			'name' => 'ASC'
    		]
        ]);

		return $query;
	}

	public function read_laboratories()
	{
		$query = System::decode_json_to_array($this->database->select('system_laboratories', [
			'id',
            'name'
        ], [
            'blocked' => false,
			'ORDER' => [
				'name' => 'ASC'
			]
        ]));

		return $query;
	}

	public function read_laboratory($path)
	{
		$query = System::decode_json_to_array($this->database->select('system_laboratories', [
			'id',
			'avatar',
            'name',
            'path',
            'business',
            'rfc',
			'sanitary_opinion',
            'address',
			'email',
            'phone',
            'rrss',
            'website',
            'time_zone',
            'colors',
            'blocked'
        ], [
			'path' => $path
		]));

		return !empty($query) ? $query[0] : null;
	}

	public function read_collectors()
	{
		$query = System::decode_json_to_array($this->database->select('system_collectors', [
            'id',
            'name'
        ], [
            'blocked' => false,
			'ORDER' => [
				'name' => 'ASC'
			]
        ]));

		return $query;
	}

	public function read_collector($token)
	{
		$query = System::decode_json_to_array($this->database->select('system_collectors', [
            'id',
            'token',
            'name',
            'schedule',
            'qrs',
            'authentication',
            'laboratories',
            'blocked'
        ], [
            'token' => $token
        ]));

		if (!empty($query))
		{
			if ($query[0]['authentication']['taker'] != 'none')
				$query[0]['authentication']['taker'] = $this->read_taker($query[0]['authentication']['taker']);

			return $query[0];
		}
		else
			return null;
	}

	public function read_takers()
	{
		$query = System::decode_json_to_array($this->database->select('system_takers', [
            'id',
            'name'
        ], [
            'blocked' => false,
			'ORDER' => [
				'name' => 'ASC'
			]
        ]));

		return $query;
	}

	public function read_taker($id)
	{
		$query = System::decode_json_to_array($this->database->select('system_takers', [
            'id',
            'name',
            'token',
            'prices'
        ], [
            'id' => $id
        ]));

		return !empty($query) ? $query[0] : null;
	}

	public function start_authentication($data)
	{
		$query = $this->database->update('system_collectors', [
			'authentication' => json_encode([
				'type' => $data['type'],
				'taker' => $data['taker']
			])
		], [
			'id' => $data['id']
		]);

		return $query;
	}

	public function end_authentication($id)
	{
		$query = $this->database->update('system_collectors', [
			'authentication' => json_encode([
				'type' => 'none',
				'taker' => 'none'
			])
		], [
			'id' => $id
		]);

		return $query;
	}
}
