<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{

    protected $table = 'replies';
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

}
