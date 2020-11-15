<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para manejo de moneda y tipos de cambio.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Currency
{
    /**
    * @summary Entrega el tipo de cambio actual entre USD/MXN, MXN/USD.
    *
    * @param int-float $number: Cantidad a convertir.
    * @param string $from: (USD, MXN) Moneda de la que se convertirá.
    * @param string $to: (USD, MXN) Moneda a la que se convertirá.
    *
    * @return int
    * @return float
    */
    static public function exchange($number = 0, $from = 'USD', $to = 'MXN')
    {
        $a1 = curl_init();

        curl_setopt($a1, CURLOPT_URL, 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF63528/datos/oportuno?token=ac32cf33a053bab54c26b061f4ebda76c4b21fa2d772a354779d121641c580f9');
        curl_setopt($a1, CURLOPT_RETURNTRANSFER, true);

        $a2 = System::decode_json_to_array(curl_exec($a1));
        $a2 = $a2['bmx']['series'][0]['datos'][0]['dato'];

        curl_close($a1);

        if ($from == 'USD' AND $to == 'MXN')
            return ($number * $a2);
        else if ($from == 'USD' AND $to == 'USD')
            return $number;
        else if ($from == 'MXN' AND $to == 'USD')
            return ($number / $a2);
        else if ($from == 'MXN' AND $to == 'MXN')
            return $number;
    }

    /**
    * @summary Entrega una moneda con formato.
    *
    * @param int-float $number: Cantidad a dar formato.
    * @param string $currency: Moneda en la que retornará $number.
    * @param int $decimals: El número de decimales con el que retornará el formato.
    *
    * @return string
    */
    public static function format($number = 0, $currency = 'MXN', $decimals = 2)
    {
        return '$ ' . number_format($number, $decimals, '.', ',') . ' ' . $currency;
    }
}
