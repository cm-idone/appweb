<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.plugins}moment/moment.min.js']);
$this->dependencies->add(['js', '{$path.plugins}moment/moment-timezone-with-data.min.js']);
$this->dependencies->add(['js', '{$path.js}Laboratory/results.js?v=1.0']);

?>

<header class="laboratory">
    <div style="background-color:<?php echo $global['laboratory']['colors']['first']; ?>;">
        <figure>
            <img src="{$path.uploads}<?php echo $global['laboratory']['avatar']; ?>">
        </figure>
        <h1><?php echo $global['laboratory']['business']; ?></h1>
    </div>
    <div>
        <h2 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['rfc']; ?></h2>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['first']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['second']; ?></h3>
    </div>
</header>
<main class="laboratory">
    <div class="results">
        <?php if ($global['custody_chain']['type'] == 'alcoholic') : ?>

        <?php elseif ($global['custody_chain']['type'] == 'antidoping') : ?>

        <?php elseif ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') : ?>
            <h2><?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? (($global['custody_chain']['closed'] == true) ? '¡{$lang.ready_results}!' : '{$lang.results_in_process}') : '{$lang.expired_results}'); ?></h2>
            <h3>{$lang.covid_test}</h3>
            <div class="title">
                <i class="fas fa-qrcode"></i>
                <h2><strong>{$lang.type}: {$lang.<?php echo $global['custody_chain']['type']; ?>} </strong>{$lang.token}: <?php echo $global['custody_chain']['token']; ?></h2>
            </div>
            <div class="counter">
                <h3 id="counter" class="<?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? 'time_on' : 'time_out'); ?>" data-date="<?php echo Dates::future_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), 72, 'hours'); ?>" data-time-zone="<?php echo $global['laboratory']['time_zone']; ?>"></h3>
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
                    <td>{$lang.sex}:</td>
                    <td>{$lang.<?php echo $global['custody_chain']['contact']['sex']; ?>}</td>
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
                    <td>{$lang.nationality}:</td>
                    <td><?php echo $global['custody_chain']['contact']['nationality']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.passport}:</td>
                    <td><?php echo $global['custody_chain']['contact']['ife']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.email}:</td>
                    <td><?php echo $global['custody_chain']['contact']['email']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.phone}:</td>
                    <td>+<?php echo $global['custody_chain']['contact']['phone']['country'] . ' ' . $global['custody_chain']['contact']['phone']['number']; ?></td>
                </tr>
                <?php if ($global['custody_chain']['contact']['sex'] == 'female') : ?>
                    <tr>
                        <td>{$lang.pregnant}:</td>
                        <td>{$lang.<?php echo $global['custody_chain']['contact']['sf']['pregnant']; ?>}</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>{$lang.symptoms}:</td>
                    <td>
                        <?php if (!empty($global['custody_chain']['contact']['sf']['symptoms'])) : ?>
                            <?php foreach ($global['custody_chain']['contact']['sf']['symptoms'] as $value) : ?>
                                {$lang.<?php echo $value; ?>}<br>
                            <?php endforeach; ?>
                        <?php else : ?>
                            {$lang.nothing}
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>{$lang.symptoms_time}:</td>
                    <td><?php echo $global['custody_chain']['contact']['sf']['symptoms_time']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.prev_travel}:</td>
                    <td><?php echo (($global['custody_chain']['contact']['sf']['travel'] == 'yeah') ? $global['custody_chain']['contact']['sf']['travel_countries'] : '{$lang.not}') ?></td>
                </tr>
                <tr>
                    <td>{$lang.covid}:</td>
                    <td>{$lang.<?php echo $global['custody_chain']['contact']['sf']['covid']; ?>}</td>
                </tr>
                <tr>
                    <td>{$lang.covid_time}:</td>
                    <td><?php echo $global['custody_chain']['contact']['sf']['covid_time']; ?></td>
                </tr>
            </table>
            <?php if ($global['custody_chain']['closed'] == true) : ?>
                <?php if (Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) : ?>
                    <figure>
                        <img src="{$path.uploads}<?php echo $global['custody_chain']['qr']; ?>">
                    </figure>
                <?php endif; ?>
                <div class="share">
                    <div>
                        <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.share_results}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['laboratory']['path']; ?>/results/<?php echo $global['custody_chain']['token']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_results_with_friends}</span></a>
                    </div>
                    <div>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                        <a href="tel:<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                        <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.know_our_laboratory}" data-url="https://<?php echo $global['laboratory']['website']; ?>"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                    </div>
                </div>
                <div class="chemical">
                    <figure>
                        <img src="{$path.uploads}<?php echo $global['custody_chain']['chemical_signature']; ?>">
                    </figure>
                    <h2><?php echo $global['custody_chain']['chemical_name']; ?></h2>
                    <h3>{$lang.this_certificate_available_by}</h3>
                </div>
                <?php if (Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) : ?>
                    <a href="{$path.uploads}<?php echo $global['custody_chain']['pdf']; ?>" download="certificate.pdf">{$lang.download_certificate_pdf}</a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
<footer class="laboratory">
    <div style="background-color:<?php echo $global['laboratory']['colors']['second']; ?>;">
        <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>"><i class="fab fa-whatsapp"></i><?php echo $global['laboratory']['phone']; ?></a>
        <a href="tel:<?php echo $global['laboratory']['phone']; ?>"><i class="fas fa-phone"></i><?php echo $global['laboratory']['phone']; ?></a>
        <a href="mailto:<?php echo $global['laboratory']['email']; ?>"><i class="fas fa-envelope"></i><?php echo $global['laboratory']['email']; ?></a>
        <a href="https://facebook.com/<?php echo $global['laboratory']['rrss']['facebook']; ?>" target="_blank"><i class="fab fa-facebook"></i>@<?php echo $global['laboratory']['rrss']['facebook']; ?></a>
        <a href="https://instagram.com/<?php echo $global['laboratory']['rrss']['instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i>@<?php echo $global['laboratory']['rrss']['instagram']; ?></a>
        <a href="https://linkedin.com/company/<?php echo $global['laboratory']['rrss']['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin"></i>@<?php echo $global['laboratory']['rrss']['linkedin']; ?></a>
        <a href="https://<?php echo $global['laboratory']['website']; ?>" target="_blank"><i class="fas fa-globe"></i><?php echo $global['laboratory']['website']; ?></a>
    </div>
    <div style="background-color:<?php echo $global['laboratory']['colors']['first']; ?>;">
        <a href="https://id.one-consultores.com" target="_blank">{$lang.power_by} <strong><?php echo Configuration::$web_page . ' ' . Configuration::$web_version; ?></strong></a>
        <a href="https://one-consultores.com" target="_blank">Copyright <i class="far fa-copyright"></i> One Consultores</a>
        <a href="https://codemonkey.com.mx" target="_blank">Software {$lang.development_by} Code Monkey</a>
    </div>
</footer>
