<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/authentication.js?v=1.0']);

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
    <?php if ($global['render'] == 'laboratory_blocked' OR $global['render'] == 'collector_blocked' OR $global['render'] == 'out_of_laboratory' OR $global['render'] == 'out_of_time') : ?>
        <div class="blocked">
            <i class="fas fa-lock"></i>
            <p>{$lang.<?php echo $global['render']; ?>}</p>
        </div>
    <?php elseif ($global['render'] == 'go') : ?>
        <div class="authentication">
            <?php if ($global['collector']['authentication']['type'] == 'none') : ?>
                <i class="fas fa-lock"></i>
                <form name="create_authentication">
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="type">
                                <option value="" class="hidden">{$lang.choose_an_option}</option>
                                <option value="alcoholic">{$lang.to_do_test} {$lang.alcoholic}</option>
                                <option value="antidoping">{$lang.to_do_test} {$lang.antidoping}</option>
                                <option value="covid">{$lang.to_do_test} {$lang.covid}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.authenticate_to}</h6>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="taker">
                                <option value="" class="hidden">{$lang.choose_an_option}</option>
                                <?php foreach ($global['takers'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.active_taker}</h6>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="button">
                            <button type="submit" class="success">{$lang.authenticate}</button>
                        </div>
                    </fieldset>
                </form>
            <?php else : ?>
                <h4><i class="fas fa-check-circle"></i>{$lang.authenticated}</h4>
                <h6>{$lang.<?php echo $global['collector']['authentication']['type']; ?>} | <?php echo $global['collector']['authentication']['taker']['name']; ?> | <?php echo Dates::format_date(Dates::current_date(), 'long_year'); ?></h6>
                <figure>
                    <img src="{$path.uploads}<?php echo $global['collector']['qrs']['authentication']; ?>">
                </figure>
                <a data-action="share" data-title="<?php echo $global['laboratory']['name']; ?>" data-text="{$lang.share_record_qr}" data-url="https://<?php echo Configuration::$domain; ?>/<?php echo $global['laboratory']['path']; ?>/record/<?php echo $global['collector']['token']; ?>/<?php echo $global['collector']['authentication']['type']; ?>"><i class="fas fa-share-alt"></i><span>{$lang.share_form}</span></a>
                <a data-action="delete_authentication">{$lang.end_authentication}</a>
            <?php endif; ?>
        </div>
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
