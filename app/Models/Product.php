<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function company() {
        return $this->belongsTo('App\Models\Company');
    }
/*
    public function user() {
        return $this->belongsTo('App\User');
    }
*/    
}
