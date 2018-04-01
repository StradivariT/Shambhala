<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {
    protected $fillable = [
        'name',
        'participants_file_name',
        'participants_file_storage',
        'incidents_file_name',
        'incidents_file_storage',
        'evaluations_file_name',
        'evaluations_file_storage'
    ];

    public function courses() { return $this->belongsTo('App\Course'); }
    
    public function students() { return $this->hasMany('App\Student'); }
}