<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.css']);
$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.js']);
$this->dependencies->add(['js', '{$path.js}Laboratory/update.js']);

?>

%{header}%
<main class="workspace unmodbar">
    <article class="scanner-4 create">
        <header>
            <figure>
                <img src="{$path.images}marbu_logotype_color.png">
            </figure>
            <h1>{$lang.custody_chain} | {$lang.<?php echo $global['custody_chain']['type']; ?>} | {$lang.token}: <?php echo $global['custody_chain']['token']; ?></h1>
            <figure>
                <img src="<?php echo (!empty(Session::get_value('vkye_account')['avatar']) ? '{$path.uploads}' . Session::get_value('vkye_account')['avatar'] : '{$path.images}logotype_color.png'); ?>">
            </figure>
        </header>
        <form name="update_custody_chain">
            <p>{$lang.custody_chain_alert_1}</p>
            <h2>{$lang.donor_identification}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span6">
                        <div class="text">
                            <input type="text" name="lastname" value="<?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? $global['custody_chain']['contact']['lastname'] : $global['custody_chain']['employee_lastname']); ?>" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? '' : 'disabled'); ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.lastname}</h6>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="text">
                            <input type="text" name="firstname" value="<?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? $global['custody_chain']['contact']['firstname'] : $global['custody_chain']['employee_firstname']); ?>" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? '' : 'disabled'); ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.firstname}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span6">
                        <div class="text">
                            <input type="text" value="<?php echo Session::get_value('vkye_account')['name']; ?>" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.institution}</h6>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="text">
                            <input type="text" name="ife" value="<?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? $global['custody_chain']['contact']['ife'] : $global['custody_chain']['employee_ife']); ?>" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? '' : 'disabled'); ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.id}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <input type="date" name="birth_date" value="<?php echo (empty($global['custody_chain']['employee']) ? $global['custody_chain']['contact']['birth_date'] : $global['custody_chain']['employee_birth_date']); ?>" <?php echo (empty($global['custody_chain']['employee']) ? '' : 'disabled'); ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.birth_date}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="number" name="age" value="<?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? $global['custody_chain']['contact']['age'] : Functions::format_age($global['custody_chain']['employee_birth_date'], true)); ?>" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? '' : 'disabled'); ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.age}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <select name="sex" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? '' : 'disabled') ?>>
                                <option value="male" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? (($global['custody_chain']['contact']['sex'] == 'male') ? 'selected' : '') : (($global['custody_chain']['employee_sex'] == 'male') ? 'selected' : '')); ?>>{$lang.male}</option>
                                <option value="female" <?php echo ((($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) ? (($global['custody_chain']['contact']['sex'] == 'female') ? 'selected' : '') : (($global['custody_chain']['employee_sex'] == 'female') ? 'selected' : '')); ?>>{$lang.female}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.sex}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php if (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND empty($global['custody_chain']['employee'])) : ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="email" name="email" value="<?php echo $global['custody_chain']['contact']['email']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.email}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="compound st-1-left">
                                <select name="phone_country">
                                    <?php foreach (Functions::countries() as $value) : ?>
                                        <option value="<?php echo $value['lada']; ?>" <?php echo (($global['custody_chain']['contact']['phone']['country'] == $value['lada']) ? 'selected' : ''); ?>><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="phone_number" value="<?php echo $global['custody_chain']['contact']['phone']['number']; ?>" placeholder="{$lang.number}">
                            </div>
                            <div class="title">
                                <h6>{$lang.phone}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="travel_to" value="<?php echo $global['custody_chain']['contact']['travel_to']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.travel_to}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php endif; ?>
            <h2>{$lang.exam_reasons}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <input type="text" value="<?php echo $global['custody_chain']['token'] ?>" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.token}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="text" value="{$lang.<?php echo $global['custody_chain']['type'] ?>}" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.type}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <select name="reason">
                                <option value="random" <?php echo (($global['custody_chain']['reason'] == 'random') ? 'selected' : '') ?>>{$lang.random}</option>
                                <option value="reasonable_suspicion" <?php echo (($global['custody_chain']['reason'] == 'reasonable_suspicion') ? 'selected' : '') ?>>{$lang.reasonable_suspicion}</option>
                                <option value="periodic" <?php echo (($global['custody_chain']['reason'] == 'periodic') ? 'selected' : '') ?>>{$lang.periodic}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.reason}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php if ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') : ?>
                <fieldset class="fields-group">
                    <div class="text">
                        <input type="text" value="{$lang.<?php echo $global['custody_chain']['type']; ?>_exam}" disabled>
                    </div>
                    <div class="title">
                        <h6>{$lang.exam}</h6>
                    </div>
                </fieldset>
            <?php endif; ?>
            <fieldset class="fields-group">
                <div class="row">
                    <?php if ($global['custody_chain']['type'] == 'alcoholic') : ?>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="test_1" value="<?php echo $global['custody_chain']['results']['1']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 1</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="test_2" value="<?php echo $global['custody_chain']['results']['2']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 2</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="test_3" value="<?php echo $global['custody_chain']['results']['3']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 3</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($global['custody_chain']['type'] == 'antidoping') : ?>
                        <div class="span2">
                            <div class="text">
                                <select name="test_COC">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['COC'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['COC'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>COC</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_THC">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['THC'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['THC'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>THC</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_MET">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['MET'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['MET'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>MET</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_ANF">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['ANF'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['ANF'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>ANF</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_BZD">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['BZD'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['BZD'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>BZD</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_OPI">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['OPI'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['OPI'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>OPI</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_BAR">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['BAR'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['BAR'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>BAR</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') : ?>
                        <div class="span4">
                            <div class="text">
                                <select name="test_result">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['result'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['result'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.result}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="test_unity">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="INDEX" <?php echo (($global['custody_chain']['results']['unity'] == 'INDEX') ? 'selected' : '') ?>>{$lang.index}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.unity}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="test_reference_values">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="detected" <?php echo (($global['custody_chain']['results']['reference_values'] == 'detected') ? 'selected' : '') ?>>{$lang.detected}</option>
                                    <option value="not_detected" <?php echo (($global['custody_chain']['results']['reference_values'] == 'not_detected') ? 'selected' : '') ?>>{$lang.not_detected}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.reference_values}</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($global['custody_chain']['type'] == 'covid_ac') : ?>
                        <div class="span3">
                            <div class="text">
                                <select name="test_igm_result">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igm']['result'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igm']['result'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgM {$lang.result}</h6>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="text">
                                <select name="test_igm_reference_values">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igm']['reference_values'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igm']['reference_values'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgM {$lang.reference_values}</h6>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="text">
                                <select name="test_igg_result">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igg']['result'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igg']['result'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgG {$lang.result}</h6>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="text">
                                <select name="test_igg_reference_values">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igg']['reference_values'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igg']['reference_values'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgG {$lang.reference_values}</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>
            <?php if ($global['custody_chain']['type'] == 'alcoholic' OR $global['custody_chain']['type'] == 'antidoping') : ?>
                <h2>{$lang.medical_information}</h2>
                <fieldset class="fields-group">
                    <div class="text">
                        <textarea name="medicines"><?php echo $global['custody_chain']['medicines']; ?></textarea>
                    </div>
                    <div class="title">
                        <h6>{$lang.medical_treatment_prescription_drugs}</h6>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span8">
                            <div class="text">
                                <input type="text" name="prescription_issued_by" value="<?php echo $global['custody_chain']['prescription']['issued_by']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.prescription_issued_by}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="date" name="prescription_date" value="<?php echo $global['custody_chain']['prescription']['date']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.date}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php endif; ?>
            <?php if (($global['custody_chain']['type'] == 'alcoholic' OR $global['custody_chain']['type'] == 'antidoping') OR (($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') AND !empty($global['custody_chain']['employee']))) : ?>
                <h2>{$lang.authorization_donor}</h2>
                <p>{$lang.custody_chain_alert_<?php echo $global['custody_chain']['type']; ?>_1}</p>
                <fieldset class="fields-group">
                    <div class="signature" id="employee_signature">
                        <canvas></canvas>
                        <div class="sign_by_first_time">
                            <a data-action="clean_employee_signature"><i class="fas fa-trash"></i></a>
                        </div>
                        <?php if (!empty($global['custody_chain']['signatures']['employee'])) : ?>
                            <div class="sign_again">
                                <p>{$lang.this_custody_chain_was_signed}</p>
                                <a data-action="sign_again">{$lang.sign_again}</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="title">
                        <h6>{$lang.signature}</h6>
                    </div>
                </fieldset>
            <?php endif; ?>
            <h2>{$lang.authorization_collector}</h2>
            <p>{$lang.custody_chain_alert_<?php echo $global['custody_chain']['type']; ?>_2}</p>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <select name="location">
                                <option value="">{$lang.undefined}</option>
                                <?php foreach ($global['locations'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo (($global['custody_chain']['location'] == $value['id']) ? 'selected' : ''); ?>><?php echo $value['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.location}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="date" name="date" value="<?php echo (!empty($global['custody_chain']['date']) ? $global['custody_chain']['date'] : Dates::current_date()); ?>">
                        </div>
                        <div class="title">
                            <h6>{$lang.date}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="time" name="hour" value="<?php echo (!empty($global['custody_chain']['hour']) ? $global['custody_chain']['hour'] : Dates::current_hour()); ?>">
                        </div>
                        <div class="title">
                            <h6>{$lang.hour}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <select name="collector">
                                <?php foreach (Functions::collectors() as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo (($global['custody_chain']['collector'] == $value['id']) ? 'selected' : ''); ?>><?php echo $value['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.name}</h6>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="text">
                            <input type="text" name="comments" value="<?php echo $global['custody_chain']['comments']; ?>">
                        </div>
                        <div class="title">
                            <h6>{$lang.conformity_result}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="button">
                    <a class="alert" data-action="go_back"><i class="fas fa-times"></i></a>
                    <button type="submit" class="success"><i class="fas fa-check"></i></button>
                </div>
            </fieldset>
        </form>
    </article>
</main>