<?php

defined('_EXEC') or die;

$this->dependencies->add(['css', '{$path.css}Covid/index.css']);
$this->dependencies->add(['js', '{$path.js}Covid/index.js']);

?>

<header class="covid">
    <figure>
        <img src="{$path.images}marbu_logotype_color.png">
    </figure>
    <figure>
        <img src="<?php echo (!empty($global['account']['avatar']) ? '{$path.uploads}' . $global['account']['avatar'] : '{$path.images}logotype_color.png'); ?>">
    </figure>
</header>
<main class="covid">
    <?php if (empty(System::temporal('get', 'covid', 'contact'))) : ?>
        <form name="covid">
            <p>{$lang.covid_alert_1}</p>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <input type="text" name="firstname">
                        </div>
                        <div class="title">
                            <h6>{$lang.firstname}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="text" name="lastname">
                        </div>
                        <div class="title">
                            <h6>{$lang.lastname}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="text" name="ife">
                        </div>
                        <div class="title">
                            <h6>{$lang.id}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <input type="date" name="birth_date">
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
                                <option value="" class="hidden"></option>
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
                                <option value="">{$lang.country}</option>
                                <?php foreach (Functions::countries() as $value) : ?>
                                    <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
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
                                <option value="" class="hidden"></option>
                                <option value="covid_pcr">PCR</option>
                                <option value="covid_an">{$lang.antigen}</option>
                                <option value="covid_ac">{$lang.anticorps}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.test_to_do}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="row">
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
                <div class="button">
                    <button type="submit" class="success"><i class="fas fa-check"></i>{$lang.end_and_send}</button>
                </div>
            </fieldset>
        </form>
    <?php else : ?>
        <section>
            <p>{$lang.covid_alert_2} <strong><?php echo System::temporal('get', 'covid', 'contact')['email']; ?></strong> {$lang.covid_alert_3}</p>
            <h4>{$lang.your_token_is}: <?php echo System::temporal('get', 'covid', 'contact')['token']; ?></h4>
            <figure>
                <img src="{$path.uploads}<?php echo System::temporal('get', 'covid', 'contact')['qr']['filename']; ?>">
            </figure>
            <a data-action="reload_form">{$lang.reload_form}</a>
        </section>
    <?php endif; ?>
</main>
<footer class="covid">
    <p><span><?php echo Configuration::$vars['marbu']['phone']; ?></span><span><?php echo Configuration::$vars['marbu']['email']; ?></span><span><?php echo Configuration::$vars['marbu']['website']; ?></span></p>
    <p><span>{$lang.power_by} <strong><?php echo Configuration::$web_page . ' ' . Configuration::$web_version; ?></strong></span><span>Software {$lang.development_by} Code Monkey</span></p>
</footer>
