<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Login/index.js']);

?>

<main class="login">
    <figure>
        <img src="{$path.images}imagotype_color.png">
    </figure>
    <form name="login">
        <fieldset class="fields-group">
            <div class="text">
                <input type="email" name="email" placeholder="{$lang.email}">
            </div>
        </fieldset>
        <fieldset class="fields-group">
            <div class="text">
                <input type="password" name="password" placeholder="{$lang.password}">
            </div>
        </fieldset>
        <div class="shurtcut">
            <a><i class="fas fa-lock"></i>{$lang.have_you_forgotten_your_password}</a>
        </div>
        <div class="button">
            <button type="submit">{$lang.login}</button>
        </div>
    </form>
    <a href="https://codemonkey.com.mx/monkeyboard/copyright" target="_blank">Copyright <i class="far fa-copyright"></i> 2021 | One Consultores</a>
    <a href="https://codemonkey.com.mx/" target="_blank"><strong><?php echo Configuration::$web_page . ' ' . Configuration::$web_version; ?></strong>Software {$lang.development_by} One Consultores & <img src="https://cdn.codemonkey.com.mx/monkeyboard/assets/images/cm_logotype_black.png"></a>
    <p>{$lang.with_love}</p>
</main>
