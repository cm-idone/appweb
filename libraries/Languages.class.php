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
                'es' => 'Resultados de prueba Covid',
                'en' => 'Covid test results'
            ],
            'get_covid_results_1' => [
                'es' => '<strong>A quien corresponda</strong>. Por este medio le hacemos llegar los resultados de sus prueba de Covid. Puedes escanera el siguiente QR para consutar tus resultados en linea, o dar ',
                'en' => '<strong>To whom it May concern</strong>. We hereby send you the results of your Covid tests. You can scan the following QR to view your results online, or give'
            ],
            'get_covid_results_2' => [
                'es' => '. Igualmente te hemos adjuntado tu certificado como PDF en este correo. Este certificado tiene una validez de <strong>72 horas</strong>, a partir del día ',
                'en' => '. We have also attached your certificate as a PDF in this email. This certificate is valid for <strong>72 hours</strong>, starting from the day '
            ],
            'get_covid_results_3' => [
                'es' => '. Sin mas por el momento quedamos en espera de su confirmación. Gracias.',
                'en' => '. Without further ado for the moment we are awaiting confirmation from him. Thank you.'
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
