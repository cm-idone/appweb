<?php

    define('_EXEC', 1);

    include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'configuration.php');
    include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'valkyrie' . DIRECTORY_SEPARATOR . 'Medoo.class.php');

    $database = new Medoo();

    $query = $database->select('system_collectors', [
        'id',
        'schedule'
    ]);

    foreach ($query as $key => $value)
    {
        $value['schedule'] = json_decode($value['schedule'], true);

        date_default_timezone_set('America/Cancun');

        $current_time = date('H:i:s', time());

        if ($current_time < $value['schedule']['open'] OR $current_time >= $value['schedule']['close'])
        {
            $database->update('system_collectors', [
                'authentication' => json_encode([
                    'type' => 'none',
                    'taker' => 'none'
                ])
            ], [
                'id' => $value['id']
            ]);
        }
    }
