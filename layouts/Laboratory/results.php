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
        <h1><?php echo $global['laboratory']['name']; ?></h1>
    </div>
    <div>
        <h2 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['rfc']; ?></h2>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['sanitary_opinion']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['first']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['second']; ?></h3>
    </div>
</header>
<main class="laboratory">
    <div class="results">
        <?php if ($global['custody_chain']['type'] == 'alcoholic') : ?>
            <!--  -->
        <?php elseif ($global['custody_chain']['type'] == 'antidoping') : ?>
            <!--  -->
        <?php elseif ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') : ?>
            <h4>{$lang.results}</h4>
            <div class="counter">
                <h5 id="counter" class="<?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? 'time_on' : 'time_out'); ?>" data-date="<?php echo Dates::future_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), 72, 'hours'); ?>" data-time-zone="<?php echo $global['laboratory']['time_zone']; ?>"></h5>
                <h6><?php echo Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour'], 'long', '12-long'); ?></h6>
            </div>
            <table>
                    <tr>
                        <td>{$lang.exam}:</td>
                        <td>
                            <?php if ($global['custody_chain']['type'] == 'covid_pcr') : ?>
                                PCR-SARS-CoV-2 (COVID-19)
                            <?php elseif ($global['custody_chain']['type'] == 'covid_an') : ?>
                                Ag-SARS-CoV-2 (COVID-19)
                            <?php elseif ($global['custody_chain']['type'] == 'covid_ac') : ?>
                                SARS-CoV-2 (2019) IgG/IgM
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>{$lang.token}:</td>
                        <td><?php echo $global['custody_chain']['token']; ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.validity}:</td>
                        <td><?php echo ((Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) ? '{$lang.72_validation}' : '{$lang.certificate_timed_out}'); ?></td>
                    </tr>
            </table>
            <table>
                <tr>
                    <td>{$lang.start_process}:</td>
                    <td><?php echo Dates::format_date($global['custody_chain']['start_process'], 'long'); ?></td>
                </tr>
                <tr>
                    <td>{$lang.end_process}:</td>
                    <td><?php echo (($global['custody_chain']['closed'] == true) ? Dates::format_date($global['custody_chain']['end_process'], 'long') : '{$lang.results_in_process}'); ?></td>
                </tr>
                <?php if ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') : ?>
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
            <p>{$lang.comments}: <?php echo (!empty($global['custody_chain']['comments']) ? $global['custody_chain']['comments'] : '{$lang.not_comments}'); ?></p>
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
                <?php if ($global['custody_chain']['version'] == 'v2') : ?>
                    <tr>
                        <td>{$lang.nationality}:</td>
                        <td><?php echo $global['custody_chain']['contact']['nationality']; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>{$lang.passport}:</td>
                    <td><?php echo $global['custody_chain']['contact']['ife']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.travel_to}:</td>
                    <td><?php echo $global['custody_chain']['contact']['travel_to']; ?></td>
                </tr>
            </table>
            <?php if ($global['custody_chain']['version'] == 'v2') : ?>
                <table>
                    <?php if ($global['custody_chain']['contact']['sex'] == 'female') : ?>
                        <tr>
                            <td>{$lang.pregnant}:</td>
                            <td>{$lang.<?php echo $global['custody_chain']['contact']['pregnant']; ?>}</td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>{$lang.symptoms}:</td>
                        <td>
                            <?php if ($global['custody_chain']['contact']['symptoms'][0] != 'nothing') : ?>
                                <?php foreach ($global['custody_chain']['contact']['symptoms'] as $value) : ?>
                                    {$lang.<?php echo $value; ?>}<br>
                                <?php endforeach; ?>
                            <?php else : ?>
                                {$lang.nothing}
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($global['custody_chain']['contact']['symptoms'][0] != 'nothing') : ?>
                        <tr>
                            <td>{$lang.symptoms_time}:</td>
                            <td><?php echo $global['custody_chain']['contact']['symptoms_time']; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>{$lang.previous_travel}:</td>
                        <td><?php echo (($global['custody_chain']['contact']['previous_travel'] == 'yeah') ? $global['custody_chain']['contact']['previous_travel_countries'] : '{$lang.not}') ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.covid_contact}:</td>
                        <td><?php echo (($global['custody_chain']['contact']['covid_contact'] == 'yeah') ? '{$lang.yeah}' : '{$lang.not}') ?></td>
                    </tr>
                    <tr>
                        <td>{$lang.covid_infection}:</td>
                        <td><?php echo (($global['custody_chain']['contact']['covid_infection'] == 'yeah') ? $global['custody_chain']['contact']['covid_infection_time'] : '{$lang.not}') ?></td>
                    </tr>
                </table>
            <?php endif; ?>
            <table>
                <tr>
                    <td>{$lang.email}:</td>
                    <td><?php echo $global['custody_chain']['contact']['email']; ?></td>
                </tr>
                <tr>
                    <td>{$lang.phone}:</td>
                    <td>+<?php echo $global['custody_chain']['contact']['phone']['country'] . ' ' . $global['custody_chain']['contact']['phone']['number']; ?></td>
                </tr>
            </table>
            <?php if ($global['custody_chain']['version'] == 'v2') : ?>
                <figure>
                    <img src="{$path.uploads}<?php echo $global['custody_chain']['signature']; ?>">
                </figure>
            <?php endif; ?>
            <?php if ($global['custody_chain']['closed'] == true) : ?>
                <?php if (Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) : ?>
                    <figure>
                        <img src="{$path.uploads}<?php echo $global['custody_chain']['qr']; ?>">
                    </figure>
                    <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.covid_share_results}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['laboratory']['path']; ?>/results/<?php echo $global['custody_chain']['token']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_results}</span></a>
                <?php endif; ?>
                <div class="chemical">
                    <figure>
                        <img src="{$path.uploads}<?php echo $global['custody_chain']['chemical_signature']; ?>">
                    </figure>
                    <h2><?php echo $global['custody_chain']['chemical_name']; ?></h2>
                    <h3>{$lang.this_certificate_available_by}</h3>
                </div>
                <?php if (Dates::diff_date_hour(Dates::format_date_hour($global['custody_chain']['date'], $global['custody_chain']['hour']), Dates::current_date_hour(), 'hours', false) < 72) : ?>
                    <a href="{$path.uploads}<?php echo $global['custody_chain']['pdf']; ?>" download="certificate.pdf">{$lang.download_certificate}</a>
                <?php endif; ?>
            <?php endif; ?>
            <div class="operating_permits">
                <figure class="hor">
                    <img src="{$path.images}secretaria_salud.png">
                </figure>
                <figure class="ver">
                    <img src="{$path.images}cofepris.png">
                </figure>
                <figure class="ver">
                    <img src="{$path.images}qroo_1.png">
                </figure>
                <figure class="ver">
                    <img src="{$path.images}qroo_2.png">
                </figure>
                <figure class="hor">
                    <img src="{$path.images}qroo_sesa.png">
                </figure>
            </div>
        <?php endif; ?>
    </div>
</main>
<footer class="laboratory">
    <div style="background-color:<?php echo $global['laboratory']['colors']['second']; ?>;">
        <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
        <a href="tel:<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i></a>
        <a href="mailto:<?php echo $global['laboratory']['email']; ?>" target="_blank"><i class="fas fa-envelope"></i></a>
        <a href="https://facebook.com/<?php echo $global['laboratory']['rrss']['facebook']; ?>" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="https://instagram.com/<?php echo $global['laboratory']['rrss']['instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://linkedin.com/company/<?php echo $global['laboratory']['rrss']['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
        <a href="https://<?php echo $global['laboratory']['website']; ?>" target="_blank"><i class="fas fa-globe"></i></a>
        <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="¡{$lang.know} <?php echo $global['laboratory']['name']; ?>!" data-url="https://<?php echo $global['laboratory']['website']; ?>/vcard"><i class="fas fa-share-alt"></i></a>
    </div>
    <div style="background-color:<?php echo $global['laboratory']['colors']['first']; ?>;">
        <a href="https://id.one-consultores.com" target="_blank">{$lang.power_by} <strong><?php echo Configuration::$web_page . ' ' . Configuration::$web_version; ?></strong></a>
        <a href="https://one-consultores.com" target="_blank">Copyright <i class="far fa-copyright"></i> One Consultores</a>
        <a href="https://codemonkey.com.mx" target="_blank">Software {$lang.development_by} Code Monkey</a>
    </div>
</footer>
