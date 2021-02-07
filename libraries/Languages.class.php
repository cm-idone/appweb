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
                'es' => 'Resultados listos',
                'en' => 'Ready results'
            ],
            'covid_test' => [
                'es' => 'Prueba Covid',
                'en' => 'Covid Test'
            ],
            'get_covid_results_1' => [
                'es' => '<strong>A quien corresponda</strong>. Por este medio le hacemos llegar los resultados de su prueba de Covid. Puedes escanera el siguiente QR para consutar tus resultados en linea, o dar click en el enlace mas abajo. Igualmente te hemos adjuntado tu certificado como PDF en este correo. Este certificado tiene una validez de <strong>72 horas</strong>, a partir del día ',
                'en' => '<strong>To whom it May concern</strong>. We hereby send you the results of your Covid test. You can scan the following QR to view your results online, or click on the link below. We have also attached your certificate as a PDF in this email. This certificate is valid for <strong> 72 hours </strong>, from the day '
            ],
            'get_covid_results_2' => [
                'es' => '. Sin mas por el momento quedamos en espera de su confirmación. Gracias.',
                'en' => '. Without further ado for the moment we are awaiting confirmation from him. Thank you.'
            ],
            'view_online_results' => [
                'es' => 'Ver resultados online',
                'en' => 'View online results'
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
