<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    public function contextResources() {
        return $this->hasMany('App\ContextResource');
    }
}
