<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.plugins}moment/moment.min.js']);
$this->dependencies->add(['js', '{$path.plugins}moment/moment-timezone-with-data.min.js']);
$this->dependencies->add(['css', '{$path.css}Covid/index.css?v=1.4']);
$this->dependencies->add(['js', '{$path.js}Covid/index.js?v=1.1']);

?>

<header class="covid">
    <div>
        <figure>
            <img src="{$path.images}marbu_logotype_color_circle.png">
        </figure>
        <h1>Marbu Salud S.A. de C.V.</h1>
    </div>
    <div>
        <h2>MSA1907259GA</h2>
        <h3>Av. Del Sol SM47 M6 L21 Planta Alta</h3>
        <h3>CP: 77506 Cancún, Qroo. México</h3>
        <?php if ($global['render'] == 'create' AND empty(System::temporal('get', 'covid', 'contact'))) : ?>
            <h3><a href="<?php echo Language::get_lang_url('es'); ?>"><img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/es.png"></a><a href="<?php echo Language::get_lang_url('en'); ?>"><img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/en.png"></a></h3>
        <?php endif; ?>
    </div>
</header>
<main class="covid">
    <?php if ($global['render'] == 'create') : ?>
        <?php if (empty(System::temporal('get', 'covid', 'contact'))) : ?>
            <form name="registry">
                <div class="share">
                    <a href="https://api.whatsapp.com/send?phone=<?php echo Configuration::$vars['marbu']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                    <a href="tel:<?php echo Configuration::$vars['marbu']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                    <a data-action="share" data-title="Marbu Salud" data-text="{$lang.share_registry}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['account']['path']; ?>/covid"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                </div>
                <h2>¡{$lang.registry_now}!</h2>
                <h3>{$lang.covid_test}</h3>
                <div class="accept_terms">
                    <div class="caption">
                        <p>{$lang.accept_terms}</p>
                    </div>
                    <div class="checkbox st-1">
                        <label>
                            <span>{$lang.accept}</span>
                            <input type="checkbox" name="accept_terms">
                        </label>
                    </div>
                </div>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="firstname">
                            </div>
                            <div class="title">
                                <h6>{$lang.firstname} (s)</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="lastname">
                            </div>
                            <div class="title">
                                <h6>{$lang.lastname} (s)</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="ife">
                            </div>
                            <div class="title">
                                <h6>{$lang.passport}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="compound st-10">
                                <select name="birth_date_year">
                                    <option value="" class="hidden">{$lang.year}</option>
                                    <?php foreach (Dates::create_lapse_date('years', 100) as $value) : ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="birth_date_month">
                                    <option value="" class="hidden">{$lang.month}</option>
                                    <?php foreach (Dates::create_lapse_date('months', Session::get_value('vkye_lang')) as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="birth_date_day">
                                    <option value="" class="hidden">{$lang.day}</option>
                                    <?php foreach (Dates::create_lapse_date('days') as $value) : ?>
                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.birth_date}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="age">
                            </div>
                            <div class="title">
                                <h6>{$lang.age}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="sex">
                                    <option value="" class="hidden">{$lang.choose_an_option}</option>
                                    <option value="male">{$lang.male}</option>
                                    <option value="female">{$lang.female}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.sex}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <?php if ($global['account']['path'] != 'moonpalace'): ?>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <input type="email" name="email">
                                </div>
                                <div class="title">
                                    <h6>{$lang.email}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="compound st-1-left">
                                    <select name="phone_country">
                                        <option value="" class="hidden">{$lang.country}</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')] . ' +' . $value['lada']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="number" name="phone_number" placeholder="{$lang.number}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="travel_to">
                                </div>
                                <div class="title">
                                    <h6>{$lang.where_you_travel}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="type">
                                <option value="" class="hidden">{$lang.choose_an_option}</option>
                                <option value="covid_pcr">PCR (PCR-SARS-CoV-2 (COVID-19))</option>
                                <option value="covid_an">{$lang.antigen} (Ag-SARS-CoV-2 (COVID-19))</option>
                                <option value="covid_ac">{$lang.anticorps} (SARS-CoV-2 (2019) IgG/IgM)</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.test_to_do}</h6>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group">
                    <div class="button">
                        <button type="submit" class="success">{$lang.end_and_send}</button>
                    </div>
                </fieldset>
            </form>
        <?php else : ?>
            <div class="create">
                <h4>{$lang.your_token_is}: <?php echo System::temporal('get', 'covid', 'contact')['token']; ?></h4>
                <?php if ($global['account']['path'] != 'moonpalace') : ?>
                    <p>¡{$lang.hi} <strong><?php echo explode(' ', System::temporal('get', 'covid', 'contact')['firstname'])[0]; ?></strong>! {$lang.covid_alert_1} <strong><?php echo System::temporal('get', 'covid', 'contact')['email']; ?></strong> {$lang.covid_alert_2}</p>
                    <div class="share">
                        <div>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo Configuration::$vars['marbu']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                            <a href="tel:<?php echo Configuration::$vars['marbu']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                            <a data-action="share" data-title="Marbu Salud" data-text="{$lang.share_registry}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['account']['path']; ?>/covid"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                        </div>
                        <div>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo Configuration::$vars['marbu']['phone']; ?>&text=Hola, soy <?php echo System::temporal('get', 'covid', 'contact')['firstname'] . ' ' . System::temporal('get', 'covid', 'contact')['lastname']; ?>. Me gustaría agendar mi cita. Ya he registrado mis datos. Mi folio es: <?php echo System::temporal('get', 'covid', 'contact')['token']; ?>" target="_blank"><i class="fas fa-headset"></i><span>{$lang.share_us_your_token}</span></a>
                            <a data-action="share" data-title="Marbu Salud" data-text="{$lang.share_token} <?php echo System::temporal('get', 'covid', 'contact')['token']; ?>" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['account']['path']; ?>/covid/<?php echo System::temporal('get', 'covid', 'contact')['token']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_token_with_friends}</span></a>
                        </div>
                    </div>
                    <figure>
                        <img src="{$path.uploads}<?php echo System::temporal('get', 'covid', 'contact')['qr']['filename']; ?>">
                    </figure>
                <?php endif; ?>
                <a data-action="restore_registry">{$lang.restore_registry}</a>
            </div>
        <?php endif; ?>
    <?php elseif ($global['render'] == 'results' AND $global['account']['path'] != 'moonpalace') : ?>
        <div class="results">
            <h2><?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? (($global['custody_chain']['closed'] == true) ? '¡{$lang.ready_results}!' : '{$lang.results_in_process}') : '{$lang.expired_results}'); ?></h2>
            <h3>{$lang.covid_test}</h3>
            <div class="title">
                <i class="fas fa-qrcode"></i>
                <h2><strong>{$lang.type}: {$lang.<?php echo $global['custody_chain']['type']; ?>} </strong>{$lang.token}: <?php echo $global['custody_chain']['token']; ?></h2>
            </div>
            <div class="counter">
                <h3 id="counter" class="<?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? 'time_on' : 'time_out'); ?>" data-date="<?php echo Dates::future_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), 72, 'hours'); ?>" data-time-zone="<?php echo $global['account']['time_zone']; ?>"></h3>
                <h2><?php echo Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour'], 'long', '12-long'); ?></h2>
                <h4><?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? '{$lang.72_validation}' : '{$lang.certificate_timed_out}'); ?></h4>
            </div>
            <table>
                <?php if ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') : ?>
                    <tr>
                        <td>{$lang.exam}:</td>
                        <?php if ($global['custody_chain']['type'] == 'covid_pcr') : ?>
                            <td>PCR-SARS-CoV-2 (COVID-19)</td>
                        <?php elseif ($global['custody_chain']['type'] == 'covid_an') : ?>
                            <td>Ag-SARS-CoV-2 (COVID-19)</td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td>{$lang.start_process}:</td>
                        <td><?php echo Dates::format_date($global['custody_chain']['start_process'], 'long'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.end_process}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? Dates::format_date($global['custody_chain']['end_process'], 'long') : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.result}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['result'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.unity}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['unity'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.reference_values}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['reference_values'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                <?php elseif ($global['custody_chain']['type'] == 'covid_ac') : ?>
                    <tr>
                        <td>{$lang.exam}:</td>
                        <td>SARS-CoV-2 (2019) IgG/IgM</td>
                    </tr>
                    <tr>
                        <td>{$lang.start_process}:</td>
                        <td><?php echo Dates::format_date($global['custody_chain']['start_process'], 'long'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.end_process}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? Dates::format_date($global['custody_chain']['end_process'], 'long') : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"><strong>{$lang.anticorps} IgM</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>{$lang.result}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['igm']['result'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.unity}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? (!empty($global['custody_chain']['results']['igm']['unity']) ? '{$lang.' . $global['custody_chain']['results']['igm']['unity'] . '}' : '- - -') : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.reference_values}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['igm']['reference_values'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;"><strong>{$lang.anticorps} IgG</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>{$lang.result}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['igg']['result'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.unity}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? (!empty($global['custody_chain']['results']['igg']['unity']) ? '{$lang.' . $global['custody_chain']['results']['igg']['unity'] . '}' : '- - -') : '{$lang.results_in_process}'); ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.reference_values}:</td>
                        <td><?php echo (($global['custody_chain']['closed'] == true) ? '{$lang.' . $global['custody_chain']['results']['igg']['reference_values'] . '}' : '{$lang.results_in_process}'); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
            <?php if (!empty($global['custody_chain']['comments'])) : ?>
                <p>{$lang.comments}: <?php echo (!empty($global['custody_chain']['comments']) ? $global['custody_chain']['comments'] : '{$lang.not_comments}'); ?></p>
            <?php endif; ?>
            <table>
                <tr>
                    <td>{$lang.name}:</td>
                    <td><?php echo $global['custody_chain']['contact']['firstname'] . ' ' . $global['custody_chain']['contact']['lastname']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.passport}:</td>
                    <td><?php echo $global['custody_chain']['contact']['ife']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.birth_date}:</td>
                    <td><?php echo Dates::format_date($global['custody_chain']['contact']['birth_date'], 'long'); ?></td>
                </tr>
                <tr>
                    <td>{$lang.age}:</td>
                    <td><?php echo $global['custody_chain']['contact']['age']; ?> {$lang.years}</td>
                </tr>
                <tr>
                    <td>{$lang.sex}:</td>
                    <td>{$lang.<?php echo $global['custody_chain']['contact']['sex']; ?>}</td>
                </tr>
                <tr>
                    <td>{$lang.email}:</td>
                    <td><?php echo $global['custody_chain']['contact']['email']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.phone}:</td>
                    <td>+<?php echo $global['custody_chain']['contact']['phone']['country'] . ' ' . $global['custody_chain']['contact']['phone']['number']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.travel_to}:</td>
                    <td><?php echo $global['custody_chain']['contact']['travel_to']; ?></td>
                </tr>
            </table>
            <?php if ($global['custody_chain']['closed'] == true) : ?>
                <div class="share">
                    <div>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo Configuration::$vars['marbu']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                        <a href="tel:<?php echo Configuration::$vars['marbu']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                        <a data-action="share" data-title="Marbu Salud" data-text="{$lang.share_registry}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['account']['path']; ?>/covid"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                    </div>
                    <div>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo Configuration::$vars['marbu']['phone']; ?>&text=Hola, soy <?php echo $global['custody_chain']['contact']['firstname'] . ' ' . $global['custody_chain']['contact']['lastname']; ?>. Mi folio es: <?php echo $global['custody_chain']['token']; ?>" target="_blank"><i class="fas fa-headset"></i><span>{$lang.whatsapp_us_to_support}</span></a>
                        <a data-action="share" data-title="Marbu Salud" data-text="{$lang.share_results} <?php echo $global['custody_chain']['token']; ?>" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['account']['path']; ?>/covid/<?php echo $global['custody_chain']['token']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_token_with_friends}</span></a>
                    </div>
                </div>
                <?php if (Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) : ?>
                    <figure>
                        <img src="{$path.uploads}<?php echo $global['custody_chain']['qr']; ?>">
                    </figure>
                    <a href="{$path.uploads}<?php echo $global['custody_chain']['pdf']; ?>" download="certificate.pdf">{$lang.download_certificate_pdf}</a>
                <?php endif; ?>
                <div class="collector">
                    <figure>
                        <img src="{$path.uploads}<?php echo $global['custody_chain']['collector_signature']; ?>">
                    </figure>
                    <h2><?php echo $global['custody_chain']['collector_name']; ?></h2>
                    <h3>{$lang.this_certificate_available_by}</h3>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>
<footer class="covid">
    <div>
        <a href="https://api.whatsapp.com/send?phone=<?php echo Configuration::$vars['marbu']['phone']; ?>"><i class="fab fa-whatsapp"></i><?php echo Configuration::$vars['marbu']['phone']; ?></a>
        <a href="tel:<?php echo Configuration::$vars['marbu']['phone']; ?>"><i class="fas fa-phone"></i><?php echo Configuration::$vars['marbu']['phone']; ?></a>
        <a href="mailto:<?php echo Configuration::$vars['marbu']['email']; ?>"><i class="fas fa-envelope"></i><?php echo Configuration::$vars['marbu']['email']; ?></a>
        <a href="https://facebook.com/<?php echo Configuration::$vars['marbu']['facebook']; ?>" target="_blank"><i class="fab fa-facebook-square"></i>@<?php echo Configuration::$vars['marbu']['facebook']; ?></a>
        <a href="https://linkedin.com/company/<?php echo Configuration::$vars['marbu']['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin"></i>@<?php echo Configuration::$vars['marbu']['linkedin']; ?></a>
        <a href="https://<?php echo Configuration::$vars['marbu']['website']; ?>" target="_blank"><i class="fas fa-globe"></i><?php echo Configuration::$vars['marbu']['website']; ?></a>
    </div>
    <div>
        <a href="https://id.one-consultores.com" target="_blank">{$lang.power_by} <strong><?php echo Configuration::$web_page . ' ' . Configuration::$web_version; ?></strong></a>
        <a href="https://one-consultores.com" target="_blank">Copyright <i class="far fa-copyright"></i> One Consultores</a>
        <a href="https://codemonkey.com.mx" target="_blank">Software {$lang.development_by} Code Monkey</a>
    </div>
</footer>
