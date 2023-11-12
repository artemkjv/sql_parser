<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dump extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'table_name'
    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }

}
