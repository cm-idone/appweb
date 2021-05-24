<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/authentication.js?v=1.0']);

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
    <?php if ($global['render'] == 'laboratory_blocked' OR $global['render'] == 'collector_blocked' OR $global['render'] == 'out_of_laboratory' OR $global['render'] == 'out_of_time') : ?>
        <div class="blocked">
            <i class="fas fa-lock"></i>
            <p>{$lang.<?php echo $global['render']; ?>}</p>
        </div>
    <?php elseif ($global['render'] == 'go') : ?>
        <?php if ($global['collector']['authentication']['type'] == 'none') : ?>
            <form name="start_authentication">
                <i class="fas fa-lock"></i>
                <fieldset class="fields-group">
                    <div class="text">
                        <select name="taker">
                            <option value="" class="hidden">{$lang.choose_a_taker}</option>
                            <?php foreach ($global['takers'] as $value) : ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="button">
                        <button type="submit" class="success">{$lang.start_authentication}</button>
                    </div>
                </fieldset>
            </form>
        <?php else : ?>
            <div class="authentication">
                <h4><i class="fas fa-check-circle"></i>{$lang.authenticated}</h4>
                <h5><?php echo $global['collector']['token']; ?> | <?php echo $global['collector']['name']; ?></h5>
                <h6><?php echo $global['collector']['authentication']['taker']['name']; ?></h6>
                <figure>
                    <img src="{$path.uploads}<?php echo $global['collector']['qrs']['record']; ?>">
                </figure>
                <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.record_now}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['laboratory']['path']; ?>/record/<?php echo $global['collector']['token']; ?>/<?php echo $global['collector']['authentication']['type']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_form}</span></a>
                <a data-action="end_authentication">{$lang.end_authentication}</a>
            </div>
        <?php endif; ?>
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
