<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PartTroubleHistory;
use App\Models\Defects;

class PthsDefects extends Model
{
    use HasFactory;

    protected $table = 'pths_defects';

    public function parts_trouble_history()
    {
        return $this->belongsTo(PartTroubleHistory::class, 'history_id', 'id');
    }

    public function defect_item()
    {
        return $this->belongsTo(Defects::class, 'defect_id', 'id');
    }
}
