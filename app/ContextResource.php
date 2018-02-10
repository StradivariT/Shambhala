<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContextResource extends Model
{
    public function context() {
        return $this->belongsTo('App\Context');
    }
}
