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
			'contact' => ($record == true) ? json_encode([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'birth_date' => $data['birth_date'],
                'age' => $data['age'],
                'sex' => $data['sex'],
				'ife' => $data['ife'],
                'email' => $data['email'],
                'phone' => [
                    'country' => $data['phone_country'],
                    'number' => $data['phone_number']
                ]
            ]) : null,
            'type' => $data['type'],
            'reason' => ($record == true) ? 'random' : $data['reason'],
			'start_process' => ($record == true) ? Dates::current_date() : (($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an' OR $data['type'] == 'covid_ac') ? $data['start_process'] : null),
			'end_process' => ($record == true) ? null : (($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an' OR $data['type'] == 'covid_ac') ? $data['end_process'] : null),
			'results' => ($record == false AND $data['type'] == 'alcoholic') ? json_encode([
                '1' => !empty($data['test_1']) ? $data['test_1'] : '',
                '2' => !empty($data['test_2']) ? $data['test_2'] : '',
                '3' => !empty($data['test_3']) ? $data['test_3'] : ''
            ]) : (($record == false AND $data['type'] == 'antidoping') ? json_encode([
                'COC' => !empty($data['test_COC']) ? $data['test_COC'] : '',
                'THC' => !empty($data['test_THC']) ? $data['test_THC'] : '',
                'MET' => !empty($data['test_MET']) ? $data['test_MET'] : '',
                'ANF' => !empty($data['test_ANF']) ? $data['test_ANF'] : '',
                'BZD' => !empty($data['test_BZD']) ? $data['test_BZD'] : '',
                'OPI' => !empty($data['test_OPI']) ? $data['test_OPI'] : '',
                'BAR' => !empty($data['test_BAR']) ? $data['test_BAR'] : ''
            ]) : (($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an') ? json_encode([
				'result' => ($record == true) ? '' : $data['test_result'],
				'unity' => ($record == true) ? '' : $data['test_unity'],
				'reference_values' => ($record == true) ? '' : $data['test_reference_values']
			]) : (($data['type'] == 'covid_ac') ? json_encode([
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
			'medicines' => ($record == true) ? null : ((($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping') AND !empty($data['medicines'])) ? $data['medicines'] : null),
			'prescription' => ($record == true) ? null : (($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping') ? json_encode([
				'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
				'date' => !empty($data['prescription_date']) ? $data['prescription_date'] : ''
			]) : null),
			'location' => ($record == true) ? null : (!empty($data['location']) ? $data['location'] : null),
			'laboratory' => ($record == true) ? $data['laboratory']['id'] : null,
			'taker' => ($record == true) ? $data['collector']['authentication']['taker']['id'] : null,
			'collector' => ($record == true) ? $data['collector']['id'] : null,
			'chemical' => ($record == true) ? null : $data['chemical'],
			'date' => ($record == true) ? Dates::current_date() : $data['date'],
			'hour' => ($record == true) ? Dates::current_hour() : $data['hour'],
			'comments' => ($record == true) ? null : (!empty($data['comments']) ? $data['comments'] : null),
			'signatures' => ($record == true) ? null : (json_encode([
                'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : ''
            ])),
			'qr' => ($record == true) ? $data['qr']['filename'] : null,
			'pdf' => null,
			'lang' => ($record == true) ? Session::get_value('vkye_lang') : null,
			'closed' => ($record == true) ? false : true,
			'user' => ($record == true) ? null : Session::get_value('vkye_user')['id'],
			'deleted' => false
        ]);

		if ($record == true AND !empty($query))
			QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);

        return $query;
    }

	public function read_custody_chains($type)
	{
		$AND = [];

		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
		{
			$accounts = [];

			foreach (Session::get_value('vkye_user')['accounts'] as $value)
				array_push($accounts, $value['id']);
		}

		if (!empty(System::temporal('get', 'laboratory', 'filter')))
		{
			$AND['custody_chains.account'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? ((System::temporal('get', 'laboratory', 'filter')['account'] == 'all') ? $accounts : System::temporal('get', 'laboratory', 'filter')['account']) : Session::get_value('vkye_account')['id'];
			$AND['custody_chains.type'] = ($type == 'covid') ? ((System::temporal('get', 'laboratory', 'filter')['type'] == 'all') ? ['covid_pcr','covid_an','covid_ac'] : System::temporal('get', 'laboratory', 'filter')['type']) : $type;
			$AND['custody_chains.date[<>]'] = [System::temporal('get', 'laboratory', 'filter')['start_date'], System::temporal('get', 'laboratory', 'filter')['end_date']];
			$AND['custody_chains.hour[<>]'] = [System::temporal('get', 'laboratory', 'filter')['start_hour'], System::temporal('get', 'laboratory', 'filter')['end_hour']];

			if ($type == 'covid' AND System::temporal('get', 'laboratory', 'filter')['sended_status'] == 'not_sended')
				$AND['custody_chains.closed'] = false;
			else if ($type == 'covid' AND System::temporal('get', 'laboratory', 'filter')['sended_status'] == 'sended')
				$AND['custody_chains.closed'] = true;

			$AND['custody_chains.deleted'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? ((System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') ? true : false) : false;
		}
		else
		{
			$AND['custody_chains.account'] = (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $accounts : Session::get_value('vkye_account')['id'];
			$AND['custody_chains.type'] = ($type == 'covid') ? ['covid_pcr','covid_an','covid_ac'] : $type;
			$AND['custody_chains.date[<>]'] = [Dates::past_date(Dates::current_date(), 1, 'days'), Dates::current_date()];
			$AND['custody_chains.deleted'] = false;
		}

		$query = System::decode_json_to_array($this->database->select('custody_chains', [
			'[>]accounts' => [
				'account' => 'id'
			],
			'[>]employees' => [
				'employee' => 'id'
			],
			'[>]users' => [
				'user' => 'id'
			]
		], [
			'custody_chains.id',
			'accounts.name(account_name)',
			'accounts.path(account_path)',
			'custody_chains.token',
			'custody_chains.employee',
			'employees.firstname(employee_firstname)',
			'employees.lastname(employee_lastname)',
			'custody_chains.contact',
			'custody_chains.type',
			'custody_chains.results',
			'custody_chains.date',
			'custody_chains.hour',
			'custody_chains.pdf',
			'custody_chains.closed',
			'custody_chains.user',
			'users.firstname(user_firstname)',
			'users.lastname(user_lastname)',
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
			'custody_chains.chemical',
			'system_chemicals.name(chemical_name)',
			'system_chemicals.signature(chemical_signature)',
			'system_chemicals.card(chemical_card)',
			'custody_chains.location',
			'custody_chains.date',
			'custody_chains.hour',
			'custody_chains.comments',
			'custody_chains.signatures',
			'custody_chains.qr',
			'custody_chains.pdf',
			'custody_chains.lang',
			'custody_chains.closed',
			'custody_chains.user',
			'custody_chains.deleted'
		], [
			'AND' => [
				'custody_chains.token' => $token,
				'custody_chains.deleted' => false
			]
		]));

		return !empty($query) ? $query[0] : null;
	}

	public function update_custody_chain($data)
    {
		if (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']))
		{
			if ($data['custody_chain']['account_path'] != 'moonpalace')
			{
				$data['qr']['content'] = 'https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/covid/' . $data['custody_chain']['token'];
				$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
				$data['qr']['level'] = 'H';
				$data['qr']['size'] = 5;
				$data['qr']['frame'] = 3;

				QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);
			}

			$data['chemical'] = $this->database->select('system_chemicals', [
				'id',
				'name',
				'signature',
				'card'
			], [
				'id' => $data['chemical']
			]);

			$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
			$writing =
			'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            <img style="width:100%;" src="' . PATH_IMAGES . 'marbu_logotype_color.png">
			        </td>
			        <td style="width:60%;margin:0px;padding:0px 0px 0px 10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            <table style="width:100%;margin:0px;padding:0px;border:0px;">
			                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#004770;">Marbu Salud S.A. de C.V.</td>
			                </tr>
			                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">MSA1907259GA</td>
			                </tr>
			                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">Av. Del Sol SM47 M6 L21 Planta Alta</td>
			                </tr>
			                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">CP: 77506 Cancún, Qroo. México</td>
			                </tr>
			            </table>
			        </td>
			        <td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            ' . (($data['custody_chain']['account_path'] != 'moonpalace') ? '<img style="width:100%;" src="' . PATH_UPLOADS . $data['qr']['filename'] . '">' : '') . '
			        </td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:28px;font-weight:600;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('result_report')[$data['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('marbu_laboratory_analisys')[$data['lang']] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('n_petition')[$data['lang']] . ': ' . $data['custody_chain']['token'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('registry_date')[$data['lang']] . ': ' . $data['date'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('company')[$data['lang']] . ': N/A</td>
					<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('patient')[$data['lang']] . ': ' . $data['firstname'] . ' ' . $data['lastname'] . '</td>
				</tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	        		<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('birth_date')[$data['lang']] . ': ' . $data['birth_date'] . '</td>
	        		<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('age')[$data['lang']] . ': ' . $data['age'] . ' ' . Languages::email('years')[$data['lang']] . '</td>
	        		<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('sex')[$data['lang']] . ': ' . Languages::email($data['sex'])[$data['lang']] . '</td>
				</tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_date')[$data['lang']] . ': ' . $data['date'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_hour')[$data['lang']] . ': ' . $data['hour'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . $data['chemical'][0]['name'] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('start_process')[$data['lang']] . ': ' . $data['start_process'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('end_process')[$data['lang']] . ': ' . $data['end_process'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('id_patient')[$data['lang']] . ': ' . $data['ife'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;"></td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('immunological_analysis')[$data['lang']] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('exam')[$data['lang']] . '</td>
			        <td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('result')[$data['lang']] . '</td>
			        <td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('unity')[$data['lang']] . '</td>
			        <td style="width:25%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('reference_values')[$data['lang']] . '</td>
			    </tr>';

			if ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an')
			{
				$writing .= '<tr style="width:100%;margin:0px;padding:0px;border:0px;">';

				if ($data['custody_chain']['type'] == 'covid_pcr')
					$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">PCR-SARS-CoV-2 (COVID-19)</td>';
				else if ($data['custody_chain']['type'] == 'covid_an')
					$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">Ag-SARS-CoV-2 (COVID-19)</td>';

				$writing .=
				'	<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_result'])[$data['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_unity'])[$data['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_reference_values'])[$data['lang']] . '</td>
				</tr>';
			}
			else if ($data['custody_chain']['type'] == 'covid_ac')
			{
				$writing .=
				'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">SARS-CoV-2 (2019) IgG/IgM</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;"></td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;"></td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;"></td>
				</tr>
				<tr style="width:100%;margin:0px;padding:0px;border:0px;">
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('anticorps')[$data['lang']] . ' IgM</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igm_result'])[$data['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igm_unity'])[$data['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igm_reference_values'])[$data['lang']] . '</td>
				</tr>
				<tr style="width:100%;margin:0px;padding:0px;border:0px;">
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('anticorps')[$data['lang']] . ' IgG</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igg_result'])[$data['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igg_unity'])[$data['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igg_reference_values'])[$data['lang']] . '</td>
				</tr>';
			}

			$writing .= '</table>';

			if ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an')
			{
				$writing .=
				'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
				        <td style="width:100%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . (($data['custody_chain']['type'] == 'covid_pcr') ? Languages::email('atila_biosystem')[$data['lang']] . ' | ' : '') . Languages::email('nasopharynx_secretion')[$data['lang']] . '</td>
				    </tr>
				</table>
				<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_1')[$data['lang']] . '</td>
				    </tr>
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_2')[$data['lang']] . '</td>
				    </tr>
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_3')[$data['lang']] . '</td>
				    </tr>
				</table>';
			}
			else if ($data['custody_chain']['type'] == 'covid_ac')
			{
				$writing .=
				'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:justify;color:#004770;">' . Languages::email('notes_ac_1')[$data['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_2')[$data['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_3')[$data['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_4')[$data['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_5')[$data['lang']] . '</td>
					</tr>
				</table>';
			}

			$writing .=
			'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('valid_results_by')[$data['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;text-align:center;">
			            <img style="width:100px" src="' . PATH_UPLOADS . $data['chemical'][0]['signature'] . '">
			        </td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . $data['chemical'][0]['name'] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('health_manager')[$data['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:0px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('identification_card')[$data['lang']] . ': ' . $data['chemical'][0]['card'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#000;">' . Languages::email('alert_pdf_covid')[$data['lang']] . (($data['custody_chain']['account_path'] != 'moonpalace') ? ' <strong style="color:#f44336;">' . Languages::email('accept_terms')[$data['lang']] . '</strong>' : '') . '</td>
			    </tr>';

			if ($data['custody_chain']['account_path'] != 'moonpalace')
			{
				$writing .=
				'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">+ (52) 998 313 2948 - 998 440 3302 | marbu@one-consultores.com | marbu.one-consultores.com</td>
			    </tr>';
			}

			$writing .= '</table>';
			$html2pdf->writeHTML($writing);
			$html2pdf->output(PATH_UPLOADS . $data['pdf']['filename'], 'F');
		}

		$query = $this->database->update('custody_chains', [
			'contact' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? json_encode([
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'ife' => $data['ife'],
				'birth_date' => $data['birth_date'],
				'age' => $data['age'],
				'sex' => $data['sex'],
				'email' => $data['email'],
				'phone' => [
					'country' => $data['phone_country'],
					'number' => $data['phone_number']
				],
				'travel_to' => $data['travel_to']
			]) : null,
			'reason' => $data['reason'],
			'start_process' => ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') ? $data['start_process'] : null,
			'end_process' => ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') ? $data['end_process'] : null,
			'results' => ($data['custody_chain']['type'] == 'alcoholic') ? json_encode([
				'1' => !empty($data['test_1']) ? $data['test_1'] : '',
				'2' => !empty($data['test_2']) ? $data['test_2'] : '',
				'3' => !empty($data['test_3']) ? $data['test_3'] : ''
			]) : (($data['custody_chain']['type'] == 'antidoping') ? json_encode([
				'COC' => !empty($data['test_COC']) ? $data['test_COC'] : '',
				'THC' => !empty($data['test_THC']) ? $data['test_THC'] : '',
				'MET' => !empty($data['test_MET']) ? $data['test_MET'] : '',
				'ANF' => !empty($data['test_ANF']) ? $data['test_ANF'] : '',
				'BZD' => !empty($data['test_BZD']) ? $data['test_BZD'] : '',
				'OPI' => !empty($data['test_OPI']) ? $data['test_OPI'] : '',
				'BAR' => !empty($data['test_BAR']) ? $data['test_BAR'] : ''
			]) : (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an') ? json_encode([
				'result' => $data['test_result'],
				'unity' => $data['test_unity'],
				'reference_values' => $data['test_reference_values']
			]) : (($data['custody_chain']['type'] == 'covid_ac') ? json_encode([
				'igm' => [
					'result' => $data['test_igm_result'],
					'unity' => $data['test_igm_unity'],
					'reference_values' => $data['test_igm_reference_values']
				],
				'igg' => [
					'result' => $data['test_igg_result'],
					'unity' => $data['test_igg_unity'],
					'reference_values' => $data['test_igg_reference_values']
				]
			]) : null))),
			'medicines' => (($data['custody_chain']['type'] == 'alcoholic' OR $data['custody_chain']['type'] == 'antidoping') AND !empty($data['medicines'])) ? $data['medicines'] : null,
			'prescription' => ($data['custody_chain']['type'] == 'alcoholic' OR $data['custody_chain']['type'] == 'antidoping') ? json_encode([
				'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
				'date' => !empty($data['prescription_date']) ? $data['prescription_date'] : ''
			]) : null,
			'chemical' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? $data['chemical'][0]['id'] : $data['chemical'],
			'location' => !empty($data['location']) ? $data['location'] : null,
			'date' => $data['date'],
			'hour' => $data['hour'],
			'comments' => !empty($data['comments']) ? $data['comments'] : null,
			'signatures' => (($data['custody_chain']['type'] == 'alcoholic' OR $data['custody_chain']['type'] == 'antidoping') OR (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND !empty($data['custody_chain']['employee']))) ? json_encode([
				'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : $data['custody_chain']['signatures']['employee']
			]) : null,
			'qr' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']) AND $data['custody_chain']['account_path'] != 'moonpalace') ? $data['qr']['filename'] : $data['custody_chain']['qr'],
			'pdf' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? $data['pdf']['filename'] : $data['custody_chain']['pdf'],
			'lang' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? $data['lang'] : $data['custody_chain']['lang'],
			'closed' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']) AND $data['custody_chain']['closed'] == false AND $data['save'] == 'only_save') ? false : true,
			'user' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? Session::get_value('vkye_user')['id'] : $data['custody_chain']['user']
		], [
			'id' => $data['custody_chain']['id']
		]);

		if (!empty($query))
		{
			if (!empty($data['custody_chain']['employee']) AND !empty($data['employee_signature']) AND !empty($data['custody_chain']['signatures']['employee']))
				Fileloader::down($data['custody_chain']['signatures']['employee']);

			if (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']))
			{
				if ($data['custody_chain']['account_path'] != 'moonpalace' AND !empty($data['custody_chain']['qr']))
					Fileloader::down($data['custody_chain']['qr']);

				if (!empty($data['custody_chain']['pdf']))
					Fileloader::down($data['custody_chain']['pdf']);
			}
		}
		else if (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']) AND $data['custody_chain']['account_path'] != 'moonpalace')
			Fileloader::down($data['qr']['filename']);

        return $query;
    }

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
		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
		{
			$accounts = [];

			foreach (Session::get_value('vkye_user')['accounts'] as $value)
				array_push($accounts, $value['id']);
		}

		$deleteds = System::decode_json_to_array($this->database->select('custody_chains', [
			'id',
			'type',
			'employee',
			'signatures',
			'qr',
			'pdf'
        ], [
            'AND' => [
				'account' => (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $accounts : Session::get_value('vkye_account')['id'],
				'deleted' => true
			]
        ]));

		foreach ($deleteds as $value)
		{
			$query = $this->database->delete('custody_chains', [
				'id' => $value['id']
			]);

			if (!empty($query))
			{
				if (!empty($value['employee']) AND !empty($value['signatures']['employee']))
					Fileloader::down($value['signatures']['employee']);

				if (($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') AND empty($value['employee']))
				{
					if (!empty($value['qr']))
						Fileloader::down($value['qr']);

					if (!empty($value['pdf']))
						Fileloader::down($value['pdf']);
				}
			}
		}

        return true;
    }

	public function delete_custody_chain($id)
    {
		$query = null;

		$deleted = System::decode_json_to_array($this->database->select('custody_chains', [
			'type',
			'employee',
			'signatures',
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
					if (!empty($deleted[0]['employee']) AND !empty($deleted[0]['signatures']['employee']))
						Fileloader::down($deleted[0]['signatures']['employee']);

					if (($deleted[0]['type'] == 'covid_pcr' OR $deleted[0]['type'] == 'covid_an' OR $deleted[0]['type'] == 'covid_ac') AND empty($deleted[0]['employee']))
					{
						if (!empty($deleted[0]['qr']))
							Fileloader::down($deleted[0]['qr']);

						if (!empty($deleted[0]['pdf']))
							Fileloader::down($deleted[0]['pdf']);
					}
				}
			}
		}

        return $query;
    }

    public function read_employee($nie)
	{
		$query = System::decode_json_to_array($this->database->select('employees', [
            'id',
			'firstname',
			'lastname',
			'birth_date',
            'sex',
            'ife',
			'nie',
			'nss',
			'rfc',
			'curp'
		], [
            'AND' => [
                'nie' => $nie,
                'blocked' => false
            ]
        ]));

        return !empty($query) ? $query[0] : null;
	}

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

	public function read_laboratory($path)
	{
		$query = System::decode_json_to_array($this->database->select('system_laboratories', [
			'id',
			'avatar',
            'name',
            'path',
            'business',
            'rfc',
            'address',
			'email',
            'phone',
            'rrss',
            'website',
            'colors',
            'blocked'
        ], [
            'path' => $path
        ]));

		return !empty($query) ? $query[0] : null;
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
            'name'
        ], [
            'id' => $id
        ]));

		return !empty($query) ? $query[0] : null;
	}

	public function create_authentication($data)
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

	public function delete_authentication($id)
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

	// public function read_custody_chain($token)
	// {
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'[>]system_chemicals' => [
	// 			'chemical' => 'id'
	// 		]
	// 	], [
	// 		'custody_chains.token',
	// 		'custody_chains.contact',
	// 		'custody_chains.type',
	// 		'custody_chains.start_process',
	// 		'custody_chains.end_process',
	// 		'custody_chains.results',
	// 		'system_chemicals.name(chemical_name)',
	// 		'system_chemicals.signature(chemical_signature)',
	// 		'custody_chains.date',
	// 		'custody_chains.hour',
	// 		'custody_chains.comments',
	// 		'custody_chains.qr',
	// 		'custody_chains.pdf',
	// 		'custody_chains.lang',
	// 		'custody_chains.closed'
	// 	], [
	// 		'AND' => [
	// 			'custody_chains.token' => $token,
	// 			'custody_chains.deleted' => false
	// 		]
	// 	]));
	//
	// 	return !empty($query) ? $query[0] : null;
	// }
	//
    // public function read_account($path)
    // {
    //     $query = System::decode_json_to_array($this->database->select('accounts', [
    //         'id',
    //         'avatar',
    //         'path',
    //         'email',
    //         'phone',
    //         'time_zone'
    //     ], [
    //         'AND' => [
	// 			'path' => $path,
	// 			'blocked' => false
	// 		]
    //     ]));
	//
    //     return !empty($query) ? $query[0] : null;
    // }

	// public function sql()
	// {
	// 	set_time_limit(1000000);
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'id'
	// 	]));
	//
	// 	foreach ($query as $value)
	// 	{
	// 		$this->database->update('custody_chains', [
	// 			'qr' => null,
	// 			'pdf' => null
	// 		], [
	// 			'id' => $value['id']
	// 		]);
	// 	}
	//
	// 	// $query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 	// 	'id',
	// 	// 	'token'
	// 	// ], [
	// 	// 	'date' => '2021-02-26'
	// 	// ]));
	// 	//
	// 	// $exists = [];
	// 	//
	// 	// foreach ($query as $value)
	// 	// {
	// 	// 	if (in_array($value['token'], $exists))
	// 	// 		$this->database->delete('custody_chains', ['id' => $value['id']]);
	// 	// 	else
	// 	// 		array_push($exists, $value['token']);
	// 	// }
	// }

	// public function sql()
	// {
	// 	set_time_limit(1000000);
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'[>]accounts' => [
	// 			'account' => 'id'
	// 		]
	// 	], [
	// 		'custody_chains.id',
	// 		'accounts.path',
	// 		'custody_chains.token',
	// 	], [
	// 		'custody_chains.account' => 19
	// 	]));
	//
	// 	print_r(count($query));
	//
	// 	// foreach ($query as $value)
	// 	// {
	// 	// 	$qr_filename = 'covid_qr_' . $value['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.png';
	// 	// 	$qr_content = 'https://id.one-consultores.com/' . $value['path'] . '/covid/' . $value['token'];
	// 	// 	$qr_dir = PATH_UPLOADS . $qr_filename;
	// 	// 	$qr_level = 'H';
	// 	// 	$qr_size = 5;
	// 	// 	$qr_frame = 3;
	// 	//
	// 	// 	QRcode::png($qr_content, $qr_dir, $qr_level, $qr_size, $qr_frame);
	// 	//
	// 	// 	$this->database->update('custody_chains', [
	// 	// 		'qr' => $qr_filename
	// 	// 	], [
	// 	// 		'id' => $value['id']
	// 	// 	]);
	// 	// }
	// }

	// public function sql()
	// {
	// 	set_time_limit(1000000);
	//
	// 	$xlsx = SimpleXLSX::parse(PATH_UPLOADS . '13 Resultados Marbu Salud 13 de Marzo del 2021/hisopados  cancun 2021.xlsx');
	// 	$start_process = '2021-03-13';
	// 	$end_process = '2021-03-13';
	//
	//     foreach ($xlsx->rows() as $value)
	//     {
	// 		$value[2] = explode(' ', $value[2]);
	// 		$value[7] = 2021 - intval(explode('-', $value[2][0])[0]);
	//
	// 		$this->database->insert('custody_chains', [
	//             'account' => 19,
	// 			'token' => System::generate_random_string(),
	//             'employee' => null,
	// 			'contact' => json_encode([
	//                 'firstname' => strtoupper($value[0]),
	//                 'lastname' => '',
	// 				'ife' => $value[1],
	//                 'birth_date' => $value[2][0],
	//                 'age' => $value[7],
	//                 'sex' => $value[3],
	//                 'email' => 'cancun@moontravel.com.ar',
	//                 'phone' => [
	//                     'country' => '54',
	//                     'number' => '1157072337'
	//                 ],
	//                 'travel_to' => strtoupper($value[4])
	//             ]),
	//             'type' => 'covid_pcr',
	//             'reason' => 'random',
	// 			'start_process' => $start_process,
	// 			'end_process' => $end_process,
	//             'results' => json_encode([
	// 				'result' => $value[6],
	// 				'unity' => 'INDEX',
	// 				'reference_values' => 'not_detected'
	// 			]),
	//             'medicines' => null,
	//             'prescription' => null,
	// 			'collector' => 2,
	// 			'location' => null,
	// 			'date' => $start_process,
	// 			'hour' => $value[5],
	// 			'comments' => null,
	//             'signatures' => null,
	// 			'qr' => null,
	// 			'pdf' => null,
	// 			'lang' => 'es',
	// 			'closed' => true,
	// 			'user' => 1,
	// 			'deleted' => false
	//         ]);
	//     }
	// }

	// public function sql()
	// {
	// 	set_time_limit(1000000);
	//
	// 	$xlsx = SimpleXLSX::parse(PATH_UPLOADS . '13 Resultados Marbu Salud 13 de Marzo del 2021/hisopados  cancun 2021 (Positivos).xlsx');
	// 	$start_process = '2021-03-13';
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'id',
	// 		'contact'
	// 	], [
	// 		'AND' => [
	// 			'account' => 19,
	// 			'date' => $start_process
	// 		]
	// 	]));
	//
	// 	print_r(count($query));
	//
	//     // foreach ($xlsx->rows() as $value)
	//     // {
	// 	// 	foreach ($query as $subvalue)
	// 	// 	{
	// 	// 		if ($value[1] == $subvalue['contact']['ife'])
	// 	// 		{
	// 	// 			$this->database->update('custody_chains', [
	// 	// 	            'results' => json_encode([
	// 	// 					'result' => $value[4],
	// 	// 					'unity' => 'INDEX',
	// 	// 					'reference_values' => 'not_detected'
	// 	// 				])
	// 	// 	        ], [
	// 	// 				'id' => $subvalue['id']
	// 	// 			]);
	// 	// 		}
	// 	// 	}
	//     // }
	// }

	// public function sql()
	// {
	// 	set_time_limit(100000000);
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'id',
	// 		'token',
	// 		'qr',
	// 		'lang',
	// 		'token',
	// 		'contact',
	// 		'hour',
	// 		'date',
	// 		'results',
	// 		'type',
	// 		'start_process',
	// 		'end_process',
	// 	], [
	// 		'account' => 19
	// 	]));
	//
	// 	// print_r(count($query));
	//
	// 	foreach ($query as $value)
	// 	{
	// 		$pdf_filename = $value['start_process'] . '_' . $value['contact']['firstname'] . '_' . $value['contact']['lastname'] . '_' . $value['token'] . '.pdf';
	//
	// 		$chemical = $this->database->select('system_chemicals', [
	// 			'id',
	// 			'name',
	// 			'signature',
	// 			'card'
	// 		], [
	// 			'id' => 1
	// 		]);
	//
	// 		$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
	// 		$writing =
	// 		'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 					<img style="width:100%;" src="https://' . Configuration::$domain . '/images/marbu_logotype_color.png">
	// 				</td>
	// 				<td style="width:60%;margin:0px;padding:0px 0px 0px 10px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 					<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#004770;">Marbu Salud S.A. de C.V.</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">MSA1907259GA</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">Av. Del Sol SM47 M6 L21 Planta Alta</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">CP: 77506 Cancún, Qroo. México</td>
	// 						</tr>
	// 					</table>
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 					<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $value['qr'] . '">
	// 				</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:28px;font-weight:600;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('result_report')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('marbu_laboratory_analisys')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('n_petition')[$value['lang']] . ': ' . $value['token'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('registry_date')[$value['lang']] . ': ' . $value['start_process'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('company')[$value['lang']] . ': ' . $value['contact']['travel_to'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('patient')[$value['lang']] . ': ' . $value['contact']['firstname'] . ' ' . $value['contact']['lastname'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('birth_date')[$value['lang']] . ': ' . $value['contact']['birth_date'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('age')[$value['lang']] . ': ' . $value['contact']['age'] . ' ' . Languages::email('years')[$value['lang']] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('sex')[$value['lang']] . ': ' . Languages::email($value['contact']['sex'])[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_date')[$value['lang']] . ': ' . $value['start_process'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_hour')[$value['lang']] . ': ' . $value['hour'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . $chemical[0]['name'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('start_process')[$value['lang']] . ': ' . $value['start_process'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('end_process')[$value['lang']] . ': ' . $value['end_process'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('id_patient')[$value['lang']] . ': ' . $value['contact']['ife'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;"></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('immunological_analysis')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('exam')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('result')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('unity')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('reference_values')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">';
	//
	// 		if ($value['type'] == 'covid_pcr')
	// 			$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">PCR-SARS-CoV-2 (COVID-19)</td>';
	// 		else if ($value['type'] == 'covid_an')
	// 			$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">Ag-SARS-CoV-2 (COVID-19)</td>';
	//
	// 		$writing .=
	// 		'		<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($value['results']['result'])[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('INDEX')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('not_detected')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
	// 				<td style="width:100%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('atila_biosystem')[$value['lang']] . ' | ' . Languages::email('nasopharynx_secretion')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_1')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_2')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_3')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('valid_results_by')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;text-align:center;">
	// 					<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $chemical[0]['signature'] . '">
	// 				</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . $chemical[0]['name'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('health_manager')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('identification_card')[$value['lang']] . ': ' . $chemical[0]['card'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#000;">' . Languages::email('alert_pdf_covid')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>';
	// 		$html2pdf->writeHTML($writing);
	//
	// 		if ($value['results']['result'] == 'negative')
	// 			$html2pdf->output(PATH_UPLOADS . $pdf_filename, 'F');
	// 		else if ($value['results']['result'] == 'positive')
	// 			$html2pdf->output(PATH_UPLOADS . 'Positivos/' . $pdf_filename, 'F');
	//
	// 		$this->database->update('custody_chains', [
	// 			'pdf' => $pdf_filename
	// 		], [
	// 			'id' => $value['id']
	// 		]);
	// 	}
	// }

	// public function sql()
	// {
	// 	set_time_limit(100000000);
	//
	// 	$start_process = '2021-03-16';
	// 	$end_process = '2021-03-16';
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'[>]accounts' => [
	// 			'account' => 'id'
	// 		]
	// 	], [
	// 		'custody_chains.id',
	// 		'custody_chains.token',
	// 		'custody_chains.qr',
	// 		'custody_chains.lang',
	// 		'custody_chains.token',
	// 		'custody_chains.contact',
	// 		'custody_chains.hour',
	// 		'custody_chains.date',
	// 		'custody_chains.type',
	// 		'accounts.path'
	// 	], [
	// 		'AND' => [
	// 			'custody_chains.type' => ['covid_pcr','covid_an'],
	// 			'custody_chains.date' => $start_process,
	// 			'custody_chains.hour[<>]' => ['00:00:00','13:00:00'],
	// 			'custody_chains.closed' => false,
	// 			'custody_chains.deleted' => false
	// 		]
	// 	]));
	//
	// 	// print_r(count($query));
	//
	// 	foreach ($query as $value)
	// 	{
	// 		$date = explode('-', $value['date']);
	// 		$hour = explode(':', $value['hour']);
	// 		$pdf_filename = 'covid_pdf_' . $value['token'] . '_' . $date[0] . '_' . $date[1] . '_' . $date[2] . '_' . $hour[0] . '_' . $hour[1] . '_' . $hour[2] . '.pdf';
	//
	// 		$chemical = $this->database->select('system_chemicals', [
	// 			'id',
	// 			'name',
	// 			'signature',
	// 			'card'
	// 		], [
	// 			'id' => 1
	// 		]);
	//
	// 		$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
	// 		$writing =
	// 		'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 					<img style="width:100%;" src="https://' . Configuration::$domain . '/images/marbu_logotype_color.png">
	// 				</td>
	// 				<td style="width:60%;margin:0px;padding:0px 0px 0px 10px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 					<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:600;text-align:left;color:#004770;">Marbu Salud S.A. de C.V.</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">MSA1907259GA</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">Av. Del Sol SM47 M6 L21 Planta Alta</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">CP: 77506 Cancún, Qroo. México</td>
	// 						</tr>
	// 					</table>
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 					<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $value['qr'] . '">
	// 				</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:28px;font-weight:600;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('result_report')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('marbu_laboratory_analisys')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('n_petition')[$value['lang']] . ': ' . $value['token'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('registry_date')[$value['lang']] . ': ' . $start_process . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('company')[$value['lang']] . ': N/A</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('patient')[$value['lang']] . ': ' . $value['contact']['firstname'] . ' ' . $value['contact']['lastname'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('birth_date')[$value['lang']] . ': ' . $value['contact']['birth_date'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('age')[$value['lang']] . ': ' . $value['contact']['age'] . ' ' . Languages::email('years')[$value['lang']] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('sex')[$value['lang']] . ': ' . Languages::email($value['contact']['sex'])[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_date')[$value['lang']] . ': ' . $start_process . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_hour')[$value['lang']] . ': ' . $value['hour'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . $chemical[0]['name'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('start_process')[$value['lang']] . ': ' . $start_process . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('end_process')[$value['lang']] . ': ' . $end_process . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('id_patient')[$value['lang']] . ': ' . $value['contact']['ife'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;"></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('immunological_analysis')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('exam')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('result')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('unity')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('reference_values')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">';
	//
	// 		if ($value['type'] == 'covid_pcr')
	// 			$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">PCR-SARS-CoV-2 (COVID-19)</td>';
	// 		else if ($value['type'] == 'covid_an')
	// 			$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">Ag-SARS-CoV-2 (COVID-19)</td>';
	//
	// 		$writing .=
	// 		'		<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('negative')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('INDEX')[$value['lang']] . '</td>
	// 				<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('not_detected')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
	// 				<td style="width:100%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('atila_biosystem')[$value['lang']] . ' | ' . Languages::email('nasopharynx_secretion')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_1')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_2')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_3')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('valid_results_by')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;text-align:center;">
	// 					<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $chemical[0]['signature'] . '">
	// 				</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . $chemical[0]['name'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('health_manager')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('identification_card')[$value['lang']] . ': ' . $chemical[0]['card'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#000;">' . Languages::email('alert_pdf_covid')[$value['lang']] . ' <strong style="color:#f44336;">' . Languages::email('accept_terms')[$value['lang']] . '</strong></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 		        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">+ (52) 998 440 3302 | marbu@one-consultores.com | marbu.one-consultores.com</td>
	// 		    </tr>
	// 		</table>';
	// 		$html2pdf->writeHTML($writing);
	// 		$html2pdf->output(PATH_UPLOADS . $pdf_filename, 'F');
	//
	// 		$query = $this->database->update('custody_chains', [
	// 			'end_process' => $end_process,
	// 			'results' => json_encode([
	// 				'result' => 'negative',
	// 				'unity' => 'INDEX',
	// 				'reference_values' => 'not_detected'
	// 			]),
	// 			'chemical' => 1,
	// 			'pdf' => $pdf_filename,
	// 			'closed' => true,
	// 			'user' => 1
	// 		], [
	// 			'id' => $value['id']
	// 		]);
	//
	// 		$mail = new Mailer(true);
	//
	// 		try
	// 		{
	// 			$mail->setFrom(Configuration::$vars['marbu']['email'], 'Marbu Salud');
	// 			$mail->addAddress($value['contact']['email'], $value['contact']['firstname'] . ' ' . $value['contact']['lastname']);
	// 			$mail->addAttachment(PATH_UPLOADS . $pdf_filename);
	// 			$mail->Subject = '¡' . Languages::email('hi')[$value['lang']] . ' ' . explode(' ',  $value['contact']['firstname'])[0] . '! ' . Languages::email('your_results_are_ready')[$value['lang']];
	// 			$mail->Body =
	// 			'<html>
	// 				<head>
	// 					<title>' . $mail->Subject . '</title>
	// 				</head>
	// 				<body>
	// 					<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#004770;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100px;margin:0px;padding:20px 0px 20px 20px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 								<img style="width:100px" src="https://' . Configuration::$domain . '/images/marbu_logotype_color_circle.png">
	// 							</td>
	// 							<td style="width:auto;margin:0px;padding:20px;border:0px;box-sizing:border-box;vertical-align:middle;">
	// 								<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 									<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 										<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:600;text-align:right;color:#fff;">Marbu Salud S.A. de C.V.</td>
	// 									</tr>
	// 									<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 										<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">MSA1907259GA</td>
	// 									</tr>
	// 									<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 										<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">Av. Del Sol SM47 M6 L21 Planta Alta</td>
	// 									</tr>
	// 									<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 										<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:12px;font-weight:400;text-align:right;color:#fff;">CP: 77506 Cancún, Qroo. México</td>
	// 									</tr>
	// 								</table>
	// 							</td>
	// 						</tr>
	// 					</table>
	// 					<table style="width:100%;max-width:600px;margin:20px 0px;padding:0px;border:1px dashed #000;box-sizing:border-box;background-color:#fff;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-align:center;text-transform:uppercase;color:#000;">¡' . Languages::email('ready_results')[$value['lang']] . '!</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:center;color:#757575;">¡' . Languages::email('hi')[Session::get_value('vkye_lang')] . ' <strong>' . explode(' ', $value['contact']['firstname'])[0] . '</strong>! ' . Languages::email('get_covid_results_1')[$value['lang']] . ' <strong>' . Dates::format_date($value['date'], 'short') . '</strong> ' . Languages::email('get_covid_results_2')[$value['lang']] . '</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
	// 								<a style="width:100%;display:block;margin:0px;padding:10px;border:1px solid #bdbdbd;border-radius:5px;box-sizing:border-box;background-color:#fff;font-size:14px;font-weight:400;text-align:center;text-decoration:none;color:#757575;" href="https://api.whatsapp.com/send?phone=' . Configuration::$vars['marbu']['phone'] . '&text=Hola, soy ' . $value['contact']['firstname'] . ' ' . $value['contact']['lastname'] . '. Mi folio es: ' . $value['token'] . '. ">' . Languages::email('whatsapp_us_to_support')[$value['lang']] . '</a>
	// 							</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;">
	// 								<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $value['qr'] . '">
	// 							</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px;border:0px;box-sizing:border-box;">
	// 								<a style="width:100%;display:block;margin:0px;padding:10px;border:0px;border-radius:5px;box-sizing:border-box;background-color:#009688;font-size:14px;font-weight:400;text-align:center;text-decoration:none;color:#fff;" href="https://' . Configuration::$domain . '/' . $value['path'] . '/covid/' . $value['token'] . '">' . Languages::email('view_online_results')[$value['lang']] . '</a>
	// 							</td>
	// 						</tr>
	// 					</table>
	// 					<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#0b5178;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="tel:' . Configuration::$vars['marbu']['phone'] . '">' . Configuration::$vars['marbu']['phone'] . '</a></td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="mailto:' . Configuration::$vars['marbu']['email'] . '">' . Configuration::$vars['marbu']['email'] . '</a></td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;"><a style="text-decoration:none;color:#fff;" href="https://' . Configuration::$vars['marbu']['website'] . '">' . Configuration::$vars['marbu']['website'] . '</a></td>
	// 						</tr>
	// 					</table>
	// 					<table style="width:100%;max-width:600px;margin:0px;padding:0px;border:0px;background-color:#004770;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:20px 20px 0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">' . Languages::email('power_by')[$value['lang']] . ' <a style="font-weight:600;text-decoration:none;color:#fff;" href="https://id.one-consultores.com">' . Configuration::$web_page . ' ' . Configuration::$web_version . '</a></td>
	// 						</tr
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Copyright (C) <a style="text-decoration:none;color:#fff;" href="https://one-consultores.com">One Consultores</a></td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 20px 20px 20px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#fff;">Software ' . Languages::email('development_by')[$value['lang']] . ' <a style="text-decoration:none;color:#fff;" href="https://codemonkey.com.mx">Code Monkey</a></td>
	// 						</tr>
	// 					</table>
	// 				</body>
	// 			</html>';
	// 			$mail->send();
	// 		}
	// 		catch (Exception $e) {}
	// 	}
	// }
}
