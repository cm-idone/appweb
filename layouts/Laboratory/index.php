<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/index.js']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="custody_chains" placeholder="{$lang.search}">
            </div>
        </fieldset>
    </div>
</header>
<main class="workspace">
    <table class="tbl-st-1" data-table="custody_chains">
        <tbody>
            <?php foreach ($global['custody_chains'] as $value) : ?>
                <tr>
                    <td class="smalltag"><span><?php echo $value['token']; ?></span></td>
                    <td class="smalltag"><span>{$lang.<?php echo $value['type']; ?>}</span></td>
                    <td>
                        <?php if (($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') AND empty($value['employee'])) : ?>
                            <?php echo $value['contact']['firstname'] . ' ' . $value['contact']['lastname']; ?>
                        <?php else: ?>
                            <?php echo $value['employee_firstname'] . ' ' . $value['employee_lastname']; ?>
                        <?php endif; ?>
                    </td>
                    <td class="mediumtag"><span><?php echo Dates::format_date_hour($value['date'], $value['hour'], 'long_year', '12-short'); ?></span></td>
                    <td class="mediumtag"><span><?php echo (!empty($value['user']) ? $value['user_firstname'] . ' ' . $value['user_lastname'] : '{$lang.not_user}'); ?></span></td>
                    <td class="button">
                        <a href="/laboratory/update/<?php echo $value['token']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
