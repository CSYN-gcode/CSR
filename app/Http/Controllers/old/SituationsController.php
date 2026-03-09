<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Situations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SituationsController extends Controller
{
    public function viewSituationsInfo(Request $request){
        $situation_details = Situations::get();

        // return $situation_details;
        return DataTables::of($situation_details)
        ->addColumn('action', function($situation_details){
            $result = "";
            $result .= "<center>";
            $result .= "<button class='btn btn-secondary btn-sm btnEdit mr-1' data-id='$situation_details->id'><i class='fa-solid fa-pen-to-square'></i></button>";
            if($situation_details->status == 0){
                $result .= "<button class='btn btn-danger btn-sm btnDisable' data-id='$situation_details->id'><i class='fa-solid fa-ban'></i></button>";
            }
            else{
                $result .= "<button class='btn btn-success btn-sm btnEnable' data-id='$situation_details->id'><i class='fa-solid fa-rotate-left'></i></button>";
            }
            $result .= "</center>";
            return $result;
        })
        ->addColumn('status_label', function($situation_details){
            $result = "";
            $result .= "<center>";

            if($situation_details->status == 0){
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

    public function addSituationsInfo(Request $request){
        $validation = array(
            'situations' => ['required', 'string', 'max:255']
        );

        $data = $request->all();
        $validator = Validator::make($data, $validation);
        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();

            try{
                $process_array = array(
                    'situation_name' => $request->situations
                );

                if(isset($request->id)){ // EDIT
                    Situations::where('id', $request->id)
                    ->update($process_array);
                }else{ // ADD
                    Situations::insert($process_array);
                }

                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Transaction Succesful']);
            }catch(Exemption $e){
                DB::rollback();
                return $e;
            }
        }
    }

    public function getSituationsById(Request $request){
        return Situations::where('id', $request->id)->first();
    }

    public function getSituations(Request $request){
        return Situations::where('status', 0)->get();
    }

    public function updateSituationsStatus(Request $request){
        DB::beginTransaction();

        try {
            $situation = Situations::findOrFail($request->id);

            $situation->status = $situation->status == 1 ? 0 : 1;
            $situation->save();

            DB::commit(); // ✅ commit here

            return response()->json([
                'success' => true,
                'new_status' => $situation->status,
                'message' => 'Situation status updated successfully.'
            ]);
        } catch (\Throwable $e) { // ✅ catch everything including DB errors
            DB::rollBack(); // ✅ rollback only if it fails

            // log the error so you can see what’s happening
            \Log::error('Situation status update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update situation status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
