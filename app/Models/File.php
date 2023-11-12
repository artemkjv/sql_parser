<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'dump_id',
        'type',
        'path'
    ];

    public function dump() {
        return $this->belongsTo(Dump::class);
    }

}
