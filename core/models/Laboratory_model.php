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

    public function create_custody_chain($data)
    {
        $query = $this->database->insert('custody_chains', [
			'account' => Session::get_value('vkye_account')['id'],
			'token' => System::generate_random_string(),
            'employee' => $data['employee'],
			'contact' => null,
            'type' => $data['type'],
            'reason' => $data['reason'],
			'start_process' => ($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an' OR $data['type'] == 'covid_ac') ? $data['start_process'] : null,
			'end_process' => ($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an' OR $data['type'] == 'covid_ac') ? $data['end_process'] : null,
			'results' => ($data['type'] == 'alcoholic') ? json_encode([
                '1' => !empty($data['test_1']) ? $data['test_1'] : '',
                '2' => !empty($data['test_2']) ? $data['test_2'] : '',
                '3' => !empty($data['test_3']) ? $data['test_3'] : ''
            ]) : (($data['type'] == 'antidoping') ? json_encode([
                'COC' => !empty($data['test_COC']) ? $data['test_COC'] : '',
                'THC' => !empty($data['test_THC']) ? $data['test_THC'] : '',
                'MET' => !empty($data['test_MET']) ? $data['test_MET'] : '',
                'ANF' => !empty($data['test_ANF']) ? $data['test_ANF'] : '',
                'BZD' => !empty($data['test_BZD']) ? $data['test_BZD'] : '',
                'OPI' => !empty($data['test_OPI']) ? $data['test_OPI'] : '',
                'BAR' => !empty($data['test_BAR']) ? $data['test_BAR'] : ''
            ]) : (($data['type'] == 'covid_pcr' OR $data['type'] == 'covid_an') ? json_encode([
				'result' => $data['test_result'],
				'unity' => $data['test_unity'],
				'reference_values' => $data['test_reference_values']
			]) : (($data['type'] == 'covid_ac') ? json_encode([
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
			'medicines' => (($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping') AND !empty($data['medicines'])) ? $data['medicines'] : null,
			'prescription' => ($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping') ? json_encode([
				'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
				'date' => !empty($data['prescription_date']) ? $data['prescription_date'] : ''
			]) : null,
			'collector' => $data['collector'],
			'location' => !empty($data['location']) ? $data['location'] : null,
			'date' => $data['date'],
			'hour' => $data['hour'],
			'comments' => !empty($data['comments']) ? $data['comments'] : null,
			'signatures' => json_encode([
                'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : '',
                'collector' => ''
            ]),
			'qr' => null,
			'pdf' => null,
			'lang' => null,
			'closed' => true,
			'user' => Session::get_value('vkye_user')['id'],
			'accept_terms' => null,
			'deleted' => false
        ]);

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
				'custody_chains.id' => 'DESC'
			]
		]));

		foreach ($query as $key => $value)
		{
			$query[$key]['status'] = 'success';

			if ($value['type'] == 'alcoholic')
			{
				if (($value['results']['1'] > 0 AND $value['results']['1'] < 0.20) OR ($value['results']['2'] > 0 AND $value['results']['2'] < 0.20) OR ($value['results']['3'] > 0 AND $value['results']['3'] < 0.20))
					$query[$key]['status'] = 'warning';
				else if ($value['results']['1'] >= 0.20 OR $value['results']['2'] >= 0.20 OR $value['results']['3'] >= 0.20)
					$query[$key]['status'] = 'alert';
			}
			else if ($value['type'] == 'antidoping' AND ($value['results']['COC'] == 'positive' OR $value['results']['THC'] == 'positive' OR $value['results']['ANF'] == 'positive' OR $value['results']['MET'] == 'positive' OR $value['results']['BZD'] == 'positive' OR $value['results']['OPI'] == 'positive' OR $value['results']['BAR'] == 'positive'))
				$query[$key]['status'] = 'alert';
			else if (($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an') AND $value['results']['result'] == 'positive')
				$query[$key]['status'] = 'alert';
			else if ($value['type'] == 'covid_ac' AND ($value['results']['igm']['result'] == 'positive' OR $value['results']['igg']['result'] == 'positive'))
				$query[$key]['status'] = 'alert';
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
			'[>]system_collectors' => [
				'collector' => 'id'
			]
		], [
			'custody_chains.id',
			'custody_chains.account',
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
			'custody_chains.collector',
			'system_collectors.name(collector_name)',
			'system_collectors.signature(collector_signature)',
			'system_collectors.card(collector_card)',
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

			$data['collector'] = $this->database->select('system_collectors', [
				'id',
				'name',
				'signature',
				'card'
			], [
				'id' => $data['collector']
			]);

			$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
			$writing =
			'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            <img style="width:100%;" src="https://' . Configuration::$domain . '/images/marbu_logotype_color.png">
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
			                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">Av. Nichupté SM51 M42 L1</td>
			                </tr>
			                <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			                    <td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">CP: 77533 Cancún, Qroo. México</td>
			                </tr>
			            </table>
			        </td>
			        <td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            ' . (($data['custody_chain']['account_path'] != 'moonpalace') ? '<img style="width:100%;" src="https://' . Configuration::$domain . '/uploads/' . $data['qr']['filename'] . '">' : '') . '
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
			        <td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . $data['collector'][0]['name'] . '</td>
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
			            <img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $data['collector'][0]['signature'] . '">
			        </td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . $data['collector'][0]['name'] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('health_manager')[$data['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:0px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('identification_card')[$data['lang']] . ': ' . $data['collector'][0]['card'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#000;">' . Languages::email('alert_pdf_covid')[$data['lang']] . ' <strong style="color:#f44336;">' . Languages::email('accept_terms')[$data['lang']] . '</strong>' . '</td>
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
			'collector' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? $data['collector'][0]['id'] : $data['collector'],
			'location' => !empty($data['location']) ? $data['location'] : null,
			'date' => $data['date'],
			'hour' => $data['hour'],
			'comments' => !empty($data['comments']) ? $data['comments'] : null,
			'signatures' => (($data['custody_chain']['type'] == 'alcoholic' OR $data['custody_chain']['type'] == 'antidoping') OR (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND !empty($data['custody_chain']['employee']))) ? json_encode([
				'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : $data['custody_chain']['signatures']['employee'],
				'collector' => ''
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
				if ($data['custody_chain']['account_path'] != 'moonpalace')
					Fileloader::down($data['custody_chain']['qr']);

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

	// public function sql()
	// {
	// 	set_time_limit(1000000);
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'id',
	// 		'account',
	// 		'token',
	// 		'qr',
	// 		'lang',
	// 		'date',
	// 		'hour',
	// 		'contact',
	// 		'start_process',
	// 		'end_process',
	// 		'results',
	// 		'pdf',
	// 	], [
	// 		'token' => 'H8VeXabF'
	// 	]));
	//
	// 	foreach ($query as $value)
	// 	{
	// 		// print_r($value);
	//
	// 		// $pdf_filename = 'covid_pdf_' . $value['token'] . '_' . Dates::current_date('Y_m_d') . '_' . Dates::current_hour('H_i_s') . '.pdf';
	// 		$pdf_filename = $value['pdf'];
	//
	// 		$collector = $this->database->select('system_collectors', [
	// 			'id',
	// 			'name',
	// 			'signature',
	// 			'card'
	// 		], [
	// 			'id' => 2
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
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">Av. Nichupté SM51 M42 L1</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:#004770;">CP: 77533 Cancún, Qroo. México</td>
	// 						</tr>
	// 					</table>
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;"></td>
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
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('registry_date')[$value['lang']] . ': 2021-02-23</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('company')[$value['lang']] . ': N/A</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('patient')[$value['lang']] . ': ' . $value['contact']['firstname'] . ' ' . $value['contact']['lastname'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('birth_date')[$value['lang']] . ': ' . $value['contact']['birth_date'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('age')[$value['lang']] . ': ' . $value['contact']['age'] . ' ' . Languages::email('years')[$value['lang']] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('sex')[$value['lang']] . ': ' . Languages::email($value['contact']['sex'])[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_date')[$value['lang']] . ': 2021-02-23</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_hour')[$value['lang']] . ': ' . $value['hour'] . '</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . $collector[0]['name'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('start_process')[$value['lang']] . ': 2021-02-23</td>
	// 				<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('end_process')[$value['lang']] . ': 2021-02-24</td>
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
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">PCR-SARS-CoV-2 (COVID-19)</td>
	// 				<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('negative')[$value['lang']] . '</td>
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
	// 					<img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $collector[0]['signature'] . '">
	// 				</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . $collector[0]['name'] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('health_manager')[$value['lang']] . '</td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('identification_card')[$value['lang']] . ': ' . $collector[0]['card'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#000;">' . Languages::email('alert_pdf_covid')[$value['lang']] . ' <strong style="color:#f44336;">' . Languages::email('accept_terms')[$value['lang']] . '</strong></td>
	// 			</tr>
	// 		</table>';
	// 		$html2pdf->writeHTML($writing);
	// 		$html2pdf->output(PATH_UPLOADS . $pdf_filename, 'F');
	//
	// 		$query = $this->database->update('custody_chains', [
	// 			'start_process' => '2021-02-23',
	// 			'end_process' => '2021-02-24',
	// 			'results' => json_encode([
	// 				'result' => 'negative',
	// 				'unity' => 'INDEX',
	// 				'reference_values' => 'not_detected'
	// 			]),
	// 			'collector' => 2,
	// 			'date' => '2021-02-23',
	// 			'pdf' => $pdf_filename,
	// 			'closed' => false,
	// 			'user' => 1
	// 		], [
	// 			'id' => $value['id']
	// 		]);
	// 	}
	// }
}
