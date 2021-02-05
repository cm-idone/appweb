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
            'your_results_are_ready' => [
                'es' => 'Resultados Marbu Laboratorio',
                'en' => 'Marbu Laboratory Results'
            ],
            'your_results_are_ready_text' => [
                'es' => '<strong>A quien corresponda</strong>. Por este medio le hacemos llegar los resultados de sus prueba de Covid. Sin mas por el momento quedamos en espera de su confirmación. Gracias.',
                'en' => '<strong>To whom it may concern</strong>. We hereby send you the results of your Covid tests. Without further ado for the moment we are waiting for your confirmation. Thank you.'
            ],
            'call' => [
                'es' => 'Llamada',
                'en' => 'Call'
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
