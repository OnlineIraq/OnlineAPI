<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{

    protected $table = 'products';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}
