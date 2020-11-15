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
            '/system' => [
                'controller' => 'System',
                'method' => 'index'
            ],
            '/dashboard' => [
                'controller' => 'Dashboard',
                'method' => 'index'
            ],
            '/laboratory' => [
                'controller' => 'Laboratory',
                'method' => 'index'
            ],
            '/laboratory/create/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'create'
            ],
            '/laboratory/create/%param%/%param%' => [
                'controller' => 'Laboratory',
                'method' => 'create'
            ],
            '/laboratory/custodychains' => [
                'controller' => 'Laboratory',
                'method' => 'custodychains'
            ],
            '/employees' => [
                'controller' => 'Employees',
                'method' => 'index'
            ],
            '/%param%/%param%' => [
                'controller' => 'Employees',
                'method' => 'Scanner'
            ]
        ];
    }
}
