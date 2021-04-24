<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/record.js?v=1.0']);

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
    <?php if ($global['render'] == 'laboratory_blocked' OR $global['render'] == 'collector_blocked' OR $global['render'] == 'out_of_laboratory' OR $global['render'] == 'out_of_time' OR $global['render'] == 'out_of_authentication') : ?>
        <div class="blocked">
            <i class="far fa-frown"></i>
            <p>{$lang.record_not_available}</p>
        </div>
    <?php elseif ($global['render'] == 'go') : ?>
        <?php if ($global['collector']['authentication']['type'] == 'alcoholic') : ?>
            <form name="record">

            </form>
        <?php elseif ($global['collector']['authentication']['type'] == 'antidoping') : ?>
            <form name="record">

            </form>
        <?php elseif ($global['collector']['authentication']['type'] == 'covid') : ?>
            <?php if (!empty(System::temporal('get', 'record', 'covid'))) : ?>
                <div class="create">

                </div>
            <?php else : ?>
                <form name="record">
                    <h2>¡{$lang.registry_now}!</h2>
                    <h6>{$lang.covid_test} | <?php echo $global['collector']['authentication']['taker']['name']; ?> | <?php echo Dates::format_date(Dates::current_date(), 'long_year'); ?></h6>
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
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
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
                            </div>
                        </div>
                    </fieldset>
                    <div class="accept_terms">
                        <div class="caption">
                            <p>{$lang.accept_terms_1} <?php echo $global['laboratory']['business']; ?> {$lang.accept_terms_2}</p>
                        </div>
                        <div class="checkbox st-1">
                            <label>
                                <span>{$lang.accept}</span>
                                <input type="checkbox" name="accept_terms">
                            </label>
                        </div>
                    </div>
                    <fieldset class="fields-group">
                        <div class="button">
                            <button type="submit" class="success">{$lang.end_and_send}</button>
                        </div>
                    </fieldset>
                    <div class="share">
                        <a href="https://api.whatsapp.com/send?phone=<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fab fa-whatsapp"></i>{$lang.whatsapp_us}</a>
                        <a href="tel:<?php echo $global['laboratory']['phone']; ?>" target="_blank"><i class="fas fa-phone"></i>{$lang.call_us}</a>
                        <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.know} <?php echo $global['laboratory']['name']; ?>" data-url="https://<?php echo $global['laboratory']['website']; ?>"><i class="fas fa-share-alt"></i>{$lang.share}</a>
                    </div>
                </form>
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
