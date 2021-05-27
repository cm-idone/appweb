<?php

defined('_EXEC') or die;

class Languages
{
    static public function email($key, $subkey = null)
    {
        $langs = [
            '' => [
                'es' => '- - -',
                'en' => '- - -',
                'pr' => '- - -'
            ],
            'hi' => [
                'es' => 'Hola',
                'en' => 'Hi',
                'pr' => 'Olá'
            ],
            'your_token_is' => [
                'es' => 'Tu folio es',
                'en' => 'Your token is',
                'pr' => 'Seu fólio é'
            ],
            'your_results_next_email' => [
                'es' => 'Este es solo un correo electrónico de confirmación de tu registro. Tu resultados llegaran próximamente, en esta misma dirección, con tu certificado online y en PDF.',
                'en' => 'This is just a confirmation email of your registration. Your results will arrive soon, at this same address, with your online certificate and in PDF.',
                'pr' => 'Este é apenas um e-mail de confirmação do seu registro. Seus resultados chegarão em breve, neste mesmo endereço, com seu certificado online e em PDF.'
            ],
            'we_send_email_1' => [
                'es' => 'Te hemos enviado un correo electrónico a',
                'en' => 'We have sent you an email to',
                'pr' => 'Enviamos um e-mail para'
            ],
            'we_send_email_2' => [
                'es' => 'con la confirmación de tu registro. Puedes consultar tus resultados aquí',
                'en' => 'with the confirmation of your registration. You can check your results here',
                'pr' => 'com a confirmação do seu cadastro. Você pode verificar seus resultados aqui'
            ],
            'we_send_email_3' => [
                'es' => 'con tus resultados. Puedes consultar tus resultados aquí',
                'en' => 'with your results. You can check your results here',
                'pr' => 'com seus resultados. Você pode verificar seus resultados aqui'
            ],
            'your_results_are_ready' => [
                'es' => 'Tus resultados están listos',
                'en' => 'Your results are ready',
                'pr' => 'Seus resultados estão prontos'
            ],
            'ready_results' => [
                'es' => 'Resultados listos',
                'en' => 'Ready results',
                'pr' => 'Resultados prontos'
            ],
            'get_covid_results_1' => [
                'es' => 'Por este medio te hacemos llegar tus resultados de tu prueba de Covid. Puedes escanear el siguiente QR para consutar tus resultados en linea, o dar click en el enlace mas abajo. Igualmente te hemos adjuntado tu certificado como PDF. Este certificado tiene una validez de <strong>72 horas</strong>, a partir del día ',
                'en' => 'By this means we send you your results of your Covid test. You can scan the following QR to view your results online, or click on the link below. We have also attached your certificate as a PDF. This certificate is valid for <strong> 72 hours </strong>, from the day ',
                'pr' => 'Dessa forma, enviamos a você os resultados do seu teste Covid. Você pode escanear o seguinte QR para ver seus resultados online ou clicar no link abaixo. Também anexamos seu certificado em PDF. Este certificado é válido por <strong> 72 horas </strong>, a partir do dia'
            ],
            'get_covid_results_2' => [
                'es' => '. Sin mas por el momento quedamos en espera de su confirmación. Gracias.',
                'en' => '. Without further ado for the moment we are awaiting confirmation from him. Thank you.',
                'pr' => '. Sem mais delongas, por enquanto, estamos aguardando sua confirmação. Obrigada.'
            ],
            'view_online_results' => [
                'es' => 'Consulta tus resultados',
                'en' => 'Consult your results',
                'pr' => 'Consulte seus resultados'
            ],
            'result_report' => [
                'es' => 'Reporte de resultados',
                'en' => 'Results report',
                'pr' => 'Relatório de resultados'
            ],
            'laboratory_analisys' => [
                'es' => 'Laboratorio clinico',
                'en' => 'Clinical laboratory',
                'pr' => 'Laboratório clínico'
            ],
            'general_patient_data' => [
                'es' => 'Datos generales del paciente',
                'en' => 'General patient data',
                'pr' => 'Dados gerais do paciente'
            ],
            'name' => [
                'es' => 'Nombre',
                'en' => 'Name',
                'pr' => 'Nome'
            ],
            'id' => [
                'es' => 'ID',
                'en' => 'ID',
                'pr' => 'ID'
            ],
            'results' => [
                'es' => 'Resultados',
                'en' => 'Results',
                'pr' => 'Resultados'
            ],
            'n_petition' => [
                'es' => 'N. petición',
                'en' => 'N. petition',
                'pr' => 'N. petição'
            ],
            'years' => [
                'es' => 'años',
                'en' => 'years',
                'pr' => 'anos'
            ],
            'registry_date' => [
                'es' => 'Fecha de registro',
                'en' => 'Registry date',
                'pr' => 'Data de registro'
            ],
            'company' => [
                'es' => 'Empresa',
                'en' => 'Company',
                'pr' => 'Empresa'
            ],
            'patient' => [
                'es' => 'Paciente',
                'en' => 'Patient',
                'pr' => 'Paciente'
            ],
            'birth_date' => [
                'es' => 'Fecha de nacimiento',
                'en' => 'Birth date',
                'pr' => 'Data de nascimento'
            ],
            'age' => [
                'es' => 'Edad',
                'en' => 'Age',
                'pr' => 'Era'
            ],
            'sex' => [
                'es' => 'Sexo',
                'en' => 'Sex',
                'pr' => 'Sexo'
            ],
            'male' => [
                'es' => 'Masculino',
                'en' => 'Male',
                'pr' => 'Macho'
            ],
            'female' => [
                'es' => 'Femenino',
                'en' => 'Female',
                'pr' => 'Fêmea'
            ],
            'get_date' => [
                'es' => 'Fecha de toma',
                'en' => 'Get date',
                'pr' => 'Obter data'
            ],
            'get_hour' => [
                'es' => 'Hora de toma',
                'en' => 'Get hour',
                'pr' => 'Obter hora'
            ],
            'start_process' => [
                'es' => 'Inicio de proceso',
                'en' => 'Start process',
                'pr' => 'Iniciar o processo'
            ],
            'end_process' => [
                'es' => 'Término de proceso',
                'en' => 'End process',
                'pr' => 'Fim do processo'
            ],
            'id_patient' => [
                'es' => 'ID de paciente',
                'en' => 'ID patient',
                'pr' => 'Paciente ID'
            ],
            'immunological_analysis' => [
                'es' => 'Análisis inmunológico',
                'en' => 'Immunological analysis',
                'pr' => 'Análise imunológica'
            ],
            'exam' => [
                'es' => 'Exámen',
                'en' => 'Exam',
                'pr' => 'Exame'
            ],
            'result' => [
                'es' => 'Resultado',
                'en' => 'Result',
                'pr' => 'Resultado'
            ],
            'unity' => [
                'es' => 'Unidad',
                'en' => 'Unity',
                'pr' => 'Unidade'
            ],
            'reference_values' => [
                'es' => 'Valor de referencia',
                'en' => 'Reference value',
                'pr' => 'Valor de referência'
            ],
            'positive' => [
                'es' => 'Detectado (Positivo)',
                'en' => 'Detected (Positive)',
                'pr' => 'Detectou (Positivo)'
            ],
            'negative' => [
                'es' => 'No detectado (Negativo)',
                'en' => 'No detected (Negative)',
                'pr' => 'Não detectado (Negativo)'
            ],
            'INDEX' => [
                'es' => 'INDEX',
                'en' => 'INDEX',
                'pr' => 'ÍNDICE'
            ],
            'detected' => [
                'es' => 'Detectado',
                'en' => 'Detected',
                'pr' => 'Detectou'
            ],
            'not_detected' => [
                'es' => 'No detectado',
                'en' => 'Not detected',
                'pr' => 'Não detectado'
            ],
            'reactive' => [
                'es' => 'Reactivo',
                'en' => 'Reactive',
                'pr' => 'Reativo'
            ],
            'not_reactive' => [
                'es' => 'No reactivo',
                'en' => 'Not reactive',
                'pr' => 'Não reativo'
            ],
            'anticorps' => [
                'es' => 'Anticuerpos',
                'en' => 'Anticorps',
                'pr' => 'Anticorps'
            ],
            'nasopharynx_secretion' => [
                'es' => 'Secrecion Nasofaríngea',
                'en' => 'Nasopharynx Secretion',
                'pr' => 'Secreção de Nasofaringe'
            ],
            'sanguine' => [
                'es' => 'Sanguínea',
                'en' => 'Sanguine',
                'pr' => 'Sanguíneo'
            ],
            'test' => [
                'es' => 'Muestra',
                'en' => 'Test',
                'pr' => 'Teste'
            ],
            'notes' => [
                'es' => 'Notas',
                'en' => 'Notes',
                'pr' => 'Notas'
            ],
            'notes_pcr_an_1' => [
                'es' => 'Un resultado “DETECTADO” (Positivo) indica la presencia de SARS-CoV-2 en el momento de la toma de la muestra biológica.',
                'en' => 'A “DETECTED” (Positive) result indicates the presence of SARS-CoV-2 at the time of biological sample collection.',
                'pr' => 'Um resultado “DETECTADO” (Positivo) indica a presença de SARS-CoV-2 no momento da coleta da amostra biológica.'
            ],
            'notes_pcr_an_2' => [
                'es' => 'Un resultado “NO DETECTADO” (Negativo) no descarta la posibilidad de infección por SARS-CoV-2 debido a factores como el periodo de incubación, variabilidad biológica, calidad de la toma de muestra; el conjunto de estos factores es reflejado en la expresión viral obtenida.',
                'en' => 'An “NOT DETECTED” (Negative) result does not rule out the possibility of SARS-CoV-2 infection due to factors such as incubation period, biological variability, quality of sample collection; all of these factors are reflected in the viral expression obtained.',
                'pr' => 'Um resultado “NÃO DETECTADO” (Negativo) não descarta a possibilidade de infecção por SARS-CoV-2 devido a fatores como período de incubação, variabilidade biológica, qualidade da coleta de amostra; todos esses fatores se refletem na expressão viral obtida.'
            ],
            'notes_pcr_an_3' => [
                'es' => 'Los resultados Detectados y No detectados serán notificados al instituto Diagnostico y Referencia (InDRE) siguiendo el Lineamiento Estandarizado para la Vigilancia Epidemiológica y por Laboratorio COVID-19.',
                'en' => 'The Detected and Undetected results will be notified to the Diagnostic and Reference Institute (InDRE) following the Standardized Guidelines for Epidemiological Surveillance and COVID-19 Laboratory.',
                'pr' => 'Os resultados detectados e não detectados serão notificados ao Instituto de Diagnóstico e Referência (InDRE) de acordo com as Diretrizes Padronizadas para Vigilância Epidemiológica e Laboratório COVID-19.'
            ],
            'notes_ac_1' => [
                'es' => 'IgM (-)/ IgG (-): No hay evidencia de infección por SARS-CoV-2.',
                'en' => 'IgM (-)/ IgG (-): There is no evidence of infection by SARS-CoV-2.',
                'pr' => 'IgM (-) / IgG (-): Não há evidência de infecção por SARS-CoV-2.'
            ],
            'notes_ac_2' => [
                'es' => 'IgM (+)/ IgG (-): Probable infección sin reciente anticuerpos protectores.',
                'en' => 'IgM (+)/ IgG (-): Probable infection without recent protective antibodies.',
                'pr' => 'IgM (+) / IgG (-): Provável infecção sem anticorpos protetores recentes.'
            ],
            'notes_ac_3' => [
                'es' => 'IgM (+)/ IgG (+): Probable infección reciente con anticuerpos en desarrollo.',
                'en' => 'IgM (+)/ IgG (+): Probable recent infection with developing antibodies.',
                'pr' => 'IgM (+) / IgG (+): Provável infecção recente com desenvolvimento de anticorpos.'
            ],
            'notes_ac_4' => [
                'es' => 'IgM (-)/ IgG (+): Probable infección pasada con anticuerpos protectores.',
                'en' => 'IgM (-)/ IgG (+): Probable past infection with protective antibodies.',
                'pr' => 'IgM (-) / IgG (+): Provável infecção anterior com anticorpos protetores.'
            ],
            'notes_ac_5' => [
                'es' => '* Tener anticuerpos protectores no excluye la posibilidad de una reinfección.',
                'en' => '* Having protective antibodies does not exclude the possibility of reinfection.',
                'pr' => '* Ter anticorpos protetores não exclui a possibilidade de reinfecção.'
            ],
            'valid_results_by' => [
                'es' => 'Resultados válidos por',
                'en' => 'Valid results by',
                'pr' => 'Resultados válidos por'
            ],
            'health_manager' => [
                'es' => 'Responsable sanitario',
                'en' => 'Healt manager',
                'pr' => 'Gerente de saúde'
            ],
            'identification_card' => [
                'es' => 'Cédula',
                'en' => 'Identification card',
                'pr' => 'Carteira de identidade'
            ],
            'pcr_atila_biosystem' => [
                'es' => 'RT-PCR (Atila BioSystems)',
                'en' => 'RT-PCR (Atila BioSystems)',
                'pr' => 'RT-PCR (Atila BioSystems)'
            ],
            'an_atila_biosystem' => [
                'es' => 'Inmunocromatografia',
                'en' => 'Immunochromatography',
                'pr' => 'Imunocromatografia'
            ],
            'ac_atila_biosystem' => [
                'es' => 'Inmunocromatografia',
                'en' => 'Immunochromatography',
                'pr' => 'Imunocromatografia'
            ],
            'method' => [
                'es' => 'Médoto',
                'en' => 'Method',
                'pr' => 'Método'
            ],
            'alert_pdf_covid' => [
                'es' => 'Este informe no podrá reproducirse parcialmente sin autorización del laboratorio que lo emite. Este documento se dirige a su destinatario y contiene información confidencial. Queda notificado que la utilización, divulgación y/o copias sin autorización está prohibido en virtud de la legislación vigente.',
                'en' => 'This report may not be partially reproduced without authorization from the issuing laboratory. This document is addressed to its recipient and contains confidential information. You are hereby notified that unauthorized use, disclosure and / or copies are prohibited under current legislation.',
                'pr' => 'Este relatório não pode ser parcialmente reproduzido sem autorização do laboratório emissor. Este documento é dirigido ao seu destinatário e contém informações confidenciais. Você é informado por meio deste que o uso, divulgação e / ou cópias não autorizadas são proibidos pela legislação em vigor.'
            ],
            'accept_terms_1' => [
                'es' => 'La captura de datos fue responsabilidad del cliente.',
                'en' => 'Data capture was responsibility of the customer.',
                'pr' => 'A captura de dados era responsabilidade do cliente.'
            ],
            'accept_terms_2' => [
                'es' => 'no se hace responsable por cualquier percance producido por la mala captura de los mismos. La corrección de dichos datos tendrá un costo extra.',
                'en' => 'it is not responsible for any mishap produced by the bad capture of the same. The correction of said data will have an extra cost.',
                'pr' => 'não se responsabiliza por qualquer contratempo produzido pela má captura do mesmo. A correção desses dados terá um custo extra.'
            ],
            'our_proccess_available_1' => [
                'es' => 'Nuestros procesos están avaládos por el Dictamen Sanitario',
                'en' => 'Our processes are endorsed by the Sanitary Opinion',
                'pr' => 'Nossos processos são respaldados pelo Parecer Sanitário'
            ],
            'our_proccess_available_2' => [
                'es' => 'con el RFC',
                'en' => 'with RFC',
                'pr' => 'com RFC'
            ],
            'expedition_date' => [
                'es' => 'Fecha de expedición',
                'en' => 'Expedition date',
                'pr' => 'Data de expedição'
            ],
            'scan_to_security' => [
                'es' => 'Escanéame para verificación de seguridad',
                'en' => 'Scan me for security verification',
                'pr' => 'Me escaneie para verificação de segurança'
            ],
            'power_by' => [
                'es' => 'Power by',
                'en' => 'Power by',
                'pr' => 'Power by'
            ],
            'development_by' => [
                'es' => 'desarrollado por',
                'en' => 'development by',
                'pr' => 'desenvolvimento por'
            ],
            'security_form' => [
                'es' => 'Formulario de seguridad',
                'en' => 'Security form',
                'pr' => 'Formulário de segurança'
            ],
            'nationality' => [
                'es' => 'Nacionalidad',
                'en' => 'Nationality',
                'pr' => 'Nacionalidade'
            ],
            'travel_to' => [
                'es' => 'Viaja a',
                'en' => 'Travel to',
                'pr' => 'Viajar para'
            ],
            'are_you_pregnant' => [
                'es' => '¿Estás embarazada?',
                'en' => 'Are you pregnant?',
                'pr' => 'Você está grávida?'
            ],
            'yeah' => [
                'es' => 'Sí',
                'en' => 'Yes',
                'pr' => 'Sim'
            ],
            'not' => [
                'es' => 'No',
                'en' => 'Not',
                'pr' => 'Não'
            ],
            'are_you_symptoms' => [
                'es' => '¿En los últimos 14 días haz tenido alguno de los siguientes síntomas?',
                'en' => 'In the last 14 days, have you had any of the following symptoms?',
                'pr' => 'Nos últimos 14 dias, você teve algum dos seguintes sintomas?'
            ],
            'fever' => [
                'es' => 'Fiebre',
                'en' => 'Fever',
                'pr' => 'Febre'
            ],
            'eyes_pain' => [
                'es' => 'Dolor de ojos',
                'en' => 'Eyes pain',
                'pr' => 'Dor nos olhos'
            ],
            'torax_pain' => [
                'es' => 'Dolor de torax',
                'en' => 'Torax pain',
                'pr' => 'Dor de tórax'
            ],
            'muscles_pain' => [
                'es' => 'Dolor de músculos',
                'en' => 'Muscles pain',
                'pr' => 'Dores musculares'
            ],
            'head_pain' => [
                'es' => 'Dolo de cabeza',
                'en' => 'Head pain',
                'pr' => 'Dor de cabeça'
            ],
            'throat_pain' => [
                'es' => 'Dolor de garganta',
                'en' => 'Throat pain',
                'pr' => 'Dor de garganta'
            ],
            'knees_pain' => [
                'es' => 'Dolor de rodillas',
                'en' => 'Knees pain',
                'pr' => 'Dor nos joelhos'
            ],
            'ears_pain' => [
                'es' => 'Dolor de oídos',
                'en' => 'Ears pain',
                'pr' => 'Dor nas orelhas'
            ],
            'joints_pain' => [
                'es' => 'Dolor de articulaciones',
                'en' => 'Joints pain',
                'pr' => 'Dor nas articulações'
            ],
            'cough' => [
                'es' => 'Tos',
                'en' => 'Cough',
                'pr' => 'Tosse'
            ],
            'difficulty_breathing' => [
                'es' => 'Dificultad respiratoria',
                'en' => 'Breathing difficulty',
                'pr' => 'Dificuldade respiratória'
            ],
            'sweating' => [
                'es' => 'Transpiración',
                'en' => 'Sweating',
                'pr' => 'Suando'
            ],
            'runny_nose' => [
                'es' => 'Escurrimiento nasal',
                'en' => 'Runny nose',
                'pr' => 'Coriza'
            ],
            'itching' => [
                'es' => 'Comezón',
                'en' => 'Itching',
                'pr' => 'Coceira'
            ],
            'conjunctivitis' => [
                'es' => 'Conjuntivitis',
                'en' => 'Conjunctivitis',
                'pr' => 'Conjuntivite'
            ],
            'vomit' => [
                'es' => 'Vómito',
                'en' => 'Vomit',
                'pr' => 'Vomitar'
            ],
            'diarrhea' => [
                'es' => 'Diarrea',
                'en' => 'Diarrhea',
                'pr' => 'Diarréia'
            ],
            'smell_loss' => [
                'es' => 'Pérdida del olfato',
                'en' => 'Smell loss',
                'pr' => 'Perda de cheiro'
            ],
            'taste_loss' => [
                'es' => 'Pérdida del gusto',
                'en' => 'Taste loss',
                'pr' => 'Perda de sabor'
            ],
            'write_symptoms_time' => [
                'es' => '¿Hace cuanto tiempo empezaron tus síntomas?',
                'en' => 'How long ago your symptoms started?',
                'pr' => 'Há quanto tempo seus sintomas começaram?'
            ],
            'are_travel_prev' => [
                'es' => '¿Haz realizado viajes prévios a otros países?',
                'en' => 'Have you made previous trips to other countries?',
                'pr' => 'Você já fez viagens anteriores a outros países?'
            ],
            'are_contact_covid' => [
                'es' => '¿En los últimos 14 días haz tenido contácto con personas que han tenido cualquiera de los síntomas anteriores o que tenga sospecha de COVID-19?',
                'en' => 'In the last 14 days, have you had contact with people who have had any of the above symptoms or are suspected of COVID-19?',
                'pr' => 'Nos últimos 14 dias, você teve contato com pessoas que apresentaram algum dos sintomas acima ou são suspeitas de COVID-19?'
            ],
            'are_you_covid' => [
                'es' => '¿Haz tenido COVID-19?',
                'en' => 'Have you had COVID-19?',
                'pr' => 'Você já experimentou o COVID-19?'
            ],
            'email' => [
                'es' => 'Correo electrónico',
                'en' => 'Email',
                'pr' => 'Correio eletrônico'
            ],
            'phone' => [
                'es' => 'Teléfono',
                'en' => 'Phone',
                'pr' => 'Telefone'
            ],
            'responsability_signature' => [
                'es' => 'Firma de responsabilidad',
                'en' => 'Responsibility signature',
                'pr' => 'Assinatura de responsabilidade'
            ]
        ];

        return !empty($subkey) ? $langs[$key][$subkey] : $langs[$key];
    }
}
