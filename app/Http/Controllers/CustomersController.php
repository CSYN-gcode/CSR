<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    public function viewCustomersInfo(Request $request){
        $customer_details = Customers::get();

        return DataTables::of($customer_details)
        ->addColumn('action', function($customer_details){
            $result = "";
            $result .= "<center>";
            $result .= "<button class='btn btn-secondary btn-sm btnEdit mr-1' data-id='$customer_details->id'><i class='fa-solid fa-pen-to-square'></i></button>";
            if($customer_details->status == 0){
                $result .= "<button class='btn btn-danger btn-sm btnDisable' data-id='$customer_details->id'><i class='fa-solid fa-ban'></i></button>";
            }
            else{
                $result .= "<button class='btn btn-success btn-sm btnEnable' data-id='$customer_details->id'><i class='fa-solid fa-rotate-left'></i></button>";
            }
            $result .= "</center>";
            return $result;
        })
        ->addColumn('status_label', function($customer_details){
            $result = "";
            $result .= "<center>";

            if($customer_details->status == 0){
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

    public function addCustomersInfo(Request $request){
        $validation = array(
            'customers' => ['required', 'string', 'max:255']
        );

        $data = $request->all();
        $validator = Validator::make($data, $validation);
        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();

            try{
                $process_array = array(
                    'customer_name' => $request->customers
                );

                if(isset($request->id)){ // EDIT
                    Customers::where('id', $request->id)
                    ->update($process_array);
                }else{ // ADD
                    Customers::insert($process_array);
                }

                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Transaction Succesful']);
            }catch(Exemption $e){
                DB::rollback();
                return $e;
            }
        }
    }

    public function getCustomersById(Request $request){
        return Customers::where('id', $request->id)->first();
    }

    public function getCustomersDropdownList(Request $request){
        return Customers::where('status', 0)->get();
    }

    public function updateCustomersStatus(Request $request){
        DB::beginTransaction();

        try {
            $customer = Customers::findOrFail($request->id);

            $customer->status = $customer->status == 1 ? 0 : 1;
            $customer->save();

            DB::commit(); // ✅ commit here

            return response()->json([
                'success' => true,
                'new_status' => $customer->status,
                'message' => 'Customer status updated successfully.'
            ]);
        } catch (\Throwable $e) { // ✅ catch everything including DB errors
            DB::rollBack(); // ✅ rollback only if it fails

            // log the error so you can see what’s happening
            \Log::error('Customer status update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
