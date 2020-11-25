<?php

defined('_EXEC') or die;

require 'plugins/php_qr_code/qrlib.php';

class Employees_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create_employee($data)
	{
		$data['qr']['filename'] = Session::get_value('vkye_account')['path'] . '_employee_qr_' . $data['nie'] . '.png';
		$data['qr']['content'] = 'https://' . Configuration::$domain . '/' . Session::get_value('vkye_account')['path'] . '/' . $data['nie'];
		$data['qr']['dir'] = PATH_UPLOADS . $data['qr']['filename'];
		$data['qr']['level'] = 'H';
		$data['qr']['size'] = 5;
		$data['qr']['frame'] = 3;

		$query = $this->database->insert('employees', [
			'account' => Session::get_value('vkye_account')['id'],
			'avatar' => !empty($data['files']['avatar']['name']) ? Fileloader::up($data['files']['avatar']) : null,
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'sex' => $data['sex'],
			'birth_date' => $data['birth_date'],
			'ife' => $data['ife'],
			'nss' => $data['nss'],
			'rfc' => $data['rfc'],
			'curp' => $data['curp'],
			'bank' => json_encode([
				'name' => $data['bank_name'],
				'account' => $data['bank_account']
			]),
			'nsv' => !empty($data['nsv']) ? $data['nsv'] : null,
			'email' => !empty($data['email']) ? $data['email'] : null,
			'phone' => json_encode([
                'country' => !empty($data['phone_country']) ? $data['phone_country'] : '',
                'number' => !empty($data['phone_number']) ? $data['phone_number'] : ''
            ]),
			'rank' => $data['rank'],
			'nie' => $data['nie'],
			'admission_date' => $data['admission_date'],
			'responsibilities' => !empty($data['responsibilities']) ? $data['responsibilities'] : null,
			'emergency_contacts' => json_encode([
				'first' => [
					'name' => $data['emergency_contacts_first_name'],
					'phone' => [
						'country' => $data['emergency_contacts_first_phone_country'],
						'number' => $data['emergency_contacts_first_phone_number']
					]
				],
				'second' => [
					'name' => !empty($data['emergency_contacts_second_name']) ? $data['emergency_contacts_second_name'] : '',
					'phone' => [
						'country' => !empty($data['emergency_contacts_second_phone_country']) ? $data['emergency_contacts_second_phone_country'] : '',
						'number' => !empty($data['emergency_contacts_second_phone_number']) ? $data['emergency_contacts_second_phone_number'] : ''
					]
				],
				'third' => [
					'name' => !empty($data['emergency_contacts_third_name']) ? $data['emergency_contacts_third_name'] : '',
					'phone' => [
						'country' => !empty($data['emergency_contacts_third_phone_country']) ? $data['emergency_contacts_third_phone_country'] : '',
						'number' => !empty($data['emergency_contacts_third_phone_number']) ? $data['emergency_contacts_third_phone_number'] : ''
					]
				],
				'fourth' => [
					'name' => !empty($data['emergency_contacts_fourth_name']) ? $data['emergency_contacts_fourth_name'] : '',
					'phone' => [
						'country' => !empty($data['emergency_contacts_fourth_phone_country']) ? $data['emergency_contacts_fourth_phone_country'] : '',
						'number' => !empty($data['emergency_contacts_fourth_phone_number']) ? $data['emergency_contacts_fourth_phone_number'] : ''
					]
				]
			]),
			'docs' => json_encode([
				'birth_certificate' => !empty($data['files']['docs_birth_certificate']['name']) ? Fileloader::up($data['files']['docs_birth_certificate']) : '',
				'address_proof' => !empty($data['files']['docs_address_proof']['name']) ? Fileloader::up($data['files']['docs_address_proof']) : '',
				'ife' => !empty($data['files']['docs_ife']['name']) ? Fileloader::up($data['files']['docs_ife']) : '',
				'rfc' => !empty($data['files']['docs_rfc']['name']) ? Fileloader::up($data['files']['docs_rfc']) : '',
				'curp' => !empty($data['files']['docs_curp']['name']) ? Fileloader::up($data['files']['docs_curp']) : '',
				'professional_license' => !empty($data['files']['docs_professional_license']['name']) ? Fileloader::up($data['files']['docs_professional_license']) : '',
				'driver_license' => !empty($data['files']['docs_driver_license']['name']) ? Fileloader::up($data['files']['docs_driver_license']) : '',
				'account_state' => !empty($data['files']['docs_account_state']['name']) ? Fileloader::up($data['files']['docs_account_state']) : '',
				'medical_examination' => !empty($data['files']['docs_medical_examination']['name']) ? Fileloader::up($data['files']['docs_medical_examination']) : '',
				'criminal_records' => !empty($data['files']['docs_criminal_records']['name']) ? Fileloader::up($data['files']['docs_criminal_records']) : '',
				'economic_study' => !empty($data['files']['docs_economic_study']['name']) ? Fileloader::up($data['files']['docs_economic_study']) : '',
				'life_insurance' => !empty($data['files']['docs_life_insurance']['name']) ? Fileloader::up($data['files']['docs_life_insurance']) : '',
				'recommendation_letters' => [
					'first' => !empty($data['files']['docs_recommendation_letters_first']['name']) ? Fileloader::up($data['files']['docs_recommendation_letters_first']) : '',
					'second' => !empty($data['files']['docs_recommendation_letters_second']['name']) ? Fileloader::up($data['files']['docs_recommendation_letters_second']) : '',
					'third' => !empty($data['files']['docs_recommendation_letters_third']['name']) ? Fileloader::up($data['files']['docs_recommendation_letters_third']) : ''
				],
				'work_contract' => !empty($data['files']['docs_work_contract']['name']) ? Fileloader::up($data['files']['docs_work_contract']) : '',
				'resignation_letter' => !empty($data['files']['docs_resignation_letter']['name']) ? Fileloader::up($data['files']['docs_resignation_letter']) : '',
				'material_responsive' => !empty($data['files']['docs_material_responsive']['name']) ? Fileloader::up($data['files']['docs_material_responsive']) : '',
				'privacy_notice' => !empty($data['files']['docs_privacy_notice']['name']) ? Fileloader::up($data['files']['docs_privacy_notice']) : '',
				'regulation' => !empty($data['files']['docs_regulation']['name']) ? Fileloader::up($data['files']['docs_regulation']) : ''
			]),
			'qr' => $data['qr']['filename'],
			'registration_date' => Dates::current_date(),
			'blocked' => false
		]);

		QRcode::png($data['qr']['content'], $data['qr']['dir'], $data['qr']['level'], $data['qr']['size'], $data['qr']['frame']);

		return $query;
	}

	public function read_employees()
	{
		$query = System::decode_json_to_array($this->database->select('employees', [
			'id',
			'avatar',
			'firstname',
			'lastname',
			'nie',
			'qr',
			'blocked'
		], [
            'account' => Session::get_value('vkye_account')['id'],
            'ORDER' => [
    			'admission_date' => 'DESC',
    			'firstname' => 'ASC',
    			'lastname' => 'ASC'
    		]
        ]));

		return $query;
	}

	public function read_employee($id, $scanner = false)
	{
		$where = [];

		if ($scanner == true)
		{
			$where = [
				'AND' => [
					'account' => Session::get_value('vkye_account')['id'],
					'nie' => $id,
					'blocked' => false
				]
			];
		}
		else
			$where['id'] = $id;

		$query = System::decode_json_to_array($this->database->select('employees', [
            'id',
            'avatar',
			'firstname',
			'lastname',
			'sex',
			'birth_date',
			'ife',
			'nss',
			'rfc',
			'curp',
			'bank',
			'nsv',
			'email',
			'phone',
			'rank',
			'nie',
			'admission_date',
			'responsibilities',
			'emergency_contacts',
			'docs',
			'blocked'
		], $where));

		if (!empty($query))
		{
			if ($scanner == true)
			{
				$custody_chanins = System::decode_json_to_array($this->database->select('custody_chanins', [
					'[>]users' => [
						'user' => 'id'
					]
				], [
		            'custody_chanins.id',
		            'users.avatar(user_avatar)',
		            'users.firstname(user_firstname)',
		            'users.lastname(user_lastname)',
					'custody_chanins.type',
					'custody_chanins.reason',
					'custody_chanins.tests',
					'custody_chanins.analysis',
					'custody_chanins.result',
					'custody_chanins.medicines',
					'custody_chanins.prescription',
					'custody_chanins.collection',
					'custody_chanins.signatures',
					'custody_chanins.date'
		        ], [
		            'custody_chanins.employee' => $query[0]['id'],
					'ORDER' => [
						'custody_chanins.date' => 'DESC'
					]
		        ]));

				$query[0]['custody_chanins']['alcoholic'] = [];
				$query[0]['custody_chanins']['antidoping'] = [];

				foreach ($custody_chanins as $key => $value)
				{
					if ($value['type'] == 'alcoholic')
						array_push($query[0]['custody_chanins']['alcoholic'], $value);
					else if ($value['type'] == 'antidoping')
						array_push($query[0]['custody_chanins']['antidoping'], $value);
				}
			}

			return $query[0];
		}
		else
			return null;
	}

	public function check_exist_employee($id, $field, $value)
	{
		$count = $this->database->count('employees', [
			'AND' => [
				'id[!]' => $id,
				'account' => Session::get_value('vkye_account')['id'],
				$field => $value
			]
		]);

		return ($count > 0) ? true : false;
	}

	public function update_employee($data)
	{
		$query = null;

        $edited = System::decode_json_to_array($this->database->select('employees', [
            'avatar',
			'docs'
        ], [
            'id' => $data['id']
        ]));

        if (!empty($edited))
        {
            $query = $this->database->update('employees', [
				'avatar' => !empty($data['files']['avatar']['name']) ? Fileloader::up($data['files']['avatar']) : $edited[0]['avatar'],
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'sex' => $data['sex'],
				'birth_date' => $data['birth_date'],
				'ife' => $data['ife'],
				'nss' => $data['nss'],
				'rfc' => $data['rfc'],
				'curp' => $data['curp'],
				'bank' => json_encode([
					'name' => $data['bank_name'],
					'account' => $data['bank_account']
				]),
				'nsv' => !empty($data['nsv']) ? $data['nsv'] : null,
				'email' => !empty($data['email']) ? $data['email'] : null,
				'phone' => json_encode([
	                'country' => !empty($data['phone_country']) ? $data['phone_country'] : '',
	                'number' => !empty($data['phone_number']) ? $data['phone_number'] : ''
	            ]),
				'rank' => $data['rank'],
				'nie' => $data['nie'],
				'admission_date' => $data['admission_date'],
				'responsibilities' => !empty($data['responsibilities']) ? $data['responsibilities'] : null,
				'emergency_contacts' => json_encode([
					'first' => [
						'name' => $data['emergency_contacts_first_name'],
						'phone' => [
							'country' => $data['emergency_contacts_first_phone_country'],
							'number' => $data['emergency_contacts_first_phone_number']
						]
					],
					'second' => [
						'name' => !empty($data['emergency_contacts_second_name']) ? $data['emergency_contacts_second_name'] : '',
						'phone' => [
							'country' => !empty($data['emergency_contacts_second_phone_country']) ? $data['emergency_contacts_second_phone_country'] : '',
							'number' => !empty($data['emergency_contacts_second_phone_number']) ? $data['emergency_contacts_second_phone_number'] : ''
						]
					],
					'third' => [
						'name' => !empty($data['emergency_contacts_third_name']) ? $data['emergency_contacts_third_name'] : '',
						'phone' => [
							'country' => !empty($data['emergency_contacts_third_phone_country']) ? $data['emergency_contacts_third_phone_country'] : '',
							'number' => !empty($data['emergency_contacts_third_phone_number']) ? $data['emergency_contacts_third_phone_number'] : ''
						]
					],
					'fourth' => [
						'name' => !empty($data['emergency_contacts_fourth_name']) ? $data['emergency_contacts_fourth_name'] : '',
						'phone' => [
							'country' => !empty($data['emergency_contacts_fourth_phone_country']) ? $data['emergency_contacts_fourth_phone_country'] : '',
							'number' => !empty($data['emergency_contacts_fourth_phone_number']) ? $data['emergency_contacts_fourth_phone_number'] : ''
						]
					],
				]),
				'docs' => json_encode([
					'birth_certificate' => !empty($data['files']['docs_birth_certificate']['name']) ? Fileloader::up($data['files']['docs_birth_certificate']) : $edited[0]['docs']['birth_certificate'],
					'address_proof' => !empty($data['files']['docs_address_proof']['name']) ? Fileloader::up($data['files']['docs_address_proof']) : $edited[0]['docs']['address_proof'],
					'ife' => !empty($data['files']['docs_ife']['name']) ? Fileloader::up($data['files']['docs_ife']) : $edited[0]['docs']['ife'],
					'rfc' => !empty($data['files']['docs_rfc']['name']) ? Fileloader::up($data['files']['docs_rfc']) : $edited[0]['docs']['rfc'],
					'curp' => !empty($data['files']['docs_curp']['name']) ? Fileloader::up($data['files']['docs_curp']) : $edited[0]['docs']['curp'],
					'professional_license' => !empty($data['files']['docs_professional_license']['name']) ? Fileloader::up($data['files']['docs_professional_license']) : $edited[0]['docs']['professional_license'],
					'driver_license' => !empty($data['files']['docs_driver_license']['name']) ? Fileloader::up($data['files']['docs_driver_license']) : $edited[0]['docs']['driver_license'],
					'account_state' => !empty($data['files']['docs_account_state']['name']) ? Fileloader::up($data['files']['docs_account_state']) : $edited[0]['docs']['account_state'],
					'medical_examination' => !empty($data['files']['docs_medical_examination']['name']) ? Fileloader::up($data['files']['docs_medical_examination']) : $edited[0]['docs']['medical_examination'],
					'criminal_records' => !empty($data['files']['docs_criminal_records']['name']) ? Fileloader::up($data['files']['docs_criminal_records']) : $edited[0]['docs']['criminal_records'],
					'economic_study' => !empty($data['files']['docs_economic_study']['name']) ? Fileloader::up($data['files']['docs_economic_study']) : $edited[0]['docs']['economic_study'],
					'life_insurance' => !empty($data['files']['docs_life_insurance']['name']) ? Fileloader::up($data['files']['docs_life_insurance']) : $edited[0]['docs']['life_insurance'],
					'recommendation_letters' => [
						'first' => !empty($data['files']['docs_recommendation_letters_first']['name']) ? Fileloader::up($data['files']['docs_recommendation_letters_first']) : $edited[0]['docs']['recommendation_letters']['first'],
						'second' => !empty($data['files']['docs_recommendation_letters_second']['name']) ? Fileloader::up($data['files']['docs_recommendation_letters_second']) : $edited[0]['docs']['recommendation_letters']['second'],
						'third' => !empty($data['files']['docs_recommendation_letters_third']['name']) ? Fileloader::up($data['files']['docs_recommendation_letters_third']) : $edited[0]['docs']['recommendation_letters']['third'],
					],
					'work_contract' => !empty($data['files']['docs_work_contract']['name']) ? Fileloader::up($data['files']['docs_work_contract']) : $edited[0]['docs']['work_contract'],
					'resignation_letter' => !empty($data['files']['docs_resignation_letter']['name']) ? Fileloader::up($data['files']['docs_resignation_letter']) : $edited[0]['docs']['resignation_letter'],
					'material_responsive' => !empty($data['files']['docs_material_responsive']['name']) ? Fileloader::up($data['files']['docs_material_responsive']) : $edited[0]['docs']['material_responsive'],
					'privacy_notice' => !empty($data['files']['docs_privacy_notice']['name']) ? Fileloader::up($data['files']['docs_privacy_notice']) : $edited[0]['docs']['privacy_notice'],
					'regulation' => !empty($data['files']['docs_regulation']['name']) ? Fileloader::up($data['files']['docs_regulation']) : $edited[0]['docs']['regulation']
				])
            ], [
                'id' => $data['id']
            ]);

            if (!empty($query))
			{
				if (!empty($data['files']['avatar']['name']) AND !empty($edited[0]['avatar']))
					Fileloader::down($edited[0]['avatar']);

				if (!empty($data['files']['docs_birth_certificate']['name']) AND !empty($edited[0]['docs']['birth_certificate']))
					Fileloader::down($edited[0]['docs']['birth_certificate']);

				if (!empty($data['files']['docs_address_proof']['name']) AND !empty($edited[0]['docs']['address_proof']))
					Fileloader::down($edited[0]['docs']['address_proof']);

				if (!empty($data['files']['docs_ife']['name']) AND !empty($edited[0]['docs']['ife']))
					Fileloader::down($edited[0]['docs']['ife']);

				if (!empty($data['files']['docs_rfc']['name']) AND !empty($edited[0]['docs']['rfc']))
					Fileloader::down($edited[0]['docs']['rfc']);

				if (!empty($data['files']['docs_curp']['name']) AND !empty($edited[0]['docs']['curp']))
					Fileloader::down($edited[0]['docs']['curp']);

				if (!empty($data['files']['docs_professional_license']['name']) AND !empty($edited[0]['docs']['professional_license']))
					Fileloader::down($edited[0]['docs']['professional_license']);

				if (!empty($data['files']['docs_driver_license']['name']) AND !empty($edited[0]['docs']['driver_license']))
					Fileloader::down($edited[0]['docs']['driver_license']);

				if (!empty($data['files']['docs_account_state']['name']) AND !empty($edited[0]['docs']['account_state']))
					Fileloader::down($edited[0]['docs']['account_state']);

				if (!empty($data['files']['docs_medical_examination']['name']) AND !empty($edited[0]['docs']['medical_examination']))
					Fileloader::down($edited[0]['docs']['medical_examination']);

				if (!empty($data['files']['docs_criminal_records']['name']) AND !empty($edited[0]['docs']['criminal_records']))
					Fileloader::down($edited[0]['docs']['criminal_records']);

				if (!empty($data['files']['docs_economic_study']['name']) AND !empty($edited[0]['docs']['economic_study']))
					Fileloader::down($edited[0]['docs']['economic_study']);

				if (!empty($data['files']['docs_life_insurance']['name']) AND !empty($edited[0]['docs']['life_insurance']))
					Fileloader::down($edited[0]['docs']['life_insurance']);

				if (!empty($data['files']['docs_recommendation_letters_first']['name']) AND !empty($edited[0]['docs']['recommendation_letters']['first']))
					Fileloader::down($edited[0]['docs']['recommendation_letters']['first']);

				if (!empty($data['files']['docs_recommendation_letters_second']['name']) AND !empty($edited[0]['docs']['recommendation_letters']['second']))
					Fileloader::down($edited[0]['docs']['recommendation_letters']['second']);

				if (!empty($data['files']['docs_recommendation_letters_third']['name']) AND !empty($edited[0]['docs']['recommendation_letters']['third']))
					Fileloader::down($edited[0]['docs']['recommendation_letters']['third']);

				if (!empty($data['files']['docs_work_contract']['name']) AND !empty($edited[0]['docs']['work_contract']))
					Fileloader::down($edited[0]['docs']['work_contract']);

				if (!empty($data['files']['docs_resignation_letter']['name']) AND !empty($edited[0]['docs']['resignation_letter']))
					Fileloader::down($edited[0]['docs']['resignation_letter']);

				if (!empty($data['files']['docs_material_responsive']['name']) AND !empty($edited[0]['docs']['material_responsive']))
					Fileloader::down($edited[0]['docs']['material_responsive']);

				if (!empty($data['files']['docs_privacy_notice']['name']) AND !empty($edited[0]['docs']['privacy_notice']))
					Fileloader::down($edited[0]['docs']['privacy_notice']);

				if (!empty($data['files']['docs_regulation']['name']) AND !empty($edited[0]['docs']['regulation']))
					Fileloader::down($edited[0]['docs']['regulation']);
			}
        }

        return $query;
	}

	public function block_employee($id)
	{
		$query = $this->database->update('employees', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_employee($id)
	{
		$query = $this->database->update('employees', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_employee($id)
    {
        $query = null;

        $deleted = System::decode_json_to_array($this->database->select('employees', [
            'avatar',
            'qr',
			'docs'
        ], [
            'id' => $id
        ]));

        if (!empty($deleted))
        {
            $query = $this->database->delete('employees', [
                'id' => $id
            ]);

			if (!empty($query))
			{
				if (!empty($deleted[0]['avatar']))
	                Fileloader::down($deleted[0]['avatar']);

				if (!empty($deleted[0]['docs']['birth_certificate']))
					Fileloader::down($deleted[0]['docs']['birth_certificate']);

				if (!empty($deleted[0]['docs']['address_proof']))
					Fileloader::down($deleted[0]['docs']['address_proof']);

				if (!empty($deleted[0]['docs']['ife']))
					Fileloader::down($deleted[0]['docs']['ife']);

				if (!empty($deleted[0]['docs']['rfc']))
					Fileloader::down($deleted[0]['docs']['rfc']);

				if (!empty($deleted[0]['docs']['curp']))
					Fileloader::down($deleted[0]['docs']['curp']);

				if (!empty($deleted[0]['docs']['professional_license']))
					Fileloader::down($deleted[0]['docs']['professional_license']);

				if (!empty($deleted[0]['docs']['driver_license']))
					Fileloader::down($deleted[0]['docs']['driver_license']);

				if (!empty($deleted[0]['docs']['account_state']))
					Fileloader::down($deleted[0]['docs']['account_state']);

				if (!empty($deleted[0]['docs']['medical_examination']))
					Fileloader::down($deleted[0]['docs']['medical_examination']);

				if (!empty($deleted[0]['docs']['criminal_records']))
					Fileloader::down($deleted[0]['docs']['criminal_records']);

				if (!empty($deleted[0]['docs']['economic_study']))
					Fileloader::down($deleted[0]['docs']['economic_study']);

				if (!empty($deleted[0]['docs']['life_insurance']))
					Fileloader::down($deleted[0]['docs']['life_insurance']);

				if (!empty($deleted[0]['docs']['recommendation_letters']['first']))
					Fileloader::down($deleted[0]['docs']['recommendation_letters']['first']);

				if (!empty($deleted[0]['docs']['recommendation_letters']['second']))
					Fileloader::down($deleted[0]['docs']['recommendation_letters']['second']);

				if (!empty($deleted[0]['docs']['recommendation_letters']['third']))
					Fileloader::down($deleted[0]['docs']['recommendation_letters']['third']);

				if (!empty($deleted[0]['docs']['work_contract']))
					Fileloader::down($deleted[0]['docs']['work_contract']);

				if (!empty($deleted[0]['docs']['resignation_letter']))
					Fileloader::down($deleted[0]['docs']['resignation_letter']);

				if (!empty($deleted[0]['docs']['material_responsive']))
					Fileloader::down($deleted[0]['docs']['material_responsive']);

				if (!empty($deleted[0]['docs']['privacy_notice']))
					Fileloader::down($deleted[0]['docs']['privacy_notice']);

				if (!empty($deleted[0]['docs']['regulation']))
					Fileloader::down($deleted[0]['docs']['regulation']);

				if (!empty($deleted[0]['qr']))
	                Fileloader::down($deleted[0]['qr']);
			}
        }

        return $query;
    }
}
