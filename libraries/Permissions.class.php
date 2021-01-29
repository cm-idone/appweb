<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para revisar los permisos de acceso a los módulos y funcionalidades de sistema.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 08 de marzo, 2020.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Permissions
{
    /**
    * @summary Valida los permisos de acceso de la url deseada deacuerdo a los permisos del usuario logueado ó de la cuenta en linea.
    *
    * @param string $option: Tipo de permiso a validar.
    * @param string $path: Url a validar.
    *
    * @return boolean
    */
    static public function urls($option, $path)
    {
        $access = false;
        $paths = [];

        if ($option == 'account')
        {
            if (!empty(Session::get_value('vkye_account')))
            {
                if (Session::get_value('vkye_account')['status'] == true)
                {
                    array_push($paths, '/Dashboard/index');
                    array_push($paths, '/System/index');

                    foreach (Session::get_value('vkye_account')['permissions'] as $key => $value)
                    {
                        switch ($value)
                        {
                            case 'laboratory' :
                                array_push($paths, '/Laboratory/index');
                                array_push($paths, '/Laboratory/create');
                                array_push($paths, '/Employees/index');
                                array_push($paths, '/Employees/profile');
                                array_push($paths, '/Locations/index');
                                break;

                            default:
                                break;
                        }
                    }
                }
            }

            $paths = array_unique($paths);
            $paths = array_values($paths);
            $access = in_array($path, $paths) ? true : false;
        }
        else if ($option == 'user')
        {
            if (Session::get_value('vkye_account')['type'] == 'business')
            {
                if (Session::get_value('vkye_user')['permissions'] != 'all')
                {
                    array_push($paths, '/Dashboard/index');
                    array_push($paths, '/System/index');

                    foreach (Session::get_value('vkye_user')['permissions'] as $key => $value)
                    {
                        switch ($value)
                        {
                            case 'control_laboratory' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'create_alcoholic' :
                                array_push($paths, '/Laboratory/create');
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'update_alcoholic' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'delete_alcoholic' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'create_antidoping' :
                                array_push($paths, '/Laboratory/create');
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'update_antidoping' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'delete_antidoping' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'create_covid' :
                                array_push($paths, '/Laboratory/create');
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'update_covid' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'delete_covid' :
                                array_push($paths, '/Laboratory/index');
                                break;

                            case 'create_employees' :
                                array_push($paths, '/Employees/index');
                                break;

                            case 'update_employees' :
                                array_push($paths, '/Employees/index');
                                break;

                            case 'control_employees' :
                                array_push($paths, '/Employees/index');
                                array_push($paths, '/Employees/profile');
                                break;

                            case 'block_employees' :
                                array_push($paths, '/Employees/index');
                                break;

                            case 'unblock_employees' :
                                array_push($paths, '/Employees/index');
                                break;

                            case 'delete_employees' :
                                array_push($paths, '/Employees/index');
                                break;

                            case 'create_locations' :
                                array_push($paths, '/Locations/index');
                                break;

                            case 'update_locations' :
                                array_push($paths, '/Locations/index');
                                break;

                            case 'block_locations' :
                                array_push($paths, '/Locations/index');
                                break;

                            case 'unblock_locations' :
                                array_push($paths, '/Locations/index');
                                break;

                            case 'delete_locations' :
                                array_push($paths, '/Locations/index');
                                break;

                            default:
                                break;
                        }
                    }

                    $paths = array_unique($paths);
                    $paths = array_values($paths);
                    $access = in_array($path, $paths) ? true : false;
                }
                else
                    $access = true;
            }
            else
                $access = true;
        }

        return $access;
    }

    /**
    * @summary Revisa los permisos de acceso de la cuenta en linea.
    *
    * @param array $data: Códigos de los permisos permitidos.
    *
    * @return boolean
    */
    static public function account($data)
    {
        $access = false;

        if (!empty(Session::get_value('vkye_account')))
        {
            foreach ($data as $value)
            {
                if (in_array($value, Session::get_value('vkye_account')['permissions']))
                    $access = true;
            }
        }

        return $access;
    }

    /**
    * @summary Revisa los permisos de acceso del usuario logueado.
    *
    * @param array $data: Códigos de los permisos permitidos.
    * @param boolean $group: Identificador para saber si se van a validar un permiso único o un grupo de permisos.
    *
    * @return boolean
    */
    static public function user($data, $group = false)
    {
        $access = false;

        if (Session::get_value('vkye_account')['type'] == 'business')
        {
            if (Session::get_value('vkye_user')['permissions'] != 'all')
            {
                foreach ($data as $value)
                {
                    if ($group == true)
                    {
                        foreach (Session::get_value('vkye_user')['permissions'] as $subvalue)
                        {
                            $subvalue = explode('_', $subvalue, 2);

                            if ($value == $subvalue[1])
                                $access = true;
                        }
                    }
                    else
                    {
                        if (in_array($value, Session::get_value('vkye_user')['permissions']))
                            $access = true;
                    }
                }
            }
            else
                $access = true;
        }
        else
            $access = true;

        return $access;
    }

    /**
    * @summary Redirige a la url corresponidiente de acuerdo a los permisos de acceso del usuario logueado y la cuenta en linea.
    *
    * @return string
    */
    static public function redirection($param = false)
    {
        if (Session::exists_var('session') == true)
        {
            $path = '/dashboard';

            if (Session::exists_var('uri') == true)
            {
                $path = Session::get_value('uri');

                Session::unset_value('uri');
            }

            if (!empty($param))
            {
                if (is_string($param))
                    header('Location: /' . $param);
                else if ($param == true)
                    return $path;
            }
            else
                header('Location: ' . $path);
        }
    }
}
