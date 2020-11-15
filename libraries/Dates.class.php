<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para fechas y horas.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Dates
{
    /**
    * @summary Establece la zona horaria por defecto.
    */
    static public function set_default_timezone()
    {
        if (Session::exists_var('session') == true)
            date_default_timezone_set(Session::get_value('vkye_account')['time_zone']);
        else
            date_default_timezone_set(Configuration::$time_zone);
    }

    /**
    * @summary Entrega la fecha actual.
    *
    * @param string $format: (<Formato de fecha de PHP>) Formato en el que retornará la fecha.
    *
    * @return date
    */
    static public function current_date($format = 'Y-m-d')
    {
        Dates::set_default_timezone();

		return date($format);
    }

    /**
    * @summary Entrega la resta de un tiempo a una fecha.
    *
    * @param date $date: Fecha a restar.
    * @param int $number: Numero de $lapse que se va a restar a $date.
    * @param string $lapse: (year, month, week, days) Lapso de tiempo que se va a restar a $date.
    * @param string $format: (<Formato de fecha de PHP>) Formato en el que retornará la fecha.
    *
    * @return date
    */
    static public function past_date($date, $number, $lapse, $format = 'Y-m-d')
    {
        Dates::set_default_timezone();

        return date($format, strtotime(date('d-m-Y', strtotime($date)) . ' - ' . $number . ' ' . $lapse));
    }

    /**
    * @summary Entrega la suma de un tiempo a una fecha.
    *
    * @param date $date: Fecha a sumar.
    * @param int $number: Numero de $lapse que se va a sumar a $date.
    * @param string $lapse: (year, month, week, days) Lapso de tiempo que se va a sumar a $date.
    * @param string $format: (<Formato de fecha de PHP>) Formato en el que retornará la fecha.
    *
    * @return date
    */
    static public function future_date($date, $number, $lapse, $format = 'Y-m-d')
    {
        Dates::set_default_timezone();

        return date($format, strtotime(date('d-m-Y', strtotime($date)) . ' + ' . $number . ' ' . $lapse));
    }

    /**
    * @summary Entrega una fecha con formato.
    *
    * @param date $date: Fecha a dar formato.
    * @param string $format: (long, short, <Formato de fecha de PHP>) Formato en el que retornará la fecha.
    * @param string $language: (es, en) Lenguage en el que retornará la fecha en caso de que el $format sea long o short.
    *
    * @return string
    * @return date
    */
    static public function format_date($date, $format = 'Y-m-d', $language = 'es')
    {
        if ($format == 'long' OR $format == 'long_month' OR $format == 'long_year' OR $format == 'short')
        {
            $date = date('Y-m-d', strtotime($date));
            $date = explode('-', $date);

            $months = [
                'es' => [
                    '01' => 'enero',
                    '02' => 'febrero',
                    '03' => 'marzo',
                    '04' => 'abril',
                    '05' => 'mayo',
                    '06' => 'junio',
                    '07' => 'julio',
                    '08' => 'agosto',
                    '09' => 'septiembre',
                    '10' => 'octubre',
                    '11' => 'noviembre',
                    '12' => 'diciembre'
                ],
                'en' => [
                    '01' => 'january',
                    '02' => 'february',
                    '03' => 'march',
                    '04' => 'april',
                    '05' => 'may',
                    '06' => 'june',
                    '07' => 'july',
                    '08' => 'august',
                    '09' => 'september',
                    '10' => 'october',
                    '11' => 'november',
                    '12' => 'december'
                ]
            ];

            if ($format == 'long')
                return $date[2] . ' {$lang.of} ' . $months[$language][$date[1]] . ' {$lang.from} ' . $date[0];
            else if ($format == 'long_month')
                return $date[2] . ' ' . $months[$language][$date[1]] . ' ' . substr($date[0], -2);
            else if ($format == 'long_year')
                return $date[2] . ' ' . substr($months[$language][$date[1]], -strlen($months[$language][$date[1]]), 3) . ' ' . $date[0];
            else if ($format == 'short')
                return $date[2] . ' ' . substr($months[$language][$date[1]], -strlen($months[$language][$date[1]]), 3) . ' ' . substr($date[0], -2);
        }
        else
            return date($format, strtotime($date));
    }

    /**
    * @summary Entrega la hora actual.
    *
    * @param string $format: (<Formato de hora de PHP>) Formato en el que retornará la hora.
    *
    * @return date
    */
    static public function current_hour($format = 'H:i:s')
    {
        Dates::set_default_timezone();

		return date($format, time());
    }

    /**
    * @summary Entrega la resta de un tiempo a una hora.
    *
    * @param time $hour: Hora a restar.
    * @param int $number: Numero de $lapse que se va a restar a $hour.
    * @param string $lapse: (hour, minute, second) Lapso de tiempo que se va a restar a $hour.
    * @param string $format: (<Formato de hora de PHP>) Formato en el que retornará la hora.
    *
    * @return date
    */
    static public function past_hour($hour, $number, $lapse, $format = 'H:i:s')
    {
        Dates::set_default_timezone();

        return date($format, strtotime('-' . $number . ' ' . $lapse, strtotime(date('H:i:s', strtotime($hour)))));
    }

    /**
    * @summary Entrega la suma de un tiempo a una hora.
    *
    * @param time $hour: Hora a sumar.
    * @param int $number: Numero de $lapse que se va a sumar a $hour.
    * @param string $lapse: (hour, minute, second) Lapso de tiempo que se va a sumar a $hour.
    * @param string $format: (<Formato de hora de PHP>) Formato en el que retornará la hora.
    *
    * @return date
    */
    static public function future_hour($hour, $number, $lapse, $format = 'H:i:s')
    {
        Dates::set_default_timezone();

        return date($format, strtotime('+' . $number . ' ' . $lapse, strtotime(date('H:i:s', strtotime($hour)))));
    }

    /**
    * @summary Entrega una hora con formato.
    *
    * @param time $hour: Hora a dar formato.
    * @param string $format: (12, 24, <Formato de hora de PHP>) Formato en el que retornará la hora.
    *
    * @return string
    * @return date
    */
    static public function format_hour($hour, $format = 'H:i:s')
    {
        if ($format == '12-short' OR $format == '12-long')
        {
            $hour = explode(':', $hour);
            $hour[3] = ($hour[0] < 12) ? 'am' : 'pm';
            $hour[0] = ($hour[0] > 12) ? $hour[0] - 12 : $hour[0];
            $hour[0] = ($hour[0] <= 9 AND $hour[3] == 'pm') ? '0' . $hour[0] : $hour[0];

            if ($format == '12-short')
                $hour = $hour[0] . ':' . (array_key_exists(1, $hour) ? $hour[1] : '00') . ' ' . $hour[3];
            else if ($format == '12-long')
                $hour = $hour[0] . ':' . (array_key_exists(1, $hour) ? $hour[1] : '00') . ':' . (array_key_exists(2, $hour) ? $hour[2] : '00') . ' ' . $hour[3];

            return $hour;
        }
        else if ($format == '24')
            return $hour . ' Hrs';
        else
            return date($format, strtotime($hour));
    }

    /**
    * @summary Entrega la fecha y hora actual.
    *
    * @param string $format: (<Formato de fecha y hora de PHP>) Formato en el que retornará la fecha y hora.
    *
    * @return date
    */
    static public function current_date_hour($format = 'Y-m-d H:i:s')
    {
        Dates::set_default_timezone();

		return date($format, time());
    }

    /**
    * @summary Entrega la diferencia entre dos fechas, horas o fechas y horas.
    *
    * @param date-time-datetime $date_hour1: Fecha inicial.
    * @param date-time-datetime $date_hour2: Fecha final.
    * @param string $lapse: (year, month, days, hours, minutes, seconds, all) Lapso de tiempo en el que retornara la función.
    * @param boolean $format: Identificador para saber si retornará la función con su formato en cadena de texto.
    *
    * @return string
    * @return array
    */
    static public function diff_date_hour($date_hour1, $date_hour2, $lapse = 'all', $format = true)
    {
        Dates::set_default_timezone();

        $date_hour1 = new DateTime($date_hour1);
        $date_hour2 = new DateTime($date_hour2);

        $a1 = $date_hour1->diff($date_hour2);
        $a2 = $a1->days;
        $a3 = '';

        $y = $a1->y;
        $m = $a1->m;
        $d = $a1->d;
        $h = $a1->h;
        $i = $a1->i;
        $s = $a1->s;

        if ($lapse == 'year')
        {
            $y = round($a2 / 365);
            $a3 .= $y . (($format == true) ? (($y == 1) ? ' año' : ' años') : '');
        }
        else if ($lapse == 'month')
        {
            $m = round($a2 / 30);
            $a3 .= $m . (($format == true) ? (($m == 1) ? ' mes' : ' meses') : '');
        }
        else if ($lapse == 'days')
        {
            $d = round($a2);
            $a3 .= $d . (($format == true) ? (($d == 1) ? ' día' : ' días') : '');
        }
        else if ($lapse == 'hours')
        {
            $h = round($a2 * 24);
            $a3 .= $h . (($format == true) ? (($h == 1) ? ' hora' : ' horas') : '');
        }
        else if ($lapse == 'minutes')
        {
            $i = round($a2 * 1440);
            $a3 .= $i . (($format == true) ? (($i == 1) ? ' minuto' : ' minutos') : '');
        }
        else if ($lapse == 'seconds')
        {
            $s = round($a2 * 86400);
            $a3 .= $s . (($format == true) ? (($s == 1) ? ' segundo' : ' segundos') : '');
        }
        else if ($lapse == 'all')
        {
            if ($format == true)
            {
                if ($y > 0)
                    $a3 .= $y . (($y == 1) ? ' año ' : ' años ');

                if ($m > 0)
                    $a3 .= $m . (($m == 1) ? ' mes ' : ' meses ');

                if ($d > 0)
                    $a3 .= $d . (($d == 1) ? ' día ' : ' días ');

                if ($h > 0)
                    $a3 .= $h . (($h == 1) ? ' hora ' : ' horas ');

                if ($i > 0)
                    $a3 .= $i . (($i == 1) ? ' minuto ' : ' minutos ');

                if ($s > 0)
                    $a3 .= $s . (($s == 1) ? ' segundo ' : ' segundos ');
            }
            else
            {
                $a3 = [];
                $a3['y'] = $y;
                $a3['m'] = $m;
                $a3['d'] = $d;
                $a3['h'] = $h;
                $a3['i'] = $i;
                $a3['s'] = $s;
            }
        }

        return $a3;
    }
}
