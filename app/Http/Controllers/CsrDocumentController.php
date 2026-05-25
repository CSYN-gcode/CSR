<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use App\Models\Customers;
use App\Models\CsrDocuments;
use App\Models\CsrAttachments;
use App\Models\CsrEvidences;
use App\Models\CsrEvidenceFiles;
use App\Models\CsrApprovals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\ExportCsrDocument;
use Illuminate\Support\Facades\Cache;

class CsrDocumentController extends Controller
{
   private function actionButton($class, $icon, $id, $extraClass = ''){
        return "<button class='btn {$class} btn-sm {$extraClass}' data-id='{$id}'>
                    <i class='fa-solid {$icon}'></i>
                </button>";
    }

    public function viewCsrDocumentInfo(Request $request){
        $globalUser = session('global_user');
        // $position = optional($globalUser)->position;
        // return $position;
        // $csr_details = CsrDocuments::with(['prepared_by_info'])->orderBy('id', 'DESC')->get();
        $csr_details = CsrDocuments::whereNull('deleted_at')->orderBy('id', 'DESC')->get();

        return DataTables::of($csr_details)
        ->addColumn('action', function($csr_details) use ($globalUser){
            $result = "";
            $result .= "<center>";

            $canManage  = $globalUser && in_array($globalUser->position, [0,1,2,3]);
            $isActive   = $csr_details->status == 1;
            $isDisabled = $csr_details->status == 2;

            $id = $csr_details->id;

            if ($isActive) {
                if ($canManage) {
                    $result .= $this->actionButton('btn-secondary btnEdit', 'fa-pen-to-square', $id, 'mr-1');
                    $result .= $this->actionButton('btn-danger btnDisable', 'fa-ban', $id);
                } else {
                    $result .= $this->actionButton('btn-info btnView', 'fa-eye', $id, 'mr-1');
                }
            }

            if ($isDisabled) {
                $result .= $this->actionButton('btn-info btnView', 'fa-eye', $id, 'mr-1');

                if ($canManage) {
                    $result .= $this->actionButton('btn-success btnEnable', 'fa-rotate-left', $id);
                }
            }

            $result .= "</center>";
            return $result;
        })
        ->addColumn('status_label', function($csr_details){
            $result = "";
            $result .= "<center>";

            if($csr_details->status == 1){
                $result .= "<span class='badge rounded-pill bg-success'>Active</span>";
            }else{
                $result .= "<span class='badge rounded-pill bg-danger'>Inactive</span>";
            }
            $result .= "</center>";

            return $result;
        })
        ->addColumn('situation_label', function($csr_details){
            $result = "";
            $result .= "<center>";
                $result .= $csr_details->situation ? $csr_details->situations->situation_name : 'N/A';
            $result .= "</center>";
            return $result;
        })
        ->rawColumns(['action', 'status_label', 'situation_label'])
        ->make(true);
    }

    public function addCsrDocumentInfo(Request $request){
        $globalUser = session('global_user');
        $validation = array(
            'customer' => 'required',
            'business_process' => 'required',
            'date_applied' => 'required',
            'rev_no' => 'required',
            'change_description' => 'required',
            'pfd_attachment' => ['nullable','file','mimes:pdf','max:10240'], // 5MB
            'excel_attachment' => ['nullable','file','mimes:xlsx,csv','max:10240'], // 5MB
            // 'prepared_by' => 'required',
            'remarks' => 'required'
        );

        $data = $request->all();
        $validator = Validator::make($data, $validation);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();

            try{
                // Control Number Generation
                $control_number = '';
                $year2 = date('Y');
                $counter = 0;

                $csr_document = CsrDocuments::select('control_no')->whereNull('deleted_at')->whereYear('created_at', $year2)->orderBy('id', 'desc')->first();

                if($csr_document != null){
                    $control_number = $csr_document->control_no;
                    $number = explode('-', $control_number);
                    $counter = intval($number[1]) + 1;
                }else{
                    $counter = 1;
                }

                //AUTO GENERATED AIDRC CONTROL NUMBER
                $control_number = date('my')."-".str_pad($counter, 3, "0", STR_PAD_LEFT);

                $csr_data_array = array(
                    'customer_name' => $request->customer,
                    'business_process' => $request->business_process,
                    'date_applied' => $request->date_applied,
                    'revision_no' => $request->rev_no,
                    'change_description' => $request->change_description,
                    'prepared_by' => $globalUser->id,
                    'remarks' => $request->remarks,
                    'created_by' => $globalUser->id,
                    'last_updated_by' => $globalUser->id,
                    'created_at' => now(),
                );

                if(isset($request->csr_document_id)){ // EDIT
                    $csr_document_id = $request->csr_document_id;
                    CsrDocuments::where('id', $request->csr_document_id)->update($csr_data_array);
                }else{ // ADD
                    $csr_data_array['control_no'] = $control_number;
                    $csr_data_array['updated_at'] = now();
                    $csr_document_id = CsrDocuments::insertGetId($csr_data_array);
                }

                $attachments = [];
                if ($request->hasFile('pdf_attachment')) {
                    $existingPdf = DB::table('csr_attachments')
                        ->where('csr_id', $csr_document_id)
                        ->where('file_type', 'pdf')
                        ->first();

                    if ($existingPdf) {
                        $path = 'public/file_attachments/' . $existingPdf->file_name;

                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }

                        DB::table('csr_attachments')->where('id', $existingPdf->id)->delete();
                    }

                    $pdf = $this->uploadFile($request->file('pdf_attachment'), $control_number . '_pdf');

                    $attachments[] = [
                        'csr_id' => $csr_document_id,
                        'file_name' => $pdf['stored_name'],
                        'original_name' => $pdf['original_name'],
                        'file_type' => 'pdf',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if ($request->hasFile('excel_attachment')) {
                    $existingExcel = DB::table('csr_attachments')
                        ->where('csr_id', $csr_document_id)
                        ->where('file_type', 'excel')
                        ->first();

                    if ($existingExcel) {
                        $path = 'public/file_attachments/' . $existingExcel->file_name;

                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }

                        DB::table('csr_attachments')->where('id', $existingExcel->id)->delete();
                    }

                    $excel = $this->uploadFile($request->file('excel_attachment'), $control_number . '_excel');

                    $attachments[] = [
                        'csr_id' => $csr_document_id,
                        'file_name' => $excel['stored_name'],
                        'original_name' => $excel['original_name'],
                        'file_type' => 'excel',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // insert only if there is new data
                if (!empty($attachments)) {
                    DB::table('csr_attachments')->insert($attachments);
                }

                DB::commit();
                return response()->json(['result' => 1, 'msg' => 'Transaction Succesful']);
            }catch(Exemption $e){
                DB::rollback();
                return $e;
            }
        }
    }

    public function getCsrDocumentById(Request $request){
        return CsrDocuments::with(['customer_info', 'pdf_attachment_info', 'excel_attachment_info'])->where('id', $request->id)->first();
        // return CsrDocuments::where('id', $request->id)->first();
    }

    public function updateCsrDocumentStatus(Request $request){
        DB::beginTransaction();

        try {
            $defect = CsrDocuments::findOrFail($request->id);

            $defect->status = $defect->status == 1 ? 0 : 1;
            $defect->save();

            DB::commit(); // ✅ commit here

            return response()->json([
                'success' => true,
                'new_status' => $defect->status,
                'message' => 'Past Trouble History Record status updated successfully.'
            ]);
        } catch (\Throwable $e) { // ✅ catch everything including DB errors
            DB::rollBack(); // ✅ rollback only if it fails

            // log the error so you can see what’s happening
            \Log::error('Past Trouble History Record status update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Past Trouble History Record status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    function uploadFile($file, $prefix = ''){
        if (!$file) return null;

        // Extract name + extension
        $originalName = $file->getClientOriginalName();
        $nameOnly = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        // Sanitize filename
        $cleanName = preg_replace('/[^A-Za-z0-9_-]/', '_', $nameOnly);
        // Generate system filename
        $storedFilename = $prefix . '_' . $cleanName . '_' . now()->format('YmdHis') . '.' . $extension;
        // Store file
        $path = $file->storeAs('public/file_attachments', $storedFilename);

        return [
            'original_name' => $originalName,   // 👈 for UI
            'stored_name' => $storedFilename,  // 👈 for system
            'path' => $path,
            'extension' => $extension
        ];
    }

    //====================================== DOWNLOAD FILE ======================================
    public function downloadFile(Request $request, $id, $type){
        $file_name = CsrDocuments::with(['pdf_attachment_info', 'excel_attachment_info'])->where('id', $id)->first();

        if ($type === 'pdf') {
            $filename = $file_name->pdf_attachment_info->file_name;
        } else {
            $filename = $file_name->excel_attachment_info->file_name;
        }

        $filePath = storage_path() . "/app/public/file_attachments/" . $filename;
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
}
