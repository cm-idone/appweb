<?php

defined('_EXEC') or die;

require 'plugins/php_qr_code/qrlib.php';

class Covid_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function create_custody_chain($data)
    {
		$data['qr']['content'] = 'https://' . Configuration::$domain . '/laboratory/update/' . $data['token'];
		$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
		$data['qr']['level'] = 'H';
		$data['qr']['size'] = 5;
		$data['qr']['frame'] = 3;

        $query = $this->database->insert('custody_chains', [
            'account' => $data['account'],
			'token' => $data['token'],
            'employee' => null,
			'contact' => json_encode([
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
            ]),
            'type' => $_POST['type'],
            'reason' => 'random',
			'start_process' => Dates::current_date(),
			'end_process' => Dates::current_date(),
            'results' => ($_POST['type'] == 'covid_pcr' OR $_POST['type'] == 'covid_an') ? json_encode([
				'result' => '',
				'unity' => '',
				'reference_values' => ''
			]) : (($_POST['type'] == 'covid_ac') ? json_encode([
				'igm' => [
					'result' => '',
					'unity' => '',
					'reference_values' => ''
				],
				'igg' => [
					'result' => '',
					'unity' => '',
					'reference_values' => ''
				]
			]) : null),
            'medicines' => null,
            'prescription' => null,
			'collector' => null,
			'location' => null,
			'date' => Dates::current_date(),
			'hour' => Dates::current_hour(),
			'comments' => null,
            'signatures' => null,
			'qr' => $data['qr']['filename'],
			'pdf' => null,
			'lang' => Session::get_value('vkye_lang'),
			'closed' => false,
			'user' => null,
			'accept_terms' => true
        ]);

		if (!empty($query))
			QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);

        return $query;
    }

	public function read_custody_chain($token)
	{
		$query = System::decode_json_to_array($this->database->select('custody_chains', [
			'[>]system_collectors' => [
				'collector' => 'id'
			]
		], [
			'custody_chains.token',
			'custody_chains.contact',
			'custody_chains.type',
			'custody_chains.start_process',
			'custody_chains.end_process',
			'custody_chains.results',
			'system_collectors.name(collector_name)',
			'system_collectors.signature(collector_signature)',
			'custody_chains.date',
			'custody_chains.hour',
			'custody_chains.comments',
			'custody_chains.qr',
			'custody_chains.pdf',
			'custody_chains.lang',
			'custody_chains.closed'
		], [
			'custody_chains.token' => $token
		]));

		return !empty($query) ? $query[0] : null;
	}

    public function read_account($path)
    {
        $query = System::decode_json_to_array($this->database->select('accounts', [
            'id',
            'avatar',
            'time_zone'
        ], [
            'path' => $path
        ]));

        return !empty($query) ? $query[0] : null;
    }
}
