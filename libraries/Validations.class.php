<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para validaciones.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Validations
{
    /**
    * @summary: Valida que un valor este establecido y no vacío.
    *
    * @param string $data: Variable a validar.
    *
    * @return boolean
    */
    public static function empty($data, $array = false)
    {
        if ($array == true)
        {
            $check = true;
            $count = 0;

            foreach ($data as $value)
            {
                if (!isset($value) OR empty($value))
                {
                    $check = false;
                    $count = $count + 1;
                }
            }

            $check = ($count == count($data)) ? true : $check;
        }
        else
            $check = (isset($data) AND !empty($data)) ? true : false;

        return $check;
    }

    /**
    * @summary: Valida que un valor este establecido y no vacío.
    *
    * @param string $data: Variable a validar.
    * @param boolean $group: Grupo de valores permitidos.
    *
    * @return boolean
    */
    public static function equals($data, $group)
    {
        $check = false;

        if (is_array($group))
        {
            foreach ($group as $value)
            {
                if ($data == $value)
                    $check = true;
            }
        }
        else
        {
            if ($data == $group)
                $check = true;
        }

        return $check;
    }

    /**
    * @summary: Valida que una cadena de texto no contenga caracteres no permitidos.
    *
    * @param string-array $option: (uppercase, lowercase, int, float) Tipo de opcion(es) permitidas.
    * @param string $data: Cadena de texto a validar.
    * @param string $empty: Identificador para saber si la cadena de texto está vacia, pueda regresar en positivo.
    *
    * @return boolean
    */
    public static function string($option, $data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = true;

        if ($break == false)
        {
            $filter = ' ';
            $uppercase = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
            $lowercase = 'abcdefghijklmnñopqrstuvwxyz';
            $int = '0123456789';
            $float = '.';

            if (is_array($option))
            {
                foreach ($option as $value)
                {
                    if ($value == 'uppercase')
                        $filter .= $uppercase;
                    else if ($value == 'lowercase')
                        $filter .= $lowercase;
                    else if ($value == 'int')
                        $filter .= $int;
                    else if ($value == 'float')
                        $filter .= $float . $int;
                }
            }
            else if ($option == 'uppercase')
                $filter = $uppercase;
            else if ($option == 'lowercase')
                $filter = $lowercase;
            else if ($option == 'int')
                $filter = $int;
            else if ($option == 'float')
                $filter = $float . $int;

            for ($i = 0; $i < strlen($data); $i++)
            {
                if (strpos($filter, substr($data, $i, 1)) == false)
                    $check = false;
            }
        }

        return $check;
    }

    /**
    * @summary: Valida que un número entero o flotante.
    *
    * @param string $option: (int, float) Tipo de número.
    * @param string $data: Número a validar.
    * @param string $empty: Identificador para saber si la cadena de texto está vacia, pueda regresar en positivo.
    *
    * @return boolean
    */
    public static function number($option, $data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = true;

        if ($break == false)
        {
            if ($option == 'int')
                $data = (int) $data;
            else if ($option == 'float')
                $data = (float) $data;

            if (!is_numeric($data))
                $check = false;
            else if ($option == 'int' AND !is_int($data))
                $check = false;
            else if ($option == 'float' AND !is_float($data))
                $check = false;
            else if ($data < 1)
                $check = false;
        }

        return $check;
    }

    /**
    * @summary: Valida que un correo electrónico sea correcto.
    *
    * @param string $data: Correo electrónico a validar.
    * @param string $empty: Identificador para saber si la cadena de texto está vacia, pueda regresar en positivo.
    *
    * @return boolean
    */
    public static function email($data, $empty = false)
    {
        $break = ($empty == true AND !isset($data) OR $empty == true AND empty($data)) ? true : false;
        $check = ($break == true) ? true : ((filter_var($data, FILTER_VALIDATE_EMAIL)) ? true : false);

        return $check;
    }
}
