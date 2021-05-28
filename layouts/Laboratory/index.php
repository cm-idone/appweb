<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/index.js?v=1.0']);

?>

%{header}%
<header class="modbar">
    <span class="title"><strong><?php echo count($global['custody_chains']) . ' {$lang.records}'; ?></strong></span>
    <div class="buttons">
        <?php if (Session::get_value('vkye_user')['id'] == 1) : ?>
            <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                <a data-action="send_custody_chains" class="btn auto"><i class="fas fa-envelope"></i>{$lang.send_results}</a>
            <?php endif; ?>
        <?php endif; ?>
        <a data-action="filter_custody_chains" class="btn auto success"><i class="fas fa-filter"></i>{$lang.filter}</a>
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
                    <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                        <td class="hidden"><?php echo $value['contact']['birth_date']; ?></td>
                        <td class="hidden"><?php echo $value['contact']['ife']; ?></td>
                        <td class="hidden"><?php echo $value['contact']['email']; ?></td>
                    <?php endif; ?>
                    <td class="smalltag"><span><?php echo $value['token']; ?></span></td>
                    <?php if ($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') : ?>
                        <td class="smalltag"><span class="<?php echo $value['type'] ?>">{$lang.<?php echo $value['type']; ?>}</span></td>
                    <?php endif; ?>
                    <td>
                        <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                            <?php echo '(' . $value['id'] . ') ' . $value['contact']['firstname'] . ' ' . $value['contact']['lastname']; ?>
                        <?php else: ?>
                            <?php echo $value['employee_firstname'] . ' ' . $value['employee_lastname']; ?>
                        <?php endif; ?>
                    </td>
                    <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                        <td class="smalltag"><?php echo (($value['sent'] == true) ? '<i class="fas fa-envelope" style="margin-right:5px;color:#009688;"></i> {$lang.sent}' : '<i class="fas fa-envelope"  style="margin-right:5px;color:#ff9800;"></i> {$lang.not_sent}'); ?></td>
                    <?php endif; ?>
                    <td class="smalltag"><i class="<?php echo (!empty($value['status']) ? (($value['status'] == 'negative') ? 'fas fa-times-circle' : (($value['status'] == 'positive') ? 'fas fa-check-circle' : 'fas fa-exclamation-circle')) : 'fas fa-question-circle'); ?>" style="margin-right:5px;color:<?php echo (!empty($value['status']) ? (($value['status'] == 'negative') ? '#f44336' : (($value['status'] == 'positive') ? '#009688' : '#ff9800')) : '#9e9e9e'); ?>;"></i>{$lang.<?php echo (!empty($value['status']) ? 'short_' . $value['status'] : 'in_process'); ?>}</td>
                    <td class="mediumtag"><span><?php echo Dates::format_date_hour($value['date'], $value['hour'], 'long_year', '12-short'); ?></span></td>
                    <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                        <td class="smalltag"><span><?php echo $value['laboratory_name']; ?></span></td>
                        <td class="smalltag"><span><?php echo $value['taker_name']; ?></span></td>
                        <td class="smalltag"><span><?php echo $value['collector_name']; ?></span></td>
                        <td class="button">
                            <?php if (!empty($value['pdf'])) : ?>
                                <a href="{$path.uploads}<?php echo $value['pdf']; ?>" download="<?php echo $value['pdf']; ?>"><i class="fas fa-file-pdf"></i><span>{$lang.download_pdf}</span></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <?php if (Session::get_value('vkye_user')['id'] == 1) : ?>
                        <?php if ($value['deleted'] == true AND (($global['render'] == 'alcoholic' AND Permissions::user(['restore_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['restore_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['restore_covid']) == true))) : ?>
                            <td class="button">
                                <a data-action="restore_custody_chain" data-id="<?php echo $value['id']; ?>"><i class="fas fa-reply"></i><span>{$lang.restore}</span></a>
                            </td>
                        <?php endif; ?>
                        <?php if (($value['deleted'] == false AND (($global['render'] == 'alcoholic' AND Permissions::user(['delete_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['delete_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['delete_covid']) == true))) OR ($value['deleted'] == true AND (($global['render'] == 'alcoholic' AND Permissions::user(['trash_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['trash_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['trash_covid']) == true)))) : ?>
                            <td class="button">
                                <a data-action="delete_custody_chain" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                            </td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($value['deleted'] == false AND (($global['render'] == 'alcoholic' AND Permissions::user(['update_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['update_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['update_covid']) == true))) : ?>
                        <td class="button">
                            <a href="/laboratory/update/<?php echo $value['token']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<section class="modal" data-modal="filter_custody_chains">
    <div class="content">
        <main>
            <form>
                <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <select name="laboratory">
                                        <option value="all" <?php echo ((System::temporal('get', 'laboratory', 'filter')['laboratory'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                        <?php foreach ($global['laboratories'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo ((System::temporal('get', 'laboratory', 'filter')['laboratory'] == $value['id']) ? 'selected' : '') ?>><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.laboratory}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <select name="taker">
                                        <option value="all" <?php echo ((System::temporal('get', 'laboratory', 'filter')['taker'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                        <?php foreach ($global['takers'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo ((System::temporal('get', 'laboratory', 'filter')['taker'] == $value['id']) ? 'selected' : '') ?>><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.taker}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <select name="collector">
                                        <option value="all" <?php echo ((System::temporal('get', 'laboratory', 'filter')['collector'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                        <?php foreach ($global['collectors'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo ((System::temporal('get', 'laboratory', 'filter')['collector'] == $value['id']) ? 'selected' : '') ?>><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.collector}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <?php if ($global['render'] == 'covid') : ?>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="type">
                                <option value="all" <?php echo ((System::temporal('get', 'laboratory', 'filter')['type'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                <option value="covid_pcr" <?php echo ((System::temporal('get', 'laboratory', 'filter')['type'] == 'covid_pcr') ? 'selected' : '') ?>>{$lang.covid_pcr}</option>
                                <option value="covid_an" <?php echo ((System::temporal('get', 'laboratory', 'filter')['type'] == 'covid_an') ? 'selected' : '') ?>>{$lang.covid_an}</option>
                                <option value="covid_ac" <?php echo ((System::temporal('get', 'laboratory', 'filter')['type'] == 'covid_ac') ? 'selected' : '') ?>>{$lang.covid_ac}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.type}</h6>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group <?php echo ((System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') ? 'hidden' : ''); ?>">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="date" name="start_date" value="<?php echo System::temporal('get', 'laboratory', 'filter')['start_date']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.start_date}</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="time" name="start_hour" value="<?php echo System::temporal('get', 'laboratory', 'filter')['start_hour']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.start_hour}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group <?php echo ((System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') ? 'hidden' : ''); ?>">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="date" name="end_date" value="<?php echo System::temporal('get', 'laboratory', 'filter')['end_date']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.end_date}</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="time" name="end_hour" value="<?php echo System::temporal('get', 'laboratory', 'filter')['end_hour']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.end_hour}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                            <div class="span6 <?php echo ((System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') ? 'hidden' : ''); ?>">
                                <div class="text">
                                    <select name="sent_status">
                                        <option value="all" <?php echo ((System::temporal('get', 'laboratory', 'filter')['sent_status'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                        <option value="not_sent" <?php echo ((System::temporal('get', 'laboratory', 'filter')['sent_status'] == 'not_sent') ? 'selected' : '') ?>>{$lang.not_sent}</option>
                                        <option value="sent" <?php echo ((System::temporal('get', 'laboratory', 'filter')['sent_status'] == 'sent') ? 'selected' : '') ?>>{$lang.sent}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.sent_status}</h6>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND System::temporal('get', 'laboratory', 'filter')['deleted_status'] != 'deleted') ? 'span6' : 'span12'); ?>">
                            <div class="text">
                                <select name="deleted_status">
                                    <option value="not_deleted" <?php echo ((System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'not_deleted') ? 'selected' : '') ?>>{$lang.not_deleted}</option>
                                    <option value="deleted" <?php echo ((System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') ? 'selected' : '') ?>>{$lang.deleted}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.deleted_status}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="button">
                        <a class="alert" button-close><i class="fas fa-times"></i></a>
                        <button type="submit" class="success"><i class="fas fa-check"></i></button>
                    </div>
                </fieldset>
            </form>
        </main>
    </div>
</section>
<?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
    <section class="modal" data-modal="send_custody_chains">
        <div class="content">
            <main>
                <form>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <select name="laboratory">
                                        <option value="all">{$lang.all}</option>
                                        <?php foreach ($global['laboratories'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.laboratory}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <select name="taker">
                                        <option value="all">{$lang.all}</option>
                                        <?php foreach ($global['takers'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.taker}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <select name="type">
                                        <option value="covid_pcr">{$lang.covid_pcr}</option>
                                        <option value="covid_an">{$lang.covid_an}</option>
                                        <option value="covid_ac">{$lang.covid_ac}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.type}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="date" name="start_date" value="<?php echo Dates::current_date(); ?>">
                                </div>
                                <div class="title">
                                    <h6>{$lang.start_date}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="text">
                                    <input type="time" name="start_hour" value="00:00:01">
                                </div>
                                <div class="title">
                                    <h6>{$lang.start_hour}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="date" name="end_date" value="<?php echo Dates::current_date(); ?>">
                                </div>
                                <div class="title">
                                    <h6>{$lang.end_date}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="text">
                                    <input type="time" name="end_hour" value="23:59:59">
                                </div>
                                <div class="title">
                                    <h6>{$lang.end_hour}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <select name="test_result">
                                        <option value="negative">{$lang.negative}</option>
                                        <option value="positive">{$lang.positive}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.result}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <select name="test_unity">
                                        <option value="INDEX">{$lang.index}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.unity}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <select name="test_reference_values">
                                        <option value="not_detected">{$lang.not_detected}</option>
                                        <option value="detected">{$lang.detected}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.reference_values}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group hidden">
                        <div class="row">
                            <div class="span2">
                                <div class="text">
                                    <select name="test_igm_result">
                                        <option value="not_reactive">{$lang.not_reactive}</option>
                                        <option value="reactive">{$lang.reactive}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>IgM {$lang.result}</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <select name="test_igm_unity">
                                        <option value="INDEX">{$lang.index}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>IgM {$lang.unity}</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <select name="test_igm_reference_values">
                                        <option value="not_reactive">{$lang.not_reactive}</option>
                                        <option value="reactive">{$lang.reactive}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>IgM {$lang.reference_values}</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <select name="test_igg_result">
                                        <option value="not_reactive">{$lang.not_reactive}</option>
                                        <option value="reactive">{$lang.reactive}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>IgG {$lang.result}</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <select name="test_igg_unity">
                                        <option value="INDEX">{$lang.index}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>IgG {$lang.unity}</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <select name="test_igg_reference_values">
                                        <option value="not_reactive">{$lang.not_reactive}</option>
                                        <option value="reactive">{$lang.reactive}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>IgG {$lang.reference_values}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="date" name="end_process" value="<?php echo Dates::current_date(); ?>">
                                </div>
                                <div class="title">
                                    <h6>{$lang.end_process}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="text">
                                    <select name="chemical">
                                        <?php foreach ($global['chemicals'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.chemical}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="button">
                            <a class="alert" button-close><i class="fas fa-times"></i></a>
                            <button type="submit" class="success"><i class="fas fa-check"></i></button>
                        </div>
                    </fieldset>
                </form>
            </main>
        </div>
    </section>
<?php endif; ?>
<?php if (($global['render'] == 'alcoholic' AND Permissions::user(['delete_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['delete_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['delete_covid']) == true)) : ?>
    <section class="modal alert" data-modal="delete_custody_chain">
        <div class="content">
            <main>
                <i class="fas fa-trash"></i>
                <div>
                    <a button-close><i class="fas fa-times"></i></a>
                    <a button-success><i class="fas fa-check"></i></a>
                </div>
            </main>
        </div>
    </section>
<?php endif; ?>
