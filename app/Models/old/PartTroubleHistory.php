<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PthsDefects;
use App\Models\Situations;
use App\Models\PthsImprovements;

class PartTroubleHistory extends Model
{
    use HasFactory;

    protected $table = 'part_trouble_histories';
    protected $primaryKey = 'id';

    public function defects(){
        return $this->hasOne(PthsDefects::class, 'history_id', 'id');
    }

    public function situations(){
        return $this->hasOne(Situations::class, 'id', 'situation');
    }

    public function improvements()
    {
        return $this->hasMany(PthsImprovements::class, 'history_id', 'id');
    }
}
