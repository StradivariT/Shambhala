<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function courses() {
        return $this->belongsTo('App\Course');
    }
    
    public function students() {
        return $this->hasMany('App\Student');
    }
}