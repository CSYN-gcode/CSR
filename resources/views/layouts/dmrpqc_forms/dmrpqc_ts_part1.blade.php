{{-- <form id="frm_prod_identification" method="post">
    @csrf
    <div class="modal-body">
    <div class="row d-none">
        <div class="col-sm-3">
            <div class="input-group input-group-sm mb-2">
                <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Global ID</span>
                </div>
                <input type="text" name="global_dmrpqc_id" id="global_dmrpqc_id" readonly>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group input-group-sm mb-2">
                <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Process Status</span>
                </div>
                <input type="text" name="global_status" id="global_status" readonly>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group input-group-sm mb-2">
                <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">User ID</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="txt_user_id" name="user_id" readonly>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="input-group input-group-sm mb-2">
                <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">User Name</span>
                </div>
                <input type="text" class="form-control form-control-sm" id="txt_user_name" name="user_name" readonly>
            </div>
        </div>
    </div>

        <div class="accordion" id="accordionMain">
            {{-- <h5 class="modal-title"> I. Product Identification & Drawing Revision Checking <strong>(Responsible:Machine Operator)</strong></h5> --}}
            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <h5 class="modal-title"> I. Product Identification & Drawing Revision Checking <strong>(Responsible:Production Operator)</strong></h5>
            </button>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
                <div class="row"><!-- 1st row -->
                    <div class="col-sm-4">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="frm_txt_device_name" name="device_name" style="color: blue; font-weight: bold;" readonly>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Item Code</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="frm_txt_item_code" name="item_code" readonly>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Rev No.</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="frm_txt_rev_no" name="rev_no" readonly>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Drawing No.</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="frm_txt_drawing_no" name="drawing_no" readonly>
                        </div>
                    </div>
                </div>

                <div class="row"><!-- 2nd row -->

                    <div class="col-sm-4">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100 bg-primary" id="basic-addon1">P.O #: (Press Enter to Search)</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="frm_txt_po_no" name="po_no" placeholder="Insert PO Number" required>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100 bg-primary" id="basic-addon1">Request Type</span>
                            </div>
                                <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                    <option value="" disabled selected>Select Request type</option>
                                    <option value="1">For Maintenance</option>
                                    <option value="2">Temporary Stop</option>
                                </select>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Die No.</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="frm_txt_die_no" name="die_no" readonly>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Date/Time</span>
                            </div>
                            <input class="form-control form-control-sm" id="frm_txt_start_datetime" name="txt_start_datetime" placeholder="(Auto Generate)" readonly>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend w-30">
                                <p class="input-group-text" id="basic-addon1">Drawing & rev# validation:<strong>(Responsible:Machine Operator)</strong></p>
                            </div>
                            <p class="input-group-text" id="basic-addon1">Using PO balance from PPC with drawing & rev#, compare it versus RAPID.</p>
                            <p class="input-group-text" id="basic-addon1">if not tally, inform Supervisor & do not proceed the production </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Part2"><!-- PART II Division Start-->
                <hr><!-- Visible Line -->
                    {{-- <h5 class="modal-title">II. Dieset Condition <strong>(Responsible:Die Maintenance Engineering)</strong></h5> --}}
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="modal-title">II. Dieset Condition <strong>(Responsible:Die Maintenance Engineering)</strong></h5>
                    </button>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingOne">
                        <div class="row col-sm-12">
                            <div class="col-sm-3 border border-right-0 border-bottom-0 border-dark">
                                <strong><label class="form-check-label"> Action Done </label></strong>
                                <div class="table-responsive col-sm-12">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-borderless table-hover display nowrap" style="width: 100%;">
                                        {{-- table-borderless --}}
                                        <tbody>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 0" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_1">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> 1. Mold Cleaned</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_2">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> 2. Mold Check</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25">
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="1" id="action_3">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> 3. Device conversion (Coma change)</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="1" id="action_4">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> 4. Die set Overhaul</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_4a">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Fix side</label>
                                                    </div>
                                                    <div style="margin-left: 2%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_4b">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Movement side</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_4c">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> With parts marking</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_4d">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Without part(s) marking (Qty: )</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <label class="form-check-label"> 5. With parts that can reversely installed</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="action_5" id="action_5_yes" value="1">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Yes</label>
                                                    </div>
                                                    <div style="margin-left: 10%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="action_5" id="action_5_no" value="0">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> No</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="1" id="action_6">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> 6. Repair</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="1" id="action_7">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> 7. Parts change</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="action_7" id="action_7a" value="1">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> New</label>
                                                    </div>
                                                    <div style="margin-left: 8%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="action_7" id="action_7b" value="1">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Fabricated</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_7c">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> With part(s) marking</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="action_7d">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> With die-set part(s) change notice</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-4 border border-left-0 border-bottom-0 border-dark">
                                <div class="col-sm-10">
                                    <strong><label class="form-check-label"> Details of activity:</label></strong>
                                    <br>
                                    <textarea class="form-control" style="height: 200px;" id="details_of_activity"></textarea>
                                </div>
                                <br>
                                {{-- TEST --}}
                                {{-- <div class="text-right">
                                    <button id="btn_add_row" class="btn btn-primary btn-xs" style="width: 15%">
                                        <i class="fa fa-check fa-xs icon_save"></i> Add Row
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover small" id="tblBOMforPMI" style="min-width: 100%;">
                                        <thead>
                                        <tr class="bg-light">
                                            <th style="width: 20%;">Action</th>
                                            <th style="width: 40%;">Parts No.</th>
                                            <th style="width: 40%;">Quantity</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div> --}}
                                {{-- TEST --}}

                                <div class="table-responsive col-sm-12">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td width="30%" class="text-center align-middle">
                                                    <label class="form-check-label"> Parts No.</label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="parts_no_1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="parts_no_2">
                                                </td>
                                                {{-- <td width="35%">
                                                    <input type="text" class="form-control" id="parts_no_3">
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <label class="form-check-label"> Quantity</label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="qty_1">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="qty_2">
                                                </td>
                                                {{-- <td>
                                                    <input type="text" class="form-control" id="qty_3">
                                                </td> --}}
                                            </tr>
                                            <tr class="text-center align-middle">
                                                <td>
                                                    <label class="form-check-label"> Date</label>
                                                </td>
                                                <td>
                                                    <label class="form-check-label"> Start</label>
                                                </td>
                                                <td>
                                                    <label class="form-check-label"> Finish</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="date" class="form-control" id="action_date_start">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="action_start_time">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="action_finish_time">
                                                </td>
                                            </tr>
                                            <tr class="text-center align-middle">
                                                <td>
                                                    <label class="form-check-label" style="margin-top: 5%"> In-charged</label>
                                                </td>
                                                <td colspan="2">
                                                    <input type="text" class="form-control" id="action_in_charged" readonly>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-sm-3 border border-left-0 border-right-0 border-bottom-0 border-dark">
                                <strong><label class="form-check-label"> Checkpoints during Mold overhauling:</label></strong>
                                <div class="table-responsive col-sm-12">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-borderless table-hover display nowrap" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td width="30" class="form-check-label">OK</td>
                                                <td width="30" class="form-check-label">N.G</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">1. Marking Check</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_1" id="check_point_1_ok" value="1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_1" id="check_point_1_ng" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">2. Tanshi Pin</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_2" id="check_point_2_ok" value="1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_2" id="check_point_2_ng" value="1">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="check_point_2a">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Crack</label>
                                                    </div>
                                                    <div style="margin-left: 2%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="check_point_2b">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Bend</label>
                                                    </div>
                                                    <div style="margin-left: 2%" class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="1" id="check_point_2c">
                                                        &NonBreakingSpace;
                                                        <label class="form-check-label"> Worn out</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">3. Dent</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_3" id="check_point_3_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_3" id="check_point_3_ng" value="option2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">4. Porous</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_4" id="check_point_4_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_4" id="check_point_4_ng" value="option2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">5. Ejection Pin</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_3" id="check_point_5_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_3" id="check_point_5_ng" value="option2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">6. Coma</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_3" id="check_point_6_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_3" id="check_point_6_ng" value="option2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div class="form-check form-check-inline">
                                                        7. Gasvent
                                                    </div>
                                                    <br>
                                                    <div style="margin-left: 17%" class="form-check form-check-inline">
                                                        Required: 0.005mm min
                                                    </div>
                                                    <br>
                                                    <div style="margin-left: 15%" class="input-group input-group-sm col-sm-10">
                                                        <span class="input-group-text">Actual: </span>
                                                        <input type="text" class="form-control" id="check_point_7">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">8. Assy. Orientation</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_8" id="check_point_8_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_8" id="check_point_8_ng" value="option2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">9. F.S and M.S fittings</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_9" id="check_point_9_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_9" id="check_point_9_ng" value="option2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">10. Sub. Gate appearance</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_10" id="check_point_10_ok" value="option1">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="check_point_10" id="check_point_10_ng" value="option2">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-2 border border-left-0 border-bottom-0 border-dark">
                                <strong><label class="form-check-label"> Remarks:</label></strong>
                                <br>
                                <textarea class="form-control" style="height: 200px;" id="check_point_remarks"></textarea>
                            </div>
                        </div>
                        <div class="row col-sm-12">
                            <div class="col-sm-4 border border-right-0 border-dark">
                                <strong><label class="form-check-label"> Mold check prior endorse to production:</label></strong>
                                <div class="table-responsive col-sm-12">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-borderless table-hover display nowrap" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td width="30" class="form-check-label">OK</td>
                                                <td width="30" class="form-check-label">N.G</td>
                                                <td width="30" class="form-check-label">N/A</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">1. Withdraw Pin (External)</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_1" id="mold_check_1_ok" value="OK">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_1" id="mold_check_1_ng" value="NG">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_1" id="mold_check_1_na" value="N/A">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">2. Withdraw Pin (Internal)</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_2" id="mold_check_2_ok" value="OK">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_2" id="mold_check_2_ng" value="NG">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_2" id="mold_check_2-na" value="N/A">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">3. Slidecore stopper</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_3" id="mold_check_3_ok" value="OK">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_3" id="mold_check_3_ng" value="NG">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_3" id="mold_check_3_na" value="N/A">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">4. Locator ring</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_4" id="mold_check_4_ok" value="OK">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_4" id="mold_check_4_ng" value="NG">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_4" id="mold_check_4_na" value="N/A">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">5. Bolts and Nuts</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_5" id="mold_check_5_ok" value="OK">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_5" id="mold_check_5_ng" value="NG">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_5" id="mold_check_5_na" value="N/A">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">6. Stripper plate</td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_6" id="mold_check_6_ok" value="OK">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_6" id="mold_check_6_ng" value="NG">
                                                </td>
                                                <td>
                                                    &nbsp;
                                                    <input style="margin-left:0" class="form-check-input" type="radio" name="mold_check_6" id="mold_check_6_na" value="N/A">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-4 border border-left-0 border-dark">
                                <div class="col-sm-12">
                                    <strong><label class="form-check-label"> Remarks:</label></strong>
                                    <br>
                                    <textarea class="form-control" style="height: 200px;" id="mold_check_remarks"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-4 border border-left-0 border-dark">
                                <div class="table-responsive col-sm-12">
                                    <strong><label class="form-check-label">References Used </label></strong>
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                        <tbody>
                                            <tr class="align-middle">
                                                <td width="25">
                                                    <input style="margin-left:0" class="form-check-input" type="radio" value="1" name="reference_used" id="reference_1">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Eval Sample</label>
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="radio" value="1" name="reference_used" id="reference_2">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Japan Sample</label>
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="radio" value="1" name="reference_used" id="reference_3">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Dieset Assy. Drawing</label>
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="radio" value="1" name="reference_used" id="reference_4">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Last Production Sample</label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row col-sm-12">
                            <div class="col-sm-12 border border-top-0 border-dark">
                                <div class="table-responsive col-sm-8">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-borderless display nowrap" style="width: 100%;">
                                        <tbody>
                                            <tr class="text-center align-middle">
                                                <td>
                                                    <label class="form-check-label"> Checked by:</label>
                                                </td>
                                                <td>
                                                    <label class="form-check-label"> Date/Time</label>
                                                </td>
                                                {{-- <td>
                                                    <label class="form-check-label"> Time</label>
                                                </td> --}}
                                                <td>
                                                    <label class="form-check-label"> Status</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control text-center align-middle" id="mold_check_checked_by" readonly>
                                                </td>
                                                <td>
                                                    <input type="datetime-local" class="form-control text-center align-middle" id="mold_check_date" readonly>
                                                </td>
                                                {{-- <td>
                                                    <input type="text" class="form-control" id="mold_check_time" readonly>
                                                </td> --}}
                                                <td width="25%">
                                                    <input type="text" class="form-control text-center align-middle" id="mold_check_status">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row col-sm-12">
                            <div class="col-sm-12 border border-top-0 border-dark">
                                <div class="table-responsive col-sm-12" style="margin-bottom: -1%">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-borderless" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <label class="form-check-label"> Remarks:</label>
                                                </td>
                                                <td colspan="3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" value="1" name="final_remarks" id="1">
                                                        <label class="form-check-label"> No problem found</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" value="2" name="final_remarks" id="2">
                                                        <label class="form-check-label"> With problem found</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div><!-- PART II Division End -->
            <div class="Part3"><!-- PART III Division Start-->
                <hr><!-- Visible Line -->
                {{-- <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5> --}}
                <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5>
                </button>
                <div id="collapseThree" class="collapse" aria-labelledby="headingOne">
                    <div class="row col-sm-12">
                        <div class="col-sm-12 border border-dark">
                            <br>
                            <div class="col-sm-6">
                                <div class="table-responsive col-sm-12">
                                    <table id="tblViewEnergyConsumption" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                        <tbody>
                                            <tr class="align-middle">
                                                <td width="25">
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="option1">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Good Condition</label>
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="option1">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Under Longevity</label>
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <td>
                                                    <input style="margin-left:0" class="form-check-input" type="checkbox" value="option1">
                                                </td>
                                                <td colspan="3">
                                                    <label class="form-check-label"> Problematic Die set</label>
                                                </td>
                                            </tr>
                                            <tr class="text-center align-middle">
                                                <td colspan="2">
                                                    <label class="form-check-label"> Checked by:</label>
                                                </td>
                                                <td>
                                                    <label class="form-check-label"> Date</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input type="text" class="form-control" readonly>
                                                </td>
                                                <td>
                                                    <input type="datetime-local" class="form-control" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Part III Division End -->
        </div>
    </div> <!-- End Body -->
    <div class="modal-footer">
        <div class="actions">
            <button type="submit" id="id_btn_save" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs icon_save"></i> Save</button>
            <button type="button" id="id_btn_close" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</form> --}}
