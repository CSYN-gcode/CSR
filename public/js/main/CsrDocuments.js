    const $csrParts = {
        1: () => ({ $scope: $('#formCSR'),      ui: ['#modalCSRPart1Footer', '#btnSubmitCSRPart1'] }),
        2: () => ({ $scope: $('#formCSRReviewStaff'), ui: ['#modalCSRPart2Footer', '#btnSubmitCSRPart2'] }),
        3: () => ({ $scope: $('#formCSRReviewStaff'), ui: [$('#cardQasDcc')] }),
        4: () => ({ $scope: $('#formCSRReviewStaff'), ui: [$('#cardReviewStaff')] }),
    };
    
$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------s
    const $table = $('#tblCSR');                                    //table for csr_document
    const $modal = $('#modalCSR');                                  //modal for csr_document
    const $addButtonCSR = $('#btnShowAddCSR');                      //button for adding csr_document
    const dtCSR = initCSRTable($table);
    const $exportReportButton = $('#btnShowExportReportModal');

    const $form = $('#formCSR');                                    //form for csr_document
    const $formPart2 = $('#formCSRReviewStaff');                    //form for csr_document
    
    $(document).on('hidden.bs.modal', '.modal', function () {
        if ($('.modal.show').length) {
            $('body').addClass('modal-open');
        }
    });

    // Initialize global AJAX setup --------------------------------------(once per project)
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
        $formPart2,
        $modal,
        $addButtonCSR,
        dtCSR,
        $exportReportButton,
    );
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 * @param {string|jQuery} formPart2Selector - the second form element or selector
 * @param {string|jQuery} tableImprovementActions - the improvement actions table element or selector
 */

/* ---------- Helpers ---------- */
 
// Inverse of setupReuploadUI: hide reupload UI, show the normal file input again.
// `required` is explicit when you know it; otherwise it falls back to whatever
// setupReuploadUI remembered in data('was-required').
function resetReuploadUI($scope, key, { required } = {}) {
    const K = cap(key);
    const $input = $scope.find(`#${key}Attachment`);
    const isRequired = required ?? !!$input.data('was-required');
 
    $scope
        .find([
            `#btnReupload${K}TriggerDiv`,
            `#btnReupload${K}Trigger`,
            `#btnReupload${K}TriggerLabel`,
            `#${key}AttachmentFileName`,
        ].join(','))
        .addClass('d-none');
 
    $scope.find(`#btnReupload${K}Trigger`).prop('checked', false);
    $input.removeClass('d-none').prop('required', isRequired);
}
 
// Empties an attachment div and puts its label back
function resetAttachmentDiv($scope, id, label) {
    $scope
        .find(`#${id}`)
        .empty()
        .append(`<label for="${id}" class="form-control-label">${label}</label>&nbsp;`);
}
 
/* ---------- Main ---------- */
 
function resetCSR(formSelector, formPart2Selector) {
    const $form = $(formSelector);
    const $formPart2 = $(formPart2Selector);
 
    /* Part 1 */
    $form[0].reset();
    setFormDisabled($form, false);
    toggleElements($form, '#btnSubmitCSRPart1', true);   // un-hide + enable
    $form.find('#txtCSRId').val('');                      // reset() doesn't clear hidden inputs
 
    ['pdf', 'excel'].forEach((key) => resetReuploadUI($form, key, { required: true }));
    resetAttachmentDiv($form, 'pdfAttachmentDiv',   'PDF ATTACHMENT');
    resetAttachmentDiv($form, 'excelAttachmentDiv', 'EXCEL ATTACHMENT');
 
    /* Part 2 */
    $formPart2[0].reset();
    setFormDisabled($formPart2, false);
    toggleElements($formPart2, '#btnSubmitCSRPart2', true);
    $formPart2.find('#txtCSRIdReviewStaff, #txtCSREvidenceIdReviewStaff').val('');
 
    ['reviewPdf', 'reviewExcel', 'reviewImage'].forEach((key) => resetReuploadUI($formPart2, key));
    resetAttachmentDiv($formPart2, 'reviewPdfAttachmentDiv',   'REVIEW PDF');
    resetAttachmentDiv($formPart2, 'reviewExcelAttachmentDiv', 'REVIEW EXCEL');
    resetAttachmentDiv($formPart2, 'reviewImageAttachmentDiv', 'REVIEW IMAGE/s');
}

// function resetCSR(formSelector, formPart2Selector) {
//     const $formSelector = $(formSelector);
//     const $formPart2Selector = $(formPart2Selector);

//     $formSelector[0].reset();
//     // $formSelector.find('input[type="hidden"]').val('');

//     $formSelector.find('#btnSubmitCSRPart1').prop('disabled', false);
//     $formSelector.find('#btnSubmitCSRPart1').prop('hidden', false);
//     $formSelector.find('input, textarea, select').prop('disabled', false);

//     // Show Reupload Div & Exisiting PDF Filename
//     $formSelector.find("#btnReuploadPdfTriggerDiv").addClass('d-none');
//     $formSelector.find("#btnReuploadPdfTrigger").addClass('d-none');
//     $formSelector.find("#btnReuploadPdfTriggerLabel").addClass('d-none');
//     $formSelector.find("#pdfAttachmentFileName").addClass('d-none');

//     // Hide PDF Upload Attachment section, remove required attribute
//     $formSelector.find("#pdfAttachment").removeClass('d-none');
//     $formSelector.find("#pdfAttachment").prop('required', true);

//     // Show Reupload Div & Exisiting EXCEL Filename
//     $formSelector.find("#btnReuploadExcelTriggerDiv").addClass('d-none');
//     $formSelector.find("#btnReuploadExcelTrigger").addClass('d-none');
//     $formSelector.find("#btnReuploadExcelTriggerLabel").addClass('d-none');
//     $formSelector.find("#excelAttachmentFileName").addClass('d-none');

//     // Hide EXCEL Upload Attachment section, remove required attribute
//     $formSelector.find("#excelAttachment").removeClass('d-none');
//     $formSelector.find("#excelAttachment").prop('required', true);

//     // // hide image preview
//     // $('#previewImage').hide();

//     // // clear error fields
//     // $('.text-danger').text('');

//     $formSelector.find('#pdfAttachmentDiv').empty();
//     $formSelector.find('#excelAttachmentDiv').empty();

//     $formSelector.find('#pdfAttachmentDiv').append('<label for="pdfAttachmentDiv" class="form-control-label">PDF ATTACHMENT</label>&nbsp;');
//     $formSelector.find('#excelAttachmentDiv').append('<label for="excelAttachmentDiv" class="form-control-label">EXCEL ATTACHMENT</label>&nbsp;');

//     $formPart2Selector[0].reset();
//     // $formPart2Selector.find('input[type="hidden"]').val('');

//     $formSelector.find('#btnSubmitCSRPart2').prop('disabled', false);
//     $formSelector.find('#btnSubmitCSRPart2').prop('hidden', false);
//     $formSelector.find('input, textarea, select').prop('disabled', false);

//     $formPart2Selector.find('#reviewPdfAttachmentDiv').empty();
//     $formPart2Selector.find('#reviewExcelAttachmentDiv').empty();
//     $formPart2Selector.find('#reviewImageAttachmentDiv').empty();

//     $formPart2Selector.find('#reviewPdfAttachmentDiv').append('<label for="reviewPdfAttachmentDiv" class="form-control-label">REVIEW PDF</label>&nbsp;');
//     $formPart2Selector.find('#reviewExcelAttachmentDiv').append('<label for="reviewExcelAttachmentDiv" class="form-control-label">REVIEW EXCEL</label>&nbsp;');
//     $formPart2Selector.find('#reviewImageAttachmentDiv').append('<label for="reviewImageAttachmentDiv" class="form-control-label">REVIEW IMAGE/s</label>&nbsp;');
// }

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
            { data: 'customer_info.customer_name' },
            { data: 'business_process' },
            { data: 'date_applied_label' },
            { data: 'revision_no' },
            { data: 'change_description' },
            { data: 'prepared_by_info.name' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindCSREvents($table, $form, $formPart2, $modal, $addButtonCSR, dtCSR, $exportReportButton) {

    $addButtonCSR.on('click', function () {
        resetCSR($form, $formPart2);
        getCustomerName($('.selectCustomer'));

        setFormDisabled($form, false);
        setFormDisabled($formPart2, true);

        //Hide the review staff card upon creating new CSR document
        // setCSRPartUI(1, true);  // Show Part 1 footer + button
        setCSRPartUI(2, false);  // Hide Part 2 Footer + Button
        setCSRPartUI(4, false);  // Hide Part 2 Div
        $('#modalCSR').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        console.log('Submitting form1');
        e.preventDefault();
        saveCSR($form, $modal, dtCSR);
    });

    // Submit form (Add / Edit)
    $formPart2.on('submit', function (e) {
        console.log('Submitting form2');
        e.preventDefault();
        saveCSR($formPart2, $modal, dtCSR);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchCSRById(id, $modal, $form, $formPart2,'edit');
    });

    // View button
    $table.on('click', '.btnView', function () {
        const id = $(this).data('id');
        const approval = $(this).data('approval');
        fetchCSRById(id, $modal, $form, $formPart2, 'view', approval);
    });

    // Final Submit button
    $table.on('click', '.btnFinalSubmit', function () {
        const id = $(this).data('id');
        let updateStatusTo = 2; //for review
        confirmAction('Are you sure you want to submit this?', function () {
            updateCSRStatus(id, dtCSR, updateStatusTo);
        });
    });

    // Final Submit Review button
    $table.on('click', '.btnFinalSubmitReview', function () {
        const id = $(this).data('id');
        let updateStatusTo = 4; //for approval
        confirmAction('Are you sure you want to submit this?', function () {
            updateCSRStatus(id, dtCSR, updateStatusTo);
        });
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        let updateStatusTo = 3; //Cancelled/Inactive
        confirmAction('Are you sure you want to disable this?', function () {
            updateCSRStatus(id, dtCSR, updateStatusTo);
        });
    });

    // Enable button
    $table.on('click', '.btnEnable', function () {
        const id = $(this).data('id');
        let updateStatusTo = 1; //Pending/Active
        confirmAction('Are you sure you want to enable this?', function () {
            updateCSRStatus(id, dtCSR, updateStatusTo);
        });
    });

    // Approve button
    $modal.find('#btnApproved').click( function () {
        console.log('approve button clicked');
        const id = $form.find('#txtCSRId').val();
        let updateStatusTo = 6; //approved
        // let forApproval = true;
        confirmAction('Approve CSR Document?', function () {
            updateCSRStatus(id, dtCSR, updateStatusTo, $modal);
        });
    });

    // Disapprove button
    $modal.find('#btnDisapproved').click( function () {
        console.log('disapprove button clicked');
        const id = $form.find('#txtCSRId').val();
        let updateStatusTo = 5; //disapproved

        confirmAction('Disapprove CSR Document?', function () {
            updateCSRStatus(id, dtCSR, updateStatusTo, $modal);
        });

        // Swal.fire({
        //     title: 'Disapprove CSR Document',
        //     input: 'textarea',
        //     id: 'disapproveRemarks',
        //     inputLabel: 'Remarks',
        //     inputPlaceholder: 'Enter reason for disapproval...',
        //     inputAttributes: {
        //         'aria-label': 'Enter remarks'
        //     },
        //     showCancelButton: true,
        //     confirmButtonText: 'Submit',
        //     cancelButtonText: 'Cancel',
        //     inputValidator: (value) => {
        //         if (!value) {
        //             return 'Remarks is required!';
        //         }
        //     }
        // }).then((result) => {
        //     if (result.isConfirmed) {
        //         let remarks = result.value;
        //         updateCSRStatus(id, dtCSR, updateStatusTo);
        //         // updateHrMemoApprovalStatus(id, dtHMA, updateStatusTo, $modal, remarks);
        //     }
        // });

        // let forApproval = true;
        // confirmAction('Disapprove HR Memo Document?', function () {
        //     updateHrMemoApprovalStatus(id, dtHMA, updateStatusTo, $modal);
        // });
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

    // ================================= RE-UPLOAD FILE =================================
    $('#btnReuploadReviewPdfTrigger').on('click', function(){
        $('#btnReuploadReviewPdfTrigger').attr('checked', 'checked');
        if($(this).is(":checked")){
            $formPart2.find("#reviewPdfAttachment").removeClass('d-none');
            $formPart2.find("#reviewPdfAttachment").attr('required', true);
            $formPart2.find("#reviewPdfAttachmentFileName").addClass('d-none');
            $formPart2.find("#downloadReviewPdfFile").addClass('d-none');
        }else{
            $formPart2.find("#reviewPdfAttachment").addClass('d-none');
            $formPart2.find("#reviewPdfAttachment").removeAttr('required');
            $formPart2.find("#reviewPdfAttachment").val('');
            $formPart2.find("#reviewPdfAttachmentFileName").removeClass('d-none');
            $formPart2.find("#downloadReviewPdfFile").removeClass('d-none');
        }
    });

    // ================================= RE-UPLOAD FILE =================================
    $('#btnReuploadReviewExcelTrigger').on('click', function(){
        $('#btnReuploadReviewExcelTrigger').attr('checked', 'checked');
        if($(this).is(":checked")){
            $formPart2.find("#reviewExcelAttachment").removeClass('d-none');
            $formPart2.find("#reviewExcelAttachment").attr('required', true);
            $formPart2.find("#reviewExcelAttachmentFileName").addClass('d-none');
            $formPart2.find("#downloadReviewExcelFile").addClass('d-none');
        }else{
            $formPart2.find("#reviewExcelAttachment").addClass('d-none');
            $formPart2.find("#reviewExcelAttachment").removeAttr('required');
            $formPart2.find("#reviewExcelAttachment").val('');
            $formPart2.find("#reviewExcelAttachmentFileName").removeClass('d-none');
            $formPart2.find("#downloadReviewExcelFile").removeClass('d-none');
        }
    });

    // ================================= RE-UPLOAD FILE =================================
    $('#btnReuploadReviewImageTrigger').on('click', function(){
        $('#btnReuploadReviewImageTrigger').attr('checked', 'checked');
        if($(this).is(":checked")){
            $formPart2.find("#reviewImageAttachment").removeClass('d-none');
            $formPart2.find("#reviewImageAttachment").attr('required', true);
            $formPart2.find("#reviewImageAttachmentFileName").addClass('d-none');
            $formPart2.find("#downloadReviewImageFile").addClass('d-none');
        }else{
            $formPart2.find("#reviewImageAttachment").addClass('d-none');
            $formPart2.find("#reviewImageAttachment").removeAttr('required');
            $formPart2.find("#reviewImageAttachment").val('');
            $formPart2.find("#reviewImageAttachmentFileName").removeClass('d-none');
            $formPart2.find("#downloadReviewImageFile").removeClass('d-none');
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

    $('#reviewImagesModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const reviewId = button.data('review-id');

        $('#reviewImagesGallery').html('<div class="col-12 text-center">Loading...</div>');

        $.ajax({
            url: 'view_images',
            method: 'GET',
            data: {
                review_id: reviewId
            },
            success: function (response) {
                let images = response[0].review_image_info
                let html = '';
                
                images.forEach(function (image) {
                    html += `
                        <div class="col-md-3 mb-3">
                            <a href="${image.url}" target="_blank" style="text-decoration: none;">
                                <div style=" border: 2px solid #dee2e6; border-radius: 8px; padding: 5px; background-color: #fff; ">
                                    <img src="${image.thumbnail_url || image.url}" class="img-fluid rounded" style="height:300px; width:100%; object-fit:cover; display: block;">
                                </div>
                            </a>
                        </div>
                    `;
                });

                $('#reviewImagesGallery').html(html);
            }
        });
    });
}

function setCSRPartUI(part, show) {
    const { $scope, ui } = $csrParts[part]();
    toggleElements($scope, ui, show);
}

function toggleElements($scope, selectors, show = true, { syncDisabled = true } = {}) {
  [].concat(selectors).forEach((selector) => {
    // const $el = $scope.find(selector);
    const $el = selector instanceof $ ? selector : $scope.find(selector);
    console.log('Toggling element:', $el, 'Show:', show);

    $el.toggleClass('d-none', !show);

    // Buttons/inputs: disabled when hidden, enabled when shown
    if (syncDisabled) {
      $el.filter(':input').prop('disabled', !show);
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
       let formData = new FormData($form[0]);
    // let form = $form[0];
    // let formData = new FormData(form);

    $.ajax({
        url: 'add_csr_document',
        method: 'POST',
        data: formData,
        contentType: false,   // required for file upload
        processData: false,   // required for file upload
        cache: false,
        beforeSend: function() {
            console.log("Submitting...");
            $('#btnSubmitCSRPart1, #btnSubmitCSRPart2').prop('disabled', true);
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
        },
        complete: function() {
            $('#btnSubmitCSRPart1, #btnSubmitCSRPart2').prop('disabled', false);
        }
    });
}

/* ---------- Helpers ---------- */
 
const cap = (s) => s.charAt(0).toUpperCase() + s.slice(1);
 
// Link styled as a button (valid HTML, no <button> inside <a>)
function renderDownloadLink($container, { url, buttonId }) {
    $('<a>', {
        href: url,
        target: '_blank',
        rel: 'noopener',
        id: buttonId,
        class: 'btn btn-primary btn-sm',
        html: '<i class="fa-solid fa-file-arrow-down"></i>&nbsp;See Attachment',
    }).appendTo($container);
}
 
function renderViewImagesButton($container, { id, count }) {
    $('<button>', {
        type: 'button',
        id: 'downloadReviewImageFile',
        class: 'btn btn-sm btn-primary',
        'data-bs-toggle': 'modal',
        'data-bs-target': '#reviewImagesModal',
        'data-review-id': id,
        html: `<i class="fa fa-images"></i> View Images (${count})`,
    }).appendTo($container);
}
 
/**
 * Shows the "reupload" checkbox + existing filename, hides the file input.
 * key examples: 'pdf', 'excel', 'reviewPdf', 'reviewExcel', 'reviewImage'
 * Expects these IDs:
 *   #btnReupload{Key}TriggerDiv, #btnReupload{Key}Trigger, #btnReupload{Key}TriggerLabel,
 *   #{key}AttachmentFileName, #{key}Attachment
 */
function setupReuploadUI($scope, key) {
  const K = cap(key);
  const $input = $scope.find(`#${key}Attachment`);

  $scope
    .find([
      `#btnReupload${K}TriggerDiv`,
      `#btnReupload${K}Trigger`,
      `#btnReupload${K}TriggerLabel`,
      `#${key}AttachmentFileName`,
    ].join(','))
    .removeClass('d-none');

  $scope.find(`#btnReupload${K}Trigger`).prop('checked', false);

  // Remember that it was required so reset can restore it
  if ($input.prop('required')) $input.data('was-required', true);
  $input.addClass('d-none').removeAttr('required');
}

// function setupReuploadUI($scope, key) {
//     const K = cap(key);
 
//     $scope
//         .find([
//             `#btnReupload${K}TriggerDiv`,
//             `#btnReupload${K}Trigger`,
//             `#btnReupload${K}TriggerLabel`,
//             `#${key}AttachmentFileName`,
//         ].join(','))
//         .removeClass('d-none');
 
//     $scope.find(`#btnReupload${K}Trigger`).prop('checked', false);
//     $scope.find(`#${key}Attachment`).addClass('d-none').removeAttr('required');
// }

// function resetReuploadUI($scope, key) {
//   const K = cap(key);
//   const $input = $scope.find(`#${key}Attachment`);

//   $scope
//     .find([
//       `#btnReupload${K}TriggerDiv`,
//       `#btnReupload${K}Trigger`,
//       `#btnReupload${K}TriggerLabel`,
//       `#${key}AttachmentFileName`,
//     ].join(','))
//     .addClass('d-none');

//   $scope.find(`#btnReupload${K}Trigger`).prop('checked', false);
//   $scope.find(`#${key}AttachmentFileName`).val('');

//   $input
//     .val('')                                   // clear any chosen file
//     .removeClass('d-none')                     // show the upload input again
//     .prop('required', !!$input.data('was-required'));
// }
 
// Fill many fields at once: { '#selector': value }
function fillFields($scope, map) {
    $.each(map, (selector, value) => $scope.find(selector).val(value ?? ''));
}
 
/* ---------- Status-based UI ---------- */
 
function applyStatusUI(response, approval, { $modal, $form, $formPart2 }) {
    const { status } = response;
 
    if (status == 4 && approval) { // For approval
        toggleElements($form,      ['#modalCSRPart1Footer', '#btnSubmitCSRPart1'], false);
        toggleElements($formPart2, ['#modalCSRPart2Footer', '#btnSubmitCSRPart2'], false);
        toggleElements($modal,     ['#btnApproved', '#btnDisapproved'], true, { syncDisabled: false });
        $('#disapproveRemarks').prop('disabled', false);
 
    } else if (status == 2) { // For Review
        setFormDisabled($form, true);
        setFormDisabled($formPart2, false);
 
        setCSRPartUI(1, false);
        setCSRPartUI(2, true);
        setCSRPartUI(3, true);
        setCSRPartUI(4, true);
        setCollapseManually('#collapsePart2', true);
 
    } else if (status == 1 || status == 5) { // Pending, Disapproved
        setFormDisabled($form, false);
        setFormDisabled($formPart2, true);
 
        setCSRPartUI(2, true);
        setCSRPartUI(4, false);
        setCollapseManually('#collapsePart2', false);
 
    } else { // Everything else: read-only
        setFormDisabled($form, true);
        setFormDisabled($formPart2, true);

        setCSRPartUI(1, false);
        setCSRPartUI(2, false);
        setCSRPartUI(3, true);
        setCSRPartUI(4, true);

        toggleElements($modal, ['#btnApproved', '#btnDisapproved'], false, { syncDisabled: false });
    }
}
 
/* ---------- Part renderers ---------- */
 
function populatePart1(response, $form) {
    ['pdf', 'excel'].forEach((type) => {
        setupReuploadUI($form, type);
        renderDownloadLink($form.find(`#${type}AttachmentDiv`), {
            url: `download_file/${response.id}/${type}`,
            buttonId: `download${cap(type)}File`,
        });
    });
 
    getCustomerName($('.selectCustomer'), response.customer_info.id);
 
    fillFields($form, {
        '#txtCSRId':           response.id,
        '#controlNo':          response.control_no,
        '#dateApplied':        response.date_applied,
        '#revNo':              response.revision_no,
        '#businessProcess':    response.business_process,
        '#changeDescription':  response.change_description,
        '#pdfAttachmentFileName':   response.pdf_attachment_info?.original_name,
        '#excelAttachmentFileName': response.excel_attachment_info?.original_name,
        '#preparedByName':     response.prepared_by_info.name,
        '#remarks':            response.remarks,
    });
}
 
function populatePart2(response, $formPart2) {
    $formPart2.find('#txtCSRIdReviewStaff').val(response.id);
 
    const review = response.review_info;
    if (response.status >= 2 && review){   
        ['reviewPdf', 'reviewExcel', 'reviewImage'].forEach((key) => setupReuploadUI($formPart2, key));
    
        [
            { type: 'pdf',   key: 'review' + 'Pdf' },
            { type: 'excel', key: 'review' + 'Excel' },
        ].forEach(({ type, key }) => {
            renderDownloadLink($formPart2.find(`#${key}AttachmentDiv`), {
                url: `download_evidence_file/${response.id}/${type}`,
                buttonId: `download${cap(key)}File`,
            });
        });
    
        const images = review.review_image_info;
        renderViewImagesButton($formPart2.find('#reviewImageAttachmentDiv'), {
            id: response.id,
            count: images?.length ?? 0,
        });
    
        fillFields($formPart2, {
            '#txtCSREvidenceIdReviewStaff':  review.id,
            '#reviewDate':                   review.date_reviewed,
            '#reviewedByName':               review.reviewed_by_info.name,
            '#reviewRemarks':                review.remarks,
            '#reviewPdfAttachmentFileName':  review.review_pdf_info?.original_name,
            '#reviewExcelAttachmentFileName': review.review_excel_info?.original_name,
            '#reviewImageAttachmentFileName': images ? `${images.length} image(s) uploaded` : '',
        });
    }else{
        // Handle the case when response.status >= 2 and review not exists
        console.warn('Review information is missing despite status being >= 2.');

    }
}

/* ---------- Main ---------- */
 
function fetchCSRById(id, $modal, $form, $formPart2, $mode, approval = false) {
    $.ajax({
        type: 'GET',
        url: 'get_csr_document_by_id',
        data: { id },
        dataType: 'json',
        beforeSend: () => resetCSR($form, $formPart2),
        success: (response) => {
            applyStatusUI(response, approval, { $modal, $form, $formPart2 });
            populatePart1(response, $form);
            populatePart2(response, $formPart2);
            $modal.modal('show');
        },
        error: (xhr) => {
            console.error('Fetch failed:', xhr.responseText);
            showError('Failed to fetch data.');
        },
    });
}


/**
 * Fetch csr_document data by ID
 */
// function fetchCSRById(id, $modal, $form, $formPart2, $mode, approval = false) {
//     $.ajax({
//         type: 'GET',
//         url: 'get_csr_document_by_id',
//         data: { id },
//         dataType: 'json',
//         beforeSend: function() {
//             console.log("Fetching CSR Document...");
//             resetCSR($form, $formPart2);
//         },
//         success: function (response){
//             // if($mode == 'view'){
//             //     disableForm($form, $formPart2);
//             // }

//             if (response.status == 4 && approval == true){ //For approval
//                 console.log('hide save, show approval');

//                 $form.find('#modalCSRPart1Footer').addClass('d-none');
//                 $form.find('#btnSubmitCSRPart1').addClass('d-none');
//                 $form.find('#btnSubmitCSRPart1').prop('disabled', true);

//                 $formPart2.find('#modalCSRPart2Footer').addClass('d-none');
//                 $formPart2.find('#btnSubmitCSRPart2').addClass('d-none');
//                 $formPart2.find('#btnSubmitCSRPart2').prop('disabled', true);
                
//                 $modal.find('#btnApproved').removeClass('d-none');
//                 $modal.find('#btnDisapproved').removeClass('d-none');
//                 $('#disapproveRemarks').prop('disabled', false);
//             }else if(response.status == 2){ //For Review
//                 console.log('show save for review only, hide ALL approval');

//                 setFormDisabled($form, true);
//                 setFormDisabled($formPart2, false);

//                 // Usage
//                 setCSRPartUI(1, false); // Hide Part 1 footer + button
//                 setCSRPartUI(2, true);  // Show Part 2 footer + button
//                 setCSRPartUI(3, true);  // Show Part 1 Div
//                 setCSRPartUI(4, true);  // Show Part 2 Div

//                 setCollapseManually('#collapsePart2', true);
//             }else if(response.status == 1 || response.status == 5){ //Pending, Disapproved
//                 console.log('show save for qas dcc only, hide ALL approval');

//                 setFormDisabled($form, false);
//                 setFormDisabled($formPart2, true);

//                 setCSRPartUI(2, true);  // Show Part 2 footer + button
//                 setCSRPartUI(4, false); // Hide Part 2 Div
//                 setCollapseManually('#collapsePart2', false);
//             }else{
//                 console.log('hide all');

//                 setFormDisabled($form, true);
//                 setFormDisabled($formPart2, true);

//                 $form.find('#btnApproved').addClass('d-none');
//                 $form.find('#btnDisapproved').addClass('d-none');
//             }

//             // Show Reupload Div & Exisiting PDF Filename
//             $form.find("#btnReuploadPdfTriggerDiv").removeClass('d-none');
//             $form.find("#btnReuploadPdfTrigger").removeClass('d-none');
//             $form.find("#btnReuploadPdfTrigger").prop('checked', false);
//             $form.find("#btnReuploadPdfTriggerLabel").removeClass('d-none');
//             $form.find("#pdfAttachmentFileName").removeClass('d-none');

//             // Hide PDF Upload Attachment section, remove required attribute
//             $form.find("#pdfAttachment").addClass('d-none');
//             $form.find("#pdfAttachment").removeAttr('required');

//             // Show Reupload Div & Exisiting EXCEL Filename
//             $form.find("#btnReuploadExcelTriggerDiv").removeClass('d-none');
//             $form.find("#btnReuploadExcelTrigger").removeClass('d-none');
//             $form.find("#btnReuploadExcelTrigger").prop('checked', false);
//             $form.find("#btnReuploadExcelTriggerLabel").removeClass('d-none');
//             $form.find("#excelAttachmentFileName").removeClass('d-none');

//             // Hide EXCEL Upload Attachment section, remove required attribute
//             $form.find("#excelAttachment").addClass('d-none');
//             $form.find("#excelAttachment").removeAttr('required');

//             // Usage
//             renderDownloadLink($form.find('#pdfAttachmentDiv'),   { id: response.id, type: 'pdf',   buttonId: 'downloadPdfFile' });
//             renderDownloadLink($form.find('#excelAttachmentDiv'), { id: response.id, type: 'excel', buttonId: 'downloadExcelFile' });

//             // // let download_file_pdf   = '<label for="pdfAttachmentDiv" class="form-control-label">PDF ATTACHMENT</label>&nbsp';
//             // let download_file_pdf   = '<a href="download_file/'+response.id+'/pdf" target="_blank">';
//             //     download_file_pdf   +=   '<button type="button" class="btn btn-primary btn-sm d-none" id="downloadPdfFile">';
//             // // let download_file_excel = '<label for="excelAttachmentDiv" class="form-control-label">EXCEL ATTACHMENT</label>&nbsp';
//             // let download_file_excel = '<a href="download_file/'+response.id+'/excel" target="_blank">';
//             //     download_file_excel +=   '<button type="button" class="btn btn-primary btn-sm d-none" id="downloadExcelFile">';
//             // let download_file       =        '<i class="fa-solid fa-file-arrow-down"></i>';
//             //     download_file       +=          '&nbsp;';
//             //     download_file       +=          'See Attachment';
//             //     download_file       +=   '</button>';
//             //     download_file       +='</a>';

//             // download_file_pdf = download_file_pdf + download_file;
//             // download_file_excel = download_file_excel + download_file;

//             // $form.find('#pdfAttachmentDiv').append(download_file_pdf);
//             // $form.find('#excelAttachmentDiv').append(download_file_excel);

//             // Show Download Button
//             $form.find("#downloadPdfFile").removeClass('d-none');
//             $form.find("#downloadExcelFile").removeClass('d-none');

//             // Populate modal fields (adjust names per csr_document)
//             getCustomerName($('.selectCustomer'), response.customer_info.id);
//             $form.find('#txtCSRId').val(response.id);
//             $form.find('#controlNo').val(response.control_no);
//             $form.find('#dateApplied').val(response.date_applied);
//             $form.find('#revNo').val(response.revision_no);
//             $form.find('#businessProcess').val(response.business_process);
//             $form.find('#changeDescription').val(response.change_description);
//             // $form.find('#pdfAttachmentFileName').val(response.pdf_attachment_info.original_name);
//             // $form.find('#excelAttachmentFileName').val(response.excel_attachment_info.original_name);

//             if (response.pdf_attachment_info && response.pdf_attachment_info.original_name) {
//                 $form.find('#pdfAttachmentFileName').val(response.pdf_attachment_info.original_name);
//             } else {
//                 $form.find('#pdfAttachmentFileName').val('');
//             }

//             if (response.excel_attachment_info && response.excel_attachment_info.original_name) {
//                 $form.find('#excelAttachmentFileName').val(response.excel_attachment_info.original_name);
//             } else {
//                 $form.find('#excelAttachmentFileName').val('');
//             }

//             $form.find('#preparedByName').val(response.prepared_by_info.name);
//             $form.find('#remarks').val(response.remarks);

//             //Part 2
//             $formPart2.find('#txtCSRIdReviewStaff').val(response.id);
//             if(response.status >= 2  && response.review_info){

//                 // Show Reupload Div & Exisiting PDF Filename
//                 $formPart2.find("#btnReuploadReviewPdfTriggerDiv").removeClass('d-none');
//                 $formPart2.find("#btnReuploadReviewPdfTrigger").removeClass('d-none');
//                 $formPart2.find("#btnReuploadReviewPdfTrigger").prop('checked', false);
//                 $formPart2.find("#btnReuploadReviewPdfTriggerLabel").removeClass('d-none');
//                 $formPart2.find("#reviewPdfAttachmentFileName").removeClass('d-none');

//                 // Hide PDF Upload Attachment section, remove required attribute
//                 $formPart2.find("#reviewPdfAttachment").addClass('d-none');
//                 $formPart2.find("#reviewPdfAttachment").removeAttr('required');

//                 // Show Reupload Div & Exisiting EXCEL Filename
//                 $formPart2.find("#btnReuploadReviewExcelTriggerDiv").removeClass('d-none');
//                 $formPart2.find("#btnReuploadReviewExcelTrigger").removeClass('d-none');
//                 $formPart2.find("#btnReuploadReviewExcelTrigger").prop('checked', false);
//                 $formPart2.find("#btnReuploadReviewExcelTriggerLabel").removeClass('d-none');
//                 $formPart2.find("#reviewExcelAttachmentFileName").removeClass('d-none');

//                 // Hide EXCEL Upload Attachment section, remove required attribute
//                 $formPart2.find("#reviewExcelAttachment").addClass('d-none');
//                 $formPart2.find("#reviewExcelAttachment").removeAttr('required');

//                 // Show Reupload Div & Exisiting IMAGES
//                 $formPart2.find("#btnReuploadReviewImageTriggerDiv").removeClass('d-none');
//                 $formPart2.find("#btnReuploadReviewImageTrigger").removeClass('d-none');
//                 $formPart2.find("#btnReuploadReviewImageTrigger").prop('checked', false);
//                 $formPart2.find("#btnReuploadReviewImageTriggerLabel").removeClass('d-none');
//                 $formPart2.find("#reviewImageAttachmentFileName").removeClass('d-none');

//                 // Hide IMAGES Upload Attachment section, remove required attribute
//                 $formPart2.find("#reviewImageAttachment").addClass('d-none');
//                 $formPart2.find("#reviewImageAttachment").removeAttr('required');

//                 // let download_file_review_pdf   = '<label for="reviewPdfAttachmentDiv" class="form-control-label">REVIEW PDF ATTACHMENT</label>&nbsp';
//                 let download_file_review_pdf   = '<a href="download_evidence_file/'+response.id+'/pdf" target="_blank">';
//                     download_file_review_pdf   +=   '<button type="button" class="btn btn-primary btn-sm d-none" id="downloadReviewPdfFile">';
//                 // let download_file_review_excel = '<label for="reviewExcelAttachmentDiv" class="form-control-label">REVIEW EXCEL ATTACHMENT</label>&nbsp';
//                 let download_file_review_excel = '<a href="download_evidence_file/'+response.id+'/excel" target="_blank">';
//                     download_file_review_excel +=   '<button type="button" class="btn btn-primary btn-sm d-none" id="downloadReviewExcelFile">';
//                 let download_file               =        '<i class="fa-solid fa-file-arrow-down"></i>';
//                     download_file              +=          '&nbsp;';
//                     download_file              +=          'See Attachment';
//                     download_file              +=   '</button>';
//                     download_file              += '</a>';

//                 let review_image_count = response.review_info.review_image_info ? response.review_info.review_image_info.length : 0;
//                 // let view_uploaded_images  = '<label for="reviewPdfAttachmentDiv" class="form-control-label">VIEW UPLOADED IMAGES</label>&nbsp';
//                 let view_uploaded_images = '<button type="button" id="downloadReviewImageFile"';
//                     view_uploaded_images +=        'class="btn btn-sm btn-primary d-none" data-bs-toggle="modal" data-bs-target="#reviewImagesModal" data-review-id="' + response.id + '">';
//                     view_uploaded_images +=    '<i class="fa fa-images"></i> View Images (' + review_image_count + ')';
//                     view_uploaded_images += '</button>';

//                 download_file_review_pdf = download_file_review_pdf + download_file;
//                 download_file_review_excel = download_file_review_excel + download_file;

//                 $formPart2.find('#reviewPdfAttachmentDiv').append(download_file_review_pdf);
//                 $formPart2.find('#reviewExcelAttachmentDiv').append(download_file_review_excel);
//                 $formPart2.find('#reviewImageAttachmentDiv').append(view_uploaded_images);

//                 // Show Download Button
//                 $formPart2.find("#downloadReviewPdfFile").removeClass('d-none');
//                 $formPart2.find("#downloadReviewExcelFile").removeClass('d-none');
//                 $formPart2.find("#downloadReviewImageFile").removeClass('d-none');

//                 $formPart2.find('#txtCSREvidenceIdReviewStaff').val(response.review_info.id);
//                 $formPart2.find('#reviewDate').val(response.review_info.date_reviewed);
//                 $formPart2.find('#reviewedByName').val(response.review_info.reviewed_by_info.name);
//                 $formPart2.find('#reviewRemarks').val(response.review_info.remarks);

//                 if (response.review_info.review_pdf_info && response.review_info.review_pdf_info.original_name) {
//                     $formPart2.find('#reviewPdfAttachmentFileName').val(response.review_info.review_pdf_info.original_name);
//                 } else {
//                     $formPart2.find('#reviewPdfAttachmentFileName').val('');
//                 }

//                 if (response.review_info.review_excel_info && response.review_info.review_excel_info.original_name) {
//                     $formPart2.find('#reviewExcelAttachmentFileName').val(response.review_info.review_excel_info.original_name);
//                 } else {
//                     $formPart2.find('#reviewExcelAttachmentFileName').val('');
//                 }

//                 if (response.review_info.review_image_info) {
//                     $formPart2.find('#reviewImageAttachmentFileName').val(response.review_info.review_image_info.length + ' image(s) uploaded');
//                 } else {
//                     $formPart2.find('#reviewImageAttachmentFileName').val('');
//                 }
//             }

//             $modal.modal('show');
//         },
//         error: function (xhr) {
//             console.error('Fetch failed:', xhr.responseText);
//             showError('Failed to fetch data.');
//         }
//     });
// }

// function renderDownloadLink($container, { id, type, buttonId, hidden = true }) {
//   const $link = $('<a>', {
//     href: `download_file/${id}/${type}`,
//     target: '_blank',
//     rel: 'noopener',
//   }).append(
//     $('<button>', {
//       type: 'button',
//       id: buttonId,
//       class: `btn btn-primary btn-sm${hidden ? ' d-none' : ''}`,
//       html: '<i class="fa-solid fa-file-arrow-down"></i>&nbsp;See Attachment',
//     })
//   );

//   $container.append($link);
// }

function setCollapseManually(collapseSelector, show) {
  const $collapse = $(collapseSelector);
  $collapse.toggleClass('show', show);

  // Keep the trigger button in sync
  $(`[data-bs-target="${collapseSelector}"]`)
    .toggleClass('collapsed', !show)
    .attr('aria-expanded', String(show));
}

function setFormDisabled(formSelector, disabled = true, except = "") {
  $(formSelector).find(":input").not(except).prop("disabled", disabled);
}

function disableForm($form, $formPart2) {
    $form.find('#modalCSRPart1Footer').prop('hidden', true);
    $form.find('#btnSubmitCSRPart1').prop('disabled', true);
    $form.find('#btnSubmitCSRPart1').prop('hidden', true);
    $form.find('input, textarea, select').prop('disabled', true);

    $formPart2.find('#modalCSRPart2Footer').prop('hidden', true);
    $formPart2.find('#btnSubmitCSRPart2').prop('disabled', true);
    $formPart2.find('#btnSubmitCSRPart2').prop('hidden', true);
    $formPart2.find('input, textarea, select').prop('disabled', true);
}

function disableForm($form, $formPart2) {
    $form.find('#modalCSRPart1Footer').prop('hidden', true);
    $form.find('#btnSubmitCSRPart1').prop('disabled', true);
    $form.find('#btnSubmitCSRPart1').prop('hidden', true);
    $form.find('input, textarea, select').prop('disabled', true);

    $formPart2.find('#modalCSRPart2Footer').prop('hidden', true);
    $formPart2.find('#btnSubmitCSRPart2').prop('disabled', true);
    $formPart2.find('#btnSubmitCSRPart2').prop('hidden', true);
    $formPart2.find('input, textarea, select').prop('disabled', true);
}

/**
 * Disable or update csr_document status
 */
function updateCSRStatus(id, dtCSR, updateStatusTo = null, $modal = null) {
    console.log('Updating CSR status for ID:', id, 'to new status:', dtCSR);
    
    $.ajax({
        type: 'POST',
        url: 'update_csr_document_status',
        data: {
            id: id,
            new_status: updateStatusTo,
        },
        dataType: 'json',
        success: function (response) {
            if(response.success == true) {
                showSuccess('Status updated successfully.');

                if($modal) {
                    $modal.modal('hide');
                }
                
                dtCSR.draw();
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
