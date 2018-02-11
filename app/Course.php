<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function educPlans() {
        return $this->belongsTo('App\EducPlan');
    }

    public function groups() {
        return $this->hasMany('App\Group');
    }
}
