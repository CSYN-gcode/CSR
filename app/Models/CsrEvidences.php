<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CsrEvidenceFiles;

class CsrEvidences extends Model
{
    use HasFactory;
    
    public function reviewed_by_info(){
        return $this->hasOne(User::class, 'id', 'reviewed_by');
    }

    public function review_pdf_info(){
        return $this->hasOne(CsrEvidenceFiles::class, 'evidence_id', 'id')->where('file_type', 'pdf');
    }

    public function review_excel_info(){
        return $this->hasOne(CsrEvidenceFiles::class, 'evidence_id', 'id')->where('file_type', 'excel');
    }

    public function review_image_info(){
        return $this->hasMany(CsrEvidenceFiles::class, 'evidence_id', 'id')->where('file_type', 'image');
    }
}
