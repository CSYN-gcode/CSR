<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function viewUsersInfo(Request $request){
        $user_details = User::get();

        // return $user_details;
        return DataTables::of($user_details)
        ->addColumn('action', function($user_details){
            $result = "";
            $result .= "<center>";
            $result .= "<button class='btn btn-secondary btn-sm btnEdit mr-1' data-id='$user_details->id'><i class='fa-solid fa-pen-to-square'></i></button>";
            if($user_details->status == 0){
                $result .= "<button class='btn btn-danger btn-sm btnDisable' data-id='$user_details->id'><i class='fa-solid fa-ban'></i></button>";
            }
            else{
                $result .= "<button class='btn btn-success btn-sm btnEnable' data-id='$user_details->id'><i class='fa-solid fa-rotate-left'></i></button>";
            }
            $result .= "</center>";
            return $result;
        })
        ->addColumn('position_label', function($user_details){
            $result = "";
            $result .= "<center>";

            if($user_details->position == 0){
                $result .= "<span class='badge rounded-pill bg-primary'>Admin</span>";
            }else if($user_details->position == 1){
                $result .= "<span class='badge rounded-pill bg-info'>QC Inspector</span>";
            }else if($user_details->position == 2){
                $result .= "<span class='badge rounded-pill bg-warning'>QC Supervisor</span>";
            }else if($user_details->position == 3){
                $result .= "<span class='badge rounded-pill bg-secondary'>Section Head</span>";
            }else if($user_details->position == 4){
                $result .= "<span class='badge rounded-pill bg-dark'>QAS Validator</span>";
            }else{
                $result .= "<span class='badge rounded-pill bg-danger'>Inactive</span>";
            }
            $result .= "</center>";

            return $result;
        })
        ->addColumn('status_label', function($user_details){
            $result = "";
            $result .= "<center>";

            if($user_details->status == 0){
                $result .= "<span class='badge rounded-pill bg-success'>Active</span>";
            }else{
                $result .= "<span class='badge rounded-pill bg-danger'>Inactive</span>";
            }
            $result .= "</center>";

            return $result;
        })
        ->rawColumns(['action', 'position_label', 'status_label'])
        ->make(true);
    }

    public function addUsersInfo(Request $request){
        $validation = array(
            'rapidx_user_id' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255']
        );

        $data = $request->all();
        $validator = Validator::make($data, $validation);
        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();

            try{
                $users_data_array = array(
                    'rapidx_user_id' => $request->rapidx_user_id,
                    'name'           => $request->name,
                    'employee_id'    => $request->employee_id,
                    'section'        => $request->section,
                    'position'       => $request->position,
                );

                if(isset($request->id)){ // EDIT
                    User::where('id', $request->id)
                    ->update($users_data_array);
                }else{ // ADD
                    User::insert($users_data_array);
                }

                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Transaction Succesful']);
            }catch(Exemption $e){
                DB::rollback();
                return $e;
            }
        }
    }

    public function getUsersById(Request $request){
        return User::where('id', $request->id)->first();
    }

    public function getRapidxUsers(Request $request){
        // rapidx users for dropdown selection in users management
        $rapidx_users = DB::connection('rapidx')
        ->table('users')
        ->join('departments', 'users.department_id', '=', 'departments.department_id')
        ->select('users.*', 'departments.department_group AS section')
        ->where('user_stat', 1)
        ->orderBy('name')
        ->get();

        return response()->json(['rapidx_user_data' => $rapidx_users]);
    }

    public function updateUsersStatus(Request $request){
        DB::beginTransaction();

        try {
            $user = User::findOrFail($request->id);

            $user->status = $user->status == 1 ? 0 : 1;
            $user->save();

            DB::commit(); // ✅ commit here

            return response()->json([
                'success' => true,
                'new_status' => $user->status,
                'message' => 'User status updated successfully.'
            ]);
        } catch (\Throwable $e) { // ✅ catch everything including DB errors
            DB::rollBack(); // ✅ rollback only if it fails

            // log the error so you can see what’s happening
            \Log::error('User status update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
