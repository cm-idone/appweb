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
			'token' => System::generate_random_string(),
            'employee' => $data['employee'],
			'contact' => null,
            'type' => $data['type'],
            'reason' => $data['reason'],
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
					'unity' => '',
					'reference_values' => $data['test_igm_reference_values']
				],
				'igg' => [
					'result' => $data['test_igg_result'],
					'unity' => '',
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
			'hour' => $data['hour'],
			'date' => $data['date'],
			'comments' => !empty($data['comments']) ? $data['comments'] : null,
			'signatures' => json_encode([
                'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : '',
                'collector' => ''
            ]),
			'qr' => null,
			'closed' => true,
			'user' => Session::get_value('vkye_user')['id']
        ]);

        return $query;
    }

	public function read_custody_chanins($type)
	{
		if ($type == 'covid')
			$type = ['covid_pcr','covid_an','covid_ac'];

		$query = System::decode_json_to_array($this->database->select('custody_chanins', [
			'[>]employees' => [
				'employee' => 'id'
			],
			'[>]users' => [
				'user' => 'id'
			]
		], [
			'custody_chanins.id',
			'custody_chanins.token',
			'custody_chanins.employee',
			'employees.firstname(employee_firstname)',
			'employees.lastname(employee_lastname)',
			'custody_chanins.contact',
			'custody_chanins.type',
			'custody_chanins.hour',
			'custody_chanins.date',
			'custody_chanins.user',
			'users.firstname(user_firstname)',
			'users.lastname(user_lastname)'
		], [
			'AND' => [
				'custody_chanins.account' => Session::get_value('vkye_account')['id'],
				'custody_chanins.type' => $type
			],
			'ORDER' => [
				'id' => 'DESC'
			]
		]));

		return $query;
	}

	public function read_custody_chanin($token)
	{
		$query = System::decode_json_to_array($this->database->select('custody_chanins', [
			'[>]employees' => [
				'employee' => 'id'
			]
		], [
			'custody_chanins.id',
			'custody_chanins.account',
			'custody_chanins.token',
			'custody_chanins.employee',
			'employees.firstname(employee_firstname)',
			'employees.lastname(employee_lastname)',
			'employees.ife(employee_ife)',
			'employees.birth_date(employee_birth_date)',
			'employees.sex(employee_sex)',
			'custody_chanins.contact',
			'custody_chanins.type',
			'custody_chanins.reason',
			'custody_chanins.results',
			'custody_chanins.medicines',
			'custody_chanins.prescription',
			'custody_chanins.collector',
			'custody_chanins.location',
			'custody_chanins.hour',
			'custody_chanins.date',
			'custody_chanins.comments',
			'custody_chanins.signatures',
			'custody_chanins.qr',
			'custody_chanins.closed',
			'custody_chanins.user'
		], [
			'custody_chanins.token' => $token
		]));

		return !empty($query) ? $query[0] : null;
	}

	public function update_custody_chain($data)
    {
        $query = $this->database->update('custody_chanins', [
			'contact' => (($data['custody_chanin']['type'] == 'covid_pcr' OR $data['custody_chanin']['type'] == 'covid_an' OR $data['custody_chanin']['type'] == 'covid_ac') AND empty($data['custody_chanin']['employee'])) ? json_encode([
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
			'results' => ($data['custody_chanin']['type'] == 'alcoholic') ? json_encode([
                '1' => !empty($data['test_1']) ? $data['test_1'] : '',
                '2' => !empty($data['test_2']) ? $data['test_2'] : '',
                '3' => !empty($data['test_3']) ? $data['test_3'] : ''
            ]) : (($data['custody_chanin']['type'] == 'antidoping') ? json_encode([
                'COC' => !empty($data['test_COC']) ? $data['test_COC'] : '',
                'THC' => !empty($data['test_THC']) ? $data['test_THC'] : '',
                'MET' => !empty($data['test_MET']) ? $data['test_MET'] : '',
                'ANF' => !empty($data['test_ANF']) ? $data['test_ANF'] : '',
                'BZD' => !empty($data['test_BZD']) ? $data['test_BZD'] : '',
                'OPI' => !empty($data['test_OPI']) ? $data['test_OPI'] : '',
                'BAR' => !empty($data['test_BAR']) ? $data['test_BAR'] : ''
            ]) : (($data['custody_chanin']['type'] == 'covid_pcr' OR $data['custody_chanin']['type'] == 'covid_an') ? json_encode([
				'result' => $data['test_result'],
				'unity' => $data['test_unity'],
				'reference_values' => $data['test_reference_values']
			]) : (($data['custody_chanin']['type'] == 'covid_ac') ? json_encode([
				'igm' => [
					'result' => $data['test_igm_result'],
					'unity' => '',
					'reference_values' => $data['test_igm_reference_values']
				],
				'igg' => [
					'result' => $data['test_igg_result'],
					'unity' => '',
					'reference_values' => $data['test_igg_reference_values']
				]
			]) : null))),
            'medicines' => (($data['custody_chanin']['type'] == 'alcoholic' OR $data['custody_chanin']['type'] == 'antidoping') AND !empty($data['medicines'])) ? $data['medicines'] : null,
            'prescription' => ($data['custody_chanin']['type'] == 'alcoholic' OR $data['custody_chanin']['type'] == 'antidoping') ? json_encode([
                'issued_by' => !empty($data['prescription_issued_by']) ? $data['prescription_issued_by'] : '',
                'date' => !empty($data['prescription_date']) ? $data['prescription_date'] : ''
            ]) : null,
			'collector' => $data['collector'],
			'location' => !empty($data['location']) ? $data['location'] : null,
			'hour' => $data['hour'],
			'date' => $data['date'],
			'comments' => !empty($data['comments']) ? $data['comments'] : null,
            'signatures' => (($data['custody_chanin']['type'] == 'alcoholic' OR $data['custody_chanin']['type'] == 'antidoping') OR (($data['custody_chanin']['type'] == 'covid_pcr' OR $data['custody_chanin']['type'] == 'covid_an' OR $data['custody_chanin']['type'] == 'covid_ac') AND !empty($data['custody_chanin']['employee']))) ? json_encode([
                'employee' => !empty($data['employee_signature']) ? Fileloader::base64($data['employee_signature']) : $data['custody_chanin']['signatures']['employee'],
                'collector' => ''
            ]) : null,
			'closed' => true,
			'user' => (($data['custody_chanin']['type'] == 'covid_pcr' OR $data['custody_chanin']['type'] == 'covid_an' OR $data['custody_chanin']['type'] == 'covid_ac') AND empty($data['custody_chanin']['employee'])) ? Session::get_value('vkye_user')['id'] : $data['custody_chanin']['user']
        ], [
			'id' => $data['custody_chanin']['id']
		]);

		if (!empty($query) AND !empty($data['custody_chanin']['employee']) AND !empty($data['employee_signature']) AND !empty($data['custody_chanin']['signatures']['employee']))
			Fileloader::down($data['custody_chanin']['signatures']['employee']);

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
