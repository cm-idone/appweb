<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Login/index.min.js']);

?>

<main class="login">
    <figure>
        <img src="{$path.images}isotype_color.png">
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
        <div class="button">
            <button type="submit">{$lang.login}</button>
        </div>
        <!-- <div class="shurtcut">
            <a>{$lang.have_you_forgotten_your_password}<i class="fas fa-lock"></i></a>
            <a>{$lang.signup_your_user}<i class="fas fa-user"></i></a>
            <a>{$lang.create_your_account}<i class="fas fa-user-circle"></i></a>
        </div> -->
    </form>
    <!-- <a>{$lang.development_by}<img src="{$path.images}code_monkey_logotype_black.svg"></a> -->
    <p>{$lang.with_love}</p>
    <a>{$lang.all_rights_reserved}</a>
</main>
