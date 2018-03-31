<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
    protected $fillable = [
        'name',
        'grade',
        'feedback',
        'incidents',
        'turned_in_date'
    ];

    public function students() { return $this->belongsTo('App\Student'); }
}