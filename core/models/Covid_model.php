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

        $query = $this->database->insert('custody_chanins', [
            'account' => $data['account'],
			'token' => $data['token'],
            'employee' => null,
            'type' => $_POST['type'],
            'reason' => null,
            'results' => null,
            'comments' => null,
            'medicines' => null,
            'prescription' => json_encode([
                'issued_by' => '',
                'date' => ''
            ]),
			'collector' => null,
            'collection' => json_encode([
                'place' => '',
                'hour' => ''
            ]),
            'signatures' => json_encode([
                'employee' => '',
                'collector' => ''
            ]),
            'contact' => json_encode([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'birth_date' => $data['birth_date'],
                'age' => $data['age'],
                'id' => $data['id'],
                'email' => $data['email'],
                'phone' => [
                    'country' => $data['phone_country'],
                    'number' => $data['phone_number']
                ],
                'travel_to' => $data['travel_to']
            ]),
			'qr' => $data['qr']['filename'],
			'date' => Dates::current_date(),
			'closed' => false
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
