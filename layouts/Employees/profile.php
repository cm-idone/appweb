<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Employees/profile.js']);

?>

%{header}%
<main class="workspace unmodbar">
    <div class="scanner-1">
        <figure>
            <img src="<?php echo (!empty($global['employee']['avatar']) ? '{$path.uploads}' . $global['employee']['avatar'] : '{$path.images}employee.png'); ?>">
        </figure>
        <h4><?php echo $global['employee']['firstname'] . ' ' . $global['employee']['lastname']; ?></h4>
        <span><?php echo $global['employee']['nie']; ?> | <?php echo (($global['employee']['blocked'] == true) ? '{$lang.blocked}' : '{$lang.active}'); ?></span>
    </div>
    <div class="scanner-2">
        <div class="tabs" data-tab-active="tab1">
            <ul>
                <li data-tab-target="tab1" class="view">{$lang.personal_proceedings}</li>
                <li data-tab-target="tab2">{$lang.labor_proceedings}</li>
                <li data-tab-target="tab3">{$lang.labor}</li>
                <li data-tab-target="tab4">{$lang.emergency_contacts}</li>
            </ul>
            <div class="tab" data-target="tab1">
                <h6><strong>{$lang.sex}</strong>: {$lang.<?php echo (!empty($global['employee']['sex']) ? $global['employee']['sex'] : '{$lang.not_available}'); ?>}</h6>
                <h6><strong>{$lang.birth_date}</strong>: <?php echo (!empty($global['employee']['birth_date']) ? Dates::format_date($global['employee']['birth_date'], 'long') : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.age}</strong>: <?php echo (!empty($global['employee']['birth_date']) ? Functions::format_age($global['employee']['birth_date']) : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.ife}</strong>: <?php echo (!empty($global['employee']['ife']) ? $global['employee']['ife'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.nss}</strong>: <?php echo (!empty($global['employee']['nss']) ? $global['employee']['nss'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.rfc}</strong>: <?php echo (!empty($global['employee']['rfc']) ? $global['employee']['rfc'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.curp}</strong>: <?php echo (!empty($global['employee']['curp']) ? $global['employee']['curp'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.account_number}</strong>: <?php echo ((!empty($global['employee']['bank']['name']) AND !empty($global['employee']['bank']['account'])) ? '(' . $global['employee']['bank']['name'] . ') ' . $global['employee']['bank']['account'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.nsv}</strong>: <?php echo (!empty($global['employee']['nsv']) ? $global['employee']['nsv'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.email}</strong>: <?php echo (!empty($global['employee']['email']) ? $global['employee']['email'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.phone}</strong>: <?php echo ((!empty($global['employee']['phone']['country']) AND !empty($global['employee']['phone']['number'])) ? '+ (' . $global['employee']['phone']['country'] . ') ' . $global['employee']['phone']['number'] : '{$lang.not_available}'); ?></h6>
            </div>
            <div class="tab" data-target="tab2">
                <h6><strong>{$lang.rank}</strong>: <?php echo (!empty($global['employee']['rank']) ? $global['employee']['rank'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.nie}</strong>: <?php echo $global['employee']['nie']; ?></h6>
                <h6><strong>{$lang.admission_date}</strong>: <?php echo (!empty($global['employee']['admission_date']) ? Dates::format_date($global['employee']['admission_date'], 'long') : '{$lang.not_available}'); ?></h6>
                <p><strong>{$lang.responsibilities}</strong>: <?php echo (!empty($global['employee']['responsibilities']) ? $global['employee']['responsibilities'] : '{$lang.not_available}'); ?></p>
            </div>
            <div class="tab" data-target="tab3">
                <div>
                    <a data-action="preview_doc" data-doc="birth_certificate"><?php echo (!empty($global['employee']['docs']['birth_certificate']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.birth_certificate}</a>
                    <a data-action="preview_doc" data-doc="address_proof"><?php echo (!empty($global['employee']['docs']['address_proof']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.address_proof}</a>
                    <a data-action="preview_doc" data-doc="ife"><?php echo (!empty($global['employee']['docs']['ife']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.ife}</a>
                    <a data-action="preview_doc" data-doc="rfc"><?php echo (!empty($global['employee']['docs']['rfc']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.rfc}</a>
                    <a data-action="preview_doc" data-doc="curp"><?php echo (!empty($global['employee']['docs']['curp']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.curp}</a>
                    <a data-action="preview_doc" data-doc="professional_license"><?php echo (!empty($global['employee']['docs']['professional_license']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.professional_license}</a>
                    <a data-action="preview_doc" data-doc="driver_license"><?php echo (!empty($global['employee']['docs']['driver_license']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.driver_license}</a>
                    <a data-action="preview_doc" data-doc="account_state"><?php echo (!empty($global['employee']['docs']['account_state']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.account_state}</a>
                </div>
                <div>
                    <a data-action="preview_doc" data-doc="medical_examination"><?php echo (!empty($global['employee']['docs']['medical_examination']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.medical_examination}</a>
                    <a data-action="preview_doc" data-doc="criminal_records"><?php echo (!empty($global['employee']['docs']['criminal_records']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.criminal_records}</a>
                    <a data-action="preview_doc" data-doc="economic_study"><?php echo (!empty($global['employee']['docs']['economic_study']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.economic_study}</a>
                    <a data-action="preview_doc" data-doc="life_insurance"><?php echo (!empty($global['employee']['docs']['life_insurance']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.life_insurance}</a>
                    <a data-action="preview_doc" data-doc="recommendation_letters_first"><?php echo (!empty($global['employee']['docs']['recommendation_letters']['first']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.recommendation_letter} 1</a>
                    <a data-action="preview_doc" data-doc="recommendation_letters_second"><?php echo (!empty($global['employee']['docs']['recommendation_letters']['second']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.recommendation_letter} 2</a>
                    <a data-action="preview_doc" data-doc="recommendation_letters_third"><?php echo (!empty($global['employee']['docs']['recommendation_letters']['third']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.recommendation_letter} 3</a>
                    <a data-action="preview_doc" data-doc="work_contract"><?php echo (!empty($global['employee']['docs']['work_contract']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.work_contract}</a>
                </div>
                <div>
                    <a data-action="preview_doc" data-doc="resignation_letter"><?php echo (!empty($global['employee']['docs']['resignation_letter']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.resignation_letter}</a>
                    <a data-action="preview_doc" data-doc="material_responsive"><?php echo (!empty($global['employee']['docs']['material_responsive']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.material_responsive}</a>
                    <a data-action="preview_doc" data-doc="privacy_notice"><?php echo (!empty($global['employee']['docs']['privacy_notice']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.privacy_notice}</a>
                    <a data-action="preview_doc" data-doc="regulation"><?php echo (!empty($global['employee']['docs']['regulation']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.regulation}</a>
                </div>
            </div>
            <div class="tab" data-target="tab4">
                <h6><strong>{$lang.emergency_contact} 1</strong>: <?php echo (!empty($global['employee']['emergency_contacts']['first']['name']) ? $global['employee']['emergency_contacts']['first']['name'] . ' | + (' . $global['employee']['emergency_contacts']['first']['phone']['country'] . ') ' . $global['employee']['emergency_contacts']['first']['phone']['number'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.emergency_contact} 2</strong>: <?php echo (!empty($global['employee']['emergency_contacts']['second']['name']) ? $global['employee']['emergency_contacts']['second']['name'] . ' | + (' . $global['employee']['emergency_contacts']['second']['phone']['country'] . ') ' . $global['employee']['emergency_contacts']['second']['phone']['number'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.emergency_contact} 3</strong>: <?php echo (!empty($global['employee']['emergency_contacts']['third']['name']) ? $global['employee']['emergency_contacts']['third']['name'] . ' | + (' . $global['employee']['emergency_contacts']['third']['phone']['country'] . ') ' . $global['employee']['emergency_contacts']['third']['phone']['number'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.emergency_contact} 4</strong>: <?php echo (!empty($global['employee']['emergency_contacts']['fourth']['name']) ? $global['employee']['emergency_contacts']['fourth']['name'] . ' | + (' . $global['employee']['emergency_contacts']['fourth']['phone']['country'] . ') ' . $global['employee']['emergency_contacts']['fourth']['phone']['number'] : '{$lang.not_available}'); ?></h6>
            </div>
        </div>
    </div>
    <div class="scanner-3">
        <h4><img src="{$path.images}marbu_logotype_color.png">Marbu {$lang.laboratory} | {$lang.tests_results}</h4>
        <div class="tabs" data-tab-active="tab1">
            <ul>
                <li data-tab-target="tab1" class="view">{$lang.alcoholic}</li>
                <li data-tab-target="tab2">{$lang.antidoping}</li>
                <li data-tab-target="tab3">{$lang.covid}</li>
            </ul>
            <div class="tab" data-target="tab1">
                <div class="tbl-st-4">
                    <h4>
                        <?php if (Permissions::user(['create_alcoholic']) == true) : ?>
                            <a href="/laboratory/create/alcoholic/<?php echo $global['employee']['nie']; ?>" class="success">{$lang.do_test}</a>
                        <?php endif; ?>
                        <span><?php echo count($global['employee']['custody_chanins']['alcoholic']); ?> {$lang.performed_tests}</span>
                    </h4>
                    <?php if (!empty($global['employee']['custody_chanins']['alcoholic'])) : ?>
                        <?php foreach ($global['employee']['custody_chanins']['alcoholic'] as $key => $value) : ?>
                            <div>
                                <h5><?php echo Dates::format_date($value['date'], 'long'); ?></h5>
                                <h6>
                                    <?php echo (!empty($value['tests']['1']) ? '<span class="' . (($value['tests']['1'] <= '0') ? 'success' : (($value['tests']['1'] > '0' AND $value['tests']['1'] < '0.20') ? 'warning' : 'alert')) . '">' . number_format($value['tests']['1'], 2, '.', '') . '</span>' : ''); ?>
                                    <?php echo (!empty($value['tests']['2']) ? '<span class="' . (($value['tests']['2'] <= '0') ? 'success' : (($value['tests']['2'] > '0' AND $value['tests']['2'] < '0.20') ? 'warning' : 'alert')) . '">' . number_format($value['tests']['2'], 2, '.', '') . '</span>' : ''); ?>
                                    <?php echo (!empty($value['tests']['3']) ? '<span class="' . (($value['tests']['3'] <= '0') ? 'success' : (($value['tests']['3'] > '0' AND $value['tests']['3'] < '0.20') ? 'warning' : 'alert')) . '">' . number_format($value['tests']['3'], 2, '.', '') . '</span>' : ''); ?>
                                </h6>
                                <a data-action="load_custody_chanin" data-type="alcoholic" data-key="<?php echo $key; ?>"><i class="fas fa-info-circle"></i><span>{$lang.load_custody_chanin}</span></a>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab" data-target="tab2">
                <div class="tbl-st-4">
                    <h4>
                        <?php if (Permissions::user(['create_antidoping']) == true) : ?>
                            <a href="/laboratory/create/antidoping/<?php echo $global['employee']['nie']; ?>" class="success">{$lang.do_test}</a>
                        <?php endif; ?>
                        <span><?php echo count($global['employee']['custody_chanins']['antidoping']); ?> {$lang.performed_tests}</span>
                    </h4>
                    <?php if (!empty($global['employee']['custody_chanins']['antidoping'])) : ?>
                        <?php foreach ($global['employee']['custody_chanins']['antidoping'] as $key => $value) : ?>
                            <div>
                                <h5><?php echo Dates::format_date($value['date'], 'long'); ?></h5>
                                <h6>
                                    <?php echo (!empty($value['analysis']['COC']) ? '<span class="' . (!empty($value['analysis']['COC']) ? (($value['analysis']['COC'] == 'positive') ? 'alert' : 'success') : '') . '">COC</span>' : ''); ?>
                                    <?php echo (!empty($value['analysis']['THC']) ? '<span class="' . (!empty($value['analysis']['THC']) ? (($value['analysis']['THC'] == 'positive') ? 'alert' : 'success') : '') . '">THC</span>' : ''); ?>
                                    <?php echo (!empty($value['analysis']['ANF']) ? '<span class="' . (!empty($value['analysis']['ANF']) ? (($value['analysis']['ANF'] == 'positive') ? 'alert' : 'success') : '') . '">ANF</span>' : ''); ?>
                                    <?php echo (!empty($value['analysis']['MET']) ? '<span class="' . (!empty($value['analysis']['MET']) ? (($value['analysis']['MET'] == 'positive') ? 'alert' : 'success') : '') . '">MET</span>' : ''); ?>
                                    <?php echo (!empty($value['analysis']['BZD']) ? '<span class="' . (!empty($value['analysis']['BZD']) ? (($value['analysis']['BZD'] == 'positive') ? 'alert' : 'success') : '') . '">BZD</span>' : ''); ?>
                                    <?php echo (!empty($value['analysis']['OPI']) ? '<span class="' . (!empty($value['analysis']['OPI']) ? (($value['analysis']['OPI'] == 'positive') ? 'alert' : 'success') : '') . '">OPI</span>' : ''); ?>
                                    <?php echo (!empty($value['analysis']['BAR']) ? '<span class="' . (!empty($value['analysis']['BAR']) ? (($value['analysis']['BAR'] == 'positive') ? 'alert' : 'success') : '') . '">BAR</span>' : ''); ?>
                                </h6>
                                <a data-action="load_custody_chanin" data-type="antidoping" data-key="<?php echo $key; ?>"><i class="fas fa-info-circle"></i><span>{$lang.load_custody_chanin}</span></a>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="tab" data-target="tab3"></div>
        </div>
    </div>
</main>
<section class="modal" data-modal="preview_doc">
    <div class="content">
        <main>
            <div class="preview-docs"></div>
            <fieldset class="fields-group">
                <div class="button">
                    <a class="success" button-close><i class="fas fa-check"></i></a>
                </div>
            </fieldset>
        </main>
    </div>
</section>
<section class="modal" data-modal="load_custody_chanin">
    <div class="content">
        <main>
            <article class="scanner-4"></article>
            <fieldset class="fields-group">
                <div class="button">
                    <a class="success" button-close><i class="fas fa-check"></i></a>
                </div>
            </fieldset>
        </main>
    </div>
</section>
