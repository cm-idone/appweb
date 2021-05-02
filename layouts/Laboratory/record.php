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
        <h1><?php echo $global['laboratory']['business']; ?></h1>
    </div>
    <div>
        <h2 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['rfc']; ?></h2>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['sanitary_opinion']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['first']; ?></h3>
        <h3 style="color:<?php echo $global['laboratory']['colors']['second']; ?>;"><?php echo $global['laboratory']['address']['second']; ?></h3>
        <?php if ($global['render'] == 'go' AND empty(System::temporal('get', 'record', 'covid'))) : ?>
            <h3><a href="<?php echo Language::get_lang_url('es'); ?>"><img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/es.png"></a><a href="<?php echo Language::get_lang_url('en'); ?>"><img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/en.png"></a></h3>
        <?php endif; ?>
    </div>
</header>
<main class="laboratory">
    <?php if ($global['render'] == 'laboratory_blocked' OR $global['render'] == 'collector_blocked' OR $global['render'] == 'out_of_laboratory' OR $global['render'] == 'out_of_time' OR $global['render'] == 'out_of_authentication') : ?>
        <div class="blocked">
            <i class="far fa-frown"></i>
            <p>{$lang.record_not_available}</p>
            <div class="share">
                <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                <a href="tel:<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.know_our_laboratory}" data-url="https://<?php echo $global['laboratory']['website']; ?>"><i class="fas fa-share-alt"></i>{$lang.share}</a>
            </div>
        </div>
    <?php elseif ($global['render'] == 'go') : ?>
        <?php if (empty(System::temporal('get', 'record', 'covid'))) : ?>
            <form name="create_record">
                <div class="share">
                    <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                    <a href="tel:<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                    <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.know_our_laboratory}" data-url="https://<?php echo $global['laboratory']['website']; ?>"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                </div>
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
                </div>
            </form>
        <?php endif; ?>


        <?php if ($global['collector']['authentication']['type'] == 'alcoholic') : ?>
            <form name="create_record">
                <!--  -->
            </form>
        <?php elseif ($global['collector']['authentication']['type'] == 'antidoping') : ?>
            <form name="create_record">
                <!--  -->
            </form>
        <?php elseif ($global['collector']['authentication']['type'] == 'covid') : ?>
            <?php if (empty(System::temporal('get', 'record', 'covid'))) : ?>
                <form name="create_record">





                    <div data-step>
                        <h4>{$lang.security_form}</h4>
                        <fieldset class="fields-group hidden" data-hidden="sf_pregnant">
                            <div class="title">
                                <p>{$lang.are_you_pregnant}</p>
                            </div>
                        </fieldset>
                        <fieldset class="fields-group hidden" data-hidden="sf_pregnant">
                            <div class="checkbox">
                                <label>
                                    <input type="radio" name="sf_pregnant" value="not" checked>
                                    <span>{$lang.not}</span>
                                </label>
                                <label style="margin-left:10px;">
                                    <input type="radio" name="sf_pregnant" value="yeah">
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
                                    <input type="checkbox" name="sf_symptoms[]" value="fever">
                                    <span>{$lang.fever}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="eyes_pain">
                                    <span>{$lang.eyes_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="torax_pain">
                                    <span>{$lang.torax_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="muscles_pain">
                                    <span>{$lang.muscles_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="head_pain">
                                    <span>{$lang.head_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="throat_pain">
                                    <span>{$lang.throat_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="knees_pain">
                                    <span>{$lang.knees_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="ears_pain">
                                    <span>{$lang.ears_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="joints_pain">
                                    <span>{$lang.joints_pain}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="cough">
                                    <span>{$lang.cough}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="difficulty_breathing">
                                    <span>{$lang.difficulty_breathing}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="sweating">
                                    <span>{$lang.sweating}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="runny_nose">
                                    <span>{$lang.runny_nose}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="itching">
                                    <span>{$lang.itching}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="conjunctivitis">
                                    <span>{$lang.conjunctivitis}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="vomit">
                                    <span>{$lang.vomit}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="diarrhea">
                                    <span>{$lang.diarrhea}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="smell_loss">
                                    <span>{$lang.smell_loss}</span>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sf_symptoms[]" value="taste_loss">
                                    <span>{$lang.taste_loss}</span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="fields-group hidden" data-hidden="sf_symptoms_time">
                            <div class="text">
                                <input type="text" name="sf_symptoms_time" placeholder="{$lang.write_symptoms_time}">
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
                                    <input type="radio" name="sf_travel" value="not" checked>
                                    <span>{$lang.not}</span>
                                </label>
                                <label style="margin-left:10px;">
                                    <input type="radio" name="sf_travel" value="yeah">
                                    <span>{$lang.yeah}</span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="fields-group hidden" data-hidden="sf_travel_countries">
                            <div class="text">
                                <textarea name="sf_travel_countries" placeholder="{$lang.write_travel_countries}"></textarea>
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
                                    <input type="radio" name="sf_contact" value="not" checked>
                                    <span>{$lang.not}</span>
                                </label>
                                <label style="margin-left:10px;">
                                    <input type="radio" name="sf_contact" value="yeah">
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
                                    <input type="radio" name="sf_covid" value="not" checked>
                                    <span>{$lang.not}</span>
                                </label>
                                <label style="margin-left:10px;">
                                    <input type="radio" name="sf_covid" value="yeah">
                                    <span>{$lang.yeah}</span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="fields-group hidden" data-hidden="sf_covid_time">
                            <div class="text">
                                <input type="text" name="sf_covid_time" placeholder="{$lang.write_are_you_covid}">
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
                            <div class="caption">
                                <p>{$lang.accept_terms_3} <?php echo $global['laboratory']['business']; ?> {$lang.accept_terms_4}</p>
                            </div>
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
                        <div>
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
                        <div>
                            <h4><?php echo $global['laboratory']['sanitary_opinion']; ?></h4>
                            <h6>{$lang.sanitary_opinion}</h6>
                        </div>
                    </div>
                </form>
            <?php else : ?>
                <div class="record">
                    <h4>{$lang.your_token_is}:</h4>
                    <h5><?php echo System::temporal('get', 'record', 'covid')['token']; ?></h5>
                    <p>¡{$lang.hi} <strong><?php echo explode(' ', System::temporal('get', 'record', 'covid')['firstname'])[0]; ?></strong>! {$lang.covid_alert_1} <strong><?php echo System::temporal('get', 'record', 'covid')['email']; ?></strong> {$lang.covid_alert_2}</p>
                    <figure>
                        <img src="{$path.uploads}<?php echo System::temporal('get', 'record', 'covid')['qr']['filename']; ?>">
                    </figure>
                    <div class="share">
                        <div>
                            <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.share_results}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['laboratory']['path']; ?>/results/<?php echo System::temporal('get', 'record', 'covid')['token']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_results_with_friends}</span></a>
                        </div>
                        <div>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                            <a href="tel:<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                            <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.know_our_laboratory}" data-url="https://<?php echo $global['laboratory']['website']; ?>"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                        </div>
                    </div>
                    <a data-action="restore_record">{$lang.record_other_person}</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>



    <?php endif; ?>
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
