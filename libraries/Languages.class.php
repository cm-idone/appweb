<?php

defined('_EXEC') or die;

class Languages
{
    static public function email($key, $subkey = null)
    {
        $langs = [
            'your_token_is' => [
                'es' => 'Tu folio es',
                'en' => 'Your token is'
            ],
            'your_results_are_ready' => [
                'es' => 'Resultados Marbu Salud',
                'en' => 'Marbu Salud Results'
            ],
            'your_results_are_ready_text' => [
                'es' => '<strong>A quien corresponda</strong>. Por este medio le hacemos llegar los resultados de sus prueba de Covid. Sin mas por el momento quedamos en espera de su confirmación. Gracias.',
                'en' => '<strong>To whom it may concern</strong>. We hereby send you the results of your Covid tests. Without further ado for the moment we are waiting for your confirmation. Thank you.'
            ],
            'get_covid_results_1' => [
                'es' => 'Escanea el QR para ver tus resultados online, o da ',
                'en' => 'Scan the QR to see your results online, or give '
            ],
            'get_covid_results_2' => [
                'es' => '. También te hemos adjuntado un PDF con tus resultados.',
                'en' => '. We have also attached a PDF with your results.'
            ],
            'click_here' => [
                'es' => 'Click aquí',
                'en' => 'Click here'
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
