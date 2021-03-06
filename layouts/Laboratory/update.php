<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Laboratory/update.js?v=1.0']);

?>

%{header}%
<main class="workspace unmodbar">
    <article class="scanner-4 create">
        <form name="update_custody_chain">
            <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                <h2>{$lang.laboratory_identification}</h2>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="text" value="<?php echo $global['custody_chain']['laboratory_name']; ?> | <?php echo $global['custody_chain']['laboratory_rfc']; ?> | <?php echo $global['custody_chain']['laboratory_sanitary_opinion']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.laboratory}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" value="<?php echo $global['custody_chain']['taker_name']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.taker}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" value="<?php echo $global['custody_chain']['collector_name']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.collector}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php endif; ?>
            <h2>{$lang.donor_identification}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span6">
                        <div class="text">
                            <input type="text" name="firstname" value="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $global['custody_chain']['contact']['firstname'] : $global['custody_chain']['employee_firstname']); ?>" <?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? '' : 'disabled') ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.firstname}</h6>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="text">
                            <input type="text" name="lastname" value="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? $global['custody_chain']['contact']['lastname'] : $global['custody_chain']['employee_lastname']); ?>" <?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? '' : 'disabled') ?>>
                        </div>
                        <div class="title">
                            <h6>{$lang.lastname}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <select name="sex">
                                    <option value="male" <?php echo (($global['custody_chain']['contact']['sex'] == 'male') ? 'selected' : ''); ?>>{$lang.male}</option>
                                    <option value="female" <?php echo (($global['custody_chain']['contact']['sex'] == 'female') ? 'selected' : ''); ?>>{$lang.female}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.sex}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="date" name="birth_date" value="<?php echo $global['custody_chain']['contact']['birth_date']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.birth_date}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="age" value="<?php echo $global['custody_chain']['contact']['age']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.age}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="nationality" value="<?php echo $global['custody_chain']['contact']['nationality']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.nationality}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="ife" value="<?php echo $global['custody_chain']['contact']['ife']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.ife}</h6>
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
                                        <option value="<?php echo $value['lada']; ?>" <?php echo (($global['custody_chain']['contact']['phone']['country'] == $value['lada']) ? 'selected' : ''); ?>><?php echo $value['name'][Session::get_value('vkye_lang')] . ' +' . $value['lada']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="phone_number" value="<?php echo $global['custody_chain']['contact']['phone']['number']; ?>" placeholder="{$lang.number}">
                            </div>
                            <div class="title">
                                <h6>{$lang.phone}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="lang">
                                    <option value="es" <?php echo (($global['custody_chain']['contact']['lang'] == 'es') ? 'selected' : '') ?>>{$lang.es}</option>
                                    <option value="en" <?php echo (($global['custody_chain']['contact']['lang'] == 'en') ? 'selected' : '') ?>>{$lang.en}</option>
                                    <option value="pr" <?php echo (($global['custody_chain']['contact']['lang'] == 'pr') ? 'selected' : '') ?>>{$lang.pr}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.language}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php endif; ?>
            <h2>{$lang.exam_reasons}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'span12' : 'span4'); ?>">
                        <div class="text">
                            <input type="text" value="<?php echo $global['custody_chain']['token'] ?>" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.token}</h6>
                        </div>
                    </div>
                    <?php if (Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') : ?>
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
                    <?php endif; ?>
                </div>
            </fieldset>
            <?php if ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an' OR $global['custody_chain']['type'] == 'covid_ac') : ?>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span4">
                            <div class="text">
                                <input type="text" value="{$lang.<?php echo $global['custody_chain']['type']; ?>_exam}" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.exam}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="date" name="start_process" value="<?php echo $global['custody_chain']['start_process']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.start_process}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="date" name="end_process" value="<?php echo (!empty($global['custody_chain']['end_process']) ? $global['custody_chain']['end_process'] : Dates::current_date()); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.end_process}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php endif; ?>
            <fieldset class="fields-group">
                <div class="row">
                    <?php if ((Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') AND $global['custody_chain']['type'] == 'alcoholic') : ?>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="test_1" value="<?php echo $global['custody_chain']['results']['1']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 1</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="test_2" value="<?php echo $global['custody_chain']['results']['2']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 2</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" name="test_3" value="<?php echo $global['custody_chain']['results']['3']; ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 3</h6>
                            </div>
                        </div>
                    <?php elseif ((Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') OR $global['custody_chain']['type'] == 'antidoping') : ?>
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
                        <div class="span1">
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
                        <div class="span1">
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
                        <div class="span1">
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
                        <div class="span1">
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
                    <?php elseif ($global['custody_chain']['type'] == 'covid_pcr' OR $global['custody_chain']['type'] == 'covid_an') : ?>
                        <div class="span4">
                            <div class="text">
                                <select name="test_result">
                                    <option value="negative" <?php echo (($global['custody_chain']['results']['result'] == 'negative') ? 'selected' : '') ?>>{$lang.negative}</option>
                                    <option value="positive" <?php echo (($global['custody_chain']['results']['result'] == 'positive') ? 'selected' : '') ?>>{$lang.positive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.result}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <select name="test_unity">
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
                                    <option value="not_detected" <?php echo (($global['custody_chain']['results']['reference_values'] == 'not_detected') ? 'selected' : '') ?>>{$lang.not_detected}</option>
                                    <option value="detected" <?php echo (($global['custody_chain']['results']['reference_values'] == 'detected') ? 'selected' : '') ?>>{$lang.detected}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.reference_values}</h6>
                            </div>
                        </div>
                    <?php elseif ($global['custody_chain']['type'] == 'covid_ac') : ?>
                        <div class="span2">
                            <div class="text">
                                <select name="test_igm_result">
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igm']['result'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igm']['result'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgM {$lang.result}</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_igm_unity">
                                    <option value="INDEX" <?php echo (($global['custody_chain']['results']['igm']['unity'] == 'INDEX') ? 'selected' : '') ?>>{$lang.index}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgM {$lang.unity}</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_igm_reference_values">
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igm']['reference_values'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igm']['reference_values'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgM {$lang.reference_values}</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_igg_result">
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igg']['result'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igg']['result'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgG {$lang.result}</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_igg_unity">
                                    <option value="INDEX" <?php echo (($global['custody_chain']['results']['igg']['unity'] == 'INDEX') ? 'selected' : '') ?>>{$lang.index}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgG {$lang.unity}</h6>
                            </div>
                        </div>
                        <div class="span2">
                            <div class="text">
                                <select name="test_igg_reference_values">
                                    <option value="not_reactive" <?php echo (($global['custody_chain']['results']['igg']['reference_values'] == 'not_reactive') ? 'selected' : '') ?>>{$lang.not_reactive}</option>
                                    <option value="reactive" <?php echo (($global['custody_chain']['results']['igg']['reference_values'] == 'reactive') ? 'selected' : '') ?>>{$lang.reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgG {$lang.reference_values}</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>
            <?php if ((Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') AND ($global['custody_chain']['type'] == 'alcoholic' OR $global['custody_chain']['type'] == 'antidoping')) : ?>
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
            <h2>{$lang.authorization_chemical}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <?php if (Session::get_value('vkye_user')['god'] == 'deactivate' OR Session::get_value('vkye_user')['god'] == 'activate_but_sleep') : ?>
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
                    <?php endif; ?>
                    <div class="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'span6' : 'span4'); ?>">
                        <div class="text">
                            <input type="date" name="date" value="<?php echo $global['custody_chain']['date']; ?>">
                        </div>
                        <div class="title">
                            <h6>{$lang.date}</h6>
                        </div>
                    </div>
                    <div class="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'span6' : 'span4'); ?>">
                        <div class="text">
                            <input type="time" name="hour" value="<?php echo $global['custody_chain']['hour']; ?>">
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
                            <select name="chemical">
                                <?php foreach ($global['chemicals'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>" <?php echo (($global['custody_chain']['chemical'] == $value['id']) ? 'selected' : ''); ?>><?php echo $value['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.chemical}</h6>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="text">
                            <input type="text" name="comments" value="<?php echo $global['custody_chain']['comments']; ?>">
                        </div>
                        <div class="title">
                            <h6>{$lang.comments}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fields-group">
                <div class="button">
                    <a class="alert" data-action="go_back"><i class="fas fa-times"></i></a>
                    <button type="submit" class="auto <?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'warning' : 'success'); ?>" data-save="only_save"><i class="<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'fas fa-save' : 'fas fa-check'); ?>"></i>{$lang.<?php echo ((Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') ? 'only_save' : 'save'); ?>}</button>
                    <?php if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up') : ?>
                        <button type="submit" class="auto success" data-save="save_and_send"><i class="fas fa-envelope"></i>{$lang.save_and_send}</button>
                    <?php endif; ?>
                </div>
            </fieldset>
        </form>
    </article>
</main>
