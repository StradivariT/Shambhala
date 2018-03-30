<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    public function groups() { return $this->belongsTo('App\Group'); }

    public function activities() { return $this->hasMany('App\Activity'); }
}