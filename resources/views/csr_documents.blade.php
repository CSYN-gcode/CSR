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
                                        <button class="btn btn-dark" id="btnShowAddCSR">
                                            <i class="fa fa-initial-icon"></i> Add CSR Document
                                        </button>
                                    </div>
                                {{-- @endif --}}

                                <div class="float-sm-left col-2">
                                    <label><strong>Filter Year : &nbsp;</strong></label>
                                    <input type="text" id="SearchYear" class="form-control" name="year" title="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>">
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
                                    <table id="tblCSR" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">Action</th>
                                                <th style="width: 5%;">Status</th>
                                                <th style="width: 5%;" class="text-center">Contro No</th>
                                                <th style="width: 10%;" class="text-center">Customer Name</th>
                                                <th style="width: 10%;"  class="text-center">Business Process</th>
                                                <th style="width: 10%;" class="text-center">Date Applied</th>
                                                <th style="width: 5%;" class="text-center">Rev No.</th>
                                                <th style="width: 25%;" class="text-center">Change Description</th>
                                                <th style="width: 20%;" class="text-center">Prepared By</th>
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
    <div class="modal fade" id="modalCSR" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <!-- MODAL HEADER -->
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fa fa-file-text-o"></i>
                        CSR Document Information
                    </h4>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <!-- ===================================================== -->
                    <!-- QAS DCC -->
                    <!-- ===================================================== -->

                    <div class="card mb-3" id="cardQasDcc">
                        <div class="card-header p-2">
                            <button type="button" class="btn btn-link btn-block text-left" data-bs-toggle="collapse" data-bs-target="#collapsePart1" aria-expanded="true">
                                <i class="fa fa-file-text-o mr-2"></i>
                                <strong>QAS DCC</strong>
                                <i class="fa fa-chevron-down float-right"></i>
                            </button>
                        </div>

                        <div id="collapsePart1" class="collapse show">
                            <div class="card-body">
                                <form method="post" id="formCSR" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="process" value="qas_dcc">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="hidden" id="txtCSRId" name="csr_document_id">

                                                <div class="form-group">
                                                    <label>Control No</label> {{-- AUTO-GENERATE --}}
                                                    <input type="text" class="form-control" name="control_no" id="controlNo" placeholder="Auto Generate" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Customer Name</label>
                                                    <select class="form-control select2bs5 selectCustomer" name="customer" id="customer" required> {{-- AUTO GENERATE --}}
                                                        <option value="" disabled selected> Select Customer </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Business Process</label>
                                                    <input type="text" class="form-control" name="business_process" id="businessProcess" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date Applied</label>
                                                    <input type="date" class="form-control" name="date_applied" id="dateApplied" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Rev No.</label>
                                                    <input type="text" class="form-control" name="rev_no" id="revNo" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Description of Change</label>
                                                    <textarea class="form-control" name="change_description" id="changeDescription" style="height: 38px;" required></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <!--PDF ATTACHMENT-->
                                                <div class="form-group">
                                                    <div class="form-control-label" id="pdfAttachmentDiv"></div>
                                                    <input type="file" class="form-control" name="pdf_attachment" id="pdfAttachment" accept=".pdf" required>
                                                    <input type="text" class="form-control d-none" name="pdf_attachment_filename" id="pdfAttachmentFileName" readonly>
                                                    <div class="form-group form-check d-none m-0" id="btnReuploadPdfTriggerDiv">
                                                        <input type="checkbox" class="form-check-input d-none" id="btnReuploadPdfTrigger">
                                                        <label class="d-none" id="btnReuploadPdfTriggerLabel"> Re-upload PDF File</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Prepared By</label>
                                                    {{-- <input type="text" class="form-control" name="prepared_by" id="preparedById" value="{{ $globalUser->rapidx_emp_id }}" hidden>
                                                    <input type="text" class="form-control" id="preparedByName" value="{{ $globalUser->name }}" readonly> --}}
                                                    <input type="text" class="form-control" name="prepared_by" id="preparedById" value="" hidden>
                                                    <input type="text" class="form-control" id="preparedByName" value="" placeholder="Auto Generate" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <!--EXCEL ATTACHMENT-->
                                                <div class="form-group">
                                                    <div class="form-control-label" id="excelAttachmentDiv"></div>
                                                    <input type="file" class="form-control" name="excel_attachment" id="excelAttachment" accept=".xlsx, .csv" required>
                                                    <input type="text" class="form-control d-none" name="excel_attachment_filename" id="excelAttachmentFileName" readonly>
                                                    <div class="form-group form-check d-none m-0" id="btnReuploadExcelTriggerDiv">
                                                        <input type="checkbox" class="form-check-input d-none" id="btnReuploadExcelTrigger">
                                                        <label class="d-none" id="btnReuploadExcelTriggerLabel"> Re-upload EXCEL File</label>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Remarks</label>
                                                    <textarea class="form-control" name="remarks" id="remarks" style="height: 38px;"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right"id="modalCSRPart1Footer">
                                        <!-- <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button> -->
                                        <button type="submit" id="btnSubmitCSRPart1" class="btn btn-success"><i class="fa fa-check"></i>Save CSR</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ===================================================== -->
                    <!-- REVIEW STAFF -->
                    <!-- ===================================================== -->

                    <div class="card mb-3" id="cardReviewStaff">
                        <div class="card-header p-2">
                            <button type="button" class="btn btn-link btn-block text-left" data-bs-toggle="collapse" data-bs-target="#collapsePart2" aria-expanded="false">
                                <i class="fa fa-search mr-2"></i>
                                <strong>Review Staff</strong>
                                <i class="fa fa-chevron-down float-right"></i>
                            </button>
                        </div>

                        <div id="collapsePart2" class="collapse">
                            <div class="card-body">
                                <form method="post" id="formCSRReviewStaff" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="csr_document_id" id="txtCSRIdReviewStaff">
                                    <input type="hidden" name="csr_evidence_id" id="txtCSREvidenceIdReviewStaff">
                                    <input type="hidden" name="process" value="review_staff">

                                    <div class="row">
                                        <!-- REVIEW DATE -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Review Date</label>
                                                <input type="date" class="form-control" name="review_date" id="reviewDate">
                                            </div>
                                        </div>

                                        <!-- REVIEWED BY -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Reviewed By</label>
                                                <input type="hidden" name="reviewed_by" id="reviewedById">
                                                <input type="text" class="form-control" id="reviewedByName" placeholder="Auto Generate" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- REVIEW ATTACHMENTS -->
                                    <h6 class="mb-3"> 
                                        <i class="fa fa-paperclip"></i> Review Attachments 
                                    </h6>

                                    <div class="row">
                                        <!-- REVIEW PDF -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="form-control-label" id="reviewPdfAttachmentDiv"></div>
                                                <input type="file" class="form-control" name="review_pdf_attachment" id="reviewPdfAttachment" accept=".pdf" required>
                                                <input type="text" class="form-control d-none" name="review_pdf_attachment_filename" id="reviewPdfAttachmentFileName" readonly>
                                                <div class="form-group form-check d-none m-0" id="btnReuploadReviewPdfTriggerDiv">
                                                    <input type="checkbox" class="form-check-input d-none" id="btnReuploadReviewPdfTrigger">
                                                    <label class="d-none" id="btnReuploadReviewPdfTriggerLabel"> Re-upload PDF File</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- REVIEW PDF -->
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>PDF Attachment</label>
                                                <div id="reviewPdfAttachmentDiv" class="form-control-label mb-1"></div>
                                                <input type="file" class="form-control" name="review_pdf_attachment" id="reviewPdfAttachment" accept=".pdf">
                                            </div>
                                        </div> -->

                                        <!-- REVIEW EXCEL -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="form-control-label" id="reviewExcelAttachmentDiv"></div>
                                                <input type="file" class="form-control" name="review_excel_attachment" id="reviewExcelAttachment" accept=".xlsx, .csv" required>
                                                <input type="text" class="form-control d-none" name="review_excel_attachment_filename" id="reviewExcelAttachmentFileName" readonly>
                                                <div class="form-group form-check d-none m-0" id="btnReuploadReviewExcelTriggerDiv">
                                                    <input type="checkbox" class="form-check-input d-none" id="btnReuploadReviewExcelTrigger">
                                                    <label class="d-none" id="btnReuploadReviewExcelTriggerLabel"> Re-upload EXCEL File</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Excel Attachment</label>
                                                <div id="reviewExcelAttachmentDiv" class="form-control-label mb-1"></div>
                                                <input type="file" class="form-control" name="review_excel_attachment" id="reviewExcelAttachment" accept=".xlsx,.csv">
                                            </div>
                                        </div> -->

                                        <!-- REVIEW IMAGES -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="form-control-label" id="reviewImageAttachmentDiv"></div>
                                                <input type="file" class="form-control" name="review_image_attachment[]" id="reviewImageAttachment" accept="image/*" multiple>
                                                <input type="text" class="form-control d-none" name="review_image_attachment_filename[]" id="reviewImageAttachmentFileName" readonly>
                                                <div class="form-group form-check d-none m-0" id="btnReuploadReviewImageTriggerDiv">
                                                    <input type="checkbox" class="form-check-input d-none" id="btnReuploadReviewImageTrigger">
                                                    <label class="d-none" id="btnReuploadReviewImageTriggerLabel"> Re-upload Image Files</label>
                                                </div>
                                                <small class="form-text text-muted"> You may select multiple images. </small>
                                            </div>
                                        </div>
                                        
                                        <!-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Image Attachments</label>
                                                <input type="file" class="form-control" name="review_image_attachments[]" id="reviewImageAttachments" accept="image/*" multiple>

                                                <small class="form-text text-muted"> You may select multiple images. </small>
                                            </div>
                                        </div> -->
                                        
                                        <!-- REVIEW REMARKS -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Review Remarks</label>
                                                <textarea class="form-control" name="review_remarks" id="reviewRemarks" style="height: 38px;"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- REVIEW BUTTON -->
                                    <div class="text-right" id="modalCSRPart2Footer">
                                        <button type="submit" id="btnSubmitCSRPart2" class="btn btn-primary">
                                            <i class="fa fa-check"></i> Save Review
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL FOOTER -->
                <div class="modal-footer">
                    <div class="row col-md-12">
                        <!-- <button type="button" class="btn btn-default" data-bs-dismiss="modal"> Close </button> -->
                        <!-- </div> -->
                        <div class="col-md-6 justify-content-start">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        </div>

                        <div class="col-md-6 justify-content-end">
                            <button type="button" id="btnApproved" class="btn btn-success float-right d-none">
                                <i class="fa fa-thumbs-up"></i> Approved
                            </button>

                            <button type="button" id="btnDisapproved" class="btn btn-danger float-right d-none mr-2">
                                <i class="fa fa-thumbs-down"></i> Disapproved
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- REVIEW IMAGES MODAL -->
    <div class="modal fade" id="reviewImagesModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Review Images</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="reviewImagesGallery"
                        class="row">
                        <!-- Images loaded dynamically -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -->
    {{-- <div class="modal fade" id="modalExportReport" data-bs-backdrop="static">
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
                        @csrf
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
        </div>
    </div> --}}
@endsection

@section('js_content')
    <script type="text/javascript">

    </script>
@endsection

