<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.css']);
$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.js']);
$this->dependencies->add(['js', '{$path.js}Laboratory/record.js?v=1.1']);

?>

<header class="laboratory">
    <div style="background-color:<?php echo $global['laboratory']['colors']['first']; ?>;">
        <figure>
            <img src="{$path.uploads}<?php echo $global['laboratory']['avatar']; ?>">
        </figure>
        <h1><?php echo $global['laboratory']['name']; ?></h1>
    </div>
    <div>
        <h2 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['business']; ?></h2>
        <h2 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['rfc']; ?></h2>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['sanitary_opinion']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['first']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['second']; ?></h3>
        <h3><a href="<?php echo Language::get_lang_url('es'); ?>"><img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/es.png"></a><a href="<?php echo Language::get_lang_url('en'); ?>"><img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/en.png"></a></h3>
    </div>
</header>
<main class="laboratory">
    <?php if ($global['render'] == 'laboratory_blocked' OR $global['render'] == 'collector_blocked' OR $global['render'] == 'out_of_laboratory' OR $global['render'] == 'out_of_time' OR $global['render'] == 'out_of_authentication') : ?>
        <div class="blocked">
            <i class="far fa-frown"></i>
            <p>{$lang.record_not_available}</p>
        </div>
    <?php elseif ($global['render'] == 'go') : ?>
        <form name="create_record">
            <?php if ($global['collector']['authentication']['type'] == 'alcoholic') : ?>
                <!--  -->
            <?php elseif ($global['collector']['authentication']['type'] == 'antidoping') : ?>
                <!--  -->
            <?php elseif ($global['collector']['authentication']['type'] == 'covid') : ?>
                <h4>{$lang.record}</h4>
                <p>{$lang.custody_chain_alert_1}</p>
                <div data-step>
                    <h4>{$lang.what_your_name}</h4>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="firstname" placeholder="{$lang.write_your_firstname}">
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="lastname" placeholder="{$lang.write_your_lastname}">
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="sex" value="male" checked>
                                <span>{$lang.im_male}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="sex" value="female">
                                <span>{$lang.im_female}</span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.what_your_born}</h4>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="birth_date_day">
                                <option value="" class="hidden">{$lang.select_your_day}</option>
                                <?php foreach (Dates::create_lapse_date('days') as $value) : ?>
                                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="birth_date_month">
                                <option value="" class="hidden">{$lang.select_your_month}</option>
                                <?php foreach (Dates::create_lapse_date('months', Session::get_value('vkye_lang')) as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="birth_date_year">
                                <option value="" class="hidden">{$lang.select_your_year}</option>
                                <?php foreach (Dates::create_lapse_date('years', 100) as $value) : ?>
                                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.what_your_age}</h4>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="number" name="age" placeholder="{$lang.write_your_age}">
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.what_your_personal_information}</h4>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="nationality" placeholder="{$lang.write_your_nationality}">
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="ife" placeholder="{$lang.write_your_ife}">
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="text" name="travel_to" placeholder="{$lang.write_travel_to}">
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.security_form}</h4>
                    <fieldset class="fields-group hidden" data-hidden="pregnant">
                        <div class="title">
                            <p>{$lang.are_you_pregnant}</p>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group hidden" data-hidden="pregnant">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="pregnant" value="not" checked>
                                <span>{$lang.not}</span>
                            </label>
                            <label style="margin-left:10px;">
                                <input type="radio" name="pregnant" value="yeah">
                                <span>{$lang.yeah}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="title">
                            <p>{$lang.are_you_symptoms}</p>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="symptoms[]" value="nothing" checked>
                                <span>{$lang.nothing}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="fever">
                                <span>{$lang.fever}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="eyes_pain">
                                <span>{$lang.eyes_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="torax_pain">
                                <span>{$lang.torax_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="muscles_pain">
                                <span>{$lang.muscles_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="head_pain">
                                <span>{$lang.head_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="throat_pain">
                                <span>{$lang.throat_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="knees_pain">
                                <span>{$lang.knees_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="ears_pain">
                                <span>{$lang.ears_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="joints_pain">
                                <span>{$lang.joints_pain}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="cough">
                                <span>{$lang.cough}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="difficulty_breathing">
                                <span>{$lang.difficulty_breathing}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="sweating">
                                <span>{$lang.sweating}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="runny_nose">
                                <span>{$lang.runny_nose}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="itching">
                                <span>{$lang.itching}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="conjunctivitis">
                                <span>{$lang.conjunctivitis}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="vomit">
                                <span>{$lang.vomit}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="diarrhea">
                                <span>{$lang.diarrhea}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="smell_loss">
                                <span>{$lang.smell_loss}</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="symptoms[]" value="taste_loss">
                                <span>{$lang.taste_loss}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group hidden" data-hidden="symptoms_time">
                        <div class="text">
                            <input type="text" name="symptoms_time" placeholder="{$lang.write_symptoms_time}">
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="title">
                            <p>{$lang.are_travel_prev}</p>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="previous_travel" value="not" checked>
                                <span>{$lang.not}</span>
                            </label>
                            <label style="margin-left:10px;">
                                <input type="radio" name="previous_travel" value="yeah">
                                <span>{$lang.yeah}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group hidden" data-hidden="previous_travel_countries">
                        <div class="text">
                            <textarea name="previous_travel_countries" placeholder="{$lang.write_travel_countries}"></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="title">
                            <p>{$lang.are_contact_covid}</p>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="covid_contact" value="not" checked>
                                <span>{$lang.not}</span>
                            </label>
                            <label style="margin-left:10px;">
                                <input type="radio" name="covid_contact" value="yeah">
                                <span>{$lang.yeah}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="title">
                            <p>{$lang.are_you_covid}</p>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="covid_infection" value="not" checked>
                                <span>{$lang.not}</span>
                            </label>
                            <label style="margin-left:10px;">
                                <input type="radio" name="covid_infection" value="yeah">
                                <span>{$lang.yeah}</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group hidden" data-hidden="covid_infection_time">
                        <div class="text">
                            <input type="text" name="covid_infection_time" placeholder="{$lang.write_are_you_covid}">
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.what_your_contact}</h4>
                    <p>{$lang.what_your_contact_description}</p>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="email" name="email" placeholder="{$lang.write_your_email}">
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="phone_country">
                                <option value="" class="hidden">{$lang.select_your_phone_country}</option>
                                <?php foreach (Functions::countries() as $value) : ?>
                                    <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')] . ' +' . $value['lada']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <input type="number" name="phone_number" placeholder="{$lang.write_your_phone}">
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.what_your_test}</h4>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="type" value="covid_pcr" checked>
                                <span>PCR (<?php echo Currency::format($global['collector']['authentication']['taker']['prices']['covid']['pcr']['usd'], 'USD') . ' - ' .  Currency::format($global['collector']['authentication']['taker']['prices']['covid']['pcr']['mxn'], 'MXN'); ?>)</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="type" value="covid_an">
                                <span>{$lang.antigen} (<?php echo Currency::format($global['collector']['authentication']['taker']['prices']['covid']['an']['usd'], 'USD') . ' - ' .  Currency::format($global['collector']['authentication']['taker']['prices']['covid']['an']['mxn'], 'MXN'); ?>)</span>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="type" value="covid_ac">
                                <span>{$lang.anticorps} (<?php echo Currency::format($global['collector']['authentication']['taker']['prices']['covid']['ac']['usd'], 'USD') . ' - ' .  Currency::format($global['collector']['authentication']['taker']['prices']['covid']['ac']['mxn'], 'MXN'); ?>)</span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div data-step>
                    <h4>{$lang.signature}</h4>
                    <p>{$lang.accept_terms_1} <a href="https://<?php echo $global['laboratory']['website']; ?>/terminos-y-condiciones" target="_blank">{$lang.terms_and_conditions}</a> {$lang.accept_terms_2} <a href="https://<?php echo $global['laboratory']['website']; ?>/aviso-de-privacidad" target="_blank">{$lang.privacy_notice}</a></p>
                    <div class="accept_terms">
                        <p>{$lang.accept_terms_3} <?php echo $global['laboratory']['business']; ?> {$lang.accept_terms_4}</p>
                    </div>
                    <fieldset class="fields-group">
                        <div class="signature" id="signature">
                            <canvas></canvas>
                            <div class="sign_by_first_time">
                                <a data-action="clean_signature"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        <div class="title">
                            <h6>{$lang.sign_here}</h6>
                        </div>
                    </fieldset>
                </div>
                <fieldset class="fields-group">
                    <div class="button">
                        <button type="submit" class="success">{$lang.end_and_send}</button>
                    </div>
                </fieldset>
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
        </form>
    <?php endif; ?>
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
