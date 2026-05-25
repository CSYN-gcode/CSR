$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------
    const $table = $('#tblCSR');                                    //table for csr_document
    const $form = $('#formCSR');                                    //form for csr_document
    const $modal = $('#modalCSR');                                  //modal for csr_document
    const $addButtonCSR = $('#btnShowAddCSR');      //button for adding csr_document
    const dtPTH = initCSRTable($table);
    const $tableIA = $('#tblImprovementActions');                   //table for improvement actions
    const $addButtonIA = $('#btnAddImprovementAction');             //button for adding improvement actions
    const $exportReportButton = $('#btnShowExportReportModal');             //button for adding improvement actions
    // --------------------------------------
    // Initialize global AJAX setup (once per project)
    // --------------------------------------
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Apply Select2 to all select elements inside any modal dynamically
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.select2bs5').each(function() {
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(this).closest('.modal') // Ensures correct parent modal
            });
        });
    });

    // --------------------------------------
    // Bind all event handlers
    // --------------------------------------
    bindCSREvents($table,
        $form,
        $modal,
        $addButtonCSR,
        dtPTH,
        $tableIA,
        $addButtonIA,
        $exportReportButton
    );
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 */
function resetCSR(formSelector, tableImprovementActions) {
    const $formSelector = $(formSelector);
    $formSelector[0].reset();
    $formSelector.find('input[type="hidden"]').val('');

    // hide image preview
    $('#previewImage').hide();

    // clear error fields
    $('.text-danger').text('');

    // Remove all rows except template row
    $(tableImprovementActions).find('tbody tr:not(.data-row)').remove();

    // Clear template row inputs
    $(tableImprovementActions).find('.data-row input').val('');

    // Remove any additional rows except the default one
    const defaultRow = `
        <tr class="data-row">
            <td id="removeIA">
                <center><button class="btn btn-md btn-danger removeIA" title="Remove Row" type="button"><i class="fa fa-times"></i></button></center>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="factor[]" required></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="cause[]" required></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="analysis[]" required></textarea>
            </td>
            <td>
                <textarea class="form-control form-control-sm" name="counter_measure[]" required></textarea>
            </td>
            <td>
                <select class="form-control form-control-lg select2bs5 selectPic" name="pic[]" required></select>
            </td>
            <td>
                <input type="date" class="form-control form-control-lg" name="implementation_date[]" required></input>
            </td>
        </tr>
    `;

    // clark comment 12/29/2025 remove remarks column
    // <td>
    //     <textarea class="form-control" name="improvement_action[]" required></textarea>
    // </td>
    // <td>
    //     <textarea class="form-control form-control-sm" name="remarks[]" required></textarea>
    // </td>

    const $tbody = $(tableImprovementActions).find('tbody');
    $tbody.html(defaultRow);

    // Hide Reupload Div & Exisiting Filename
    formSelector.find("#btnReuploadTriggerDiv").addClass('d-none');
    formSelector.find("#btnReuploadTrigger").addClass('d-none');
    formSelector.find("#btnReuploadTrigger").prop('checked', false);
    formSelector.find("#btnReuploadTriggerLabel").addClass('d-none');
    formSelector.find("#illustrationOfDefectFileName").addClass('d-none');

    // Show Upload Attachment section, remove required attribute
    formSelector.find("#illustrationOfDefect").removeClass('d-none');
    formSelector.find("#illustrationOfDefect").prop('required', true);

    // Hide Download Button
    formSelector.find("#downloadFile").addClass('d-none');

    updateRemoveButtons(tableImprovementActions);
}

/**
 * Initialize DataTable
 */
function initCSRTable($table, url = 'view_csr_document') {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: url },
        fixedHeader: true,
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'status_label' },
            { data: 'control_no' },
            { data: 'customer_name' },
            { data: 'business_process' },
            { data: 'date_applied' },
            { data: 'revision_no' },
            { data: 'change_description' },
            { data: 'prepared_by' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindCSREvents($table, $form, $modal, $addButtonCSR, dtPTH, $tableIA, $addButtonIA, $exportReportButton) {

    // initial check (on page load)
    updateRemoveButtons($tableIA);

    $addButtonCSR.on('click', function () {
        resetCSR($form, $tableIA);
        // getDefects($('#defectId'));
        // getSituations($('#selectSituation'));
        // getPic($('#tblImprovementActions #selectPic'));
        // getPic($('#tblImprovementActions tr:last').find('.selectPic'));
        getCustomerName($('.selectCustomer'));
        $('#modalCSR').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        e.preventDefault();
        saveCSR($form, $modal, dtPTH);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchCSRById(id, $modal, $form, 'edit');
    });

    // View button
    $table.on('click', '.btnView', function () {
        const id = $(this).data('id');
        fetchCSRById(id, $modal, $form, 'view');
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to disable this?', function () {
            updateCSRStatus(id, dtPTH);
        });
    });

    // Enable button
    $table.on('click', '.btnEnable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to enable this?', function () {
            updateCSRStatus(id, dtPTH);
        });
    });

    // --------------------
    // IMAGE PREVIEW
    // --------------------
    $('#attachment').on('change', function() {
        let file = this.files[0];
        if (!file) return;

        let reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImage').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    });

    $('#section').on('change', function() {
        console.log('set readonly false');
        $('#selectDeviceName').prop('disabled', false);
        getDeviceName($('#selectDeviceName'), $(this).val());
    });

    $addButtonIA.on('click', function () {
        const $templateRow = $tableIA.find('.data-row').first();

        // Clone WITHOUT events & select2 bindings
        let newRow = $templateRow.clone(false, false);

        // Clear inputs
        newRow.find('input, textarea').val('');
        newRow.find('.removeIA').prop('disabled', false);

        // 🔥 Remove select2 generated container inside cloned row
        newRow.find('.select2-container').remove();

        let $newSelect = newRow.find('.selectPic');

        // 🔥 Clean select2 plugin traces from cloned select
        $newSelect
            .removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id')
            .removeAttr('tabindex')
            .removeAttr('aria-hidden')
            .empty()        // ← THIS clears options
            .val(null);

        // Append new row first
        $tableIA.find('tbody').append(newRow);

        // 🔥 Reinitialize select2 ONLY for the new row
        $newSelect.select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Update button states
        updateRemoveButtons($tableIA);
        // getPic($newSelect);
    });

    // --------------------
    // REMOVE ROW
    // --------------------
    $tableIA.on('click', '.removeIA', function() {
        // if it is template row, DON'T allow delete
        // if ($(this).closest('tr').hasClass('data-row')) return;
        $(this).closest('tr').remove();
        updateRemoveButtons($tableIA);
    });

    // ================================= RE-UPLOAD FILE =================================
    $('#btnReuploadPdfTrigger').on('click', function(){
        $('#btnReuploadPdfTrigger').attr('checked', 'checked');
        if($(this).is(":checked")){
            $form.find("#pdfAttachment").removeClass('d-none');
            $form.find("#pdfAttachment").attr('required', true);
            $form.find("#pdfAttachmentFileName").addClass('d-none');
            $form.find("#downloadPdfFile").addClass('d-none');
        }else{
            $form.find("#pdfAttachment").addClass('d-none');
            $form.find("#pdfAttachment").removeAttr('required');
            $form.find("#pdfAttachment").val('');
            $form.find("#pdfAttachmentFileName").removeClass('d-none');
            $form.find("#downloadPdfFile").removeClass('d-none');
        }
    });

    $('#btnReuploadExcelTrigger').on('click', function(){
        $('#btnReuploadExcelTrigger').attr('checked', 'checked');
        if($(this).is(":checked")){
            $form.find("#excelAttachment").removeClass('d-none');
            $form.find("#excelAttachment").attr('required', true);
            $form.find("#excelAttachmentFileName").addClass('d-none');
            $form.find("#downloadExcelFile").addClass('d-none');
        }else{
            $form.find("#excelAttachment").addClass('d-none');
            $form.find("#excelAttachment").removeAttr('required');
            $form.find("#excelAttachment").val('');
            $form.find("#excelAttachmentFileName").removeClass('d-none');
            $form.find("#downloadExcelFile").removeClass('d-none');
        }
    });

    $exportReportButton.on('click', function (){
        const $formExport = $('#exportPTHSReportForm');
        $formExport[0].reset();
        // getSituations($('#selectSituationToExport'), '', 'Export');
        // getDefects($('#defectIdToExport'), '', 'Export');
        $('#modalExportReport').modal('show');
    });

    $('#selectSectionToExport').on('change', function (){
        const section = $(this).val();
        console.log('section to export', $(this).val());
        if($(this).val() != 'ALL'){
            getDeviceName($('#selectDeviceNameToExport'), section, '', 'Export');
            $('#selectDeviceNameToExport').prop('disabled', false);
        }else{
            $('#selectDeviceNameToExport').prop('disabled', true);
        }
    });

    $form.on('input', '#situation, #section, #selectDeviceName, #defectId, #dateEncountered', function (){
        console.log('change no of occurence');

        if($('#selectSituation').val() != '' && $('#section').val() != '' && $('#selectDeviceName').val() != null && $('#defectId').val() != null && $('#dateEncountered').val() != ''){
            $.ajax({
                method: "get",
                url: "get_count_no_of_occurrence",
                data: {
                    situation: $('#selectSituation').val(),
                    section: $('#section').val(),
                    model: $('#selectDeviceName').val(),
                    defect_id: $('#defectId').val(),
                    date_encountered: $('#dateEncountered').val(),
                },
                dataType: "json",
                success: function (response) {
                    $('#noOfOccurrence').val(response.ordinal);
                },
                error: function(data, xhr, status) {
                    console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                }
            });
        }else{
            console.log('missing parameter');
        }
    });
}

function updateRemoveButtons($tableIA) {
//     let rowCount = $tableIA.find('tbody tr').length;
//     $tableIA.find('.removeIA').prop('disabled', rowCount <= 1);
    let rowCount = $tableIA.find('.data-row').length;
    console.log('rowCount:', rowCount);

    if (rowCount <= 1) {
        $tableIA.find('.removeIA').prop('disabled', true);
    } else {
        $tableIA.find('.removeIA').prop('disabled', false);
    }
}

function getDefects(cboElement, defectId = null, mode = null){
    let result = '<option value="" disabled selected> Select Defect </option>';
    $.ajax({
        method: "get",
        url: "get_defects",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            if(response.length > 0){
                    result = '<option value="" disabled selected> Select Defect </option>';

                if(mode == 'Export'){
                    result += '<option value="ALL"> ALL </option>';
                }

                for (let di = 0; di < response.length; di++) {
                    result += '<option value="' + response[di]['id'] + '">' + response[di]['defect_name'] + '</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
            if(defectId != null){
                cboElement.val(defectId).trigger('change');
            }

            if(mode == 'view'){
                cboElement.prop('disabled', true).trigger('change.select2');
            }
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function getDeviceName(cboElement, section, deviceName = null, mode = null){
    let result = '<option value="" disabled selected> Select Series Name </option>';
    $.ajax({
        method: "get",
        url: "get_device_name",
        data: { section },
        dataType: "json",
        beforeSend: function(){
            result = '<option value="" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            console.log('response', response);
            $('#selectDeviceName').prop('disabled', false);
            if(response.length > 0){
                    result = '<option value="" disabled selected> Select Series Name </option>';

                if(mode == 'Export'){
                    result += '<option value="ALL"> ALL </option>';
                }

                for (let dni = 0; dni < response.length; dni++) {
                    result += '<option value="' + response[dni]['materials'] + '">' + response[dni]['materials'] + '</option>';
                }
            }else{
                // result = '<option value="0" selected disabled> -- No record found -- </option>';
                result = '<option value="" disabled selected>--Loading--</option>';
            }
            cboElement.html(result);
            if(deviceName != null){
                cboElement.val(deviceName).trigger('change');
            }

            if(mode == 'view'){
                cboElement.prop('disabled', true).trigger('change.select2');
            }
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function getSituations(cboElement, situationId = null, mode = null){
    let result = '<option value="" disabled selected> Select Situation </option>';
    $.ajax({
        method: "get",
        url: "get_situations",
        // data: { section },
        dataType: "json",
        beforeSend: function(){
            result = '<option value="" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            if(response.length > 0){
                    result = '<option value="" disabled selected> Select Situation </option>';

                if(mode == 'Export'){
                    result += '<option value="ALL"> ALL </option>';
                }

                for (let si = 0; si < response.length; si++){
                    result += '<option value="' + response[si]['id'] + '">' + response[si]['situation_name'] + '</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
            if(situationId != null){
                cboElement.val(situationId).trigger('change');
            }

            if(mode === 'view'){
                cboElement.prop('disabled', true).trigger('change.select2');
            }
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function getPic(cboElement, picId = null, $mode = null){
    let result = '<option value="" disabled selected> Select Person-In Charge </option>';
    $.ajax({
        method: "get",
        url: "get_users",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            let users = response.users_data;
            if(users.length > 0){
                    result = '<option value="" disabled selected> Select Person-In Charge </option>';

                for (let ui = 0; ui < users.length; ui++) {
                    let id = users[ui]['id'];
                    let name = users[ui]['name'];

                    result += '<option value="'+id+'">' + name + '</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);

            if (picId != null) {
                cboElement.val(picId).trigger('change');
            }

            if($mode === 'view'){
                cboElement.prop('disabled', true).trigger('change.select2');
            }
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

/**
 * Save (add/update) csr_document data
 */
function saveCSR($form, $modal, dtCSR) {
    let form = $form[0];
    let formData = new FormData(form);

    $.ajax({
        url: 'add_csr_document',
        method: 'POST',
        data: formData,
        contentType: false,   // required for file upload
        processData: false,   // required for file upload
        cache: false,
        beforeSend: function() {
            console.log("Submitting...");
        },
        success: function (response) {
            if (response.result === 1) {
                dtCSR.draw(false);
                $modal.modal('hide');
                $form[0].reset();
                showSuccess('Successfully saved!');
            }
        },
        error: function (xhr) {
            console.error('Save failed:', xhr.responseText);
            showError('Failed to save data.');
        }
    });
}

/**
 * Fetch csr_document data by ID
 */
function fetchCSRById(id, $modal, $form, $mode) {
    $.ajax({
        type: 'GET',
        url: 'get_csr_document_by_id',
        data: { id },
        dataType: 'json',
        success: function (response){
            if($mode == 'view'){
                disableForm($form);
            }

            // Show Reupload Div & Exisiting PDF Filename
            $form.find("#btnReuploadPdfTriggerDiv").removeClass('d-none');
            $form.find("#btnReuploadPdfTrigger").removeClass('d-none');
            $form.find("#btnReuploadPdfTrigger").prop('checked', false);
            $form.find("#btnReuploadPdfTriggerLabel").removeClass('d-none');
            $form.find("#pdfAttachmentFileName").removeClass('d-none');

            // Hide PDF Upload Attachment section, remove required attribute
            $form.find("#pdfAttachment").addClass('d-none');
            $form.find("#pdfAttachment").removeAttr('required');

            // Show Reupload Div & Exisiting EXCEL Filename
            $form.find("#btnReuploadExcelTriggerDiv").removeClass('d-none');
            $form.find("#btnReuploadExcelTrigger").removeClass('d-none');
            $form.find("#btnReuploadExcelTrigger").prop('checked', false);
            $form.find("#btnReuploadExcelTriggerLabel").removeClass('d-none');
            $form.find("#excelAttachmentFileName").removeClass('d-none');

            // Hide EXCEL Upload Attachment section, remove required attribute
            $form.find("#excelAttachment").addClass('d-none');
            $form.find("#excelAttachment").removeAttr('required');

            // Populate modal fields (adjust names per csr_document)
            getCustomerName($('.selectCustomer'), response.customer_info.id);
            $form.find('#txtCSRId').val(response.id);
            $form.find('#revNo').val(response.revision_no);
            $form.find('#businessProcess').val(response.business_process);
            $form.find('#changeDescription').val(response.change_description);
            $form.find('#pdfAttachmentFileName').val(response.pdf_attachment_info.original_name);
            $form.find('#excelAttachmentFileName').val(response.excel_attachment_info.original_name);
            // $form.find('#preparedByName').val(response.prepared_by_name);
            $form.find('#remarks').val(response.remarks);

            // let download_file;
            let download_file_pdf   ='<a href="download_file/'+response.id+'/pdf" target="_blank">';
                download_file_pdf   +=   '<button type="button" class="btn btn-primary btn-sm d-none" id="downloadPdfFile">';
            let download_file_excel ='<a href="download_file/'+response.id+'/excel" target="_blank">';
                download_file_excel +=   '<button type="button" class="btn btn-primary btn-sm d-none" id="downloadExcelFile">';
            let download_file       =        '<i class="fa-solid fa-file-arrow-down"></i>';
                download_file       +=          '&nbsp;';
                download_file       +=          'See Attachment';
                download_file       +=   '</button>';
                download_file       +='</a>';

            download_file_pdf = download_file_pdf + download_file;
            download_file_excel = download_file_excel + download_file;

            $form.find('#pdfAttachmentDiv').append(download_file_pdf);
            $form.find('#excelAttachmentDiv').append(download_file_excel);

            // Show Download Button
            $form.find("#downloadPdfFile").removeClass('d-none');
            $form.find("#downloadExcelFile").removeClass('d-none');

            $modal.modal('show');
        },
        error: function (xhr) {
            console.error('Fetch failed:', xhr.responseText);
            showError('Failed to fetch data.');
        }
    });
}

function disableForm($form){
    $form.find('#btnSubmitCSR').prop('disabled', true);
    $form.find('#btnSubmitCSR').prop('hidden', true);
    $form.find('input, textarea, select').prop('disabled', true);
    $form.find('#btnReuploadTrigger').prop('disabled', true);
    $form.find('#btnReuploadTrigger').prop('checked', false);
}

/**
 * Disable or update csr_document status
 */
function updateCSRStatus(id, dtPTH) {
    $.ajax({
        type: 'POST',
        url: 'update_csr_document_status',
        data: { id },
        dataType: 'json',
        success: function (response) {
            if(response.success == true) {
                showSuccess('Status updated successfully.');
                dtPTH.draw();
            }
        },
        error: function (xhr) {
            console.error('Status update failed:', xhr.responseText);
            showError('Failed to update status.');
        }
    });
}

/**
 * SweetAlert confirmation
 */
function confirmAction(message, callback) {
    Swal.fire({
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) callback();
    });
}

/**
 * SweetAlert success helper
 */
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        text: message,
        timer: 1500,
        showConfirmButton: false
    });
}

/**
 * SweetAlert error helper
 */
function showError(message) {
    Swal.fire({
        icon: 'error',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}
