<?php

defined('_EXEC') or die;

class Functions
{
    static public function format_age($date)
    {
        $today = explode('-', Dates::current_date());
        $date = explode('-', $date);
        $age = ($today[0] - $date[0]);

        return $age . ' {$lang.years}';
    }

    static public function countries()
    {
        $database = new Medoo();

        return System::decode_json_to_array($database->select('system_countries', [
            'name',
            'code',
            'lada'
        ], [
            'ORDER' => [
                'name' => 'ASC'
            ]
        ]));
    }
}
