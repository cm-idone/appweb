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
            '/%param%/covid' => [
                'controller' => 'Covid',
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
            '/laboratory/marbu' => [
                'controller' => 'Laboratory',
                'method' => 'marbu'
            ],
            '/laboratory/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'index'
            ],
            '/laboratory/create/%param%/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'create'
            ],
            '/laboratory/update/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'update'
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
