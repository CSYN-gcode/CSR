<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Defects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DefectsController extends Controller
{
    public function viewDefectsInfo(Request $request){
        $defect_details = Defects::get();

        // return $defect_details;
        return DataTables::of($defect_details)
        ->addColumn('action', function($defect_details){
            $result = "";
            $result .= "<center>";
            $result .= "<button class='btn btn-secondary btn-sm btnEdit mr-1' data-id='$defect_details->id'><i class='fa-solid fa-pen-to-square'></i></button>";
            if($defect_details->status == 0){
                $result .= "<button class='btn btn-danger btn-sm btnDisable' data-id='$defect_details->id'><i class='fa-solid fa-ban'></i></button>";
            }
            else{
                $result .= "<button class='btn btn-success btn-sm btnEnable' data-id='$defect_details->id'><i class='fa-solid fa-rotate-left'></i></button>";
            }
            $result .= "</center>";
            return $result;
        })
        ->addColumn('status_label', function($defect_details){
            $result = "";
            $result .= "<center>";

            if($defect_details->status == 0){
                $result .= "<span class='badge rounded-pill bg-success'>Active</span>";
            }else{
                $result .= "<span class='badge rounded-pill bg-danger'>Inactive</span>";
            }
            $result .= "</center>";

            return $result;
        })
        ->rawColumns(['action', 'status_label'])
        ->make(true);
    }

    public function addDefectsInfo(Request $request){
        $validation = array(
            'defects' => ['required', 'string', 'max:255']
        );

        $data = $request->all();
        $validator = Validator::make($data, $validation);
        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();

            try{
                $process_array = array(
                    'defect_name' => $request->defects
                );

                if(isset($request->id)){ // EDIT
                    Defects::where('id', $request->id)
                    ->update($process_array);
                }else{ // ADD
                    Defects::insert($process_array);
                }

                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Transaction Succesful']);
            }catch(Exemption $e){
                DB::rollback();
                return $e;
            }
        }
    }

    public function getDefectsById(Request $request){
        return Defects::where('id', $request->id)->first();
    }

    public function getDefects(Request $request){
        return Defects::where('status', 0)->get();
    }

    // public function updateDefectsStatus(Request $request){
    //     DB::beginTransaction();
    //     try{
    //         Defects::where('id', $request->id)
    //                 ->update(['status' => $request->status]);
    //     }catch(Exemption $e){
    //         DB::rollback();
    //         return $e;
    //     }
    // }

    public function updateDefectsStatus(Request $request){
        DB::beginTransaction();

        try {
            $defect = Defects::findOrFail($request->id);

            $defect->status = $defect->status == 1 ? 0 : 1;
            $defect->save();

            DB::commit(); // ✅ commit here

            return response()->json([
                'success' => true,
                'new_status' => $defect->status,
                'message' => 'Defect status updated successfully.'
            ]);
        } catch (\Throwable $e) { // ✅ catch everything including DB errors
            DB::rollBack(); // ✅ rollback only if it fails

            // log the error so you can see what’s happening
            \Log::error('Defect status update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update defect status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function updateDefectsStatus(Request $request){
    //     DB::beginTransaction();

    //     try {
    //         // Find the defect record
    //         $defect = Defects::findOrFail($request->id);
    //         // return $defect;
    //         // Toggle status: if 0 → 1, if 1 → 0
    //         $defect->status = $defect->status == 1 ? 0 : 1;
    //         // return $defect->status;
    //         // Save the change
    //         $defect->save();

    //         // Update the record
    //         // $defect->update(['status' => $newStatus]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'new_status' => $defect->status,
    //             'message' => 'Defect status updated successfully.'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to update defect status.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
