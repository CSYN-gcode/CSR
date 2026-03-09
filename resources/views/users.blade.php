@php $layout = 'layouts.super_user_layout'; @endphp
{{-- @auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth --}}

{{-- @auth --}}
@extends($layout)
@section('title', 'Users')
@section('content_page')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">User List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                        <!-- general form elements -->
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">User List Module</h3>
                            </div>

                            <!-- Start Page Content -->
                            <div class="card-body">
                                <div style="float: right;">
                                    <button class="btn btn-dark" id="btnShowAddUserModal">
                                        <i class="fa fa-plus"></i> Add User
                                    </button>
                                </div> <br><br>
                                <div class="table-responsive">
                                    <table id="tblUsers" class="table table-bordered table-striped table-hover"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Name</th>
                                                <th>Employee ID</th>
                                                <th>Section</th>
                                                <th>Position</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- !-- End Page Content -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- MODALS -->
    <div class="modal fade" id="modalAddUsers">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add/Edit User Info</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formUsers" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="hidden" name="id" id="userId">
                                <input type="hidden" name="rapidx_user_id" id="rapidxUserId">

                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control select2bs5" name="name" id="selectName" required>
                                         {{-- AUTO GENERATE --}}
                                        <option value="" disabled selected> Select Employee Name </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Employee ID</label>
                                    <input type="text" class="form-control" name="employee_id" id="employeeId" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Section</label>
                                    <input type="text" class="form-control" name="section" id="section" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Position</label>
                                    <select class="form-control" name="position" id="position" required>
                                        <option value="" disabled selected>Select Position</option>
                                        <option value="0">Admin</option>
                                        <option value="1">QAS DCC</option>
                                        <option value="2">Review Staff</option>
                                        <option value="3">Section Head</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnProcess" class="btn btn-dark"><i
                                class="fa fa-check"></i> Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('js_content')
    <script type="text/javascript">

    </script>
@endsection

