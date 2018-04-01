<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    protected $fillable = [
        'name',
        'information'
    ];

    public function educPlans() { return $this->belongsTo('App\EducPlan'); }

    public function groups() { return $this->hasMany('App\Group'); }
}