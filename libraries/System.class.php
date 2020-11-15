<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones de sistema.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
* <@update> 08 de marzo, 2020.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class System
{
    /**
    * @summary: Resguarda una variable de forma temporal.
    *
    * @param string $option:
    * @param string $module:
    * @param string $key:
    * @param int $value:
    *
    * @return string
    * @return array
    * @return boolean
    */
    public static function temporal($option, $module, $key, $value = null)
    {
        $temporal = Session::get_value('vkye_temporal');

        if ($option == 'set_forced' OR $option == 'set_if_not_exist')
        {
            if ($option == 'set_forced')
                $temporal[$module][$key] = $value;
            else if ($option == 'set_if_not_exist')
            {
                if (!array_key_exists($key, $temporal[$module]) OR empty($temporal[$module][$key]))
                    $temporal[$module][$key] = $value;
            }

            Session::set_value('vkye_temporal', $temporal);
        }
        else if ($option == 'get')
            return (array_key_exists($module, $temporal) AND array_key_exists($key, $temporal[$module])) ? $temporal[$module][$key] : [];
        else if ($option == 'get_if_exists')
            return (array_key_exists($module, $temporal) AND array_key_exists($key, $temporal[$module])) ? true : false;
    }

    /**
    * @summary: Realiza la sumatoria de un array de números o texto.
    *
    * @param string $option:
    * @param string $data:
    * @param string $key:
    * @param int $subkey:
    *
    * @return int
    * @return float
    * @return string
    */
    public static function summation($option, $data, $key, $subkey = null, $marker = ', ')
    {
        if ($option == 'math' OR $option == 'count')
            $sum = 0;
        else if ($option == 'string')
            $sum = '';

        foreach ($data as $value)
        {
            if (!empty($subkey))
            {
                foreach ($value[$subkey] as $subvalue)
                {
                    if (isset($key) AND !empty($key))
                    {
                        if ($option == 'math')
                            $sum += $subvalue[$key];
                    }
                }
            }
            else
            {
                if (isset($key) AND !empty($key))
                {
                    if ($option == 'math')
                        $sum += $value[$key];
                    else if ($option == 'count')
                    {
                        foreach ($value as $subvalue)
                            $sum += 1;
                    }
                    else if ($option == 'string')
                        $sum .= $value[$key] . $marker;
                }
                else
                {
                    if ($option == 'math')
                        $sum += $value;
                    else if ($option == 'count')
                        $sum += 1;
                    else if ($option == 'string')
                        $sum .= $value . $marker;
                }
            }
        }

        if ($option == 'math' OR $option == 'count')
            return $sum;
        else if ($option == 'string')
            return substr($sum, 0, -strlen($marker));
    }

    /**
    * @summary: Entrega una cadena de texto aleatoria.
    *
    * @param string $option: (allcase, uppercase, lowercase) Formato en el que retornará la cadena de texto.
    * @param int $length: Número de caracteres en que retornará la cadena de texto.
    *
    * @return string
    */
    public static function generate_random_string($option = 'allcase', $length = 8)
    {
        $security = new Security;

        if ($option == 'allcase')
            return $security->random_string($length);
        else if ($option == 'uppercase')
            return strtoupper($security->random_string($length));
        else if ($option == 'lowercase')
            return strtolower($security->random_string($length));
    }

    /**
    * @summary: Entrega una cadena de texto encriptada bajo el estandar Password de Valkyrie.
    *
    * @param string $string: Cadena de texto a encriptar.
    *
    * @return string
    */
    public static function encrypt_string($string)
    {
        $security = new Security;

        return $security->create_password($string);
    }

    /**
    * @summary: Entrega una cadena de texto recortada.
    *
    * @param string $string: Cadena de texto a recortar.
    * @param int $length: Número de caracteres en el retornará la cadena de texto.
    *
    * @return string
    */
    public static function shorten_string($string, $length = 400)
	{
		return (strlen(strip_tags($string)) > $length) ? substr(strip_tags($string), 0, $length) . '...' : substr(strip_tags($string), 0, $length);
    }

    /**
    * @summary: Entrega una cadena de texto limpia para una URL.
    *
    * @param string $option: Tipo de .
    * @param string $string: Cadena de texto a limpiar.
    *
    * @return string
    */
    public static function clean_string($option, $string)
	{
        if ($option == 'url')
            return strtolower(str_replace(' ', '-', $string));
        else if ($option == 'commas')
            return substr($string, -strlen($string), -2);
    }

    /**
    * @summary: Entrega un array json decodificados.
    *
    * @param array-string $array: Array a decodificar.
    *
    * @return array
    */
    public static function decode_json_to_array($array)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                if (is_array($array[$key]))
                {
                    foreach ($array[$key] as $subkey => $subvalue)
                        $array[$key][$subkey] = (is_array(json_decode($array[$key][$subkey], true)) AND (json_last_error() == JSON_ERROR_NONE)) ? json_decode($array[$key][$subkey], true) : $array[$key][$subkey];
                }
                else
                    $array[$key] = (is_array(json_decode($array[$key], true)) AND (json_last_error() == JSON_ERROR_NONE)) ? json_decode($array[$key], true) : $array[$key];
            }
        }
        else
            $array = (is_array(json_decode($array, true)) AND (json_last_error() == JSON_ERROR_NONE)) ? json_decode($array, true) : $array;

        return $array;
    }

    /**
    * @summary Entrega la configuraciones generales del sistema.
    *
    * @param string $option: Tipo de configuración a regresar.
    * @param string $key: Llave del tipo de configuración a regresar.
    * @param string $key: Llave del tipo de configuración a regresar.
    *
    * @return string
    */
    static public function settings($option, $key, $subkey = null, $lang = false)
    {
        $data = [
            'seo' => [
                'title' => [
                    'index' => [
                        'es' => 'Inicio',
                        'en' => 'Home'
                    ]
                ],
                'keywords' => [
                    'index' => [
                        'es' => 'Lorem, ipsum, dolor, sit, amet.',
                        'en' => 'Lorem, ipsum, dolor, sit, amet.'
                    ]
                ],
                'description' => [
                    'index' => [
                        'es' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commo.',
                        'en' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commo.'
                    ]
                ]
            ]
        ];

        if (!empty($subkey))
        {
            if (array_key_exists($subkey, $data[$option][$key]))
                return ($lang == true) ? $data[$option][$key][$subkey][Session::get_value('vkye_lang')] : $data[$option][$key][$subkey];
            else
                return '';
        }
        else
            return ($lang == true) ? $data[$option][$key][Session::get_value('vkye_lang')] : $data[$option][$key];
    }
}
