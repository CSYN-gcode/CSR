<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PthsDefects;


class Defects extends Model
{
    use HasFactory;

    protected $table = 'defects';

    public function defects()
    {
        return $this->hasMany(PthsDefects::class, 'defect_id', 'id');
    }
}
