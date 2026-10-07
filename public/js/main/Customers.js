$(document).ready(function () {
    // --------------------------------------
    // Cache DOM elements
    // --------------------------------------
    const $table = $('#tblCustomers');        // e.g., #tblCustomers
    const $form = $('#formCustomers');        // e.g., #formCustomers
    const $modal = $('#modalAddCustomers');      // e.g., #modalAddCustomers

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
    const dtCustomers = initCustomersTable($table);

    // --------------------------------------
    // Bind all event handlers
    // --------------------------------------
    bindCustomersEvents($table, $form, $modal, dtCustomers);
});

/**
 * Reset a form and clear hidden fields
 * @param {string|jQuery} formSelector - the form element or selector
 */
function resetCustomerForm(formSelector) {
    const $form = $(formSelector);
    $form[0].reset();
    $form.find('input[type="hidden"]').val('');
}

/**
 * Initialize DataTable
 */
function initCustomersTable($table, url = 'view_customers') {
    return $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: url },
        fixedHeader: true,
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'customer_name' },    // customize this per customers
            { data: 'status_label' }
        ]
    });
}

/**
 * Bind events for buttons, forms, etc.
 */
function bindCustomersEvents($table, $form, $modal, dtCustomers){

    $('#btnShowAddCustomerModal').on('click', function () {
        resetCustomerForm($form);
        $('#modalAddCustomers').modal('show');
    });

    // Submit form (Add / Edit)
    $form.on('submit', function (e) {
        e.preventDefault();
        saveCustomers($form, $modal, dtCustomers);
    });

    // Edit button
    $table.on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        fetchCustomersById(id, $modal);
    });

    // Disable button
    $table.on('click', '.btnDisable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to disable this customer?', function () {
            updateCustomersStatus(id, dtCustomers);
        });
    });

    // Enable button
    $table.on('click', '.btnEnable', function () {
        const id = $(this).data('id');
        confirmAction('Are you sure you want to enable this customer?', function () {
            updateCustomersStatus(id, dtCustomers);
        });
    });
}

/**
 * Save (add/update) customers data
 */
function saveCustomers($form, $modal, dtCustomers) {
    $.ajax({
        type: 'POST',
        url: 'add_customers',
        data: $form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.result === 1) {
                dtCustomers.draw(false);
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
 * Fetch customers data by ID
 */
function fetchCustomersById(id, $modal) {
    $.ajax({
        type: 'GET',
        url: 'get_customers_by_id',
        data: { id },
        dataType: 'json',
        success: function (response) {
            // Populate modal fields (adjust names per customers)
            $('#txtCustomerId').val(response.id);
            $('#txtCustomerName').val(response.customer_name);
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
 * Disable or update customers status
 */
function updateCustomersStatus(id, dtCustomers) {
    $.ajax({
        type: 'POST',
        url: 'update_customers_status',
        data: { id },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                showSuccess('Status updated successfully.');
                dtCustomers.draw(false);
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

function getCustomerName(cboElement, customerId = null){
    let result = '<option value="" disabled selected> Select Customer Name </option>';
    $.ajax({
        method: "get",
        url: "get_customer_dropdown_list",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="" disabled selected>--Loading--</option>';
        },
        success: function (response){
            if(response.length > 0){
                    result = '<option value="" disabled selected> Select Customer Name </option>';

                for (let i = 0; i < response.length; i++) {
                    result += '<option value="' + response[i]['id'] + '">' + response[i]['customer_name'] + '</option>';
                }
            }else{
                result = '<option value="" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
            if(customerId != null){
                cboElement.val(customerId).trigger('change');
            }
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}
