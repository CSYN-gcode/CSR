//============================== ADD USER ==============================
function AddUser(){
	$.ajax({
        url: "add_user",
        method: "post",
        data: $('#addUserForm').serialize(),
        dataType: "json",
        beforeSend: function () {
            $("#btnAddUserIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddUser").prop('disabled', 'disabled');
        },
        success: function (response) {
            if (response['validation'] == 'hasError') {
                if (response['error']['name'] === undefined) {
                    $("#idname").removeClass('is-invalid');
                    $("#idname").attr('title', '');
                }
                else {
                    $("#idname").addClass('is-invalid');
                    $("#idname").attr('title', response['error']['name']);
                }
                if (response['error']['userLevel'] === undefined) {
                    $("#selUserLevelId").removeClass('is-invalid');
                    $("#selUserLevelId").attr('title', '');
                }
                else {
                    $("#selUserLevelId").addClass('is-invalid');
                    $("#selUserLevelId").attr('title', response['error']['userLevel']);
                }
                toastr.error('Saving user failed!');
            }
            else if (response['result'] == 1) {
                $("#modalAddUser").modal('hide');
                dataTableusersTable.draw(); // reload the tables after insertion
                toastr.success('User added!');
            }
            else if (response['result'] == 0) {
                toastr.error('User already exists!');
            }

            $("#btnAddUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddUser").removeAttr('disabled');
            $("#btnAddUserIcon").addClass('fa fa-check');
        },
        error: function (data, xhr, status) {
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#btnAddUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddUser").removeAttr('disabled');
            $("#btnAddUserIcon").addClass('fa fa-check');
        }
    });
}

function DeactivateUser() {
    $.ajax({
        url: "deactivate_user",
        method: "post",
        data: $('#deactivateUserForm').serialize(),
        dataType: "json",
        beforeSend: function () {
            $("#deactivateIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnDeactivateUser").prop('disabled', 'disabled');
        },
        success: function (response) {
            let result = response['result'];
            if (result == 1) {
                dataTableusersTable.draw();
                $("#modalDeactivateUser").modal('hide');
                // $("#deactivateUserForm")[0].reset();
                toastr.success('User successfully deactivated!');
            }
            else {
                toastr.warning('User already deactivated!');
            }

            $("#deactivateIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnDeactivateUser").removeAttr('disabled');
            $("#deactivateIcon").addClass('fa fa-check');
        },
        error: function (data, xhr, status) {
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#deactivateIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnDeactivateUser").removeAttr('disabled');
            $("#deactivateIcon").addClass('fa fa-check');
        }
    });
}

function ActivateUser() {
    $.ajax({
        url: "activate_user",
        method: "post",
        data: $('#activateUserForm').serialize(),
        dataType: "json",
        beforeSend: function () {
            $("#activateIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnActivateUser").prop('disabled', 'disabled');
        },
        success: function (response) {
            let result = response['result'];
            if (result == 1) {
                $("#modalActivateUser").modal('hide');
                // $("#activateUserForm")[0].reset();
                toastr.success('User successfully activated!');
                dataTableusersTable.draw();
            }
            else {
                toastr.warning('User already deactivated!');
            }

            $("#activateIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnActivateUser").removeAttr('disabled');
            $("#activateIcon").addClass('fa fa-check');
        },
        error: function (data, xhr, status) {
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#activateIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnActivateUser").removeAttr('disabled');
            $("#activateIcon").addClass('fa fa-check');
        }
    });
}

function GetUserId(userId) {
    $.ajax({
        url: "get_id_edit_user",
        method: "get",
        data: {
            user_id: userId
        },
        dataType: "json",
        beforeSend: function () {
            //GET DEFAULT SELECTED SECTION
            // $("#editUserForm select[id='selSection']").val('0');

            //GET DEFAULT SELECTED POSITION
            // $("#editUserForm select[id='selPosition']").val('0');
        },
        success: function (JsonObject) {
            // let userData = response['user_data'];
            if (JsonObject['user_data'].length > 0) {
                //GET RAPIDX ID
                $("#selEditUserAccessUserId").val(JsonObject['user_data'][0].rapidx_id);

                //GET SECTION
                var sec_array = ['BOD','IAS','FIN','HRD','ESS','LOG','FAC','ISS','QAD','EMS','TS','CN','YF','PPS','PPS-TS','PPS-CN'];
                for(var index = 0; index < sec_array.length; index++){
                    if(JsonObject['user_data'][0].section == (index + 1)){
                        // $("#txtEditUserSectionId").val(sec_array[index]);
                        // $("#selSection").val((index + 1));
                        $("#editUserForm select[id='selSection']").val((index + 1));

                    }
                }

                //GET POSITION
                var pos_array = ['POS 1','POS 2','POS 3','POS 4','POS 5'];
                for(var index = 0; index < pos_array.length; index++){
                    if(JsonObject['user_data'][0].position == (index + 1)){
                        // $("#txtEditUserPositionId").val(pos_array[index]);
                        $("#editUserForm select[id='selPosition']").val((index + 1));
                    }
                }

                // $("#selPosition").val(JsonObject['user_data'][0].position);
                //GET USERLEVEL ID
                $("#selEditUserLevelId").val(JsonObject['user_data'][0].user_level_id);
            }
            else {
                toastr.warning('No Record Found!');
            }
        },
        error: function (data, xhr, status) {
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


//============================== EDIT USER ==============================
function EditUser(){
    $.ajax({
        url: "edit_user",
        method: "post",
        data: $('#editUserForm').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditUserIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditUser").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                toastr.error('Updating User Failed!');
                if(response['error']['name'] === undefined){
                    $("#idname").removeClass('is-invalid');
                    $("#idname").attr('title', '');
                }else{
                    $("#idname").addClass('is-invalid');
                    $("#idname").attr('title', response['error']['name']);
                }
                if(response['error']['userLevel'] === undefined){
                    $("#selUserLevelId").removeClass('is-invalid');
                    $("#selUserLevelId").attr('title', '');
                }else{
                    $("#selUserLevelId").addClass('is-invalid');
                    $("#selUserLevelId").attr('title', response['error']['userLevel']);
                }
            }else{
                if(response['result'] == 1){
                    $("#modalEditUser").modal('hide');
                    $("#selEditUserLevel").select2('val', '0');

                    dataTableusersTable.draw();
                    toastr.success('User was succesfully updated!');
                }else if (response['result'] == 0) {
                    toastr.error('User already exists!');
                }else{
                    toastr.warning(response['tryCatchError'] + "<br>" +
                    'Try Catch Error');
                }
            }
            $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditUser").removeAttr('disabled');
            $("#iBtnEditUserIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditUser").removeAttr('disabled');
            $("#iBtnEditUserIcon").addClass('fa fa-check');
        }
    });
}

// function GetDepartment() {
//     $.ajax({
//         url: "get_department",
//         method: "get",
//         dataType: "json",
//         success: function (response) {
//             var section = response['section'];
//             if (section > 0) {
//                 $('#txtSelectTargetDepartment').val(section);
//                 $('#txtSelectActualDepartment').val(section);
//             }
//             else {
//                 $('#txtSelectTargetDepartment').val(0);
//                 $('#txtSelectActualDepartment').val(0);
//             }
//         }
//     });
// }

// function GetDepartmentForInk() {
//     $.ajax({
//         url: "get_department_for_ink",
//         method: "get",
//         dataType: "json",
//         success: function (response) {
//             var section = response['section'];
//             if (section > 0) {
//                 $('#txtSelectTargetDepartment').val(section);
//                 $('#txtSelectActualDepartment').val(section);
//             }
//             else {
//                 $('#txtSelectTargetDepartment').val(0);
//                 $('#txtSelectActualDepartment').val(0);
//             }
//         }
//     });
// }

// function GetEnergyEntryDetailsforEdit() {
//     $.ajax({
//         url: "get_department",
//         method: "get",
//         dataType: "json",
//         success: function (response) {
//             var section = response['section'];
//             if (section > 0) {
//                 $('#txtSelectAddDepartment').val(section);
//                 $('#txtSelectEditDepartment').val(section);
//             }
//             else {
//                 $('#txtSelectAddDepartment').val(0);
//                 $('#txtSelectEditDepartment').val(0);
//             }
//         }
//     });
// }



//============================== EDIT USER BY ID TO EDIT ==============================
// function GetUserByIdToEdit(userId){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "get_user_by_id",
//         method: "get",
//         data: {
//             user_id: userId
//         },
//         dataType: "json",
//         beforeSend: function(){

//         },
//         success: function(response){
//             let user = response['user'];
//             if(user.length > 0){
//                 $("#txtEditUserName").val(user[0].name);
//                 $("#txtEditUserUserName").val(user[0].username);
//                 $("#txtEditUserPosition").val(user[0].position);
//                 $("#selEditUserLevel").val(user[0].user_level_id).trigger('change');
//             }
//             else{
//                 toastr.warning('No User Record Found!');
//             }
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }

//============================== EDIT USER DATA FROM RAPIDX ==============================
// function GetUserDetails() {
//     $.ajax({
//         url: "get_user_details",
//         method: "get",
//         dataType: "json",
//         success: function (response) {
//             var rapidx_user_id = response['rapidx_user_id'];
//             if (rapidx_user_id > 0) {
//                 $('#RapidxUserId').val(rapidx_user_id);
//             }
//             else {
//                 $('#RapidxUserId').val(0);
//             }
//         }
//     });
// }
//============================== EDIT USER DATA FROM RAPIDX END ==============================

//============================== DELETE USER ==============================
// function DeleteUser(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "delete_user",
//         method: "post",
//         data: $('#formDeleteUser').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnDeleteUserIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnDeleteUser").prop('disabled', 'disabled');
//         },
//         success: function(response){
//             let result = response['result'];
//             if(result == 1){
//                 dataTableusersTable.draw();
//                 dataTableUsersArchive.draw();
//                 $("#modalDeleteUser").modal('hide');
//                 $("#formDeleteUser")[0].reset();
//                 toastr.success('User successfully deleted');
//             }
//             else{
//                 toastr.warning('No user found!');
//             }

//             $("#iBtnDeleteUserIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnDeleteUser").removeAttr('disabled');
//             $("#iBtnDeleteUserIcon").addClass('fa fa-check');
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnDeleteUserIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnDeleteUser").removeAttr('disabled');
//             $("#iBtnDeleteUserIcon").addClass('fa fa-check');
//         }
//     });
// }

//============================== RESTORE USER ==============================
// function RestoreUser(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "restore_user",
//         method: "post",
//         data: $('#formRestoreUser').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnRestoreUserIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnRestoreUser").prop('disabled', 'disabled');
//         },
//         success: function(response){
//             let result = response['result'];
//             if(result == 1){
//                 dataTableUsersArchive.draw();
//                 dataTableusersTable.draw();
//                 $("#modalRestoreUser").modal('hide');
//                 $("#formRestoreUser")[0].reset();
//                 toastr.success('User successfully restored');
//             }
//             else{
//                 toastr.warning('Cannot restore the user');
//             }

//             $("#iBtnRestoreUserIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnRestoreUser").removeAttr('disabled');
//             $("#iBtnRestoreUserIcon").addClass('fa fa-check');
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnRestoreUserIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnRestoreUser").removeAttr('disabled');
//             $("#iBtnRestoreUserIcon").addClass('fa fa-check');
//         }
//     });
// }


//============================== SIGN IN ==============================
// function SignIn(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "sign_in",
//         method: "post",
//         data: $('#formSignIn').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnSignInIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnSignIn").prop('disabled', 'disabled');
//         },
//         success: function(response){
//             if(response['validation'] == 'hasError'){
//                 if(response['error']['username'] === undefined){
//                     $("#txtSignInUsername").removeClass('is-invalid');
//                     $("#txtSignInUsername").attr('title', '');
//                 }
//                 else{
//                     $("#txtSignInUsername").addClass('is-invalid');
//                     $("#txtSignInUsername").attr('title', response['error']['username']);
//                     // toastr.error(response['error']['username']);
//                 }

//                 if(response['error']['password'] === undefined){
//                     $("#txtSignInPassword").removeClass('is-invalid');
//                     $("#txtSignInPassword").attr('title', '');
//                 }
//                 else{
//                     $("#txtSignInPassword").addClass('is-invalid');
//                     $("#txtSignInPassword").attr('title', response['error']['password']);
//                     // toastr.error(response['error']['password']);
//                 }
//             }
//             else{
//                 if(response['result'] == 0){
//                     toastr.error(response['error_message']);
//                     $("#txtSignInUsername").removeClass('is-invalid');
//                     $("#txtSignInUsername").attr('title', '');
//                     $("#txtSignInPassword").removeClass('is-invalid');
//                     $("#txtSignInPassword").attr('title', '');
//                 }
//                 else {
//                     if(response['logdel'] == 'deleted'){
//                         toastr.error('Your account is deleted!');
//                     }
//                     else if(response['status'] == 'inactive'){
//                         toastr.error('Your account is inactive!');
//                         $("#txtSignInUsername").removeClass('is-invalid');
//                         $("#txtSignInUsername").attr('title', '');
//                         $("#txtSignInPassword").removeClass('is-invalid');
//                         $("#txtSignInPassword").attr('title', '');
//                     }
//                     else if(response['result'] == 1){
//                         window.location = "dashboard";
//                         // console.log(response['session']);
//                     }
//                     else if(response['result'] == 2){
//                         window.location = "change_pass_view";
//                     }
//                 }
//             }
//             $("#iBtnSignInIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnSignIn").removeAttr('disabled');
//             $("#iBtnSignInIcon").addClass('fa fa-check');
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnSignInIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnSignIn").removeAttr('disabled');
//             $("#iBtnSignInIcon").addClass('fa fa-check');
//         }
//     });
// }


//==============================SIGN OUT==============================
// function SignOut(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "sign_out",
//         method: "post",
//         data: $('#formSignOut').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnSignOutIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnSignOut").prop('disabled', 'disabled');
//         },
//         success: function(response){
//             $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnSignOut").removeAttr('disabled');
//             $("#iBtnSignOutIcon").addClass('fa fa-check');
//             if(response['result'] == 1){
//                 window.location = "http://rapidx/online-it-library/";
//             }
//             else{
//                 toastr.error('Logout Failed!');
//             }
//         },
//         error: function(data, xhr, status){
//             // toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnSignOut").removeAttr('disabled');
//             $("#iBtnSignOutIcon").addClass('fa fa-check');
//         }
//     });
// }

//============================== LOGIN ANOTHER(change_password view) ==============================
// function LoginAnother(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "sign_out",
//         method: "post",
//         data: $('#formChangePassword').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnSignOutIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnSignOut").prop('disabled', 'disabled');
//         },
//         success: function(result){
//             $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnSignOut").removeAttr('disabled');
//             $("#iBtnSignOutIcon").addClass('fa fa-check');
//             if(result['result'] == 1){
//                 window.location.href = "http://rapidx/online-it-library/";
//             }
//             else{
//                 toastr.error('Logout Failed!');
//             }
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnSignOut").removeAttr('disabled');
//             $("#iBtnSignOutIcon").addClass('fa fa-check');
//         }
//     });
// }

//============================== CHANGE PASSWORD ==============================
// function ChangePassword(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "change_pass",
//         method: "post",
//         data: $('#formChangePassword').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnChangePassIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnChangePass").prop('disabled', 'disabled');
//         },
//         success: function(response){
//             if(response['validation'] == 'hasError'){
//                 if(response['error']['username'] === undefined){
//                     $("#txtChangePasswordUsername").removeClass('is-invalid');
//                     $("#txtChangePasswordUsername").attr('title', '');
//                 }
//                 else{
//                     $("#txtChangePasswordUsername").addClass('is-invalid');
//                     $("#txtChangePasswordUsername").attr('title', response['error']['username']);
//                 }

//                 if(response['error']['password'] === undefined){
//                     $("#txtChangePasswordPassword").removeClass('is-invalid');
//                     $("#txtChangePasswordPassword").attr('title', '');
//                 }
//                 else{
//                     $("#txtChangePasswordPassword").addClass('is-invalid');
//                     $("#txtChangePasswordPassword").attr('title', response['error']['password']);
//                 }

//                 if(response['error']['new_password'] === undefined){
//                     $("#txtChangePasswordNewPassword").removeClass('is-invalid');
//                     $("#txtChangePasswordNewPassword").attr('title', '');
//                 }
//                 else{
//                     $("#txtChangePasswordNewPassword").addClass('is-invalid');
//                     $("#txtChangePasswordNewPassword").attr('title', response['error']['new_password']);
//                 }

//                 if(response['error']['confirm_password'] === undefined){
//                     $("#txtChangePasswordConfirmPassword").removeClass('is-invalid');
//                     $("#txtChangePasswordConfirmPassword").attr('title', '');
//                 }
//                 else{
//                     $("#txtChangePasswordConfirmPassword").addClass('is-invalid');
//                     $("#txtChangePasswordConfirmPassword").attr('title', response['error']['confirm_password']);
//                 }
//             }
//             else{
//                 if(response['result'] == 1){
//                     window.location = "dashboard";
//                     console.log(response['session']);
//                 }
//                 else{
//                     toastr.error(response['error_message']);
//                 }
//             }

//             $("#iBtnChangePassIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnChangePass").removeAttr('disabled');
//             $("#iBtnChangePassIcon").addClass('fa fa-check');
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnChangePassIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnChangePass").removeAttr('disabled');
//             $("#iBtnChangePassIcon").addClass('fa fa-check');
//         }
//     });
// }


//============================== CHANGE USER STATUS ==============================
// function ChangeUserStatus(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "change_user_stat",
//         method: "post",
//         data: $('#formChangeUserStat').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnChangeUserStatIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnChangeUserStat").prop('disabled', 'disabled');
//         },
//         success: function(response){

//             if(response['validation'] == 'hasError'){
//                 toastr.error('User activation failed!');
//             }else{
//                 if(response['result'] == 1){
//                     // check if the value of txtChangeUserStatUserStat is 1, this is use for activation,
//                     // since the default value of txtChangeUserStatUserStat is 2, this is use for deactivation.
//                     // In this case, first is to check if user status is equals to 1 means deactivated, then if you want to activate it then set the user status value to 2(use for deactivation)
//                     if($("#txtChangeUserStatUserStat").val() == 1){
//                         toastr.success('User activation success!');
//                         $("#txtChangeUserStatUserStat").val() == 2;
//                     }
//                     else{
//                         toastr.success('User deactivation success!');
//                         $("#txtChangeUserStatUserStat").val() == 1;
//                     }
//                 }
//                 $("#modalChangeUserStat").modal('hide');
//                 $("#formChangeUserStat")[0].reset();
//                 dataTableusersTable.draw();
//             }

//             $("#iBtnChangeUserStatIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnChangeUserStat").removeAttr('disabled');
//             $("#iBtnChangeUserStatIcon").addClass('fa fa-check');
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnChangeUserStatIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnChangeUserStat").removeAttr('disabled');
//             $("#iBtnChangeUserStatIcon").addClass('fa fa-check');
//         }
//     });
// }


//============================== RESET USER PASSWORD ==============================
// function ResetUserPass(){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "reset_password",
//         method: "post",
//         data: $('#formResetUserPassword').serialize(),
//         dataType: "json",
//         beforeSend: function(){
//             $("#iBtnResetUserPassIcon").addClass('fa fa-spinner fa-pulse');
//             $("#btnResetUserPass").prop('disabled', 'disabled');
//         },
//         success: function(response){
//             if(response['result'] == 1){
//                 toastr.success('Reset Password Success!');
//             }
//             else{
//                 toastr.error('Resetting Password Failed!');
//             }

//             $("#modalResetUserPassword").modal('hide');
//             $("#iBtnResetUserPasswordIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnResetUserPassword").removeAttr('disabled');
//             $("#iBtnResetUserPasswordIcon").addClass('fa fa-check');

//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             $("#iBtnResetUserPasswordIcon").removeClass('fa fa-spinner fa-pulse');
//             $("#btnResetUserPassword").removeAttr('disabled');
//             $("#iBtnResetUserPasswordIcon").addClass('fa fa-check');
//         }
//     });
// }

//============================== GET USER BY STATUS FOR DASHBOARD ==============================
// function CountUserByStatForDashboard(status){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };
//     $.ajax({
//         url: "get_user_by_stat",
//         method: "get",
//         data: {
//             status: status
//         },
//         dataType: "json",
//         beforeSend: function(){

//         },
//         success: function(JsonObject){
//             if(JsonObject['user'].length > 0){
//                 $("#h3TotalNoOfUsers").text(JsonObject['user'].length);
//             }
//             else{
//                 toastr.warning('No User Record Found!');
//             }
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//             return totalNoOfUsers;
//         }
//     });
// }

//============================== GET USER LIST ==============================
// function GetUserList(cboElement){
//     let result = '<option value="">N/A</option>';
//     $.ajax({
//         url: 'get_user_list',
//         method: 'get',
//         dataType: 'json',
//         beforeSend: function(){
//             result = '<option value=""> -- Loading -- </option>';
//             cboElement.html(result);
//         },
//         success: function(JsonObject){
//             result = '';
//             if(JsonObject['users'].length > 0){
//                 result = '<option value="">N/A</option>';
//                 for(let index = 0; index < JsonObject['users'].length; index++){
//                     let disabled = '';

//                     if(JsonObject['users'][index].status == 2){
//                         disabled = 'disabled';
//                     }
//                     else{
//                         disabled = '';
//                     }
//                     result += '<option data-code="' + JsonObject['users'][index].employee_id + '" value="' + JsonObject['users'][index].id + '" ' + disabled + '>' + JsonObject['users'][index].name + '</option>';
//                 }
//             }
//             else{
//                 result = '<option value=""> -- No record found -- </option>';
//             }

//             cboElement.html(result);
//         },
//         error: function(data, xhr, status){
//             result = '<option value=""> -- Reload Again -- </option>';
//             cboElement.html(result);
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }
