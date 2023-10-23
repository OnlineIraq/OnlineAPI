<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{

    protected $table = 'complains';
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function replies() {
        return $this->hasMany(Replay::class); // Assuming you have a Reply model
    }

}
