/**
 * ==========================================
 * TEMPLATE Situations SCRIPT
 * ------------------------------------------
 * Rename "Situations" and related variables
 * to match your actual feature.
 *
 * Example:
 *   - Replace "Situations" with "Situations"
 *   - Replace URLs accordingly
 * ==========================================
 */

$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------
    const $table = $('#tblSituations');        // e.g., #tblSituations
    const $form = $('#formSituations');        // e.g., #formSituations
    const $modal = $('#modalAddSituations');      // e.g., #modalAddSituations

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
    const dtSituations = initSituationsTable($table);

    // --------------------------------------
    // Bind all event handlers
    // --------------------------------------
    bindSituationsEvents($table, $form, $modal, dtSituations);
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 */
function resetSituationForm(formSelector) {
    const $form = $(formSelector);
    console.log('resetting form', $form);
    $form[0].reset();
    $form.find('input[type="hidden"]').val('');
}

/**
 * Initialize DataTable
 */
function initSituationsTable($table, url = 'view_situations') {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: url },
        fixedHeader: true,
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'situation_name' },    // customize this per situations
            { data: 'status_label' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindSituationsEvents($table, $form, $modal, dtSituations){

    $('#btnShowAddSituationModal').on('click', function () {
        resetSituationForm($form);
        $('#modalAddSituations').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        e.preventDefault();
        saveSituations($form, $modal, dtSituations);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchSituationsById(id, $modal);
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to disable this situation?', function () {
            updateSituationsStatus(id, dtSituations);
        });
    });

    // Enable button
    $table.on('click', '.btnEnable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to enable this situation?', function () {
            updateSituationsStatus(id, dtSituations);
        });
    });
}

/**
 * Save (add/update) situations data
 */
function saveSituations($form, $modal, dtSituations) {
    $.ajax({
        type: 'POST',
        url: 'add_situations',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.result === 1) {
                dtSituations.draw(false);
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
 * Fetch situations data by ID
 */
function fetchSituationsById(id, $modal) {
    $.ajax({
        type: 'GET',
        url: 'get_situations_by_id',
        data: { id },
        dataType: 'json',
        success: function (response) {
            // Populate modal fields (adjust names per situations)
            $('#txtSituationId').val(response.id);
            $('#txtSituationName').val(response.situation_name);
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
 * Disable or update situations status
 */
function updateSituationsStatus(id, dtSituations) {
    $.ajax({
        type: 'POST',
        url: 'update_situations_status',
        data: { id },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showSuccess('Status updated successfully.');
                dtSituations.draw(false);
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
