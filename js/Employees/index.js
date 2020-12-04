'use strict';

$(document).ready(function()
{
    $('[data-search="employees"]').focus();

    $('[data-search="employees"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="employees"]').find(' > div'));
    });

    var create_action = 'create_employee';
    var read_action = 'read_employee';
    var update_action = 'update_employee';
    var block_action = 'block_employee';
    var unblock_action = 'unblock_employee';
    var delete_action = 'delete_employee';

    $(document).on('click', '[data-action="' + create_action + '"]', function()
    {
        action = create_action;
        id = null;

        $('[name="sex"][value="male"]').parent().find('span').addClass('checked');
        $('[name="sex"][value="female"]').parent().find('span').removeClass('checked');

        transform_form_modal('create', $('[data-modal="' + create_action + '"]'));
        open_form_modal('create', $('[data-modal="' + create_action + '"]'));
    });

    $('[name="phone_country"]').on('change', function()
    {
        if ($(this).val().length <= 0)
            $('[name="phone_number"]').val('');
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[data-action="generate_random_nie"]').on('click', function()
    {
        generate_string(['uppercase','lowercase','int'], 8, $('[name="nie"]'));
    });

    $('[name="emergency_contacts_first_phone_country"]').on('change', function()
    {
        if ($(this).val().length <= 0)
            $('[name="emergency_contacts_first_phone_number"]').val('');
    });

    $('[name="emergency_contacts_first_phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="emergency_contacts_second_phone_country"]').on('change', function()
    {
        if ($(this).val().length <= 0)
            $('[name="emergency_contacts_second_phone_number"]').val('');
    });

    $('[name="emergency_contacts_second_phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="emergency_contacts_third_phone_country"]').on('change', function()
    {
        if ($(this).val().length <= 0)
            $('[name="emergency_contacts_third_phone_number"]').val('');
    });

    $('[name="emergency_contacts_third_phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="emergency_contacts_fourth_phone_country"]').on('change', function()
    {
        if ($(this).val().length <= 0)
            $('[name="emergency_contacts_fourth_phone_number"]').val('');
    });

    $('[name="emergency_contacts_fourth_phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[data-modal="' + create_action + '"]').find('form').on('submit', function(event)
    {
        send_form_modal('create', $(this), event);
    });

    $(document).on('click', '[data-action="' + update_action + '"]', function()
    {
        action = read_action;
        id = $(this).data('id');

        transform_form_modal('update', $('[data-modal="' + create_action + '"]'));
        open_form_modal('update', $('[data-modal="' + create_action + '"]'), function(data)
        {
            action = update_action;

            $('[name="avatar"]').parents('.uploader').find('img').attr('src', ((validate_string('empty', data.avatar) == false) ? '../uploads/' + data.avatar : '../images/employee.png'));
            $('[name="firstname"]').val(data.firstname);
            $('[name="lastname"]').val(data.lastname);

            if (data.sex == 'male')
            {
                $('[name="sex"][value="male"]').prop('checked', true);
                $('[name="sex"][value="male"]').parent().find('span').addClass('checked');
                $('[name="sex"][value="female"]').parent().find('span').removeClass('checked');
            }
            else if (data.sex == 'female')
            {
                $('[name="sex"][value="female"]').prop('checked', true);
                $('[name="sex"][value="male"]').parent().find('span').removeClass('checked');
                $('[name="sex"][value="female"]').parent().find('span').addClass('checked');
            }

            $('[name="birth_date"]').val(data.birth_date);
            $('[name="ife"]').val(data.ife);
            $('[name="nss"]').val(data.nss);
            $('[name="rfc"]').val(data.rfc);
            $('[name="curp"]').val(data.curp);
            $('[name="bank_name"]').val(data.bank.name);
            $('[name="bank_account"]').val(data.bank.account);
            $('[name="nsv"]').val(data.nsv);
            $('[name="email"]').val(data.email);
            $('[name="phone_country"]').val(data.phone.country);
            $('[name="phone_number"]').val(data.phone.number);
            $('[name="rank"]').val(data.rank);
            $('[name="nie"]').val(data.nie);
            $('[name="admission_date"]').val(data.admission_date);
            $('[name="responsibilities"]').val(data.responsibilities);
            $('[name="emergency_contacts_first_name"]').val(data.emergency_contacts.first.name);
            $('[name="emergency_contacts_first_phone_country"]').val(data.emergency_contacts.first.phone.country);
            $('[name="emergency_contacts_first_phone_number"]').val(data.emergency_contacts.first.phone.number);
            $('[name="emergency_contacts_second_name"]').val(data.emergency_contacts.second.name);
            $('[name="emergency_contacts_second_phone_country"]').val(data.emergency_contacts.second.phone.country);
            $('[name="emergency_contacts_second_phone_number"]').val(data.emergency_contacts.second.phone.number);
            $('[name="emergency_contacts_third_name"]').val(data.emergency_contacts.third.name);
            $('[name="emergency_contacts_third_phone_country"]').val(data.emergency_contacts.third.phone.country);
            $('[name="emergency_contacts_third_phone_number"]').val(data.emergency_contacts.third.phone.number);
            $('[name="emergency_contacts_fourth_name"]').val(data.emergency_contacts.fourth.name);
            $('[name="emergency_contacts_fourth_phone_country"]').val(data.emergency_contacts.fourth.phone.country);
            $('[name="emergency_contacts_fourth_phone_number"]').val(data.emergency_contacts.fourth.phone.number);

            var docs_birth_certificate = (validate_string('empty', data.docs.birth_certificate) == false) ? data.docs.birth_certificate.split('.') : '';

            if (validate_string('empty', docs_birth_certificate) == false)
            {
                if (docs_birth_certificate[1] == 'pdf')
                    docs_birth_certificate = '../images/pdf.png';
                else
                    docs_birth_certificate = '../uploads/' + docs_birth_certificate[0] + '.' + docs_birth_certificate[1];
            }
            else
                docs_birth_certificate = '../images/empty.png';

            $('[name="docs_birth_certificate"]').parents('.uploader').find('img').attr('src', docs_birth_certificate);

            var docs_address_proof = (validate_string('empty', data.docs.address_proof) == false) ? data.docs.address_proof.split('.') : '';

            if (validate_string('empty', docs_address_proof) == false)
            {
                if (docs_address_proof[1] == 'pdf')
                    docs_address_proof = '../images/pdf.png';
                else
                    docs_address_proof = '../uploads/' + docs_address_proof[0] + '.' + docs_address_proof[1];
            }
            else
                docs_address_proof = '../images/empty.png';

            $('[name="docs_address_proof"]').parents('.uploader').find('img').attr('src', docs_address_proof);

            var docs_ife = (validate_string('empty', data.docs.ife) == false) ? data.docs.ife.split('.') : '';

            if (validate_string('empty', docs_ife) == false)
            {
                if (docs_ife[1] == 'pdf')
                    docs_ife = '../images/pdf.png';
                else
                    docs_ife = '../uploads/' + docs_ife[0] + '.' + docs_ife[1];
            }
            else
                docs_ife = '../images/empty.png';

            $('[name="docs_ife"]').parents('.uploader').find('img').attr('src', docs_ife);

            var docs_rfc = (validate_string('empty', data.docs.rfc) == false) ? data.docs.rfc.split('.') : '';

            if (validate_string('empty', docs_rfc) == false)
            {
                if (docs_rfc[1] == 'pdf')
                    docs_rfc = '../images/pdf.png';
                else
                    docs_rfc = '../uploads/' + docs_rfc[0] + '.' + docs_rfc[1];
            }
            else
                docs_rfc = '../images/empty.png';

            $('[name="docs_rfc"]').parents('.uploader').find('img').attr('src', docs_rfc);

            var docs_curp = (validate_string('empty', data.docs.curp) == false) ? data.docs.curp.split('.') : '';

            if (validate_string('empty', docs_curp) == false)
            {
                if (docs_curp[1] == 'pdf')
                    docs_curp = '../images/pdf.png';
                else
                    docs_curp = '../uploads/' + docs_curp[0] + '.' + docs_curp[1];
            }
            else
                docs_curp = '../images/empty.png';

            $('[name="docs_curp"]').parents('.uploader').find('img').attr('src', docs_curp);

            var docs_professional_license = (validate_string('empty', data.docs.professional_license) == false) ? data.docs.professional_license.split('.') : '';

            if (validate_string('empty', docs_professional_license) == false)
            {
                if (docs_professional_license[1] == 'pdf')
                    docs_professional_license = '../images/pdf.png';
                else
                    docs_professional_license = '../uploads/' + docs_professional_license[0] + '.' + docs_professional_license[1];
            }
            else
                docs_professional_license = '../images/empty.png';

            $('[name="docs_professional_license"]').parents('.uploader').find('img').attr('src', docs_professional_license);

            var docs_driver_license = (validate_string('empty', data.docs.driver_license) == false) ? data.docs.driver_license.split('.') : '';

            if (validate_string('empty', docs_driver_license) == false)
            {
                if (docs_driver_license[1] == 'pdf')
                    docs_driver_license = '../images/pdf.png';
                else
                    docs_driver_license = '../uploads/' + docs_driver_license[0] + '.' + docs_driver_license[1];
            }
            else
                docs_driver_license = '../images/empty.png';

            $('[name="docs_driver_license"]').parents('.uploader').find('img').attr('src', docs_driver_license);

            var docs_account_state = (validate_string('empty', data.docs.account_state) == false) ? data.docs.account_state.split('.') : '';

            if (validate_string('empty', docs_account_state) == false)
            {
                if (docs_account_state[1] == 'pdf')
                    docs_account_state = '../images/pdf.png';
                else
                    docs_account_state = '../uploads/' + docs_account_state[0] + '.' + docs_account_state[1];
            }
            else
                docs_account_state = '../images/empty.png';

            $('[name="docs_account_state"]').parents('.uploader').find('img').attr('src', docs_account_state);

            var docs_medical_examination = (validate_string('empty', data.docs.medical_examination) == false) ? data.docs.medical_examination.split('.') : '';

            if (validate_string('empty', docs_medical_examination) == false)
            {
                if (docs_medical_examination[1] == 'pdf')
                    docs_medical_examination = '../images/pdf.png';
                else
                    docs_medical_examination = '../uploads/' + docs_medical_examination[0] + '.' + docs_medical_examination[1];
            }
            else
                docs_medical_examination = '../images/empty.png';

            $('[name="docs_medical_examination"]').parents('.uploader').find('img').attr('src', docs_medical_examination);

            var docs_criminal_records = (validate_string('empty', data.docs.criminal_records) == false) ? data.docs.criminal_records.split('.') : '';

            if (validate_string('empty', docs_criminal_records) == false)
            {
                if (docs_criminal_records[1] == 'pdf')
                    docs_criminal_records = '../images/pdf.png';
                else
                    docs_criminal_records = '../uploads/' + docs_criminal_records[0] + '.' + docs_criminal_records[1];
            }
            else
                docs_criminal_records = '../images/empty.png';

            $('[name="docs_criminal_records"]').parents('.uploader').find('img').attr('src', docs_criminal_records);

            var docs_economic_study = (validate_string('empty', data.docs.economic_study) == false) ? data.docs.economic_study.split('.') : '';

            if (validate_string('empty', docs_economic_study) == false)
            {
                if (docs_economic_study[1] == 'pdf')
                    docs_economic_study = '../images/pdf.png';
                else
                    docs_economic_study = '../uploads/' + docs_economic_study[0] + '.' + docs_economic_study[1];
            }
            else
                docs_economic_study = '../images/empty.png';

            $('[name="docs_economic_study"]').parents('.uploader').find('img').attr('src', docs_economic_study);

            var docs_life_insurance = (validate_string('empty', data.docs.life_insurance) == false) ? data.docs.life_insurance.split('.') : '';

            if (validate_string('empty', docs_life_insurance) == false)
            {
                if (docs_life_insurance[1] == 'pdf')
                    docs_life_insurance = '../images/pdf.png';
                else
                    docs_life_insurance = '../uploads/' + docs_life_insurance[0] + '.' + docs_life_insurance[1];
            }
            else
                docs_life_insurance = '../images/empty.png';

            $('[name="docs_life_insurance"]').parents('.uploader').find('img').attr('src', docs_life_insurance);

            var docs_recommendation_letters_first = (validate_string('empty', data.docs.recommendation_letters.first) == false) ? data.docs.recommendation_letters.first.split('.') : '';

            if (validate_string('empty', docs_recommendation_letters_first) == false)
            {
                if (docs_recommendation_letters_first[1] == 'pdf')
                    docs_recommendation_letters_first = '../images/pdf.png';
                else
                    docs_recommendation_letters_first = '../uploads/' + docs_recommendation_letters_first[0] + '.' + docs_recommendation_letters_first[1];
            }
            else
                docs_recommendation_letters_first = '../images/empty.png';

            $('[name="docs_recommendation_letters_first"]').parents('.uploader').find('img').attr('src', docs_recommendation_letters_first);

            var docs_recommendation_letters_second = (validate_string('empty', data.docs.recommendation_letters.second) == false) ? data.docs.recommendation_letters.second.split('.') : '';

            if (validate_string('empty', docs_recommendation_letters_second) == false)
            {
                if (docs_recommendation_letters_second[1] == 'pdf')
                    docs_recommendation_letters_second = '../images/pdf.png';
                else
                    docs_recommendation_letters_second = '../uploads/' + docs_recommendation_letters_second[0] + '.' + docs_recommendation_letters_second[1];
            }
            else
                docs_recommendation_letters_second = '../images/empty.png';

            $('[name="docs_recommendation_letters_second"]').parents('.uploader').find('img').attr('src', docs_recommendation_letters_second);

            var docs_recommendation_letters_third = (validate_string('empty', data.docs.recommendation_letters.third) == false) ? data.docs.recommendation_letters.third.split('.') : '';

            if (validate_string('empty', docs_recommendation_letters_third) == false)
            {
                if (docs_recommendation_letters_third[1] == 'pdf')
                    docs_recommendation_letters_third = '../images/pdf.png';
                else
                    docs_recommendation_letters_third = '../uploads/' + docs_recommendation_letters_third[0] + '.' + docs_recommendation_letters_third[1];
            }
            else
                docs_recommendation_letters_third = '../images/empty.png';

            $('[name="docs_recommendation_letters_third"]').parents('.uploader').find('img').attr('src', docs_recommendation_letters_third);

            var docs_work_contract = (validate_string('empty', data.docs.work_contract) == false) ? data.docs.work_contract.split('.') : '';

            if (validate_string('empty', docs_work_contract) == false)
            {
                if (docs_work_contract[1] == 'pdf')
                    docs_work_contract = '../images/pdf.png';
                else
                    docs_work_contract = '../uploads/' + docs_work_contract[0] + '.' + docs_work_contract[1];
            }
            else
                docs_work_contract = '../images/empty.png';

            $('[name="docs_work_contract"]').parents('.uploader').find('img').attr('src', docs_work_contract);

            var docs_resignation_letter = (validate_string('empty', data.docs.resignation_letter) == false) ? data.docs.resignation_letter.split('.') : '';

            if (validate_string('empty', docs_resignation_letter) == false)
            {
                if (docs_resignation_letter[1] == 'pdf')
                    docs_resignation_letter = '../images/pdf.png';
                else
                    docs_resignation_letter = '../uploads/' + docs_resignation_letter[0] + '.' + docs_resignation_letter[1];
            }
            else
                docs_resignation_letter = '../images/empty.png';

            $('[name="docs_resignation_letter"]').parents('.uploader').find('img').attr('src', docs_resignation_letter);

            var docs_material_responsive = (validate_string('empty', data.docs.material_responsive) == false) ? data.docs.material_responsive.split('.') : '';

            if (validate_string('empty', docs_material_responsive) == false)
            {
                if (docs_material_responsive[1] == 'pdf')
                    docs_material_responsive = '../images/pdf.png';
                else
                    docs_material_responsive = '../uploads/' + docs_material_responsive[0] + '.' + docs_material_responsive[1];
            }
            else
                docs_material_responsive = '../images/empty.png';

            $('[name="docs_material_responsive"]').parents('.uploader').find('img').attr('src', docs_material_responsive);

            var docs_privacy_notice = (validate_string('empty', data.docs.privacy_notice) == false) ? data.docs.privacy_notice.split('.') : '';

            if (validate_string('empty', docs_privacy_notice) == false)
            {
                if (docs_privacy_notice[1] == 'pdf')
                    docs_privacy_notice = '../images/pdf.png';
                else
                    docs_privacy_notice = '../uploads/' + docs_privacy_notice[0] + '.' + docs_privacy_notice[1];
            }
            else
                docs_privacy_notice = '../images/empty.png';

            $('[name="docs_privacy_notice"]').parents('.uploader').find('img').attr('src', docs_privacy_notice);

            var docs_regulation = (validate_string('empty', data.docs.regulation) == false) ? data.docs.regulation.split('.') : '';

            if (validate_string('empty', docs_regulation) == false)
            {
                if (docs_regulation[1] == 'pdf')
                    docs_regulation = '../images/pdf.png';
                else
                    docs_regulation = '../uploads/' + docs_regulation[0] + '.' + docs_regulation[1];
            }
            else
                docs_regulation = '../images/empty.png';

            $('[name="docs_regulation"]').parents('.uploader').find('img').attr('src', docs_regulation);
        });
    });

    $(document).on('click', '[data-action="' + block_action + '"]', function()
    {
        action = block_action;
        id = $(this).data('id');

        send_form_modal('block');
    });

    $(document).on('click', '[data-action="' + unblock_action + '"]', function()
    {
        action = unblock_action;
        id = $(this).data('id');

        send_form_modal('unblock');
    });

    $(document).on('click', '[data-action="' + delete_action + '"]', function()
    {
        action = delete_action;
        id = $(this).data('id');

        open_form_modal('delete', $('[data-modal="' + delete_action + '"]'));
    });

    $('[data-modal="' + delete_action + '"]').modal().onSuccess(function()
    {
        send_form_modal('delete');
    });
});
