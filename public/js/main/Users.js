/**
 * ==========================================
 * TEMPLATE Users SCRIPT
 * ------------------------------------------
 * Rename "Users" and related variables
 * to match your actual feature.
 *
 * Example:
 *   - Replace "Users" with "Users"
 *   - Replace URLs accordingly
 * ==========================================
 */

$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------
    const $table = $('#tblUsers');        // e.g., #tblUsers
    const $form = $('#formUsers');        // e.g., #formUsers
    const $modal = $('#modalAddUsers');      // e.g., #modalAddUsers

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
    const dtUsers = initUsersTable($table);

    // --------------------------------------
    // Bind all event handlers
    // --------------------------------------
    bindUsersEvents($table, $form, $modal, dtUsers);
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 */
function resetUserForm(formSelector) {
    const $form = $(formSelector);
    console.log('resetting form', $form);
    $form[0].reset();
    $form.find('input[type="hidden"]').val('');
}

/**
 * Initialize DataTable
 */
function initUsersTable($table, url = 'view_users') {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: url },
        fixedHeader: true,
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name' },    // customize this per users
            { data: 'employee_id' },
            { data: 'section' },
            { data: 'position_label' },
            { data: 'status_label' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindUsersEvents($table, $form, $modal, dtUsers){

    $('#btnShowAddUserModal').on('click', function () {
        resetUserForm($form);
        getUsers($('#selectName'));
        $('#modalAddUsers').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        e.preventDefault();
        saveUsers($form, $modal, dtUsers);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchUsersById(id, $modal);
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to disable this user?', function () {
            updateUsersStatus(id, dtUsers);
        });
    });

    // Enable button
    $table.on('click', '.btnEnable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to enable this user?', function () {
            updateUsersStatus(id, dtUsers);
        });
    });

    $('#selectName').on('change', function() {
        const selectedOption = $(this).find('option:selected');

        $('#rapidxUserId').val(selectedOption.data('id'));
        $('#employeeId').val(selectedOption.data('emp_id'));
        $('#section').val(selectedOption.data('section'));
    });
}

function getUsers(cboElement, userId = null){
    let result = '<option value="" disabled selected> Select User </option>';
    $.ajax({
        method: "get",
        url: "get_rapidx_users",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            let rapidx_users = response.rapidx_user_data;
            if(rapidx_users.length > 0){
                    result = '<option value="" disabled selected> Select User </option>';

                for (let ui = 0; ui < rapidx_users.length; ui++) {
                    let id = rapidx_users[ui]['id'];
                    let name = rapidx_users[ui]['name'];
                    let emp_id = rapidx_users[ui]['employee_number'];
                    let section = rapidx_users[ui]['section'];

                    result += '<option value="'+name+'" data-id="'+id+'" data-emp_id="'+emp_id+'" data-section="'+section+'">' + name + '</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);

            if (userId != null) {
                let current_option = cboElement.find('option[data-id="'+ userId +'"]');

                if (current_option.length) {
                    cboElement.val(current_option.val()).trigger('change');
                }
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
 * Save (add/update) users data
 */
function saveUsers($form, $modal, dtUsers) {
    $.ajax({
        type: 'POST',
        url: 'add_users',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.result === 1) {
                dtUsers.draw(false);
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
 * Fetch users data by ID
 */
function fetchUsersById(id, $modal) {
    $.ajax({
        type: 'GET',
        url: 'get_users_by_id',
        data: { id },
        dataType: 'json',
        success: function (response) {
            // Populate modal fields (adjust names per users)
            $('#userId').val(response.id);
            $('#position').val(response.position);
            getUsers($('#selectName'), response.rapidx_user_id);
            $modal.modal('show');
        },
        error: function (xhr) {
            console.error('Fetch failed:', xhr.responseText);
            showError('Failed to fetch data.');
        }
    });
}

/**
 * Disable or update users status
 */
function updateUsersStatus(id, dtUsers) {
    $.ajax({
        type: 'POST',
        url: 'update_users_status',
        data: { id },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showSuccess('Status updated successfully.');
                dtUsers.draw(false);
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
