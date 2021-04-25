<?php

defined('_EXEC') or die;

class Urls_registered_vkye
{
    static public $home_page_default = '/';

    static public function urls()
    {
        return [
            '/' => [
                'controller' => 'Index',
                'method' => 'index'
            ],
            '/login' => [
                'controller' => 'Login',
                'method' => 'index'
            ],
            '/dashboard' => [
                'controller' => 'Dashboard',
                'method' => 'index'
            ],
            '/laboratory/%param%' => [
                'controller' => 'Laboratory', // Pendiente
                'method' => 'index'
            ],
            '/laboratory/control' => [
                'controller' => 'Laboratory', // Pendiente
                'method' => 'control'
            ],
            '/laboratory/create/%param%/%param%' => [
                'controller' => 'Laboratory', // Pendiente
                'method' => 'create'
            ],
            '/laboratory/update/%param%' => [
                'controller' => 'Laboratory', // Pendiente
                'method' => 'update'
            ],
            '/%param%/authentication/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'authentication'
            ],
            '/%param%/record/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'record'
            ],
            '/%param%/record/%param%/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'record'
            ],
            '/%param%/results/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'results'
            ],
            '/employees' => [
                'controller' => 'Employees',
                'method' => 'index'
            ],
            '/employees/profile/%param%' => [
                'controller' => 'Employees', // Pendiente
                'method' => 'profile'
            ],
            '/locations' => [
                'controller' => 'Locations',
                'method' => 'index'
            ],
            '/system' => [
                'controller' => 'System',
                'method' => 'index'
            ]
        ];
    }
}
