<?php

defined('_EXEC') or die;

class Languages
{
    static public function email($key, $subkey = null)
    {
        $langs = [
            'laboratory' => [
                'es' => 'Laboratorio',
                'en' => 'Laboratory'
            ],
            'your_token_is' => [
                'es' => 'Tu folio es',
                'en' => 'Your token is'
            ],
            'power_by' => [
                'es' => 'Power by',
                'en' => 'Power by'
            ],
            'development_by' => [
                'es' => 'desarrollado por',
                'en' => 'development by'
            ]
        ];

        return !empty($subkey) ? $langs[$key][$subkey] : $langs[$key];
    }
}
