@php $layout = 'layouts.super_user_layout'; @endphp
@extends($layout)
@section('title', 'Customer Specific Requirements (CSR)')
@section('content_page')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customer Specific Requirements (CSR)</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Customer Specific Requirements (CSR)</li>
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
                                <h3 class="card-title">Customer Specific Requirements Module</h3>
                            </div>

                            <!-- Start Page Content -->
                            <div class="card-body">
                                {{-- <div class="float-sm-right ml-2">
                                    <button class="btn btn-success" id="btnShowExportReportModal">
                                        <i class="fa fa-initial-icon"></i> Export Report
                                    </button>
                                </div> --}}

                                {{-- @if ( $globalUser && in_array( $globalUser->position, [0,2,3])) --}}
                                    <div class="float-sm-right">
                                        <button class="btn btn-dark" id="btnShowAddPartsTroubleHistory">
                                            <i class="fa fa-initial-icon"></i> Add Past Trouble History
                                        </button>
                                    </div>
                                {{-- @endif --}}

                                <div class="float-sm-left col-2">
                                    {{-- <form id="frmSearchYear" class="form-inline"> --}}
                                        <label><strong>Filter Year : &nbsp;</strong></label>
                                        <input type="text" id="SearchYear" class="form-control" name="year" title="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>">

                                        {{-- <button class="btn btn-primary" type="submit">Search</button> --}}
                                    {{-- </form> --}}

                                {{-- <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalAdvancedSearch" id=""><i class="fas fa-search fa-md"></i> Advanced Search</button> --}}
                                </div>

                                <div class="float-sm-left mb-4 col-2">
                                    <label><strong>Month :</strong></label>
                                    <select class="form-control selectMonth" name="month_value" id="SelectMonth">
                                        <option value="<?php echo date('m'); ?>" readonly><?php echo date('F'); ?></option><!-- selected -->
                                        <option value="" selected>All</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>

                                <div class="table-responsive">
                                    <table id="tblPartsTroubleHistory" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">Action</th>
                                                <th style="width: 5%;">Status</th>
                                                <th style="width: 10%;" class="text-center">Date Encountered</th>
                                                <th style="width: 10%;" class="text-center">Situation</th>
                                                <th style="width: 5%;"  class="text-center">Section</th>
                                                <th style="width: 15%;" class="text-center">Series / Model</th>
                                                <th style="width: 15%;" class="text-center">Mode of Defect</th>
                                                <th style="width: 25%;" class="text-center">Illustration of Defect</th>
                                                <th style="width: 10%;" class="text-center">No of Occurence</th>
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
    <div class="modal fade" id="modalPartsTroubleHistory" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add/Edit Past Trouble History Info</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formPartsTroubleHistory" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="hidden" id="txtPartsTroubleHistoryId" name="history_id">

                                <div class="form-group">
                                    <label>Situation</label>
                                    <select class="form-control select2bs5" name="situation" id="selectSituation" required>
                                         {{-- AUTO GENERATE --}}
                                        <option value="" disabled selected> Select Situation </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Section</label>
                                    <select class="form-control" name="section" id="section" required>
                                        <option value="" disabled selected>Select Section</option>
                                        <option value="TS">TS</option>
                                        <option value="CN">CN</option>
                                        <option value="PPD">PPD</option>
                                        <option value="YF">YF</option>
                                    </select>
                                </div>

                                <!--ATTACHMENT-->
                                <div class="form-group">
                                    <div class="form-control-label" id="attachmentDiv">
                                        <label for="imageUpload" class="form-control-label">Illustration of Defect</label>
                                    </div>
                                    <input type="file" class="form-control" name="illustration_of_defect" id="illustrationOfDefect" accept="image/*" required>
                                    <input type="text" class="form-control d-none" name="illustration_of_defect_filename" id="illustrationOfDefectFileName" readonly>
                                    <div class="form-group form-check d-none m-0" id="btnReuploadTriggerDiv">
                                        <input type="checkbox" class="form-check-input d-none" id="btnReuploadTrigger">
                                        <label class="d-none" id="btnReuploadTriggerLabel"> Re-upload File</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Series / Model Name</label>
                                    {{-- <input type="text" class="form-control" name="model" id="model" required> --}}
                                    <select class="form-control select2bs5" name="model" id="selectDeviceName" disabled required>
                                        <option value="" disabled selected> Select Series Name </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Mode of Defect</label>
                                    <select class="form-control select2bs5" name="defect_id" id="defectId" required></select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Date Encountered</label>
                                    <input type="date" class="form-control" name="date_encountered" id="dateEncountered" required>
                                </div>

                                <div class="form-group">
                                    <label>No. of Occurrence</label>
                                    {{-- AUTO-GENERATE --}}
                                    <input type="text" class="form-control" name="no_of_occurrence" id="noOfOccurrence" placeholder="Auto Generate" readonly>
                                </div>
                            </div>

                            {{-- Multiple Data for Improvement Actions --}}
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-between">

                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" id="btnAddImprovementAction" class="btn btn-primary"><i class="fa fa-plus"></i> Add Improvements</button>
                                        </div>
                                        <br>
                                        <table class="table table-sm" id="tblImprovementActions">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">Action</th>
                                                    <th style="width: 10%;">Factor</th>
                                                    <th style="width: 15%;">Cause</th>
                                                    <th style="width: 20%;">Analysis</th>
                                                    <th style="width: 20%;">Counter Measure</th>
                                                    <th style="width: 20%;">PIC</th>
                                                    <th style="width: 10%;">Implementation Date</th>
                                                    {{-- <th style="width: 10%;">Remarks</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Multiple Data for Improvement Actions --}}

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnSubmitPartsTroubleHistory" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- MODALS -->
    <div class="modal fade" id="modalExportReport" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-hand-pointer"></i> Select Parameters for Export</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="exportPTHSReportForm" action="{{ route('export_excel') }}" method="GET">
                    <div class="modal-body">
                        {{-- @csrf --}}
                        <div class="row" style="display:flex; gap:10px; align-items:end;">
                            <div class="col">
                                <label>From</label>
                                <input type="date" name="date_from_export" class="form-control" required>
                            </div>

                            <div class="col">
                                <label>To</label>
                                <input type="date" name="date_to_export" class="form-control" required>
                            </div>
                        </div>

                        <div class="row" style="display:flex; gap:10px; align-items:end; margin-top:15px;">
                            <div class="col">
                                <label>Situation</label>
                                <select class="form-control select2bs5" name="situation_export" id="selectSituationToExport" required>
                                    <option value="" selected>Select Situation</option>
                                    {{-- <option value="ALL">ALL</option>
                                    <option value="External Claim">External Claim</option>
                                    <option value="Internal Claim">Internal Claim</option>
                                    <option value="Lot Out">Lot Out</option>
                                    <option value="Yield of Targets">Yield of Targets</option>
                                    <option value="Defect Escalation">Defect Escalation</option> --}}
                                </select>
                            </div>

                            <div class="col">
                                <label>Mode of Defect</label>
                                <select class="form-control select2bs5" name="defect_export" id="defectIdToExport" required></select>
                            </div>
                        </div>

                        <div class="row" style="display:flex; gap:10px; align-items:end; margin-top:15px;">
                            <div class="col">
                                <label>Section</label>
                                <select class="form-control select2bs5" name="section_export" id="selectSectionToExport" required>
                                    <option value="" disabled selected>Select Section</option>
                                    <option value="ALL">ALL</option>
                                    <option value="TS">TS</option>
                                    <option value="CN">CN</option>
                                    <option value="PPD">PPD</option>
                                    <option value="YF">YF</option>
                                </select>
                            </div>

                            <div class="col">
                                <label>Series / Model Name</label>
                                <select class="form-control select2bs5" name="model_export" id="selectDeviceNameToExport" disabled required>
                                    <option value="" disabled selected> Select Series Name </option>
                                </select>
                            </div>
                        </div>

                        <div style="display:flex; gap:10px; align-items:end; margin-top:15px;">
                            <button type="submit" class="btn btn-success">
                                Export to Excel
                            </button>
                        </div>

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

