<?php

require 'plugins/php_qr_code/qrlib.php';

public function excel()
{
    $xlsx = SimpleXLSX::parse(PATH_UPLOADS . 'imports.xlsx');

    foreach ($xlsx->rows() as $value)
    {
        $value[3] = explode(' ', $value[3]);
        $value[3] = $value[3][0];

        $value[8] = explode(' ', $value[8]);
        $value[8] = $value[8][0];

        $nie = System::generate_random_string();

        $qr['filename'] = 'cancunsailing_employee_qr_' . $nie . '.png';
        $qr['content'] = 'https://' . Configuration::$domain . '/cancunsailing/' . $nie;
        $qr['dir'] = PATH_UPLOADS . $qr['filename'];
        $qr['level'] = 'H';
        $qr['size'] = 5;
        $qr['frame'] = 3;

        $this->database->insert('employees', [
            'account' => 2,
            'avatar' => null,
            'firstname' => !empty($value[0]) ? $value[0] : '',
            'lastname' => !empty($value[1]) ? $value[1] : '',
            'sex' => !empty($value[2]) ? $value[2] : '',
            'birth_date' => !empty($value[3]) ? $value[3] : '',
            'ife' => System::generate_random_string(),
            'nss' => !empty($value[4]) ? $value[4] : '',
            'rfc' => !empty($value[5]) ? $value[5] : '',
            'curp' => !empty($value[6]) ? $value[6] : '',
            'bank' => json_encode([
                'name' => '',
                'account' => ''
            ]),
            'nsv' => null,
            'email' => null,
            'phone' => json_encode([
                'country' => '',
                'number' => ''
            ]),
            'rank' => !empty($value[7]) ? $value[7] : '',
            'nie' => $nie,
            'admission_date' => !empty($value[8]) ? $value[8] : '',
            'responsibilities' => null,
            'emergency_contacts' => json_encode([
                'first' => [
                    'name' => '',
                    'phone' => [
                        'country' => '',
                        'number' => ''
                    ]
                ],
                'second' => [
                    'name' => '',
                    'phone' => [
                        'country' => '',
                        'number' => ''
                    ]
                ],
                'third' => [
                    'name' => '',
                    'phone' => [
                        'country' => '',
                        'number' => ''
                    ]
                ],
                'fourth' => [
                    'name' => '',
                    'phone' => [
                        'country' => '',
                        'number' => ''
                    ]
                ]
            ]),
            'docs' => json_encode([
                'birth_certificate' => '',
                'address_proof' => '',
                'ife' => '',
                'rfc' => '',
                'curp' => '',
                'professional_license' => '',
                'driver_license' => '',
                'account_state' => '',
                'medical_examination' => '',
                'criminal_records' => '',
                'economic_study' => '',
                'life_insurance' => '',
                'recommendation_letters' => [
                    'first' => '',
                    'second' => '',
                    'third' => ''
                ],
                'work_contract' => '',
                'resignation_letter' => '',
                'material_responsive' => '',
                'privacy_notice' => '',
                'regulation' => ''
            ]),
            'qr' => $qr['filename'],
            'registration_date' => Dates::current_date(),
            'blocked' => false
        ]);

        QRcode::png($qr['content'], $qr['dir'], $qr['level'], $qr['size'], $qr['frame']);
    }
}
