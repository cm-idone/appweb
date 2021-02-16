<?php

defined('_EXEC') or die;

class Languages
{
    static public function email($key, $subkey = null)
    {
        $langs = [
            '' => [
                'es' => '- - -',
                'en' => '- - -'
            ],
            'your_token_is' => [
                'es' => 'Tu folio es',
                'en' => 'Your token is'
            ],
            'your_results_are_ready' => [
                'es' => '¡Tus resultados están listos!',
                'en' => 'Your results are ready!'
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
            'result_report' => [
                'es' => 'Reporte de resultados',
                'en' => 'Results report'
            ],
            'marbu_laboratory_analisys' => [
                'es' => 'Laboratorio de análisis clinicos Marbu',
                'en' => 'Marbu clinical analysis laboratory'
            ],
            'n_petition' => [
                'es' => 'N. petición',
                'en' => 'N. petition'
            ],
            'years' => [
                'es' => 'años',
                'en' => 'years'
            ],
            'registry_date' => [
                'es' => 'Fecha de registro',
                'en' => 'Registry date'
            ],
            'company' => [
                'es' => 'Empresa',
                'en' => 'Company'
            ],
            'patient' => [
                'es' => 'Paciente',
                'en' => 'Patient'
            ],
            'birth_date' => [
                'es' => 'Fecha de nacimiento',
                'en' => 'Birth date'
            ],
            'age' => [
                'es' => 'Edad',
                'en' => 'Age'
            ],
            'sex' => [
                'es' => 'Sexo',
                'en' => 'Sex'
            ],
            'male' => [
                'es' => 'Masculino',
                'en' => 'Male'
            ],
            'female' => [
                'es' => 'Femenino',
                'en' => 'Female'
            ],
            'get_date' => [
                'es' => 'Fecha de toma',
                'en' => 'Get date'
            ],
            'get_hour' => [
                'es' => 'Hora de toma',
                'en' => 'Get hour'
            ],
            'start_process' => [
                'es' => 'Inicio de proceso',
                'en' => 'Start process'
            ],
            'end_process' => [
                'es' => 'Término de proceso',
                'en' => 'End process'
            ],
            'id_patient' => [
                'es' => 'ID de paciente',
                'en' => 'ID patient'
            ],
            'immunological_analysis' => [
                'es' => 'Análisis inmunológico',
                'en' => 'Immunological analysis'
            ],
            'exam' => [
                'es' => 'Exámen',
                'en' => 'Exam'
            ],
            'result' => [
                'es' => 'Resultado',
                'en' => 'Result'
            ],
            'unity' => [
                'es' => 'Unidad',
                'en' => 'Unity'
            ],
            'reference_values' => [
                'es' => 'Valor de referencia',
                'en' => 'Reference value'
            ],
            'positive' => [
                'es' => 'Detectado (Positivo)',
                'en' => 'Detected (Positive)'
            ],
            'negative' => [
                'es' => 'No detectado (Negativo)',
                'en' => 'No detected (Negative)'
            ],
            'INDEX' => [
                'es' => 'INDEX',
                'en' => 'INDEX'
            ],
            'detected' => [
                'es' => 'Detectado',
                'en' => 'Detected'
            ],
            'not_detected' => [
                'es' => 'No detectado',
                'en' => 'Not detected'
            ],
            'reactive' => [
                'es' => 'Reactivo',
                'en' => 'Reactive'
            ],
            'not_reactive' => [
                'es' => 'No reactivo',
                'en' => 'Not reactive'
            ],
            'anticorps' => [
                'es' => 'Anticuerpos',
                'en' => 'Anticorps'
            ],
            'nasopharynx_secretion' => [
                'es' => 'Muestra: Secrecion Nasofaríngea',
                'en' => 'Test: Nasopharynx Secretion'
            ],
            'notes' => [
                'es' => 'Notas',
                'en' => 'Notes'
            ],
            'notes_pcr_an_1' => [
                'es' => 'Un resultado “DETECTADO” (Positivo) indica la presencia de SARS-CoV-2 en el momento de la toma de la muestra biológica.',
                'en' => 'A “DETECTED” (Positive) result indicates the presence of SARS-CoV-2 at the time of biological sample collection.'
            ],
            'notes_pcr_an_2' => [
                'es' => 'Un resultado “NO DETECTADO” (Negativo) no descarta la posibilidad de infección por SARS-CoV-2 debido a factores como el periodo de incubación, variabilidad biológica, calidad de la toma de muestra; el conjunto de estos factores es reflejado en la expresión viral obtenida.',
                'en' => 'An “NOT DETECTED” (Negative) result does not rule out the possibility of SARS-CoV-2 infection due to factors such as incubation period, biological variability, quality of sample collection; all of these factors are reflected in the viral expression obtained.'
            ],
            'notes_pcr_an_3' => [
                'es' => 'Los resultados Detectados y No detectados serán notificados por MARBU laboratorio al instituto Diagnostico y Referencia (InDRE) siguiendo el Lineamiento Estandarizado para la Vigilancia Epidemiológica y por Laboratorio COVID-19.',
                'en' => 'The Detected and Undetected results will be notified by the MARBU laboratory to the Diagnostic and Reference Institute (InDRE) following the Standardized Guidelines for Epidemiological Surveillance and COVID-19 Laboratory.'
            ],
            'notes_ac_1' => [
                'es' => 'IgM (-)/ IgG (-): No hay evidencia de infección por SARS-CoV-2.',
                'en' => 'IgM (-)/ IgG (-): There is no evidence of infection by SARS-CoV-2.'
            ],
            'notes_ac_2' => [
                'es' => 'IgM (+)/ IgG (-): Probable infección sin reciente anticuerpos protectores.',
                'en' => 'IgM (+)/ IgG (-): Probable infection without recent protective antibodies.'
            ],
            'notes_ac_3' => [
                'es' => 'IgM (+)/ IgG (+): Probable infección reciente con anticuerpos en desarrollo.',
                'en' => 'IgM (+)/ IgG (+): Probable recent infection with developing antibodies.'
            ],
            'notes_ac_4' => [
                'es' => 'IgM (-)/ IgG (+): Probable infección pasada con anticuerpos protectores.',
                'en' => 'IgM (-)/ IgG (+): Probable past infection with protective antibodies.'
            ],
            'notes_ac_5' => [
                'es' => '* Tener anticuerpos protectores no excluye la posibilidad de una reinfección.',
                'en' => '* Having protective antibodies does not exclude the possibility of reinfection.'
            ],
            'valid_results_by' => [
                'es' => 'Resultados válidos por',
                'en' => 'Valid results by'
            ],
            'health_manager' => [
                'es' => 'Responsable sanitario',
                'en' => 'Healt manager'
            ],
            'identification_card' => [
                'es' => 'Cédula',
                'en' => 'Identification card'
            ],
            'atila_biosystem' => [
                'es' => 'Médoto: RT-PCR (Atila BioSystems)',
                'en' => 'Method: RT-PCR (Atila BioSystems)'
            ],
            'alert_pdf_covid' => [
                'es' => 'Este informe no podrá reproducirse parcialmente sin autorización del laboratorio que lo emite. Este documento se dirige a su destinatario y contiene información confidencial. Queda notificado que la utilización, divulgación y/o copias sin autorización está prohibido en virtud de la legislación vigente.',
                'en' => 'This report may not be partially reproduced without authorization from the issuing laboratory. This document is addressed to its recipient and contains confidential information. You are hereby notified that unauthorized use, disclosure and / or copies are prohibited under current legislation.'
            ],
            'accept_terms' => [
                'es' => 'La captura de datos fue responsabilidad del cliente. Marbu Salud S.A. de C.V. no se hace responsable por cualquier percance producido por la mal captura de estos datos. La corrección de datos tendrá un costo extra.',
                'en' => 'Data capture was responsibility of the customer. Marbu Salud S.A. de C.V. It is not responsible for any mishap produced by the wrong capture of this data. Data correction will cost extra.'
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
