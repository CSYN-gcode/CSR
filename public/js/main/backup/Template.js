/**
 * ==========================================
 * TEMPLATE MODULE SCRIPT
 * ------------------------------------------
 * Rename "Module" and related variables
 * to match your actual feature.
 *
 * Example:
 *   - Replace "Module" with "Defects"
 *   - Replace URLs accordingly
 * ==========================================
 */

$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------
    const $table = $('#tblModule');        // e.g., #tblDefects
    const $form = $('#formModule');        // e.g., #formDefects
    const $modal = $('#modalModule');      // e.g., #modalAddDefects

    // --------------------------------------
    // Initialize global AJAX setup (once per project)
    // --------------------------------------
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // --------------------------------------
    // Initialize DataTable
    // --------------------------------------
    const dtModule = initModuleTable($table);

    // --------------------------------------
    // Bind all event handlers
    // --------------------------------------
    bindModuleEvents($table, $form, $modal, dtModule);
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 */
function resetModuleForm(formSelector) {
    const $form = $(formSelector);
    $form[0].reset();
    $form.find('input[type="hidden"]').val('');
}

/**
 * Initialize DataTable
 */
function initModuleTable($table, url = 'view_module') {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: url },
        fixedHeader: true,
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name' },    // customize this per module
            { data: 'status' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindModuleEvents($table, $form, $modal, dtModule) {

    $('#btnShowAddDevice').on('click', function () {
        resetModuleForm($form);
        $('#modalAddModule').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        e.preventDefault();
        saveModule($form, $modal, dtModule);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchModuleById(id, $modal);
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to disable this item?', function () {
            updateModuleStatus(id, dtModule);
        });
    });
}

/**
 * Save (add/update) module data
 */
function saveModule($form, $modal, dtModule) {
    $.ajax({
        type: 'POST',
        url: 'add_module',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.result === 1) {
                dtModule.draw(false);
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
 * Fetch module data by ID
 */
function fetchModuleById(id, $modal) {
    $.ajax({
        type: 'GET',
        url: 'get_module_by_id',
        data: { id },
        dataType: 'json',
        success: function (response) {
            // Populate modal fields (adjust names per module)
            $('#txtModuleId').val(response.id);
            $('#txtModuleName').val(response.name);
            $('#selStatus').val(response.status);

            $modal.modal('show');
        },
        error: function (xhr) {
            console.error('Fetch failed:', xhr.responseText);
            showError('Failed to fetch data.');
        }
    });
}

/**
 * Disable or update module status
 */
function updateModuleStatus(id, dtModule) {
    $.ajax({
        type: 'POST',
        url: 'update_module_status',
        data: { id },
        dataType: 'json',
        success: function (response) {
            if (response.result === 1) {
                showSuccess('Status updated successfully.');
                dtModule.draw(false);
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
