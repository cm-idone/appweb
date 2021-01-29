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
		$data['qr']['filename'] = 'tmp_' . $data['account']['path'] . '_covid_qr_' . $data['token'] . '.png';
		$data['qr']['content'] = 'https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/' . $data['nie'];
		$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
		$data['qr']['level'] = 'H';
		$data['qr']['size'] = 5;
		$data['qr']['frame'] = 3;

        $query = $this->database->insert('custody_chanins', [
            'account' => $data['account']['id'],
            'employee' => null,
            'user' => null,
            'type' => $_POST['test'],
            'reason' => null,
            'tests' => null,
            'analysis' => null,
            'result' => null,
            'medicines' => null,
            'prescription' => json_encode([
                'issued_by' => '',
                'date' => ''
            ]),
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
                'travel' => $data['travel']
            ]),
			'qr' => '',
			'external' => true,
			'date' => Dates::current_date()
        ]);

        return $query;
    }

    public function read_account($path)
    {
        $query = System::decode_json_to_array($this->database->select('accounts', [
            'id',
            'avatar',
            'path'
        ], [
            'path' => $path
        ]));

        return !empty($query) ? $query[0] : null;
    }
}
