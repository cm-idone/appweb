<?php

defined('_EXEC') or die;

$this->dependencies->add(['js', '{$path.js}Employees/index.js']);

?>

%{header}%
<header class="modbar">
    <div class="buttons">
        <fieldset class="fields-group big">
            <div class="compound st-4-left">
                <span><i class="fas fa-search"></i></span>
                <input type="text" data-search="employees" placeholder="{$lang.search}">
            </div>
        </fieldset>
        <?php if (Permissions::user(['create_employees']) == true) : ?>
            <a data-action="create_employee" class="success"><i class="fas fa-plus"></i><span>{$lang.create}</span></a>
        <?php endif; ?>
    </div>
</header>
<main>
    <div class="tbl-st-2" data-table="employees">
        <?php foreach ($global['employees'] as $value) : ?>
            <div>
                <figure>
                    <img src="<?php echo (!empty($value['avatar']) ? '{$path.uploads}' . $value['avatar'] : '{$path.images}employee.png'); ?>">
                </figure>
                <h4><?php echo $value['firstname'] . ' ' . $value['lastname']; ?></h4>
                <span><?php echo $value['nie']; ?></span>
                <div class="button">
                    <?php if (Permissions::user(['block_employees','unblock_employees']) == true) : ?>
                        <?php if ($value['blocked'] == true) : ?>
                            <a data-action="unblock_employee" data-id="<?php echo $value['id']; ?>"><i class="fas fa-lock"></i><span>{$lang.unblock}</span></a>
                        <?php elseif ($value['blocked'] == false) : ?>
                            <a data-action="block_employee" data-id="<?php echo $value['id']; ?>"><i class="fas fa-unlock"></i><span>{$lang.block}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['delete_employees']) == true) : ?>
                        <?php if ($value['blocked'] == false) : ?>
                            <a data-action="delete_employee" data-id="<?php echo $value['id']; ?>" class="alert"><i class="fas fa-trash"></i><span>{$lang.delete}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['update_employees']) == true) : ?>
                        <?php if ($value['blocked'] == false) : ?>
                            <a data-action="update_employee" data-id="<?php echo $value['id']; ?>" class="warning"><i class="fas fa-pen"></i><span>{$lang.update}</span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Permissions::user(['control_employees']) == true) : ?>
                        <a href="{$path.uploads}<?php echo $value['qr']; ?>" download="<?php echo $value['nie']; ?>.png"><i class="fas fa-qrcode"></i><span>{$lang.download_id_one}</span></a>
                        <a href="/<?php echo Session::get_value('vkye_account')['path']; ?>/<?php echo $value['nie']; ?>"><i class="fas fa-id-card-alt"></i><span>{$lang.view_id_one}</span></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php if (Permissions::user(['create_employees','update_employees']) == true) : ?>
    <section class="modal" data-modal="create_employee">
        <div class="content">
            <main>
                <form>
                    <fieldset class="fields-group">
                        <div class="uploader" data-low-uploader>
                            <figure data-preview>
                                <img src="{$path.images}employee.png">
                                <a data-select><i class="fas fa-pen"></i></a>
                            </figure>
                            <input type="file" name="avatar" accept="image/*" data-select>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="text" name="firstname">
                                </div>
                                <div class="title">
                                    <h6>{$lang.firstname}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="text">
                                    <input type="text" name="lastname">
                                </div>
                                <div class="title">
                                    <h6>{$lang.lastname}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="compound st-5-double">
                                    <div>
                                        <label>
                                            <input type="radio" name="sex" value="male" checked>
                                            <span class="checked">{$lang.male}</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio" name="sex" value="female">
                                            <span>{$lang.female}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="title">
                                    <h6>{$lang.sex}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="date" name="birth_date">
                                </div>
                                <div class="title">
                                    <h6>{$lang.birth_date}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="ife">
                                </div>
                                <div class="title">
                                    <h6>{$lang.ife}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="nss">
                                </div>
                                <div class="title">
                                    <h6>{$lang.nss}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="rfc">
                                </div>
                                <div class="title">
                                    <h6>{$lang.rfc}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="curp">
                                </div>
                                <div class="title">
                                    <h6>{$lang.curp}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="bank_name">
                                </div>
                                <div class="title">
                                    <h6>{$lang.bank}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="bank_account">
                                </div>
                                <div class="title">
                                    <h6>{$lang.account_number}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="nsv">
                                </div>
                                <div class="title">
                                    <h6>{$lang.nsv}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span6">
                                <div class="text">
                                    <input type="text" name="email">
                                </div>
                                <div class="title">
                                    <h6>{$lang.email}</h6>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="compound st-1-left">
                                    <select name="phone_country">
                                        <option value="">{$lang.country} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="phone_number" placeholder="{$lang.number}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="breaker">
                            <span></span>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span4">
                                <div class="text">
                                    <input type="text" name="rank">
                                </div>
                                <div class="title">
                                    <h6>{$lang.rank}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="compound st-2-left">
                                    <a data-action="generate_random_nie"><i class="fas fa-redo" aria-hidden="true"></i></a>
                                    <input type="text" name="nie">
                                </div>
                                <div class="title">
                                    <h6>{$lang.nie}</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="text">
                                    <input type="date" name="admission_date">
                                </div>
                                <div class="title">
                                    <h6>{$lang.admission_date}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="text">
                            <textarea name="responsibilities"></textarea>
                        </div>
                        <div class="title">
                            <h6>{$lang.responsibilities}</h6>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="breaker">
                            <span></span>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="emergency_contacts_first_name">
                                </div>
                                <div class="title">
                                    <h6>{$lang.emergency_contact} 1</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="compound st-1-left">
                                    <select name="emergency_contacts_first_phone_country">
                                        <option value="">{$lang.country} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="emergency_contacts_first_phone_number" placeholder="{$lang.number}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="emergency_contacts_second_name">
                                </div>
                                <div class="title">
                                    <h6>{$lang.emergency_contact} 2</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="compound st-1-left">
                                    <select name="emergency_contacts_second_phone_country">
                                        <option value="">{$lang.country} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="emergency_contacts_second_phone_number" placeholder="{$lang.number}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="emergency_contacts_third_name">
                                </div>
                                <div class="title">
                                    <h6>{$lang.emergency_contact} 3</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="compound st-1-left">
                                    <select name="emergency_contacts_third_phone_country">
                                        <option value="">{$lang.country} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="emergency_contacts_third_phone_number" placeholder="{$lang.number}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="row">
                            <div class="span8">
                                <div class="text">
                                    <input type="text" name="emergency_contacts_fourth_name">
                                </div>
                                <div class="title">
                                    <h6>{$lang.emergency_contact} 4</h6>
                                </div>
                            </div>
                            <div class="span4">
                                <div class="compound st-1-left">
                                    <select name="emergency_contacts_fourth_phone_country">
                                        <option value="">{$lang.country} ({$lang.empty})</option>
                                        <?php foreach (Functions::countries() as $value) : ?>
                                            <option value="<?php echo $value['lada']; ?>"><?php echo $value['name'][Session::get_value('vkye_lang')]; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="emergency_contacts_fourth_phone_number" placeholder="{$lang.number}">
                                </div>
                                <div class="title">
                                    <h6>{$lang.phone}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="breaker">
                            <span></span>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="group-uploader">
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_birth_certificate" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.birth_certificate}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_address_proof" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.address_proof}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_ife" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.ife}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_rfc" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.rfc}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_curp" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.curp}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_professional_license" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.professional_license}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_driver_license" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.driver_license}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_account_state" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.account_state}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_medical_examination" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.medical_examination}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_criminal_records" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.criminal_records}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_economic_study" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.economic_study}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_life_insurance" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.life_insurance}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_recommendation_letters_first" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.recommendation_letter} 1</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_recommendation_letters_second" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.recommendation_letter} 2</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_recommendation_letters_third" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.recommendation_letter} 3</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_work_contract" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.work_contract}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_resignation_letter" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.resignation_letter}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_material_responsive" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.material_responsive}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_privacy_notice" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.privacy_notice}</h6>
                                </div>
                            </div>
                            <div class="uploader" data-low-uploader>
                                <figure data-preview>
                                    <img src="{$path.images}empty.png">
                                    <a data-select><i class="fas fa-pen"></i></a>
                                </figure>
                                <input type="file" name="docs_regulation" accept="image/*,application/pdf" data-select>
                                <div class="title">
                                    <h6>{$lang.regulation}</h6>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fields-group">
                        <div class="button">
                            <a class="alert" button-close><i class="fas fa-times"></i></a>
                            <button type="submit" class="success"><i class="fas fa-plus"></i></button>
                        </div>
                    </fieldset>
                </form>
            </main>
        </div>
    </section>
<?php endif; ?>
<?php if (Permissions::user(['delete_employees']) == true) : ?>
    <section class="modal alert" data-modal="delete_employee">
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
