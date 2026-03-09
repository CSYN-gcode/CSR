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
@section('title', 'Situations')
@section('content_page')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List of Situations</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">List of Situations</li>
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
                                <h3 class="card-title">Situations Module</h3>
                            </div>

                            <!-- Start Page Content -->
                            <div class="card-body">
                                <div style="float: right;">
                                    <button class="btn btn-dark" id="btnShowAddSituationModal">
                                        <i class="fa fa-plus"></i> Add Situation
                                    </button>
                                </div> <br><br>
                                <div class="table-responsive">
                                    <table id="tblSituations" class="table table-bordered table-striped table-hover"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Situation Name</th>
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
    <div class="modal fade" id="modalAddSituations">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add/Edit Situation Info</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formSituations" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="hidden" id="txtSituationId" name="id">

                                <div class="form-group">
                                    <label>Situation Name</label>
                                    <input type="text" class="form-control" name="situations" id="txtSituationName">
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

