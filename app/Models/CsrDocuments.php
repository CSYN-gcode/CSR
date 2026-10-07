<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CsrAttachments;
use App\Models\Customers;
use App\Models\CsrEvidences;

class CsrDocuments extends Model
{
    use HasFactory;

    public function customer_info(){
        return $this->hasOne(Customers::class, 'id', 'customer_name');
    }

    public function pdf_attachment_info(){
        return $this->hasOne(CsrAttachments::class, 'csr_id', 'id')->where('file_type', 'pdf');
    }

    public function excel_attachment_info(){
        return $this->hasOne(CsrAttachments::class, 'csr_id', 'id')->where('file_type', 'excel');
    }

    public function prepared_by_info(){
        return $this->hasOne(User::class, 'id', 'prepared_by');
    }

    public function review_info(){
        return $this->hasOne(CsrEvidences::class, 'csr_id', 'id')->whereNull('deleted_at');
    }

}
