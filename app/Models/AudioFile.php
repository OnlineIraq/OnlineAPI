<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudioFile extends Model
{

    protected $table = 'audio_files';
    public $timestamps = true;

    protected $fillable = ['audio_file_name'];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}
