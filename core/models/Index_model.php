<?php

defined('_EXEC') or die;

require 'vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

class Index_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	// public function sql()
    // {
	// 	set_time_limit(100000000);
	//
	// 	$query = System::decode_json_to_array($this->database->select('custody_chains', [
	// 		'[>]system_laboratories' => [
	// 			'laboratory' => 'id'
	// 		],
	// 		'[>]system_chemicals' => [
	// 			'chemical' => 'id'
	// 		]
	// 	], [
	// 		'custody_chains.id',
	// 		'custody_chains.token',
	// 		'custody_chains.contact',
	// 		'custody_chains.type',
	// 		'custody_chains.start_process',
	// 		'custody_chains.end_process',
	// 		'custody_chains.results',
	// 		'custody_chains.laboratory',
	// 		'system_laboratories.avatar(laboratory_avatar)',
	// 		'system_laboratories.name(laboratory_name)',
	// 		'system_laboratories.path(laboratory_path)',
	// 		'system_laboratories.business(laboratory_business)',
	// 		'system_laboratories.rfc(laboratory_rfc)',
	// 		'system_laboratories.sanitary_opinion(laboratory_sanitary_opinion)',
	// 		'system_laboratories.address(laboratory_address)',
	// 		'system_laboratories.email(laboratory_email)',
	// 		'system_laboratories.phone(laboratory_phone)',
	// 		'system_laboratories.website(laboratory_website)',
	// 		'system_laboratories.colors(laboratory_colors)',
	// 		'custody_chains.chemical',
	// 		'system_chemicals.name(chemical_name)',
	// 		'system_chemicals.signature(chemical_signature)',
	// 		'custody_chains.date',
	// 		'custody_chains.hour',
	// 		'custody_chains.signature',
	// 		'custody_chains.qr',
	// 		'custody_chains.pdf',
	// 		'custody_chains.lang'
	// 	], [
	// 		'AND' => [
	// 			'custody_chains.laboratory' => 2,
	// 			'custody_chains.date[<>]' => ['2021-05-01','2021-05-23'],
	// 			'custody_chains.sent' => true,
	// 			'custody_chains.closed' => true,
	// 			'custody_chains.deleted' => false
	// 		]
	// 	]));
	//
	// 	// 'AND' => [
	// 	// 	'custody_chains.laboratory' => 2,
	// 	// 	'custody_chains.date[<>]' => ['2021-05-01','2021-05-23'],
	// 	// 	'custody_chains.sent' => true,
	// 	// 	'custody_chains.closed' => true,
	// 	// 	'custody_chains.deleted' => false
	// 	// ]
	// 	// 'token' => 'TCN-ibyIAbu9'
	// 	// print_r(count($query));
	// 	// print_r($query);
	//
	// 	foreach ($query as $value)
	// 	{
	// 		$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [0,0,0,0]);
	//
	// 		$writing =
	// 		'<table style="width:100%;margin:0px;padding:20px 40px;border:0px;border-top:20px;border-color:' . $value['laboratory_colors']['second'] . ';box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 					<img style="width:100%;" src="https://id.one-consultores.com/uploads/' . $value['laboratory_avatar'] . '">
	// 				</td>
	// 				<td style="width:80%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 					<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:24px;font-weight:600;text-transform:uppercase;text-align:right;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('result_report')[$value['lang']] . '</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:18px;font-weight:400;text-transform:uppercase;text-align:right;color:' . $value['laboratory_colors']['second'] . ';">' .  Languages::email('laboratory_analisys')[$value['lang']] . ' <span style="font-weight:600;">' . $value['laboratory_name'] . '</span></td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $value['laboratory_colors']['second'] . ';">' . $value['laboratory_address']['first'] . '</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:right;color:' . $value['laboratory_colors']['second'] . ';">' . $value['laboratory_address']['second'] . '</td>
	// 						</tr>
	// 					</table>
	// 				</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 5px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 0px 10px;border:0px;border-left:5px;border-color:' . $value['laboratory_colors']['second'] . ';box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('general_patient_data')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('n_petition')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['token'] . '</span></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('name')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['firstname'] . ' ' . $value['contact']['lastname'] . '</span></td>
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('birth_date')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['birth_date'] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('sex')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['contact']['sex'])[$value['lang']] . '</span></td>
	// 				<td style="width:50%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('company')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">N/A</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:50%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('age')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['age'] . ' ' . Languages::email('years')[$value['lang']] . '</span></td>
	// 				<td style="width:50%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('id')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['ife'] . '</span></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 5px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 0px 10px;border:0px;border-left:5px;border-color:' . $value['laboratory_colors']['second'] . ';box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('results')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('get_date')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['date'] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('method')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">';
	//
	// 		if ($value['type'] == 'covid_pcr')
	// 			$writing .= Languages::email('pcr_atila_biosystem')[$value['lang']];
	// 		else if ($value['type'] == 'covid_an')
	// 			$writing .= Languages::email('an_atila_biosystem')[$value['lang']];
	// 		else if ($value['type'] == 'covid_ac')
	// 			$writing .= Languages::email('ac_atila_biosystem')[$value['lang']];
	//
	// 		$writing .=
	// 		'			</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('test')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an') ? Languages::email('nasopharynx_secretion')[$value['lang']] : Languages::email('sanguine')[$value['lang']]) . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('get_hour')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['hour'] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('start_process')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['start_process'] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('end_process')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['end_process'] . '</span></td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:20px 40px;border:0px;box-sizing:border-box;background-color:#e1f5fe;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:center;color:' . $value['laboratory_colors']['second'] . ';">';
	//
	// 		if ($value['type'] == 'covid_pcr')
	// 			$writing .= 'PCR-SARS-CoV-2 (COVID-19)';
	// 		else if ($value['type'] == 'covid_an')
	// 			$writing .= 'Ag-SARS-CoV-2 (COVID-19)';
	// 		else if ($value['type'] == 'covid_ac')
	// 			$writing .= 'SARS-CoV-2 (2019) IgG/IgM';
	//
	// 		$writing .=
	// 		'        </td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('immunological_analysis')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#e1f5fe;">';
	//
	// 		if ($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an')
	// 		{
	// 			$writing .=
	// 			'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('result')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['result'])[$value['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('unity')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['unity'])[$value['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('reference_values')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['reference_values'])[$value['lang']] . '</span></td>
	// 			</tr>';
	// 		}
	// 		else if ($value['type'] == 'covid_ac')
	// 		{
	// 			$writing .=
	// 			'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">IgM ' . Languages::email('result')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['igm']['result'])[$value['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">IgM ' . Languages::email('unity')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['igm']['unity'])[$value['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">IgM ' . Languages::email('reference_values')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['igm']['reference_values'])[$value['lang']] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">IgG ' . Languages::email('result')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['igg']['result'])[$value['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">IgG ' . Languages::email('unity')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['igg']['unity'])[$value['lang']] . '</span></td>
	// 				<td style="width:33.33%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['first'] . ';">IgG ' . Languages::email('reference_values')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['results']['igg']['reference_values'])[$value['lang']] . '</span></td>
	// 			</tr>';
	// 		}
	//
	// 		$writing .=
	// 		'</table>
	// 		<table style="width:100%;margin:0px;padding:20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:justify;color:' . $value['laboratory_colors']['first'] . ';">';
	//
	// 		if ($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an')
	// 			$writing .= Languages::email('notes_pcr_an_1')[$value['lang']] . ' ' . Languages::email('notes_pcr_an_2')[$value['lang']] . ' ' . Languages::email('notes_pcr_an_3')[$value['lang']];
	// 		else if ($value['type'] == 'covid_ac')
	// 			$writing .= Languages::email('notes_ac_1')[$value['lang']] . ' ' . Languages::email('notes_ac_2')[$value['lang']] . ' ' . Languages::email('notes_ac_3')[$value['lang']] . ' ' . Languages::email('notes_ac_4')[$value['lang']] . ' ' . Languages::email('notes_ac_5')[$value['lang']];
	//
	// 		$writing .=
	// 		'		</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:60%;margin:0px;padding:0px;border:0px;"></td>
	// 				<td style="width:40%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 					<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;text-align:center;">
	// 								<img style="width:100px" src="https://id.one-consultores.com/uploads/' . $value['chemical_signature'] . '">
	// 							</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:#212121;">' . Languages::email('valid_results_by')[$value['lang']] . '</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:#212121;">' . $value['chemical_name'] . '</td>
	// 						</tr>
	// 					</table>
	// 				</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:80%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 					<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:12px;font-weight:400;text-align:justify;color:#212121;">' . Languages::email('alert_pdf_covid')[$value['lang']] . ' ' . Languages::email('accept_terms_1')[$value['lang']] . ' ' . $value['laboratory_name'] . ' ' . Languages::email('accept_terms_2')[$value['lang']] . ' ' . Languages::email('our_proccess_available_1')[$value['lang']] . ' ' . $value['laboratory_sanitary_opinion'] . ' ' . Languages::email('our_proccess_available_2')[$value['lang']] . ' ' . $value['laboratory_rfc'] . '</td>
	// 						</tr>
	// 						<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:#212121;">' . Languages::email('expedition_date')[$value['lang']] . ' ' . $value['end_process'] . '</td>
	// 						</tr>
	// 					</table>
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;font-size:8px;font-weight:400;text-align:center:color:#212121">
	// 					<img style="width:100%;" src="https://id.one-consultores.com/uploads/' . $value['qr'] . '">
	// 					' . Languages::email('scan_to_security')[$value['lang']] . '
	// 				</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px;border:0px;font-size:14px;font-weight:400;text-align:center;color:' . $value['laboratory_colors']['second'] . ';">' . $value['laboratory_phone'] . ' | ' . $value['laboratory_email'] . ' | ' . $value['laboratory_website'] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 					<img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/secretaria_salud.png">
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 					<img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/cofepris.png">
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 					<img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_1.png">
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 					<img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_2.png">
	// 				</td>
	// 				<td style="width:20%;margin:0px;padding:0px;border:0px;vertical-align:middle;text-align:center;">
	// 					<img style="width:auto;height:40px;" src="' . PATH_IMAGES . '/qroo_sesa.png">
	// 				</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:100px 0px 0px 0px;padding:40px 40px 5px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 0px 10px;border:0px;border-left:5px;border-color:' . $value['laboratory_colors']['second'] . ';box-sizing:border-box;font-size:18px;font-weight:600;text-transform:uppercase;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('security_form')[$value['lang']] . '</td>
	// 			</tr>
	// 		</table>
	// 		<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('nationality')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['nationality'] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('travel_to')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['travel_to'] . '</span></td>
	// 			</tr>';
	//
	// 		if ($value['contact']['sex'] == 'female')
	// 		{
	// 			$writing .=
	// 			'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('are_you_pregnant')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['contact']['pregnant'])[$value['lang']] . '</span></td>
	// 			</tr>';
	// 		}
	//
	// 		$writing .=
	// 		'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('are_you_symptoms')[$value['lang']] . '</td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('fever')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('fever', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('eyes_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('eyes_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('torax_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('torax_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('muscles_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('muscles_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('head_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('head_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('throat_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('throat_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('knees_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('knees_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('ears_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('ears_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('joints_pain')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('joints_pain', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('cough')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('cough', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('difficulty_breathing')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('difficulty_breathing', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('sweating')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('sweating', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('runny_nose')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('runny_nose', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('itching')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('itching', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('conjunctivitis')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('conjunctivitis', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('vomit')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('vomit', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('diarrhea')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('diarrhea', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('smell_loss')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('smell_loss', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>
	// 		<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 			<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('taste_loss')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (in_array('taste_loss', $value['contact']['symptoms']) ? Languages::email('yeah')[$value['lang']] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 		</tr>';
	//
	// 		if ($value['contact']['symptoms'][0] != 'nothing')
	// 		{
	// 			$writing .=
	// 			'<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('write_symptoms_time')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['symptoms_time'] . '</span></td>
	// 			</tr>';
	// 		}
	//
	// 		$writing .=
	// 		'	<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('are_travel_prev')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (($value['contact']['previous_travel'] == 'yeah') ? $value['contact']['previous_travel_countries'] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('are_contact_covid')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . Languages::email($value['contact']['covid_contact'])[$value['lang']] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('are_you_covid')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . (($value['contact']['covid_infection'] == 'yeah') ? $value['contact']['covid_infection_time'] : Languages::email('not')[$value['lang']]) . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('email')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">' . $value['contact']['email'] . '</span></td>
	// 			</tr>
	// 			<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 				<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:left;color:' . $value['laboratory_colors']['first'] . ';">' . Languages::email('phone')[$value['lang']] . ' <span style="color:' . $value['laboratory_colors']['second'] . ';">+' . $value['contact']['phone']['country'] . ' ' . $value['contact']['phone']['number'] . '</span></td>
	// 			</tr>
	// 		</table>';
	//
	// 		$fp = curl_init("https://id.one-consultores.com/uploads/" . $value['signature']);
	// 		$ret = curl_setopt($fp, CURLOPT_RETURNTRANSFER, 1);
	// 		$ret = curl_setopt($fp, CURLOPT_TIMEOUT, 30);
	// 		$ret = curl_exec($fp);
	// 		$info = curl_getinfo($fp, CURLINFO_HTTP_CODE);
	//
	// 		curl_close($fp);
	//
	// 		if ($info == 404)
	// 		{
	//
	// 		}
	// 		else
	// 		{
	// 			$writing .=
	// 			'<table style="width:100%;margin:0px;padding:0px 40px 20px 40px;border:0px;box-sizing:border-box;background-color:#fff;">
	// 				<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 					<td style="width:60%;margin:0px;padding:0px;border:0px;"></td>
	// 					<td style="width:40%;margin:0px;padding:0px;border:0px;vertical-align:middle;">
	// 						<table style="width:100%;margin:0px;padding:0px;border:0px;">
	// 							<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 								<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;text-align:center;">
	// 									<img style="width:100px" src="https://id.one-consultores.com/uploads/' . $value['signature'] . '">
	// 								</td>
	// 							</tr>
	// 							<tr style="width:100%;margin:0px;padding:0px;border:0px;">
	// 								<td style="width:100%;margin:0px;padding:0px 0px 5px 0px;border:0px;box-sizing:border-box;font-size:14px;font-weight:400;text-align:center;color:#212121;">' . Languages::email('responsability_signature')[$value['lang']] . '</td>
	// 							</tr>
	// 						</table>
	// 					</td>
	// 				</tr>
	// 			</table>';
	// 		}
	//
	// 		$html2pdf->writeHTML($writing);
	// 		$html2pdf->output(PATH_UPLOADS . $value['pdf'], 'F');
	// 	}
    // }
}
