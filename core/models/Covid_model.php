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
			'hour' => null,
			'comments' => null,
            'signatures' => null,
			'qr' => $data['qr']['filename'],
			'pdf' => null,
			'lang' => Session::get_value('vkye_lang'),
			'closed' => false,
			'user' => null
        ]);

		if (!empty($query))
			QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);

        return $query;
    }

    public function read_account($path)
    {
        $query = System::decode_json_to_array($this->database->select('accounts', [
            'id',
            'avatar'
        ], [
            'path' => $path
        ]));

        return !empty($query) ? $query[0] : null;
    }
}