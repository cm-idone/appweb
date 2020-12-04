<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.css']);
$this->dependencies->add(['js', '{$path.plugins}signature_pad/signature_pad.js']);
$this->dependencies->add(['js', '{$path.js}Laboratory/create.min.js']);

?>

%{header}%
<main class="unmodbar">
    <?php if ($data['type'] == 'alcoholic' OR $data['type'] == 'antidoping') : ?>
        <article class="scanner-4 create">
            <header>
                <figure>
                    <img src="{$path.images}marbu_logotype_color.png">
                </figure>
                <h1>{$lang.custody_chanin}</h1>
                <figure>
                    <img src="<?php echo (!empty(Session::get_value('vkye_account')['avatar']) ? '{$path.uploads}' . Session::get_value('vkye_account')['avatar'] : '{$path.images}account.png'); ?>">
                </figure>
            </header>
            <form name="create_custody_chain">
                <p>{$lang.custody_chanin_alert_1}</p>
                <h2>{$lang.donor_identification}</h2>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="text" value="<?php echo $data['employee']['lastname']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.lastname} ({$lang.paternal_maternal})</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="text" value="<?php echo $data['employee']['firstname']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.firstname}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span8">
                            <div class="text">
                                <input type="text" value="<?php echo Session::get_value('vkye_account')['name']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.institution}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="text" value="<?php echo $data['employee']['ife']; ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.ife}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span3">
                            <div class="text">
                                <input type="text" value="<?php echo Functions::format_age($data['employee']['birth_date']); ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.age}</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="text" value="<?php echo Dates::format_date($data['employee']['birth_date'], 'long'); ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.birth_date}</h6>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="text">
                                <input type="text" value="{$lang.<?php echo $data['employee']['sex']; ?>}" disabled>
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
                        <div class="span3">
                            <div class="text">
                                <input type="text" value="{$lang.<?php echo $data['type'] ?>}" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.type}</h6>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="text">
                                <select name="reason">
                                    <option value="" hidden>{$lang.choose_an_option}</option>
                                    <option value="random">{$lang.random}</option>
                                    <option value="reasonable_suspicion">{$lang.reasonable_suspicion}</option>
                                    <option value="periodic">{$lang.periodic}</option>
                                </select>
                            </div>
                            <div class="title">
                                <h6>{$lang.reason}</h6>
                            </div>
                        </div>
                        <?php if ($data['type'] == 'alcoholic') : ?>
                            <div class="span2">
                                <div class="text">
                                    <input type="text" name="test_1">
                                </div>
                                <div class="title">
                                    <h6>{$lang.test} 1</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <input type="text" name="test_2">
                                </div>
                                <div class="title">
                                    <h6>{$lang.test} 2</h6>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="text">
                                    <input type="text" name="test_3">
                                </div>
                                <div class="title">
                                    <h6>{$lang.test} 3</h6>
                                </div>
                            </div>
                        <?php elseif ($data['type'] == 'antidoping') : ?>
                            <div class="span2">
                                <div class="text">
                                    <select name="analysis_COC">
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
                                    <select name="analysis_THC">
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
                                    <select name="analysis_MET">
                                        <option value="">{$lang.undefined}</option>
                                        <option value="positive">{$lang.positive}</option>
                                        <option value="negative">{$lang.negative}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>MET</h6>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </fieldset>
                <?php if ($data['type'] == 'antidoping') : ?>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span2">
                                <div class="text">
                                    <select name="analysis_ANF">
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
                                    <select name="analysis_BZD">
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
                                    <select name="analysis_OPI">
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
                                    <select name="analysis_BAR">
                                        <option value="">{$lang.undefined}</option>
                                        <option value="positive">{$lang.positive}</option>
                                        <option value="negative">{$lang.negative}</option>
                                    </select>
                                </div>
                                <div class="title">
                                    <h6>BAR</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                <?php endif; ?>
                <h2>{$lang.medical_treatment}</h2>
                <fieldset class="fields-group">
                    <div class="text">
                        <textarea name="medicines"></textarea>
                    </div>
                    <div class="title">
                        <h6>{$lang.prescription_drugs}</h6>
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
                <h2>{$lang.authorization}</h2>
                <p>{$lang.custody_chanin_alert_<?php echo $data['type']; ?>_1}</p>
                <fieldset class="fields-group">
                    <div class="signature" id="employee_signature">
                        <canvas></canvas>
                        <div>
                            <a data-action="clean_employee_signature"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                    <div class="title">
                        <h6>{$lang.signature}</h6>
                    </div>
                </fieldset>
                <h2>{$lang.collector}</h2>
                <p>{$lang.custody_chanin_alert_<?php echo $data['type']; ?>_2}</p>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span8">
                            <div class="text">
                                <input type="text" name="collection_place">
                            </div>
                            <div class="title">
                                <h6>{$lang.place}</h6>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="text">
                                <input type="time" name="collection_hour" value="<?php echo Dates::current_hour(); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.hour}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="text">
                        <input type="text" name="result">
                    </div>
                    <div class="title">
                        <h6>{$lang.conformity_result}</h6>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="row">
                        <div class="span6">
                            <div class="text">
                                <input type="text" value="<?php echo Session::get_value('vkye_user')['firstname'] . ' ' . Session::get_value('vkye_user')['lastname'] ?>" disabled>
                            </div>
                            <div class="title">
                                <h6>{$lang.name}</h6>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="text">
                                <input type="date" name="date" value="<?php echo Dates::current_date(); ?>">
                            </div>
                            <div class="title">
                                <h6>{$lang.date}</h6>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="signature" id="collector_signature">
                        <canvas></canvas>
                        <div>
                            <a data-action="clean_collector_signature"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                    <div class="title">
                        <h6>{$lang.signature}</h6>
                    </div>
                </fieldset>
                <fieldset class="fields-group">
                    <div class="button">
                        <a class="alert" data-action="go_back"><i class="fas fa-times"></i></a>
                        <button type="submit" class="success"><i class="fas fa-plus"></i></button>
                    </div>
                </fieldset>
            </form>
        </article>
    <?php endif; ?>
</main>
