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
			'user' => Session::get_value('vkye_user')['id']
        ]);

        return $query;
    }

	public function read_custody_chains($type)
	{
		$query = System::decode_json_to_array($this->database->select('custody_chains', [
			'[>]employees' => [
				'employee' => 'id'
			],
			'[>]users' => [
				'user' => 'id'
			]
		], [
			'custody_chains.id',
			'custody_chains.token',
			'custody_chains.employee',
			'employees.firstname(employee_firstname)',
			'employees.lastname(employee_lastname)',
			'custody_chains.contact',
			'custody_chains.type',
			'custody_chains.date',
			'custody_chains.hour',
			'custody_chains.user',
			'users.firstname(user_firstname)',
			'users.lastname(user_lastname)'
		], [
			'AND' => [
				'custody_chains.account' => Session::get_value('vkye_account')['id'],
				'custody_chains.type' => ($type == 'covid') ? ['covid_pcr','covid_an','covid_ac'] : $type
			],
			'ORDER' => [
				'id' => 'DESC'
			]
		]));

		return $query;
	}

	public function read_custody_chain($token)
	{
		$query = System::decode_json_to_array($this->database->select('custody_chains', [
			'[>]employees' => [
				'employee' => 'id'
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
			'custody_chains.user'
		], [
			'custody_chains.token' => $token
		]));

		return !empty($query) ? $query[0] : null;
	}

	public function update_custody_chain($data)
    {
		if (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']))
		{
			$data['qr']['content'] = 'https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/covid/' . $data['custody_chain']['token'];
			$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
			$data['qr']['level'] = 'H';
			$data['qr']['size'] = 5;
			$data['qr']['frame'] = 3;

			QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);

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
			        <td style="width:100px;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            <img style="width:100px" src="https://' . Configuration::$domain . '/images/marbu_logotype_color.png">
			        </td>
			        <td style="width:auto;margin:0px;padding:0px;border:0px;box-sizing:border-box;vertical-align:middle;">
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
			        <td style="width:100px;margin:0px;padding:10px;border:0px;box-sizing:border-box;vertical-align:middle;">
			            <img style="width:100px" src="https://' . Configuration::$domain . '/uploads/' . $data['qr']['filename'] . '">
			        </td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:28px;font-weight:600;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('result_report')[$data['custody_chain']['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;text-transform:uppercase;color:#004770;">' . Languages::email('marbu_laboratory_analisys')[$data['custody_chain']['lang']] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('n_petition')[$data['custody_chain']['lang']] . ': ' . $data['custody_chain']['token'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('registry_date')[$data['custody_chain']['lang']] . ': ' . $data['date'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('company')[$data['custody_chain']['lang']] . ': N/A</td>
					<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('patient')[$data['custody_chain']['lang']] . ': ' . $data['firstname'] . ' ' . $data['lastname'] . '</td>
				</tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
	        		<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('birth_date')[$data['custody_chain']['lang']] . ': ' . $data['birth_date'] . '</td>
	        		<td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('age')[$data['custody_chain']['lang']] . ': ' . $data['age'] . ' ' . Languages::email('years')[$data['custody_chain']['lang']] . '</td>
	        		<td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('sex')[$data['custody_chain']['lang']] . ': ' . Languages::email($data['sex'])[$data['custody_chain']['lang']] . '</td>
				</tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_date')[$data['custody_chain']['lang']] . ': ' . $data['date'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('get_hour')[$data['custody_chain']['lang']] . ': ' . $data['hour'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . $data['collector'][0]['name'] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('start_process')[$data['custody_chain']['lang']] . ': ' . $data['start_process'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('end_process')[$data['custody_chain']['lang']] . ': ' . $data['end_process'] . '</td>
			        <td style="width:33.33%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('id_patient')[$data['custody_chain']['lang']] . ': ' . $data['ife'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;"></td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:14px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('immunological_analysis')[$data['custody_chain']['lang']] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#deeaf6;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('exam')[$data['custody_chain']['lang']] . '</td>
			        <td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('result')[$data['custody_chain']['lang']] . '</td>
			        <td style="width:25%;margin:0px;padding:10px 0px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('unity')[$data['custody_chain']['lang']] . '</td>
			        <td style="width:25%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('reference_values')[$data['custody_chain']['lang']] . '</td>
			    </tr>';

			if ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an')
			{
				$writing .= '<tr style="width:100%;margin:0px;padding:0px;border:0px;">';

				if ($data['custody_chain']['type'] == 'covid_pcr')
					$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">PCR-SARS-CoV-2 (COVID-19)</td>';
				else if ($data['custody_chain']['type'] == 'covid_an')
					$writing .= '<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">Ag-SARS-CoV-2 (COVID-19)</td>';

				$writing .=
				'	<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_result'])[$data['custody_chain']['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_unity'])[$data['custody_chain']['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_reference_values'])[$data['custody_chain']['lang']] . '</td>
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
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('anticorps')[$data['custody_chain']['lang']] . ' IgM</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igm_result'])[$data['custody_chain']['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igm_unity'])[$data['custody_chain']['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igm_reference_values'])[$data['custody_chain']['lang']] . '</td>
				</tr>
				<tr style="width:100%;margin:0px;padding:0px;border:0px;">
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email('anticorps')[$data['custody_chain']['lang']] . ' IgG</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igg_result'])[$data['custody_chain']['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igg_unity'])[$data['custody_chain']['lang']] . '</td>
					<td style="width:25%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:left;color:#004770;">' . Languages::email($data['test_igg_reference_values'])[$data['custody_chain']['lang']] . '</td>
				</tr>';
			}

			$writing .= '</table>';

			if ($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an')
			{
				$writing .=
				'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;border-top:2px solid #5b9bd5;border-bottom:2px solid #5b9bd5;">
				        <td style="width:100%;margin:0px;padding:10px 0px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:left;color:#004770;">' . Languages::email('show')[$data['custody_chain']['lang']] . ': ' . Languages::email('nasopharynx_secretion')[$data['custody_chain']['lang']] . '</td>
				    </tr>
				</table>
				<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:justify;color:#004770;">' . Languages::email('notes')[$data['custody_chain']['lang']] . ':</td>
				    </tr>
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_1')[$data['custody_chain']['lang']] . '</td>
				    </tr>
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_2')[$data['custody_chain']['lang']] . '</td>
				    </tr>
				    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
				        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_pcr_an_3')[$data['custody_chain']['lang']] . '</td>
				    </tr>
				</table>';
			}
			else if ($data['custody_chain']['type'] == 'covid_ac')
			{
				$writing .=
				'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:justify;color:#004770;">' . Languages::email('notes_ac_1')[$data['custody_chain']['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_2')[$data['custody_chain']['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_3')[$data['custody_chain']['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_4')[$data['custody_chain']['lang']] . '</td>
					</tr>
					<tr style="width:100%;margin:0px;padding:0px;border:0px;">
						<td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#004770;">' . Languages::email('notes_ac_5')[$data['custody_chain']['lang']] . '</td>
					</tr>
				</table>';
			}

			$writing .=
			'<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('valid_results_by')[$data['custody_chain']['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;text-align:center;">
			            <img style="width:200px" src="https://' . Configuration::$domain . '/uploads/' . $data['collector'][0]['signature'] . '">
			        </td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px 10px 0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . $data['collector'][0]['name'] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:0px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('health_manager')[$data['custody_chain']['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:0px 10px 10px 10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">' . Languages::email('identification_card')[$data['custody_chain']['lang']] . ': ' . $data['collector'][0]['card'] . '</td>
			    </tr>
			</table>
			<table style="width:100%;margin:0px;padding:0px;border:0px;background-color:#fff;">
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#000;">' . Languages::email('alert_pdf_covid')[$data['custody_chain']['lang']] . '</td>
			    </tr>
			    <tr style="width:100%;margin:0px;padding:0px;border:0px;">
			        <td style="width:100%;margin:0px;padding:10px;border:0px;box-sizing:border-box;font-size:12px;font-weight:600;text-align:center;color:#004770;">+ (52) 998 313 2948 - 998 440 3302 | marbu@one-consultores.com | marbu.one-consultores.com</td>
			    </tr>
			</table>';
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
			'qr' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? $data['qr']['filename'] : $data['custody_chain']['qr'],
			'pdf' => (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee'])) ? $data['pdf']['filename'] : $data['custody_chain']['pdf'],
			'closed' => true,
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
				Fileloader::down($data['custody_chain']['qr']);
				Fileloader::down($data['custody_chain']['pdf']);
			}
		}
		else
		{
			if (($data['custody_chain']['type'] == 'covid_pcr' OR $data['custody_chain']['type'] == 'covid_an' OR $data['custody_chain']['type'] == 'covid_ac') AND empty($data['custody_chain']['employee']))
				Fileloader::down($data['qr']['filename']);
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
}
