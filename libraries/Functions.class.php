<?php

defined('_EXEC') or die;

class Functions
{
    static public function format_age($date, $int = false)
    {
        $today = explode('-', Dates::current_date());
        $date = explode('-', $date);
        $age = ($today[0] - $date[0]);

        if ($int == false)
            return $age . ' {$lang.years}';
        else
            return $age;
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
