/**
 * ==========================================
 * TEMPLATE Defects SCRIPT
 * ------------------------------------------
 * Rename "Defects" and related variables
 * to match your actual feature.
 *
 * Example:
 *   - Replace "Defects" with "Defects"
 *   - Replace URLs accordingly
 * ==========================================
 */

$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------
    const $table = $('#tblDefects');        // e.g., #tblDefects
    const $form = $('#formDefects');        // e.g., #formDefects
    const $modal = $('#modalAddDefects');      // e.g., #modalAddDefects

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
    const dtDefects = initDefectsTable($table);

    // --------------------------------------
    // Bind all event handlers
    // --------------------------------------
    bindDefectsEvents($table, $form, $modal, dtDefects);
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 */
function resetDefectForm(formSelector) {
    const $form = $(formSelector);
    $form[0].reset();
    $form.find('input[type="hidden"]').val('');
}

/**
 * Initialize DataTable
 */
function initDefectsTable($table, url = 'view_defects') {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: url },
        fixedHeader: true,
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'defect_name' },    // customize this per defects
            { data: 'status_label' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindDefectsEvents($table, $form, $modal, dtDefects){

    $('#btnShowAddDefectModal').on('click', function () {
        resetDefectForm($form);
        $('#modalAddDefects').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        e.preventDefault();
        saveDefects($form, $modal, dtDefects);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchDefectsById(id, $modal);
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to disable this defect?', function () {
            updateDefectsStatus(id, dtDefects);
        });
    });

    // Enable button
    $table.on('click', '.btnEnable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to enable this defect?', function () {
            updateDefectsStatus(id, dtDefects);
        });
    });
}

/**
 * Save (add/update) defects data
 */
function saveDefects($form, $modal, dtDefects) {
    $.ajax({
        type: 'POST',
        url: 'add_defects',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.result === 1) {
                dtDefects.draw(false);
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
 * Fetch defects data by ID
 */
function fetchDefectsById(id, $modal) {
    $.ajax({
        type: 'GET',
        url: 'get_defects_by_id',
        data: { id },
        dataType: 'json',
        success: function (response) {
            // Populate modal fields (adjust names per defects)
            $('#txtDefectId').val(response.id);
            $('#txtDefectName').val(response.defect_name);
            // $('#selStatus').val(response.status);

            $modal.modal('show');
        },
        error: function (xhr) {
            console.error('Fetch failed:', xhr.responseText);
            showError('Failed to fetch data.');
        }
    });
}

/**
 * Disable or update defects status
 */
function updateDefectsStatus(id, dtDefects) {
    $.ajax({
        type: 'POST',
        url: 'update_defects_status',
        data: { id },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showSuccess('Status updated successfully.');
                dtDefects.draw(false);
            }else {
                // ⚠️ If success is false
                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'error'
                });
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
