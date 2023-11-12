<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'dump_id',
        'title',
        'content'
    ];

    public function dump() {
        return $this->belongsTo(Dump::class);
    }

}
