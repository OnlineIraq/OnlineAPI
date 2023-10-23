<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AudioFile;

class Podcast extends Model
{

    protected $table = 'podcasts';
    public $timestamps = true;

    protected $fillable = [
        'id', // Add id to allow mass assignment
        'title',
        'description',
        'author',
        'image',
    ];

    public function audioFiles()
    {
        return $this->hasMany(AudioFile::class);
    }

    use SoftDeletes;

    protected $dates = ['deleted_at'];

}
