<?php

defined('_EXEC') or die;

class Laboratory_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function create_custody_chain($data)
    {
        $query = $this->database->insert('custody_chanins', [
            'account' => Session::get_value('vkye_account')['id'],
            'employee' => $data['employee'],
            'user' => Session::get_value('vkye_user')['id'],
            'type' => $data['type'],
            'reason' => $data['reason'],
            'tests' => ($data['type'] == 'alcoholic') ? json_encode([
                '1' => !empty($data['test_1']) ? $data['test_1'] : '0.00',
                '2' => !empty($data['test_2']) ? $data['test_2'] : '',
                '3' => !empty($data['test_3']) ? $data['test_3'] : ''
            ]) : null,
            'analysis' => ($data['type'] == 'antidoping') ? json_encode([
                'COC' => !empty($data['analysis_COC']) ? $data['analysis_COC'] : '',
                'THC' => !empty($data['analysis_THC']) ? $data['analysis_THC'] : '',
                'MET' => !empty($data['analysis_MET']) ? $data['analysis_MET'] : '',
                'ANF' => !empty($data['analysis_ANF']) ? $data['analysis_ANF'] : '',
                'BZD' => !empty($data['analysis_BZD']) ? $data['analysis_BZD'] : '',
                'OPI' => !empty($data['analysis_OPI']) ? $data['analysis_OPI'] : '',
                'BAR' => !empty($data['analysis_BAR']) ? $data['analysis_BAR'] : ''
            ]) : null,
            'result' => !empty($_POST['result']) ? $_POST['result'] : null,
            'medicines' => !empty($_POST['medicines']) ? $_POST['medicines'] : null,
            'prescription' => json_encode([
                'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
                'date' => !empty($data['prescription_date']) ? $data['date'] : ''
            ]),
            'collection' => json_encode([
                'place' => !empty($data['collection_place']) ? $data['collection_place'] : '',
                'hour' => $data['collection_hour']
            ]),
            'signatures' => json_encode([
                'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : '',
                'collector' => !empty($data['collector_signature']) ? Fileloader::base64($data['collector_signature']) : ''
            ]),
            'date' => $_POST['date']
        ]);

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
