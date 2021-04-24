<?php

defined('_EXEC') or die;

$this->dependencies->add(['css', '{$path.css}Marbu/styles.css?v=1.0']);
$this->dependencies->add(['css', '{$path.css}Marbu/authentication.css?v=1.0']);
$this->dependencies->add(['js', '{$path.js}Marbu/authentication.js?v=1.0']);

?>

<header class="marbu">
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
    </div>
</header>
<main class="marbu">
    
</main>
<footer class="marbu">
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
