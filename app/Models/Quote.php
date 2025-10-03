<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $table = 'quote';

    public function service() {
        return $this->belongsTo(Services::class,'service_id','id');
    }
}
