<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.css']);
$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.js']);
$this->dependencies->add(['js', '{$path.js}Laboratory/create.js']);

?>

%{header}%
<main class="workspace unmodbar">
    <article class="scanner-4 create">
        <header>
            <figure>
                <img src="{$path.images}marbu_logotype_color.png">
            </figure>
            <h1>{$lang.custody_chain} | {$lang.<?php echo $global['type']; ?>} | {$lang.new_test}</h1>
            <figure>
                <img src="<?php echo (!empty(Session::get_value('vkye_account')['avatar']) ? '{$path.uploads}' . Session::get_value('vkye_account')['avatar'] : '{$path.images}logotype_color.png'); ?>">
            </figure>
        </header>
        <form name="create_custody_chain">
            <p>{$lang.custody_chain_alert_1}</p>
            <h2>{$lang.donor_identification}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span6">
                        <div class="text">
                            <input type="text" value="<?php echo $global['employee']['lastname']; ?>" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.lastname}</h6>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="text">
                            <input type="text" value="<?php echo $global['employee']['firstname']; ?>" disabled>
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
                            <input type="text" value="<?php echo $global['employee']['ife']; ?>" disabled>
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
                            <input type="text" value="<?php echo Dates::format_date($global['employee']['birth_date'], 'long'); ?>" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.birth_date}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="text" value="<?php echo Functions::format_age($global['employee']['birth_date']); ?>" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.age}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="text" value="{$lang.<?php echo $global['employee']['sex']; ?>}" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.sex}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h2>{$lang.exam_reasons}</h2>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <input type="text" value="{$lang.auto}" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.token}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="text" value="{$lang.<?php echo $global['type'] ?>}" disabled>
                        </div>
                        <div class="title">
                            <h6>{$lang.type}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <select name="reason">
                                <option value="" class="hidden">{$lang.choose_an_option}</option>
                                <option value="random">{$lang.random}</option>
                                <option value="reasonable_suspicion">{$lang.reasonable_suspicion}</option>
                                <option value="periodic">{$lang.periodic}</option>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.reason}</h6>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php if ($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an' OR $global['type'] == 'covid_ac') : ?>
                <fieldset class="fields-group">
                    <div class="text">
                        <input type="text" value="{$lang.<?php echo $global['type']; ?>_exam}" disabled>
                    </div>
                    <div class="title">
                        <h6>{$lang.exam}</h6>
                    </div>
                </fieldset>
            <?php endif; ?>
            <fieldset class="fields-group">
                <div class="row">
                    <?php if ($global['type'] == 'alcoholic') : ?>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="test_1">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 1</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="test_2">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 2</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="number" name="test_3">
                            </div>
                            <div class="title">
                                <h6>{$lang.test} 3</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($global['type'] == 'antidoping') : ?>
                        <div class="span2">
                            <div class="text">
                                <select name="test_COC">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>BAR</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($global['type'] == 'covid_pcr' OR $global['type'] == 'covid_an') : ?>
                        <div class="span4">
                            <div class="text">
                                <select name="test_result">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="positive">{$lang.positive}</option>
                                    <option value="negative">{$lang.negative}</option>
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
                                    <option value="">{$lang.undefined}</option>
                                    <option value="detected">{$lang.detected}</option>
                                    <option value="not_detected">{$lang.not_detected}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.reference_values}</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($global['type'] == 'covid_ac') : ?>
                        <div class="span3">
                            <div class="text">
                                <select name="test_igm_result">
                                    <option value="">{$lang.undefined}</option>
                                    <option value="reactive">{$lang.reactive}</option>
                                    <option value="not_reactive">{$lang.not_reactive}</option>
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
                                    <option value="reactive">{$lang.reactive}</option>
                                    <option value="not_reactive">{$lang.not_reactive}</option>
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
                                    <option value="reactive">{$lang.reactive}</option>
                                    <option value="not_reactive">{$lang.not_reactive}</option>
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
                                    <option value="reactive">{$lang.reactive}</option>
                                    <option value="not_reactive">{$lang.not_reactive}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>IgG {$lang.reference_values}</h6>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </fieldset>
            <?php if ($global['type'] == 'alcoholic' OR $global['type'] == 'antidoping') : ?>
                <h2>{$lang.medical_information}</h2>
                <fieldset class="fields-group">
                    <div class="text">
                        <textarea name="medicines"></textarea>
                    </div>
                    <div class="title">
                        <h6>{$lang.medical_treatment_prescription_drugs}</h6>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span8">
                            <div class="text">
                                <input type="text" name="prescription_issued_by">
                            </div>
                            <div class="title">
                                <h6>{$lang.prescription_issued_by}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="date" name="prescription_date">
                            </div>
                            <div class="title">
                                <h6>{$lang.date}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php endif; ?>
            <h2>{$lang.authorization_donor}</h2>
            <p>{$lang.custody_chain_alert_<?php echo $global['type']; ?>_1}</p>
            <fieldset class="fields-group">
                <div class="signature" id="employee_signature">
                    <canvas></canvas>
                    <div class="sign_by_first_time">
                        <a data-action="clean_employee_signature"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
                <div class="title">
                    <h6>{$lang.signature}</h6>
                </div>
            </fieldset>
            <h2>{$lang.authorization_collector}</h2>
            <p>{$lang.custody_chain_alert_<?php echo $global['type']; ?>_2}</p>
            <fieldset class="fields-group">
                <div class="row">
                    <div class="span4">
                        <div class="text">
                            <select name="location">
                                <option value="">{$lang.undefined}</option>
                                <?php foreach ($global['locations'] as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.location}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="date" name="date" value="<?php echo Dates::current_date(); ?>">
                        </div>
                        <div class="title">
                            <h6>{$lang.date}</h6>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="text">
                            <input type="time" name="hour" value="<?php echo Dates::current_hour(); ?>">
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
                                <option value="" class="hidden">{$lang.choose_an_option}</option>
                                <?php foreach (Functions::collectors() as $value) : ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="title">
                            <h6>{$lang.name}</h6>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="text">
                            <input type="text" name="comments">
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
