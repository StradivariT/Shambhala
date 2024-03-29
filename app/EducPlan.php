<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducPlan extends Model {
    protected $fillable = ['name'];

    public function courses() { return $this->hasMany('App\Course'); }
}