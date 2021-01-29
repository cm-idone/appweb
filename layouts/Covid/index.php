<?php

defined('_EXEC') or die;

$this->dependencies->add(['css', '{$path.css}Covid/index.css']);
$this->dependencies->add(['js', '{$path.js}Covid/index.js']);

?>

<section class="container">
    <?php if (!empty($global['account'])) : ?>
        <article class="scanner-4">
            <header>
                <figure>
                    <img src="{$path.images}marbu_logotype_color.png">
                </figure>
                <h1></h1>
                <figure>
                    <img src="<?php echo (!empty($global['account']['avatar']) ? '{$path.uploads}' . $global['account']['avatar'] : '{$path.images}marbu_logotype_color.png'); ?>">
                </figure>
            </header>
            <main>
                <?php if (empty(System::temporal('get', 'covid', 'result'))) : ?>
                    <form name="covid">
                        <p>{$lang.covid_alert_1}</p>
                        <fieldset class="fields-group">
                            <div class="row">
                                <div class="span6">
                                    <div class="text">
                                        <input type="text" name="firstname">
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.firstname}</h6>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="text">
                                        <input type="text" name="lastname">
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.lastname}</h6>
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
                                        <input type="text" name="age">
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.age}</h6>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="text">
                                        <input type="text" name="id">
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
                                        <input type="email" name="email">
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.email}</h6>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="compound st-1-left">
                                        <select name="phone_country">
                                            <option value="">{$lang.lada}</option>
                                            <?php foreach (Functions::countries() as $value) : ?>
                                                <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="phone_number" placeholder="{$lang.number}">
                                    </div>
                                    <div class="title">
                                        <h6>{$lang.phone}</h6>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="text">
                                        <select name="test">
                                            <option value="" hidden></option>
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
                                        <input type="name" name="travel">
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
                <?php else: ?>
                    <div>
                        <p></p>
                        <h4></h4>
                        <figure>
                            <img src="">
                        </figure>
                    </div>
                <?php endif; ?>
            </main>
            <footer>
                <p><a href="tel:9983132948" target="_blank">+52 (998) 313 2948</a> | <a href="mailto:marbu@one-consultores.com" target="_blank">marbu@one-consultores.com</a> | <a href="https://one-consultores.com" target="_blank">marbu.one-consultores.com</a></p>
                <p>Copyright (C) <strong>ID One 1.0</strong> by <a href="https://codemonkey.com.mx" target="_blank">Code Monkey</a></p>
            </footer>
        </article>
    <?php endif; ?>
</section>
