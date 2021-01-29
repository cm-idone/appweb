<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Employees/scanner.js']);

?>

%{header}%
<main class="unmodbar">
    <div class="scanner-1">
        <figure>
            <img src="<?php echo (!empty($data['employee']['avatar']) ? '{$path.uploads}' . $data['employee']['avatar'] : '{$path.images}employee.png'); ?>">
        </figure>
        <h4><?php echo $data['employee']['firstname'] . ' ' . $data['employee']['lastname']; ?></h4>
        <span><?php echo $data['employee']['nie']; ?> | <?php echo (($data['employee']['blocked'] == true) ? '{$lang.blocked}' : '{$lang.active}'); ?></span>
    </div>
    <div class="scanner-2">
        <div class="tabs" data-tab-active="tab1">
            <ul>
                <li data-tab-target="tab1" class="view">{$lang.personal_proceedings}</li>
                <li data-tab-target="tab2">{$lang.labor_proceedings}</li>
                <li data-tab-target="tab3">{$lang.emergency_contacts}</li>
            </ul>
            <div class="tab" data-target="tab1">
                <h6><strong>{$lang.sex}</strong>: {$lang.<?php echo (!empty($data['employee']['sex']) ? $data['employee']['sex'] : '{$lang.not_available}'); ?>}</h6>
                <h6><strong>{$lang.birth_date}</strong>: <?php echo (!empty($data['employee']['birth_date']) ? Dates::format_date($data['employee']['birth_date'], 'long') : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.age}</strong>: <?php echo (!empty($data['employee']['birth_date']) ? Functions::format_age($data['employee']['birth_date']) : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.ife}</strong>: <?php echo (!empty($data['employee']['ife']) ? $data['employee']['ife'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.nss}</strong>: <?php echo (!empty($data['employee']['nss']) ? $data['employee']['nss'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.rfc}</strong>: <?php echo (!empty($data['employee']['rfc']) ? $data['employee']['rfc'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.curp}</strong>: <?php echo (!empty($data['employee']['curp']) ? $data['employee']['curp'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.account_number}</strong>: <?php echo ((!empty($data['employee']['bank']['name']) AND !empty($data['employee']['bank']['account'])) ? '(' . $data['employee']['bank']['name'] . ') ' . $data['employee']['bank']['account'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.nsv}</strong>: <?php echo (!empty($data['employee']['nsv']) ? $data['employee']['nsv'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.email}</strong>: <?php echo (!empty($data['employee']['email']) ? $data['employee']['email'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.phone}</strong>: <?php echo ((!empty($data['employee']['phone']['country']) AND !empty($data['employee']['phone']['number'])) ? '+ (' . $data['employee']['phone']['country'] . ') ' . $data['employee']['phone']['number'] : '{$lang.not_available}'); ?></h6>
            </div>
            <div class="tab" data-target="tab2">
                <h6><strong>{$lang.rank}</strong>: <?php echo (!empty($data['employee']['rank']) ? $data['employee']['rank'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.nie}</strong>: <?php echo $data['employee']['nie']; ?></h6>
                <h6><strong>{$lang.admission_date}</strong>: <?php echo (!empty($data['employee']['admission_date']) ? Dates::format_date($data['employee']['admission_date'], 'long') : '{$lang.not_available}'); ?></h6>
                <p><strong>{$lang.responsibilities}</strong>: <?php echo (!empty($data['employee']['responsibilities']) ? $data['employee']['responsibilities'] : '{$lang.not_available}'); ?></p>
            </div>
            <div class="tab" data-target="tab3">
                <h6><strong>{$lang.emergency_contact} 1</strong>: <?php echo (!empty($data['employee']['emergency_contacts']['first']['name']) ? $data['employee']['emergency_contacts']['first']['name'] . ' | + (' . $data['employee']['emergency_contacts']['first']['phone']['country'] . ') ' . $data['employee']['emergency_contacts']['first']['phone']['number'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.emergency_contact} 2</strong>: <?php echo (!empty($data['employee']['emergency_contacts']['second']['name']) ? $data['employee']['emergency_contacts']['second']['name'] . ' | + (' . $data['employee']['emergency_contacts']['second']['phone']['country'] . ') ' . $data['employee']['emergency_contacts']['second']['phone']['number'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.emergency_contact} 3</strong>: <?php echo (!empty($data['employee']['emergency_contacts']['third']['name']) ? $data['employee']['emergency_contacts']['third']['name'] . ' | + (' . $data['employee']['emergency_contacts']['third']['phone']['country'] . ') ' . $data['employee']['emergency_contacts']['third']['phone']['number'] : '{$lang.not_available}'); ?></h6>
                <h6><strong>{$lang.emergency_contact} 4</strong>: <?php echo (!empty($data['employee']['emergency_contacts']['fourth']['name']) ? $data['employee']['emergency_contacts']['fourth']['name'] . ' | + (' . $data['employee']['emergency_contacts']['fourth']['phone']['country'] . ') ' . $data['employee']['emergency_contacts']['fourth']['phone']['number'] : '{$lang.not_available}'); ?></h6>
            </div>
        </div>
    </div>
    <div class="scanner-3">
        <div class="tabs" data-tab-active="tab1">
            <ul>
                <li data-tab-target="tab1" class="view">{$lang.labor}</li>
                <li data-tab-target="tab2">{$lang.alcoholic}</li>
                <li data-tab-target="tab3">{$lang.antidoping}</li>
                <li data-tab-target="tab4">{$lang.covid}</li>
                <li data-tab-target="tab5">{$lang.polygraph}</li>
                <li data-tab-target="tab6">{$lang.psychometric}</li>
            </ul>
            <div class="tab" data-target="tab1">
                <div>
                    <a data-action="preview_doc" data-doc="birth_certificate"><?php echo (!empty($data['employee']['docs']['birth_certificate']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.birth_certificate}</a>
                    <a data-action="preview_doc" data-doc="address_proof"><?php echo (!empty($data['employee']['docs']['address_proof']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.address_proof}</a>
                    <a data-action="preview_doc" data-doc="ife"><?php echo (!empty($data['employee']['docs']['ife']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.ife}</a>
                    <a data-action="preview_doc" data-doc="rfc"><?php echo (!empty($data['employee']['docs']['rfc']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.rfc}</a>
                    <a data-action="preview_doc" data-doc="curp"><?php echo (!empty($data['employee']['docs']['curp']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.curp}</a>
                    <a data-action="preview_doc" data-doc="professional_license"><?php echo (!empty($data['employee']['docs']['professional_license']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.professional_license}</a>
                    <a data-action="preview_doc" data-doc="driver_license"><?php echo (!empty($data['employee']['docs']['driver_license']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.driver_license}</a>
                    <a data-action="preview_doc" data-doc="account_state"><?php echo (!empty($data['employee']['docs']['account_state']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.account_state}</a>
                    <a data-action="preview_doc" data-doc="medical_examination"><?php echo (!empty($data['employee']['docs']['medical_examination']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.medical_examination}</a>
                    <a data-action="preview_doc" data-doc="criminal_records"><?php echo (!empty($data['employee']['docs']['criminal_records']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.criminal_records}</a>
                    <a data-action="preview_doc" data-doc="economic_study"><?php echo (!empty($data['employee']['docs']['economic_study']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.economic_study}</a>
                    <a data-action="preview_doc" data-doc="life_insurance"><?php echo (!empty($data['employee']['docs']['life_insurance']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.life_insurance}</a>
                    <a data-action="preview_doc" data-doc="recommendation_letters_first"><?php echo (!empty($data['employee']['docs']['recommendation_letters']['first']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.recommendation_letter} 1</a>
                    <a data-action="preview_doc" data-doc="recommendation_letters_second"><?php echo (!empty($data['employee']['docs']['recommendation_letters']['second']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.recommendation_letter} 2</a>
                    <a data-action="preview_doc" data-doc="recommendation_letters_third"><?php echo (!empty($data['employee']['docs']['recommendation_letters']['third']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.recommendation_letter} 3</a>
                </div>
                <div>
                    <a data-action="preview_doc" data-doc="work_contract"><?php echo (!empty($data['employee']['docs']['work_contract']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.work_contract}</a>
                    <a data-action="preview_doc" data-doc="resignation_letter"><?php echo (!empty($data['employee']['docs']['resignation_letter']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.resignation_letter}</a>
                    <a data-action="preview_doc" data-doc="material_responsive"><?php echo (!empty($data['employee']['docs']['material_responsive']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.material_responsive}</a>
                    <a data-action="preview_doc" data-doc="privacy_notice"><?php echo (!empty($data['employee']['docs']['privacy_notice']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.privacy_notice}</a>
                    <a data-action="preview_doc" data-doc="regulation"><?php echo (!empty($data['employee']['docs']['regulation']) ? '<i class="fas fa-check-square success"></i>' : '<i class="fas fa-times-circle error"></i>'); ?> {$lang.regulation}</a>
                </div>
            </div>
            <div class="tab" data-target="tab2">
                <div class="tbl-st-4">
                    <h4>
                        <?php if (Permissions::user(['create_alcoholic']) == true) : ?>
                            <a href="/laboratory/create/alcoholic/<?php echo $data['employee']['nie']; ?>" class="success">{$lang.do_test}</a>
                        <?php endif; ?>
                        <span><?php echo count($data['employee']['custody_chanins']['alcoholic']); ?> {$lang.performed_tests}</span>
                    </h4>
                    <?php if (!empty($data['employee']['custody_chanins']['alcoholic'])) : ?>
                        <?php foreach ($data['employee']['custody_chanins']['alcoholic'] as $key => $value) : ?>
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
            <div class="tab" data-target="tab3">
                <div class="tbl-st-4">
                    <h4>
                        <?php if (Permissions::user(['create_antidoping']) == true) : ?>
                            <a href="/laboratory/create/antidoping/<?php echo $data['employee']['nie']; ?>" class="success">{$lang.do_test}</a>
                        <?php endif; ?>
                        <span><?php echo count($data['employee']['custody_chanins']['antidoping']); ?> {$lang.performed_tests}</span>
                    </h4>
                    <?php if (!empty($data['employee']['custody_chanins']['antidoping'])) : ?>
                        <?php foreach ($data['employee']['custody_chanins']['antidoping'] as $key => $value) : ?>
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
            <div class="tab" data-target="tab4"></div>
            <div class="tab" data-target="tab5"></div>
            <div class="tab" data-target="tab6"></div>
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
