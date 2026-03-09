<?php

namespace App\Http\Controllers\Traits;

/**
 * Import Models
 */

use Illuminate\Http\Request;
use App\Models\ProductIdentification;
use App\Models\DiesetCondition;
use App\Models\DiesetConditionChecking;
use App\Models\MachineSetup;
use App\Models\ProductReqChecking;
use App\Models\ProductReqCheckingDetails;
use App\Models\MachineSetupSample;
use App\Models\MachineParameterChecking;
use App\Models\Specification;
use App\Models\CompletionActivity;
// use App\Models\User;

trait GetDmrpqcDetailsTrait {

    public function getData($article) {
        return 'Successfully get data';
    }

    // public function get_dmrpqc_details_id(){ 
        public function getDmrpqcDetailsId($id, $process_status){
            $dmrpqc_details = ProductIdentification::with(['created_by.rapidx_user_details' => function($query) { $query->select('id', 'name'); }])
            ->where('id', $id)
            ->where('logdel', 0)->get();

            if($process_status >= 2){ //Get Dieset Condition Data if the Product Identification Status is (2)Ongoing
                $dieset_condition_details = DiesetCondition::with(['in_charged.rapidx_user_details' => function($query) { $query->select('id', 'name'); },
                                                                    'checked_by.rapidx_user_details' => function($query) { $query->select('id', 'name'); }])
                                                                    ->where('request_id', $id)
                                                                    ->where('logdel', 0)->get();

                if($dieset_condition_details[0]->status == 0){ //return blank data if the (p2) status is unchanged(0) not yet updated
                    $dieset_condition_details = '';
                }

                $dieset_condition_checking_details = DiesetConditionChecking::with(['checked_by.rapidx_user_details' => function($query) { $query->select('id', 'name'); }])
                                                                                    ->where('request_id', $id)
                                                                                    ->where('logdel', 0)->get();

                if($dieset_condition_checking_details[0]->status == 0){ //return blank data if the (p3) status is unchanged(0) not yet updated
                    $dieset_condition_checking_details = '';
                }
            }else{
                $dieset_condition_details = '';
                $dieset_condition_checking_details = '';
            }

            if($process_status >= 4){
                $machine_setup_details = MachineSetup::where('request_id', $id)->where('logdel', 0)->get();
                if($machine_setup_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $machine_setup_details = '';
                }
            }else{
                $machine_setup_details = '';
            }

            if($process_status >= 5){
                $product_req_checking_details = ProductReqChecking::with(['prod_req_checking_details'])->where('request_id', $id)->where('logdel', 0)->get();

                if($product_req_checking_details[0]->prod_req_checking_details == ''){
                    $product_req_checking_details = '';
                }

                $machine_setup_sample_details = MachineSetupSample::where('request_id', $id)->where('logdel', 0)->get();

                if($machine_setup_sample_details[0]->pic == '' || $machine_setup_sample_details[0]->pic_datetime == ''){
                    $machine_setup_sample_details = '';
                }
            }else{
                $product_req_checking_details = '';
                $machine_setup_sample_details = '';
            }

            if($process_status >= 6){
                $machine_param_checking_details = MachineParameterChecking::where('request_id', $id)
                                                        ->where('logdel', 0)->get();

                if($machine_param_checking_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $machine_param_checking_details = '';
                }
            }else{
                $machine_param_checking_details = '';
            }

            if($process_status >= 7){
                $specification_details = Specification::where('request_id', $id)->where('logdel', 0)->get();

                if($specification_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $specification_details = '';
                }
            }else{
                $specification_details = '';
            }

            if($process_status >= 8){
                $completion_activity_details = CompletionActivity::where('request_id', $id)
                                                        ->where('logdel', 0)->get();

                if($completion_activity_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $completion_activity_details = '';
                }
            }else{
                $completion_activity_details = '';
            }

            // return response()->json(['dmrpqc_details' => $dmrpqc_details,
            //                         'dieset_condition_details' => $dieset_condition_details,
            //                         'dieset_condition_checking_details' => $dieset_condition_checking_details,
            //                         'machine_setup_details' => $machine_setup_details,
            //                         'product_req_checking_details' => $product_req_checking_details,
            //                         'machine_setup_sample_details' => $machine_setup_sample_details,
            //                         'machine_param_checking_details' => $machine_param_checking_details,
            //                         'specification_details' => $specification_details,
            //                         'completion_activity_details' => $completion_activity_details]);
        }
    // }
}