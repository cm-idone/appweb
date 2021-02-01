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
                'es' => 'Tu resultados están listos',
                'en' => 'Your results are ready'
            ],
            'your_results_are_ready_text' => [
                'es' => 'Hemos adjuntado un <strong>PDF</strong> con los resultados de tu exámen. Muchas gracias por confiar en nosotros.',
                'en' => 'We have attached a <strong>PDF</strong> with the results of your exam. Thank you very much for trusting us.'
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
