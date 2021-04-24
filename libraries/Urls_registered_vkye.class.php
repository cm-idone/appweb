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
                'controller' => 'Laboratory',
                'method' => 'index'
            ],
            '/laboratory/control' => [
                'controller' => 'Laboratory',
                'method' => 'control'
            ],
            '/laboratory/create/%param%/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'create'
            ],
            '/laboratory/update/%param%' => [
                'controller' => 'Laboratory',
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
                'controller' => 'Employees',
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
