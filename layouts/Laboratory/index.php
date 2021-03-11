<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/index.js?v=1.1']);

?>

%{header}%
<header class="modbar">
    <span style="width:auto;height:100%;float:left;display:flex;align-items:center;justify-content:center;padding-left:20px;text-transform:uppercase;"><strong><?php echo count($global['custody_chains']) . ' Registros'; ?></strong></span>
    <div class="buttons">
        <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up' AND !empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') : ?>
            <a data-action="empty_custody_chains" class="btn alert auto"><i class="fas fa-trash"></i>{$lang.empty_trash}</a>
        <?php endif; ?>
        <a data-action="filter_custody_chains" class="btn <?php echo (!empty(System::temporal('get', 'laboratory', 'filter')) ? 'success' : ''); ?> auto"><i class="fas fa-filter"></i>{$lang.filter}</a>
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
                    <?php if (($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') AND empty($value['employee'])) : ?>
                        <td class="hidden"><?php echo $value['contact']['ife']; ?></td>
                        <td class="hidden"><?php echo $value['contact']['birth_date']; ?></td>
                        <td class="hidden"><?php echo $value['contact']['age']; ?></td>
                        <td class="hidden"><?php echo $value['contact']['sex']; ?></td>
                        <td class="hidden"><?php echo $value['contact']['email']; ?></td>
                        <td class="hidden">+<?php echo $value['contact']['phone']['country'] . $value['contact']['phone']['number']; ?></td>
                    <?php endif; ?>
                    <td class="smalltag"><span><?php echo $value['token']; ?></span></td>
                    <?php if ($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') : ?>
                        <td class="smalltag"><span class="<?php echo $value['type'] ?>">{$lang.<?php echo $value['type']; ?>}</span></td>
                    <?php endif; ?>
                    <td>
                        <?php if (($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') AND empty($value['employee'])) : ?>
                            <?php echo $value['contact']['firstname'] . ' ' . $value['contact']['lastname']; ?>
                        <?php else: ?>
                            <?php echo $value['employee_firstname'] . ' ' . $value['employee_lastname']; ?>
                        <?php endif; ?>
                    </td>
                    <?php if ($value['type'] == 'covid_pcr' OR $value['type'] == 'covid_an' OR $value['type'] == 'covid_ac') : ?>
                        <td class="smalltag"><?php echo (empty($value['employee']) ? (($value['closed'] == true) ? '<i class="fas fa-envelope" style="margin-right:5px;color:#009688;"></i> {$lang.sended}' : '<i class="fas fa-envelope"  style="margin-right:5px;color:#ff9800;"></i> {$lang.not_sended}') : '<strong>{$lang.internal}</strong>'); ?></td>
                    <?php endif; ?>
                    <td class="smalltag"><i class="<?php echo (!empty($value['status']) ? (($value['status'] == 'negative') ? 'fas fa-times-circle' : (($value['status'] == 'positive') ? 'fas fa-check-circle' : 'fas fa-exclamation-circle')) : 'fas fa-question-circle'); ?>" style="margin-right:5px;color:<?php echo (!empty($value['status']) ? (($value['status'] == 'negative') ? '#f44336' : (($value['status'] == 'positive') ? '#009688' : '#ff9800')) : '#9e9e9e'); ?>;"></i>{$lang.<?php echo (!empty($value['status']) ? 'short_' . $value['status'] : 'in_process'); ?>}</td>
                    <td class="mediumtag"><span><?php echo Dates::format_date_hour($value['date'], $value['hour'], 'long_year', '12-short'); ?></span></td>
                    <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                        <td class="mediumtag"><span><?php echo $value['account_name']; ?></span></td>
                    <?php endif; ?>
                    <td class="button">
                        <?php if ($value['account_path'] != 'moonpalace' AND !empty($value['pdf'])) : ?>
                            <a href="{$path.uploads}<?php echo $value['pdf']; ?>" download="<?php echo $value['pdf']; ?>"><i class="fas fa-file-pdf"></i><span>{$lang.download_pdf}</span></a>
                        <?php endif; ?>
                    </td>
                    <?php if ($value['deleted'] == true AND (($global['render'] == 'alcoholic' AND Permissions::user(['delete_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['delete_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['delete_covid']) == true))) : ?>
                        <td class="button">
                            <a data-action="restore_custody_chain" data-id="<?php echo $value['id']; ?>"><i class="fas fa-reply"></i><span>{$lang.restore}</span></a>
                        </td>
                    <?php endif; ?>
                    <?php if (($global['render'] == 'alcoholic' AND Permissions::user(['delete_alcoholic']) == true) OR ($global['render'] == 'antidoping' AND Permissions::user(['delete_antidoping']) == true) OR ($global['render'] == 'covid' AND Permissions::user(['delete_covid']) == true)) : ?>
                        <td class="button">
                            <a data-action="delete_custody_chain" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                        </td>
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
                            <div class="span6">
                                <div class="text">
                                    <select name="account">
                                        <option value="all" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['account'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                        <?php foreach (Session::get_value('vkye_user')['accounts'] as $value) : ?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['account'] == $value['id']) ? 'selected' : '') ?>><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.account}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="text">
                                    <select name="deleted_status">
                                        <option value="not_deleted" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'not_deleted') ? 'selected' : '') ?>>{$lang.not_deleted}</option>
                                        <option value="deleted" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['deleted_status'] == 'deleted') ? 'selected' : '') ?>>{$lang.deleted}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>{$lang.deleted_status}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <?php if ($global['render'] == 'covid') : ?>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="type">
                                <option value="all" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['type'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                <option value="covid_pcr" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['type'] == 'covid_pcr') ? 'selected' : '') ?>>{$lang.covid_pcr}</option>
                                <option value="covid_an" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['type'] == 'covid_an') ? 'selected' : '') ?>>{$lang.covid_an}</option>
                                <option value="covid_ac" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['type'] == 'covid_ac') ? 'selected' : '') ?>>{$lang.covid_ac}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.type}</h6>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="date" name="start_date" value="<?php echo (!empty(System::temporal('get', 'laboratory', 'filter')) ? System::temporal('get', 'laboratory', 'filter')['start_date'] : Dates::past_date(Dates::current_date(), 1, 'days')); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.start_date}</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="date" name="end_date" value="<?php echo (!empty(System::temporal('get', 'laboratory', 'filter')) ? System::temporal('get', 'laboratory', 'filter')['end_date'] : Dates::current_date()); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.end_date}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="time" name="start_hour" value="<?php echo (!empty(System::temporal('get', 'laboratory', 'filter')) ? System::temporal('get', 'laboratory', 'filter')['start_hour'] : '00:00'); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.start_hour}</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="time" name="end_hour" value="<?php echo (!empty(System::temporal('get', 'laboratory', 'filter')) ? System::temporal('get', 'laboratory', 'filter')['end_hour'] : '23:59'); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.end_hour}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <?php if ($global['render'] == 'covid') : ?>
                    <fieldset class="fields-group">
                        <div class="text">
                            <select name="sended_status">
                                <option value="all" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['sended_status'] == 'all') ? 'selected' : '') ?>>{$lang.all}</option>
                                <option value="not_sended" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['sended_status'] == 'not_sended') ? 'selected' : '') ?>>{$lang.not_sended}</option>
                                <option value="sended" <?php echo ((!empty(System::temporal('get', 'laboratory', 'filter')) AND System::temporal('get', 'laboratory', 'filter')['sended_status'] == 'sended') ? 'selected' : '') ?>>{$lang.sended}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.sended_status}</h6>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <fieldset class="fields-group">
                    <div class="button">
                        <?php if (!empty(System::temporal('get', 'laboratory', 'filter'))) : ?>
                            <a class="btn auto warning" button-cancel><i class="fas fa-sync"></i>{$lang.end_filter}</a>
                        <?php endif; ?>
                        <a class="alert" button-close><i class="fas fa-times"></i></a>
                        <button type="submit" class="success"><i class="fas fa-check"></i></button>
                    </div>
                </fieldset>
            </form>
        </main>
    </div>
</section>
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
