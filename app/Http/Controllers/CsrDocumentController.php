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
    private function actionButton($class, $icon, $id, $extraClass = '', $approval = false, $remarks = ''){
        $remarksSafe = htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8');
        return "<button class='btn {$class} btn-sm {$extraClass}' data-id='{$id}' data-approval='{$approval}' data-remarks=\"{$remarksSafe}\">
                    <i class='fa-solid {$icon}'></i>
                </button>";
    }

    public function viewCsrDocumentInfo(Request $request){
        $globalUser = session('global_user');
        // $position = optional($globalUser)->position;
        // return $position;
        // $csr_details = CsrDocuments::with(['prepared_by_info'])->orderBy('id', 'DESC')->get();
        $csr_details = CsrDocuments::with(['customer_info','prepared_by_info','review_info'])->whereNull('deleted_at')->orderBy('id', 'DESC')->get();

        return DataTables::of($csr_details)
        ->addColumn('action', function($csr_details) use ($globalUser){
            $result = "";
            $result .= "<center>";

            $canManage  = $globalUser && in_array($globalUser->position, [0,1,2,3]);
            $canReviewCSR  = $globalUser->position == 0 || $globalUser->position == 2; //SuperAdmin & Reviewing Authority only is allowed
            $canApproveCSR  = $globalUser->position == 0 || $globalUser->position == 3; //SuperAdmin & Approving Authority only is allowed

            $isActive   = $csr_details->status == 1;
            $isForReview = $csr_details->status == 2;
            $isDisabled = $csr_details->status == 3;
            $isForApproval = $csr_details->status == 4;
            $isDisapproved = $csr_details->status == 5;

            $id = $csr_details->id;

            if($isActive){
                if ($canManage) {
                    $result .= $this->actionButton('btn-secondary btnEdit', 'fa-pen-to-square', $id, 'mr-1');
                    $result .= $this->actionButton('btn-success btnFinalSubmit', 'fas fa-check-square', $id, 'mr-1');
                    $result .= $this->actionButton('btn-danger btnDisable', 'fa-ban', $id);
                } else {
                    $result .= $this->actionButton('btn-info btnView', 'fa-eye', $id, 'mr-1');
                }
            }else if ($isDisabled){
                $result .= $this->actionButton('btn-info btnView', 'fa-eye', $id, 'mr-1');

                if ($canManage) {
                    $result .= $this->actionButton('btn-success btnEnable', 'fa-rotate-left', $id);
                }
            }else if($isForReview){
                if($canReviewCSR){
                    $result .= $this->actionButton('btn-primary btnView', 'fas fa-file-alt', $id, 'mr-1');

                    if($csr_details->review_info != null){
                        $result .= $this->actionButton('btn-success btnFinalSubmitReview', 'fas fa-check-square', $id, 'mr-1');
                    }
                }else{
                    $result .= $this->actionButton('btn-info btnView', 'fas fa-eye', $id, 'mr-1');
                }
            }else if ($isForApproval){
                if($canApproveCSR){
                    $result .= $this->actionButton('btn-success btnView', 'fas fa-check-square', $id, 'mr-1', 'true');
                }else{
                    $result .= $this->actionButton('btn-info btnView', 'fas fa-eye', $id, 'mr-1');
                }
            }else if ($isDisapproved){
                $result .= $this->actionButton('btn-secondary btnEdit', 'fas fa-edit', $id, 'mr-1');
                $result .= $this->actionButton('btn-success btnFinalSubmit', 'fas fa-check-square', $id, 'mr-1');
                $result .= $this->actionButton('btn-danger btnDisable', 'fa-ban', $id);
            }else{
                $result .= $this->actionButton('btn-info btnView', 'fas fa-eye', $id, 'mr-1');
            }

            $result .= "</center>";
            return $result;
        })
        ->addColumn('status_label', function($csr_details){
            $result = "";
            $result .= "<center>";

            if($csr_details->status == 1){
                $result .= "<span class='badge rounded-pill bg-info'>Pending</gspan>";
            }else if($csr_details->status == 3){
                $result .= "<span class='badge rounded-pill bg-danger'>Cancelled</span>";
            }else if($csr_details->status == 2){
                $result .= "<span class='badge rounded-pill bg-primary'>For Review</span>";
            }else if($csr_details->status == 4){
                $result .= "<span class='badge rounded-pill bg-warning'>For Approval</span>";
            }else if($csr_details->status == 5){
                $result .= "<span class='badge rounded-pill bg-danger'>Disapproved</span>";
            }else if($csr_details->status == 6){
                $result .= "<span class='badge rounded-pill bg-success'>Done</span>";
            }
            $result .= "</center>";

            return $result;
        })
        ->addColumn('date_applied_label', function($csr_details){
            return $csr_details->date_applied
                ? '<center>' . date('M j, Y', strtotime($csr_details->date_applied)) . '</center>'
                : '<center>N/A</center>';
        })
        // ->addColumn('situation_label', function($csr_details){
        //     $result = "";
        //     $result .= "<center>";
        //         $result .= $csr_details->situation ? $csr_details->situations->situation_name : 'N/A';
        //     $result .= "</center>";
        //     return $result;
        // })
        ->rawColumns(['action', 'status_label', 'date_applied_label'])
        ->make(true);
    }

    public function addCsrDocumentInfo(Request $request){
        $globalUser = session('global_user');
        $process = $request->process;
        
        if ($process === 'qas_dcc') {

            $validation = [
                'customer' => 'required',
                'business_process' => 'required',
                'date_applied' => 'required',
                'rev_no' => 'required',
                'change_description' => 'required',

                'pfd_attachment' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:10240',
                ],

                'excel_attachment' => [
                    'nullable',
                    'file',
                    'mimes:xlsx,xls,csv',
                    'max:10240',
                ],

                // 'prepared_by' => 'required',
                // 'remarks' => 'required'
            ];

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

                    // $process = $request->process;

                    // if ($process === 'qas_dcc') {

                        // Save QAS DCC information
                        
                        $csr_data_array = array(
                            'customer_name' => $request->customer,
                            'business_process' => $request->business_process,
                            'date_applied' => $request->date_applied,
                            'revision_no' => $request->rev_no,
                            'change_description' => $request->change_description,
                            'prepared_by' => $globalUser->id,
                            'remarks' => $request->remarks,
                            'created_by' => $globalUser->id,
                            'created_at' => now(),
                        );

                        if(isset($request->csr_document_id)){ // EDIT
                            $csr_data_array['last_updated_by'] = $globalUser->id;
                            $csr_data_array['updated_at'] = now();
                            $csr_document_id = $request->csr_document_id;
                            CsrDocuments::where('id', $request->csr_document_id)->update($csr_data_array);
                        }else{ // ADD
                            $csr_data_array['control_no'] = $control_number;
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

        }elseif ($process === 'review_staff') {
            // $validation = array(
            //     'csr_document_id' => 'required',
            //     'review_date' => 'required',
            //     'review_pdf_attachment' => ['nullable','file','mimes:pdf','max:10240'], // 10MB
            //     // 'review_excel_attachment' => ['nullable','file','mimes:xlsx,csv','max:10240'], // 10MB
            //     'review_excel_attachment' => ['nullable','file','extensions:xlsx,xls,csv','max:10240'], // 10MB
            //     'review_image_attachments[]' => ['nullable','file','mimes:image/*','max:10240'], // 10MB
            //     // 'prepared_by' => 'required',
            //     // 'remarks' => 'required'
            // );
            $validation = [
                'csr_document_id' => 'required',
                'review_date' => 'required',
                'review_pdf_attachment' => [
                    'nullable',
                    'file',
                    'mimes:pdf',
                    'max:10240',
                ],
                'review_excel_attachment' => [
                    'nullable',
                    'file',
                    'mimes:xlsx,xls,csv',
                    'max:10240',
                ],
                'review_image_attachments.*' => [
                    'nullable',
                    'file',
                    'mimes:jpg,jpeg,png,gif,webp',
                    'max:10240',
                ],
            ];

            $data = $request->all();
            $validator = Validator::make($data, $validation);

            if ($validator->fails()) {
                return response()->json(['result' => '0', 'error' => $validator->messages()]);
            }else{
                DB::beginTransaction();

                try{
                    $control_number = CsrDocuments::where('id', $request->csr_document_id)->value('control_no');
                    // return $control_number;
                    // }elseif ($process === 'review_staff') {
                        // Save Review Staff information

                        $csr_data_array = array(
                            'csr_id' => $request->csr_document_id,
                            'reviewed_by' => $globalUser->id,
                            'date_reviewed' => $request->review_date,
                            'remarks' => $request->review_remarks,
                        );

                        if(isset($request->csr_evidence_id)){ // EDIT
                            $csr_data_array['last_updated_by'] = $globalUser->id;
                            $csr_data_array['updated_at'] = now();
                            $csr_evidence_id = $request->csr_evidence_id;
                            CsrEvidences::where('id', $request->csr_evidence_id)->update($csr_data_array);
                        }else{ // ADD
                            $csr_data_array['created_by'] = $globalUser->id;
                            $csr_data_array['created_at'] = now();
                            $csr_evidence_id = CsrEvidences::insertGetId($csr_data_array);
                        }

                        $csr_evidence_files = [];
                        if ($request->hasFile('review_pdf_attachment')) {
                            $existingPdf = DB::table('csr_evidence_files')
                                ->where('evidence_id', $csr_evidence_id)
                                ->where('file_type', 'pdf')
                                ->first();

                            if ($existingPdf) {
                                $path = 'public/file_attachments/' . $existingPdf->file_name;

                                if (Storage::exists($path)) {
                                    Storage::delete($path);
                                }

                                DB::table('csr_evidence_files')->where('id', $existingPdf->id)->delete();
                            }

                            $review_pdf = $this->uploadFile($request->file('review_pdf_attachment'), $control_number . '_pdf');

                            $csr_evidence_files[] = [
                                'evidence_id' => $csr_evidence_id,
                                'file_name' => $review_pdf['stored_name'],
                                'original_name' => $review_pdf['original_name'],
                                'file_type' => 'pdf',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        if ($request->hasFile('review_excel_attachment')) {
                            $existingExcel = DB::table('csr_evidence_files')
                                ->where('evidence_id', $csr_evidence_id)
                                ->where('file_type', 'excel')
                                ->first();

                            if ($existingExcel) {
                                $path = 'public/file_attachments/' . $existingExcel->file_name;

                                if (Storage::exists($path)) {
                                    Storage::delete($path);
                                }

                                DB::table('csr_evidence_files')->where('id', $existingExcel->id)->delete();
                            }

                            $review_excel = $this->uploadFile($request->file('review_excel_attachment'), $control_number . '_excel');

                            $csr_evidence_files[] = [
                                'evidence_id' => $csr_evidence_id,
                                'file_name' => $review_excel['stored_name'],
                                'original_name' => $review_excel['original_name'],
                                'file_type' => 'excel',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        if ($request->hasFile('review_image_attachment')) {

                            // Get all existing images for this evidence
                            $existingImages = DB::table('csr_evidence_files')
                                ->where('evidence_id', $csr_evidence_id)
                                ->where('file_type', 'image')
                                ->get();

                            // Delete existing image files
                            foreach ($existingImages as $existingImage) {
                                $path = 'public/file_attachments/' . $existingImage->file_name;

                                if (Storage::exists($path)) {
                                    Storage::delete($path);
                                }
                            }

                            // Delete existing image records
                            DB::table('csr_evidence_files')
                                ->where('evidence_id', $csr_evidence_id)
                                ->where('file_type', 'image')
                                ->delete();

                            // Upload new images
                            foreach ($request->file('review_image_attachment') as $imageFile) {
                                $review_image = $this->uploadFile(
                                    $imageFile,
                                    $control_number . '_image'
                                );

                                $csr_evidence_files[] = [
                                    'evidence_id' => $csr_evidence_id,
                                    'file_name' => $review_image['stored_name'],
                                    'original_name' => $review_image['original_name'],
                                    'file_type' => 'image',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }

                        // insert only if there is new data
                        if (!empty($csr_evidence_files)) {
                            DB::table('csr_evidence_files')->insert($csr_evidence_files);
                        }

                    DB::commit();
                    return response()->json(['result' => 1, 'msg' => 'Transaction Succesful']);
                }catch(Exemption $e){
                    DB::rollback();
                    return $e;
                }
            }
        }
    }

    public function getCsrDocumentById(Request $request){
        return CsrDocuments::with(['customer_info',
            'pdf_attachment_info',
            'excel_attachment_info',
            'prepared_by_info',
            // 'review_info',
            'review_info.review_pdf_info',
            'review_info.review_excel_info',
            'review_info.review_image_info',
            'review_info.reviewed_by_info'
        ])->where('id', $request->id)->first();
        // return CsrDocuments::where('id', $request->id)->first();
    }

    public function updateCsrDocumentStatus(Request $request){
        DB::beginTransaction();

        try {
            $csr = CsrDocuments::findOrFail($request->id);

            // Toggle status between 1 (Active) and 3 (Inactive)
            $csr->status = $request->new_status;
            $csr->save();

            DB::commit(); // ✅ commit here

            return response()->json([
                'success' => true,
                'new_status' => $csr->status,
                'message' => 'CSR status updated successfully.'
            ]);
        } catch (\Throwable $e) { // ✅ catch everything including DB errors
            DB::rollBack(); // ✅ rollback only if it fails

            // log the error so you can see what’s happening
            \Log::error('CSR status update failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update CSR status.',
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

    //====================================== DOWNLOAD FILE ======================================
    public function downloadEvidenceFile(Request $request, $id, $type){
        $file_name = CsrEvidences::with(['review_pdf_info', 'review_excel_info', 'review_image_info'])->where('csr_id', $id)->first();

        if ($type === 'pdf') {
            $filename = $file_name->review_pdf_info->file_name;
        } else if(($type === 'excel')){
            $filename = $file_name->review_excel_info->file_name;
        } else if(($type === 'image')){
            $filename = $file_name->review_image_info->file_name;
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

    public function viewImages(Request $request){
        $images = CsrEvidences::with(['review_image_info'])->where('csr_id', $request->review_id)->first();
        // return $images->review_image_info[0]->file_name;
        $review_image_info = [];

        foreach ($images->review_image_info as $image_info) {
            $path = 'app/public/file_attachments/' . $image_info->file_name;
            // $path = storage_path() . "/app/public/file_attachments/" . $image_info->filename;

            $review_image_info[] = [
                'url' => asset('storage/'. $path),
                'thumbnail_url' => asset('storage/'. $path),
            ];
        }

        return response()->json([
            [
                'review_image_info' => $review_image_info
            ]
        ]);
        
        return response()->json($file_name);
    }
}
