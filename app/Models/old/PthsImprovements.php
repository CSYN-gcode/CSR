<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PartTroubleHistory;

class PthsImprovements extends Model
{
    use HasFactory;

    protected $table = 'pths_improvements';
    protected $primaryKey = 'id';

    public function parts_trouble_history()
    {
        return $this->belongsTo(PartTroubleHistory::class, 'history_id', 'id');
    }
}
