<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sites extends Model
{

    protected $table = 'sites';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}
